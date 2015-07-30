<?php

use yii\db\Schema;
use yii\db\Migration;

class m150710_095834_newautoincrement extends Migration
{
    public function up()
    {
        $this->alterColumn('orders', 'order_id', "BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`order_id`)");
    }

    public function down()
    {
        echo "m150710_095834_newautoincrement cannot be reverted.\n";

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
