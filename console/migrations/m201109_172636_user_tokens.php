<?php

use yii\db\Schema;
use yii\db\Migration;

class m201109_172636_user_tokens extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            '{{%user_tokens}}',
            [
                'id'=> $this->primaryKey(11),
                'user_id'=> $this->integer(11)->notNull(),
                'new_password'=> $this->string(255)->null()->defaultValue(null),
                'new_email'=> $this->string(255)->null()->defaultValue(null),
                'new_username'=> $this->string(255)->null()->defaultValue(null),
                'token_password'=> $this->string(255)->null()->defaultValue(null),
                'token_email'=> $this->string(255)->null()->defaultValue(null),
                'token_username'=> $this->string(255)->null()->defaultValue(null),
            ],$tableOptions
        );
        $this->createIndex('user_id','{{%user_tokens}}',['user_id'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('user_id', '{{%user_tokens}}');
        $this->dropTable('{{%user_tokens}}');
    }
}
