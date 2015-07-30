<?php

use yii\db\Schema;
use yii\db\Migration;

class m150715_134045_newsletter extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'newsletter', 'SMALLINT(1) NULL');
    }

    public function down()
    {
        echo "m150715_134045_newsletter cannot be reverted.\n";

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
