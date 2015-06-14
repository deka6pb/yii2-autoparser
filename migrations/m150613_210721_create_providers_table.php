<?php

use yii\db\Schema;
use yii\db\Migration;

class m150613_210721_create_providers_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%providers}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(256) NOT NULL',
            'options' => 'LONGTEXT NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150613_210721_create_providers_table cannot be reverted.\n";

        $this->dropTable('{{%providers}}');

        return false;
    }
}
