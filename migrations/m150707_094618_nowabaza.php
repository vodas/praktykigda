<?php

use yii\db\Schema;
use yii\db\Migration;

class m150707_094618_nowabaza extends Migration
{
    public function up()
    {

        $this->addColumn('users', 'token', 'VARCHAR(255) COLLATE utf8_polish_ci');

    }

    public function down()
    {
        echo "m150707_094618_nowabaza cannot be reverted.\n";

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
