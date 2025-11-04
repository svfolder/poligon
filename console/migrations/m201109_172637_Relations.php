<?php

use yii\db\Schema;
use yii\db\Migration;

class m201109_172637_Relations extends Migration
{

    public function init()
    {
       $this->db = 'db';
       parent::init();
    }

    public function safeUp()
    {
        $this->addForeignKey('fk_user_tokens_user_id',
            '{{%user_tokens}}','user_id',
            '{{%users}}','id',
            'CASCADE','CASCADE'
         );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_user_tokens_user_id', '{{%user_tokens}}');
    }
}
