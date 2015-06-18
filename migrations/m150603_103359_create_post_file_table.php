<?php

use yii\db\Migration;
use yii\db\Schema;

class m150603_103359_create_post_file_table extends Migration {
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%post_file}}', [
            'post_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'file_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'PRIMARY KEY (`post_id`,`file_id`)'
        ], $tableOptions);

        $this->addForeignKey('pfile', '{{%post_file}}', 'file_id', '{{%files}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('post', '{{%post_file}}', 'post_id', '{{%posts}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down() {
        echo "m150603_103359_create_post_file_table cannot be reverted.\n";

        $this->dropTable('{{%post_file}}');

        return false;
    }
}
