<?php

use yii\db\Schema;
use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
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
            'created' => Schema::TYPE_INTEGER . ' NOT NULL',
            'published' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->createTable('{{%files}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(256) NOT NULL',
            'url' => Schema::TYPE_TEXT . ' NOT NULL',
        ], $tableOptions);

        $this->createTable('{{%post_file}}', [
            'post_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'file_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'PRIMARY KEY (`post_id`,`file_id`)'
        ], $tableOptions);

        $this->addForeignKey('pfile', '{{%post_file}}', 'file_id', '{{%files}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('post', '{{%post_file}}', 'post_id', '{{%posts}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        echo "m130524_201442_init cannot be reverted.\n";
        
        $this->dropTable('{{%posts}}');
        $this->dropTable('{{%files}}');
        $this->dropTable('{{%post_file}}');
    }
}
