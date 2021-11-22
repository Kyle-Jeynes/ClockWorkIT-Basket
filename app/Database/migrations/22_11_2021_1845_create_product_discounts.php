<?php

return [
    "CREATE TABLE product_discounts (
        id INT NOT NULL AUTO_INCREMENT,
        product_id INT NOT NULL,
        percentage INT NOT NULL DEFAULT '10',
        product_effects_id INT NOT NULL,
        when_product_has_quantity INT NOT NULL DEFAULT '1',
        PRIMARY KEY(id)
    )",
    "INSERT INTO product_discounts (product_id, percentage, product_effects_id, when_product_has_quantity) VALUES (1, 50, 2, 2)",
    "INSERT INTO product_discounts (product_id, product_effects_id) VALUES (4, 4)",
];