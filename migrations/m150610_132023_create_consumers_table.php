<?php

use yii\db\Migration;
use yii\db\Schema;

class m150610_132023_create_consumers_table extends Migration {
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%consumers}}', [
            'id'      => Schema::TYPE_PK,
            'name'    => Schema::TYPE_STRING . '(256) NOT NULL',
            'options' => 'LONGTEXT NOT NULL',
        ], $tableOptions);
    }

    public function down() {
        echo "m150610_132023_create_consumers_table cannot be reverted.\n";

        $this->dropTable('{{%consumers}}');

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
