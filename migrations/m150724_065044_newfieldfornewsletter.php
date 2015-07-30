<?php

use yii\db\Schema;
use yii\db\Migration;

class m150724_065044_newfieldfornewsletter extends Migration
{
    public function up()
    {
        $this->addColumn('newsletter', 'sent', 'BOOLEAN NOT NULL DEFAULT FALSE');
    }

    public function down()
    {
        echo "m150724_065044_newfieldfornewsletter cannot be reverted.\n";

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
