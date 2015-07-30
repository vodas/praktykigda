<?php

use yii\db\Schema;
use yii\db\Migration;

class m150709_062656_orderstypestonull extends Migration
{
    public function up()
    {

        $this->alterColumn('orders', 'user_id', "BIGINT(20) UNSIGNED NULL");
        $this->alterColumn('orders', 'user_info', "TEXT CHARACTER SET utf8 COLLATE utf8_polish_ci NULL COMMENT 'information about main user that buy everything'");
        $this->alterColumn('orders', 'other_users', "TEXT CHARACTER SET utf8 COLLATE utf8_polish_ci NULL COMMENT 'serializing(user_id,product_id,amountOfProduct)'");
    }

    public function down()
    {
        echo "m150709_062656_orderstypestonull cannot be reverted.\n";

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
