<?php

use yii\db\Migration;
use yii\db\Schema;

class m150603_103349_create_files_table extends Migration {
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%files}}', [
            'id'   => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(256) NOT NULL',
            'url'  => Schema::TYPE_TEXT . ' NOT NULL',
        ], $tableOptions);
    }

    public function down() {
        echo "m150603_103409_create_files_table cannot be reverted.\n";

        $this->dropTable('{{%files}}');

        return false;
    }
}
