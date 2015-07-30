<?php

use yii\db\Schema;
use yii\db\Migration;

class m150706_120356_cleanCopy extends Migration
{
    public function up()
    {
        $this->createTable('orders',[
            "`order_id` bigint(20) unsigned NOT NULL,
             `user_id` bigint(20) unsigned NOT NULL,
             `user_info` text COLLATE utf8_polish_ci NOT NULL COMMENT 'information about main user that buy everything',
             `restaurant_id` bigint(20) unsigned NOT NULL,
             `order_info` text COLLATE utf8_polish_ci NOT NULL COMMENT 'serializing(product_id,amountOfProduct)',
             `status` int(1) NOT NULL COMMENT 'statusOfOrder',
             `other_users` text COLLATE utf8_polish_ci NOT NULL COMMENT 'serializing(user_id,product_id,amountOfProduct)'"
        ],"ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci");
        $this->createTable('products',[
            " `product_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(60) COLLATE utf8_polish_ci NOT NULL,
            `description` text COLLATE utf8_polish_ci NOT NULL,
            PRIMARY KEY (`product_id`)
             "
        ],"ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci");
        $this->createTable('restaurants',[
            "`restaurant_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(60) COLLATE utf8_polish_ci NOT NULL,
            `street` varchar(100) COLLATE utf8_polish_ci NOT NULL,
            `house_nr` int(4) NOT NULL,
            `flat_nr` int(4) NOT NULL,
            `zip_code` varchar(6) COLLATE utf8_polish_ci NOT NULL,
            `city` varchar(100) COLLATE utf8_polish_ci NOT NULL,
            PRIMARY KEY (`restaurant_id`)"
        ],"ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci");
        $this->createTable('review',[
            " `restaurant_id` bigint(20) unsigned NOT NULL,
            `product_id` bigint(20) unsigned NOT NULL,
            `review` varchar(500) COLLATE utf8_polish_ci NOT NULL COMMENT 'review of product',
            KEY `restaurant_id` (`restaurant_id`),
            KEY `product_id` (`product_id`),
            CONSTRAINT `review_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `review_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`restaurant_id`) ON DELETE CASCADE ON UPDATE CASCADE"
        ]," ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci");
        $this->createTable('users',[
            "`user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `login` varchar(25) COLLATE utf8_polish_ci NOT NULL,
            `pass` varchar(255) COLLATE utf8_polish_ci NOT NULL,
            `email` varchar(100) COLLATE utf8_polish_ci NOT NULL,
            `name` varchar(30) COLLATE utf8_polish_ci NOT NULL,
            `surname` varchar(100) COLLATE utf8_polish_ci NOT NULL,
            `street` varchar(100) COLLATE utf8_polish_ci NOT NULL,
            `house_nr` int(4) unsigned NOT NULL,
            `flat_nr` int(4) unsigned NOT NULL,
            `zipcode` varchar(6) COLLATE utf8_polish_ci NOT NULL,
            `city` varchar(100) COLLATE utf8_polish_ci NOT NULL,
            PRIMARY KEY (`user_id`),
            UNIQUE KEY `login` (`login`),
            UNIQUE KEY `email` (`email`)"
        ],"ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci");
        $this->createTable('users_role',[
            "`user_id` bigint(20) unsigned NOT NULL,
            `role_id` int(2) unsigned NOT NULL COMMENT 'role for permissions(admin-1,user-2 etc)',
            KEY `user_id` (`user_id`),
            CONSTRAINT `users_role_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE"
        ],"ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci");
        $this->createTable('warehouse',[
            "`restaurant_id` bigint(20) unsigned NOT NULL,
            `product_id` bigint(20) unsigned NOT NULL,
            `description` varchar(500) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'optional if description in warehouse is different than in products',
            `price` float NOT NULL,
            KEY `restaurant_id` (`restaurant_id`),
            KEY `product_id` (`product_id`),
            CONSTRAINT `warehouse_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `warehouse_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`restaurant_id`) ON DELETE CASCADE ON UPDATE CASCADE"
        ],"ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci");


    }

    public function down()
    {
        echo "m150706_120356_cleanCopy cannot be reverted.\n";

        return false;
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
