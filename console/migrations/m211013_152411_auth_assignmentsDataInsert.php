<?php

use yii\db\Schema;
use yii\db\Migration;

class m211013_152411_auth_assignmentsDataInsert extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->batchInsert('{{%auth_assignment}}',
            ["item_name", "user_id", "created_at"],
            [
                [
                    'item_name' => 'admin',
                    'user_id' => '1',
                    'created_at' => '1634138550',
                ],
                [
                    'item_name' => 'admin',
                    'user_id' => '2',
                    'created_at' => '1634138550',
                ],
                [
                    'item_name' => 'manager',
                    'user_id' => '3',
                    'created_at' => '1634138550',
                ],
                [
                    'item_name' => 'demo',
                    'user_id' => '4',
                    'created_at' => '1634138550',
                ],
                [
                    'item_name' => 'super',
                    'user_id' => '5',
                    'created_at' => '1634138550',
                ],
            ]
        );
    }

    public function safeDown()
    {
        $this->truncateTable('{{%auth_assignment}} CASCADE');
    }
}
