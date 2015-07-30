<?php

use yii\db\Schema;
use yii\db\Migration;

class m150708_074319_zmianapolusers extends Migration
{
    public function up()
    {
        $this->alterColumn('users','street','VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_polish_ci NULL DEFAULT NULL');
        $this->alterColumn('users','house_nr','INT(4) UNSIGNED NULL DEFAULT NULL');
        $this->alterColumn('users','flat_nr','INT(4) UNSIGNED NULL DEFAULT NULL');
        $this->alterColumn('users','zipcode','VARCHAR(6) CHARACTER SET utf8 COLLATE utf8_polish_ci NULL DEFAULT NULL');
        $this->alterColumn('users','city','VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_polish_ci NULL DEFAULT NULL');
    }

    public function down()
    {
        echo "m150708_074319_zmianapolusers cannot be reverted.\n";

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
