<?php

namespace App\Console\Controllers;

use App\Database\Database;

class BasketController
{
    private array $argv = [];
    private array $basket = [];
    
    public function __construct() {}
    
    /**
     * Filters the database to ensure the products exist
     * @param string $argv
     */
    public function validate(array $argv): BasketController
    {
        $this->argv = $argv;
        
        $stmt = Database::raw()->prepare('SELECT name FROM products');
        $stmt->execute();
        
        # Convert to lower-case for none-sensitive comparison
        $products = array_map(fn($p) => strtolower($p), array_column($stmt->fetchAll(), 'name'));
        
        # Ensure only items inside the database can be parsed
        if(($notFoundProducts = array_diff(array_map(fn($p) => strtolower($p), $argv), $products)))
        {
            # Non-specified output so introduced a error prompt
            die('Sorry, some of those products could not be found: ' . implode(', ', $notFoundProducts));
        }
        
        return $this;
    }
    
    /**
     * Process the basket ready for summary
     */
    public function processOrder(): void
    {
        # Database transaction for speed and correct locks
        $pdo = Database::raw();
        $pdo->beginTransaction();
        
        foreach($this->argv as $product)
        {
            # Select name also for the correct case-sensitive representation of the product
            $stmt = $pdo->prepare('SELECT id, price, name FROM products WHERE name = ?');
            $stmt->execute([$product]);
            
            # Check product is already set
            if(isset($this->basket[($result = $stmt->fetch())['id']]))
            {
                # Append to quantity, subtotal and total
                $this->basket[$result['id']]['quantity']++;
                $this->basket[$result['id']]['subtotal'] = $this->basket[$result['id']]['quantity'] * $result['price'];
                $this->basket[$result['id']]['total'] = $this->basket[$result['id']]['quantity'] * $result['price'];
            }
            else
            {
                # Add product
                $this->basket[$result['id']] = array_merge(['quantity' => 1], $result);
                $this->basket[$result['id']]['subtotal'] = $this->basket[$result['id']]['quantity'] * $result['price'];
                $this->basket[$result['id']]['total'] = $this->basket[$result['id']]['quantity'] * $result['price'];
            }
        }
        
        # Commit the transaction, apply discounts and output
        $pdo->commit();
        $this->applyDiscounts()
            ->outputResults();
    }
    
    /**
     * Loops database and applies selected discounts dependent on user basket
     * @return BasketController
     */
    private function applyDiscounts(): BasketController
    {
        $stmt = Database::raw()->prepare('SELECT product_id, percentage, product_effects_id, when_product_has_quantity FROM product_discounts');
        $stmt->execute();
        $discounts = $stmt->fetchAll();
        
        # Loop through discounts in DB
        foreach($discounts as $discount)
        {
            # Export array
            list($productId, $percentage, $productEffectsId, $filter) = array_values($discount);

            # If both the discounted product and factored product is set
            if(isset($this->basket[$productId]) && isset($this->basket[$productEffectsId]))
            {
                # Check the factored product has met the criteria
                if($this->basket[$productId]['quantity'] >= $filter)
                {
                    $appliedCounter = 0;
                    $total = $this->basket[$productEffectsId]['quantity'] * $this->basket[$productEffectsId]['price'];
                    $shouldDeduct = floor($this->basket[$productId]['quantity'] / $filter); # 2/2 = 1
                    $difference = 0;
                    
                    # Calcualte how many times we should introduce the discount
                    for($i = 0; $i <= $shouldDeduct ; $i++)
                    {
                        # Factor in that there be discounts that cannot transact due to improportionate quantities
                        # I could of used recursion here and would do in real-time
                        if($this->basket[$productEffectsId]['quantity'] > $appliedCounter++)
                        {
                            $previous = $total;
                            $total = $total - (($this->basket[$productEffectsId]['price'] / 100) * $percentage);
                            $difference += $previous - $total;
                        }
                    }
                    
                    # Make the neccesary changes
                    $this->basket[$productEffectsId]['total'] = $total;
                    $this->basket[$productEffectsId]['subtotal'] = $this->basket[$productEffectsId]['quantity'] * $this->basket[$productEffectsId]['price'];
                    $this->basket[$productEffectsId]['discount'][] = sprintf('%s %s%% off: -£%s', $this->basket[$productEffectsId]['name'], $percentage, number_format($difference, 2));
                }
            }
            else
            {
                # If there was no discounted product but the factored product requirements was met
                if(!isset($this->basket[$productEffectsId]) && isset($this->basket[$productId]))
                {
                    if ($this->basket[$productId]['quantity'] >= $filter)
                    {
                        # All checks pass, prompt them that they could get a discount if they add the product to their basket
                        $stmt = Database::raw()->prepare('SELECT name FROM products WHERE id = ?');
                        $stmt->execute([$productEffectsId]);
                        $this->basket[$productId]['discount'][] = sprintf('%s %s%% off: -£%s', $stmt->fetch()['name'], $percentage, number_format(0, 2));
                    }
                }
            }
        }
        
        return $this;
    }
    
    /**
     * Output to terminal
     * @return void
     */
    private function outputResults(): void
    {
        # Calculate the easy way
        $subTotal = number_format(array_sum(array_column($this->basket, 'subtotal')), 2);
        $total = number_format(array_sum(array_column($this->basket, 'total')), 2);
        $discount = array_column($this->basket, 'discount');
        
        echo "Subtotal £{$subTotal}\n";
        if(count($discount) > 0)
            foreach($discount as $d) echo implode("\n", $d) . "\n";
        else
            echo "(no offers available)\n";
        echo "Total: £{$total}";
    }
}
