<?php

use yii\db\Schema;
use yii\db\Migration;

class m150708_114531_RestaurantTypesToNull extends Migration
{
    public function up()
    {
        //fields can have null value
        $this->alterColumn('restaurants', 'house_nr', "INT(4) NULL");
        $this->alterColumn('restaurants', 'flat_nr', "INT(4) NULL");
    }

    public function down()
    {
        echo "m150708_114531_RestaurantIntToNull cannot be reverted.\n";

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
