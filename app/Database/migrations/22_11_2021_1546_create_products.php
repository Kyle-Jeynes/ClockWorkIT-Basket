<?php

/**
 * PoC - Typically, I would use Laravel's built-in migrations.
 */

return [
    "CREATE TABLE products (
        id int NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        price decimal(19,2) NOT NULL,
        PRIMARY KEY (id),
        UNIQUE (name)
    )",
    "INSERT INTO products (name, price) VALUES ('Kyes Special Soup', '0.65')",
    "INSERT INTO products (name, price) VALUES ('Bread', '0.80')",
    "INSERT INTO products (name, price) VALUES ('Milk', '1.30')",
    "INSERT INTO products (name, price) VALUES ('Apple Bag', '1.00')",
];