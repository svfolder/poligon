<?php

use yii\db\Schema;
use yii\db\Migration;

class m230822_000839_menu extends Migration
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
            '{{%menu}}',
            [
                'id'=> $this->primaryKey(11),
                'name'=> $this->string(255)->notNull(),
                'alias'=> $this->string(50)->notNull(),
                'status'=> $this->integer(2)->notNull()->defaultValue(1),
            ],$tableOptions
        );
        $this->createIndex('alias','{{%menu}}',['alias'],true);

    }

    public function safeDown()
    {
        $this->dropIndex('alias', '{{%menu}}');
        $this->dropTable('{{%menu}}');
    }
}
