# ClockWorkIT - Budgets Software

My solution uses a MYSQL database to make it more dynamic and maintainable; I attempted to build it in the same way Laravel would work to demonstrate comptenteny in Laravel but noticed the technical interview says not to use Frameworks.

## Installation

Ensure you update the `.env` file inside the main repository with your revelant SQL information. I used XAMPP locally to test.

```bash
# clone the repository
git clone https://github.com/Kyle-Jeynes/ClockWorkIT-Basket.git /opt/kyle-jeynes-submission

# generate autoload
composer dump-autoload -o

# migrate the database
php -q /opt/kyle-jeynes-submission/public/index.php MigrateDatabase

# use the application
php -q /opt/kyle-jeynes-submission/public/index.php PriceBasket "Kyes Special Soup" "Kyes Special Soup" "Bread"
```

## Products to choose from

- Kyes Special Soup (Recommended)
- Bread (50% off each loaf when you purchase 2 recommended soups)
- Milk
- Apple Bag (10% Off)

## Summary

Everything was built from scratch from the ground up in an attempt to focus the appliation around using Vanilla-PHP with a Laravel feel of the code. Keeping to the specifications, the first argument in the method is the command followed by `n` values of products.