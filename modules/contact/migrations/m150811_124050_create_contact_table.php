<?php

use yii\db\Schema;
use yii\db\Migration;

class m150811_124050_create_contact_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%contact}}', [
            'id' => Schema::TYPE_PK,
            'parent_id' => Schema::TYPE_INTEGER . ' NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NULL',
            'user_email' => Schema::TYPE_STRING . ' NULL',
            'user_name' => Schema::TYPE_STRING . ' NULL',
            'user_message' => Schema::TYPE_TEXT . ' NOT NULL',
            'user_ip' => Schema::TYPE_STRING . ' NULL',
            'user_host' => Schema::TYPE_STRING . ' NULL',
            'user_agent' => Schema::TYPE_STRING . ' NULL',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
        ], $tableOptions);

        $this->createIndex('idx_contact_user_id', '{{%contact}}', 'user_id');
        $this->createIndex('idx_user_status', '{{%contact}}', 'status');
    }

    public function down()
    {
        $this->dropTable('{{%contact}}');
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
