<?php

use yii\db\Schema;
use yii\db\Migration;

class m211013_152353_userDataInsert extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->batchInsert('{{%user}}',
            ["id", "username", "first_name", "last_name", "image", "auth_key", "password_hash", "password_reset_token", "email", "status", "created_at", "updated_at", "email_confirm_token", "is_banned", "activity_at"],
            [
                [
                    'id' => '1',
                    'username' => 'svfolder',
                    'first_name' => 'Дмитрий',
                    'last_name' => 'Неважно',
                    'image' => null,
                    'auth_key' => 'WZOhRFp7TLiLCqazxUrQJZlI6n7fU1hz',
                    'password_hash' => '$2y$13$dB3v9uf0y28mz5wae.kIA.ZTr7trqzHsyQ6.betRP8tWfrBy/JZYu',
                    'password_reset_token' => null,
                    'email' => 'sv_Gvozdj@mail.ru',
                    'status' => '10',
                    'created_at' => '1634138550',
                    'updated_at' => '1634138550',
                    'email_confirm_token' => null,
                    'is_banned' => '0',
                    'activity_at' => '0',
                ],
                [
                    'id' => '2',
                    'username' => 'admin',
                    'first_name' => 'Admin',
                    'last_name' => 'Неважно',
                    'image' => null,
                    'auth_key' => 'WZOhRFp7TLiLCqazxUrQJZlI6n7fU1hz',
                    'password_hash' => '$2y$13$dB3v9uf0y28mz5wae.kIA.ZTr7trqzHsyQ6.betRP8tWfrBy/JZYu',
                    'password_reset_token' => null,
                    'email' => 'admin@demo.open',
                    'status' => '10',
                    'created_at' => '1634138550',
                    'updated_at' => '1634138550',
                    'email_confirm_token' => null,
                    'is_banned' => '0',
                    'activity_at' => '0',
                ],
                [
                    'id' => '3',
                    'username' => 'manager',
                    'first_name' => 'Manager',
                    'last_name' => 'Неважно',
                    'image' => null,
                    'auth_key' => 'WZOhRFp7TLiLCqazxUrQJZlI6n7fU1hz',
                    'password_hash' => '$2y$13$dB3v9uf0y28mz5wae.kIA.ZTr7trqzHsyQ6.betRP8tWfrBy/JZYu',
                    'password_reset_token' => null,
                    'email' => 'manager@demo.open',
                    'status' => '10',
                    'created_at' => '1634138550',
                    'updated_at' => '1634138550',
                    'email_confirm_token' => null,
                    'is_banned' => '0',
                    'activity_at' => '0',
                ],
                [
                    'id' => '4',
                    'username' => 'demo',
                    'first_name' => 'Demo',
                    'last_name' => 'Неважно',
                    'image' => null,
                    'auth_key' => 'WZOhRFp7TLiLCqazxUrQJZlI6n7fU1hz',
                    'password_hash' => '$2y$13$dB3v9uf0y28mz5wae.kIA.ZTr7trqzHsyQ6.betRP8tWfrBy/JZYu',
                    'password_reset_token' => null,
                    'email' => 'demo@demo.open',
                    'status' => '10',
                    'created_at' => '1634138550',
                    'updated_at' => '1634138550',
                    'email_confirm_token' => null,
                    'is_banned' => '0',
                    'activity_at' => '0',
                ],
                [
                    'id' => '5',
                    'username' => 'super',
                    'first_name' => 'Super',
                    'last_name' => 'Неважно',
                    'image' => null,
                    'auth_key' => 'WZOhRFp7TLiLCqazxUrQJZlI6n7fU1hz',
                    'password_hash' => '$2y$13$dB3v9uf0y28mz5wae.kIA.ZTr7trqzHsyQ6.betRP8tWfrBy/JZYu',
                    'password_reset_token' => null,
                    'email' => 'super@demo.open',
                    'status' => '10',
                    'created_at' => '1634138550',
                    'updated_at' => '1634138550',
                    'email_confirm_token' => null,
                    'is_banned' => '0',
                    'activity_at' => '0',
                ],
            ]
        );
    }

    public function safeDown()
    {
        $this->truncateTable('{{%user}} CASCADE');
    }
}
