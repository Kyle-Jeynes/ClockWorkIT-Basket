# ClockWorkIT - Basket Software

My solution uses a MYSQL database to make it more dynamic and maintainable; I attempted to build it in the same way Laravel would work to demonstrate comptenteny in Laravel but noticed the technical interview says not to use Frameworks.

## Images of the application

> Manual installation and execution

![PoC](https://i.imgur.com/vss65XQ.png)

> Docker installation and execution

![Docker](https://i.imgur.com/V3sXe6n.png)

## Requirements

- Composer
- XAMPP or local MySQL server
- PHP 7.4^
- PHP-PDO Extension

## Installation with Docker (recommended)

All the configuration is already set, just download docker and docker-compose on your envrionment and you are good to go.

```bash
# clone the repository
git clone https://github.com/Kyle-Jeynes/ClockWorkIT-Basket.git && cd ClockWorkIT-Basket

# build and run containers
docker-compose up -d --build > /dev/null

# interact with container
docker exec -it php /bin/bash

# migrate the tables - wait about 20 seconds before running so the mysql server can start
php -f public/index.php MigrateDatabase

# use the basket
php -f public/index.php PriceBasket "Kyes Special Soup" "Kyes Special Soup" Bread
```

## Installation - Manually

Ensure you update the `.env` file inside the main repository with your revelant SQL information. You will need [composer](https://getcomposer.org/download/) and you will need to have a running SQL server at `127.0.0.1:3306` (I used [XAMPP](https://www.apachefriends.org/download.html)).

```bash
# clone the repository
git clone https://github.com/Kyle-Jeynes/ClockWorkIT-Basket.git /opt/kyle-jeynes-submission && cd /opt/kyle-jeynes-submisson

# generate autoload with composer
composer dump-autoload -o

# migrate the database
php -q /opt/kyle-jeynes-submission/public/index.php MigrateDatabase

# use the application
php -q /opt/kyle-jeynes-submission/public/index.php PriceBasket "Kyes Special Soup" "Kyes Special Soup" Bread
```

## Extending products

Migrations can be found in [app/Database/migrations](https://github.com/Kyle-Jeynes/ClockWorkIT-Basket/tree/main/app/Database/migrations). Products and Discounts can be added and removed from here to expand the products.

## Extending the application

- All of the magic happens in the [app/Console/Controllers/BasketController.php](https://github.com/Kyle-Jeynes/ClockWorkIT-Basket/blob/main/app/Console/Controllers/BasketController.php).
- This controller is executed via the [app/Console/Console.php](https://github.com/Kyle-Jeynes/ClockWorkIT-Basket/blob/main/app/Console/Console.php).

## Products to choose from

- Kyes Special Soup (Recommended) @ £0.65/tin
- Bread (50% off each loaf when you purchase 2 recommended soups) @ £0.80/loaf
- Milk @ £1.30/bottle
- Apple Bag (10% Off) @ £1.00/bag

## Summary

Everything was built from scratch from the ground up in an attempt to focus the appliation around using Vanilla-PHP with a Laravel feel of the code. Keeping to the specifications, the first argument in the method is the command followed by `n` values of products.

## Database

![Database](https://i.imgur.com/CECqH8r.png)
