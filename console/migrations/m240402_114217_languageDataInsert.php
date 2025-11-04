<?php

use yii\db\Schema;
use yii\db\Migration;

class m240402_114217_languageDataInsert extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->batchInsert('{{%language}}',
                           ["id", "name", "code", "status"],
                            [
    [
        'id' => '1',
        'name' => 'Русский',
        'code' => 'ru',
        'status' => '1',
    ],
    [
        'id' => '2',
        'name' => 'Английский',
        'code' => 'en',
        'status' => '1',
    ],
    [
        'id' => '3',
        'name' => 'Румынский',
        'code' => 'ro',
        'status' => '0',
    ],
    [
        'id' => '4',
        'name' => 'Немецкий',
        'code' => 'de',
        'status' => '0',
    ],
    [
        'id' => '5',
        'name' => 'Белорусский',
        'code' => 'be',
        'status' => '0',
    ],
]
        );
    }

    public function safeDown()
    {
        //$this->truncateTable('{{%language}} CASCADE');
    }
}
