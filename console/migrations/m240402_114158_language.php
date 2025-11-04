<?php

use yii\db\Schema;
use yii\db\Migration;

class m240402_114158_language extends Migration
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
            '{{%language}}',
            [
                'id'=> $this->primaryKey(11),
                'name'=> $this->string(255)->notNull(),
                'code'=> $this->string(10)->notNull(),
                'status'=> $this->integer(2)->notNull()->defaultValue(1),
            ],$tableOptions
        );
        $this->createIndex('code','{{%language}}',['code'],true);

    }

    public function safeDown()
    {
        $this->dropIndex('code', '{{%language}}');
        $this->dropTable('{{%language}}');
    }
}
