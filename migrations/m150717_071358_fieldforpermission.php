<?php

use yii\db\Schema;
use yii\db\Migration;

class m150717_071358_fieldforpermission extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'user_role', 'SMALLINT(2) UNSIGNED NULL');
    }

    public function down()
    {
        echo "m150717_071358_fieldforpermission cannot be reverted.\n";

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
