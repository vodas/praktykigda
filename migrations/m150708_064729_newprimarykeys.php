<?php

use yii\db\Schema;
use yii\db\Migration;

class m150708_064729_newprimarykeys extends Migration
{
    public function up()
    {
        $this->addColumn('review', 'review_id', 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`review_id`)');
        $this->addColumn('warehouse', 'warehouse_id', 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`warehouse_id`)');
    }

    public function down()
    {
        echo "m150708_064729_newprimarykeys cannot be reverted.\n";

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
