<?php

use yii\db\Schema;
use yii\db\Migration;

class m150716_131908_facebookId extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'facebook_id', 'TEXT NULL');
        $this->alterColumn('users', 'login', 'VARCHAR(100) NOT NULL');
    }

    public function down()
    {
        echo "m150716_131908_facebookId cannot be reverted.\n";

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
