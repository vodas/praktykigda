<?php

use yii\db\Schema;
use yii\db\Migration;

class m150716_091432_new_field_for_twitter_id extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'twitter_id', 'BIGINT(20) UNSIGNED NULL');
    }

    public function down()
    {
        echo "m150716_091432_new_field_for_twitter_id cannot be reverted.\n";

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
