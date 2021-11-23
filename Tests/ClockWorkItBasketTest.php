<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use App\Console\Console;
use App\Console\Controllers\BasketController;

define('ROOT_DIR', dirname(dirname(__FILE__)) . '/');

class ClockWorkItBasketTest extends TestCase
{
    protected ?BasketController $basketController = null;
    
    protected function setUp(): void
    {
        Console::getInstance();
        $this->basketController = new BasketController();
    }
    
    /**
     * @dataProvider productsDataProvider
     */
    public function testProductsAsExpected(array $expectedResult, array $argv)
    {
        $result = $this->basketController
            ->validate($argv)
            ->processOrder()
            ->getResults();
        
        $this->assertEquals($expectedResult, $result);
    }
    
    public function productsDataProvider()
    {
        return [
            '2 soups and 1 bread should discount 40p' => [
                ['2.10', '1.70'],
                ['Kyes Special Soup', 'Kyes Special Soup', 'Bread']
            ],
            '3 apples should discount 30p' => [
                ['3.00', '2.70'],
                ['Apple Bag', 'Apple Bag', 'Apple Bag']
            ],
            '2 soups should discount nothing' => [
                ['1.30', '1.30'],
                ['Kyes Special Soup', 'Kyes Special Soup']
            ],
            '4 soups, 2 apples, milk with only one bread should discount 60p and ignore the additional 2 soups' => [
                ['6.70', '6.10'],
                ['Kyes Special Soup', 'Kyes Special Soup', 'Kyes Special Soup', 'Kyes Special Soup', 'Apple Bag', 'Apple Bag', 'Milk', 'Bread']
            ],
            'bread and milk should discount nothing' => [
                ['2.10', '2.10'],
                ['Bread', 'Milk']
            ],
            'case sensitive should succeed and 1 apple should discount 10p' => [
                ['1.00', '0.90'],
                ['ApPlE bAG']
            ],
        ];
    }
}