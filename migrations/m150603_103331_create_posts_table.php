<?php

use yii\db\Schema;
use yii\db\Migration;

class m150603_103331_create_posts_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%posts}}', [
            'id' => Schema::TYPE_PK,
            'type' => 'tinyint(1) NOT NULL',
            'text' => Schema::TYPE_TEXT,
            'status' => 'tinyint(1) NOT NULL',
            'tags' => Schema::TYPE_STRING . '(256) DEFAULT NULL',
            'sid' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'url' => Schema::TYPE_STRING . '(2083) DEFAULT NULL',
            'provider' => Schema::TYPE_STRING . '(256) NOT NULL',
            'created' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
            'published' => Schema::TYPE_TIMESTAMP,
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150603_103331_create_posts_table cannot be reverted.\n";

        $this->dropTable('{{%posts}}');

        return false;
    }
}
