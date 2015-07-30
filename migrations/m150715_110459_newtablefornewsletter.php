<?php

use yii\db\Schema;
use yii\db\Migration;

class m150715_110459_newtablefornewsletter extends Migration
{
    public function up()
    {
        $this->createTable('newsletter',[
            "`newsletter_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `news` text COLLATE utf8_polish_ci,
           PRIMARY KEY (`newsletter_id`) "
        ],"ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci");

    }

    public function down()
    {
        echo "m150715_110459_newtablefornewsletter cannot be reverted.\n";

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
