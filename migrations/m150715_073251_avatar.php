<?php

use yii\db\Schema;
use yii\db\Migration;

class m150715_073251_avatar extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'filename', 'text null');
    }

    public function down()
    {
        echo "m150715_073251_avatar cannot be reverted.\n";

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
