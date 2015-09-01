<?php

use yii\db\Schema;
use yii\db\Migration;

class m150818_051036_create_user_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%login_history}}', [
            'id' => Schema::TYPE_PK,
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NULL',
            'user_email' => Schema::TYPE_STRING . ' NULL',
            'user_name' => Schema::TYPE_STRING . ' NULL',
            'user_ip' => Schema::TYPE_STRING . ' NULL',
            'user_host' => Schema::TYPE_STRING . ' NULL',
            'user_agent' => Schema::TYPE_STRING . ' NULL'
        ], $tableOptions);

        $this->createIndex('idx_lh_user_id', '{{%login_history}}', 'user_id');

        $this->addColumn('{{%user}}', 'first_name', Schema::TYPE_STRING.' NOT NULL');
        $this->addColumn('{{%user}}', 'last_name', Schema::TYPE_STRING.' NOT NULL');
        $this->addColumn('{{%user}}', 'country', Schema::TYPE_STRING . '(2) DEFAULT \'GB\' NOT NULL');

        $this->addColumn('{{%user}}', 'gender', Schema::TYPE_STRING.' NULL'); // male, female
        $this->addColumn('{{%user}}', 'region', Schema::TYPE_STRING.' NULL'); // country region
        $this->addColumn('{{%user}}', 'address1', Schema::TYPE_STRING.' NULL');
        $this->addColumn('{{%user}}', 'address2', Schema::TYPE_STRING.' NULL');
        $this->addColumn('{{%user}}', 'zip', Schema::TYPE_STRING.' NULL');
        $this->addColumn('{{%user}}', 'telephone', Schema::TYPE_STRING.' NULL');
        $this->addColumn('{{%user}}', 'timezone', Schema::TYPE_STRING.' NULL');
    }

    public function down()
    {
        $this->dropTable('{{%login_history}}');

        $this->dropColumn('{{%user}}', 'first_name');
        $this->dropColumn('{{%user}}', 'last_name');
        $this->dropColumn('{{%user}}', 'country');

        $this->dropColumn('{{%user}}', 'gender');
        $this->dropColumn('{{%user}}', 'region');
        $this->dropColumn('{{%user}}', 'address1');
        $this->dropColumn('{{%user}}', 'address2');
        $this->dropColumn('{{%user}}', 'zip');
        $this->dropColumn('{{%user}}', 'telephone');
        $this->dropColumn('{{%user}}', 'timezone');
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
