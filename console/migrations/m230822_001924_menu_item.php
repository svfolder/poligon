<?php

use yii\db\Schema;
use yii\db\Migration;

class m230822_001924_menu_item extends Migration
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
            '{{%menu_item}}',
            [
                'id'=> $this->primaryKey(11),
                'parent_id'=> $this->integer(11)->null()->defaultValue(null),
                'menu_id'=> $this->integer(11)->notNull(),
                'name'=> $this->string(255)->notNull(),
                'link'=> $this->string(255)->notNull(),
                'sort'=> $this->integer(11)->notNull()->defaultValue(0),
                'status'=> $this->integer(2)->notNull()->defaultValue(1),
            ],$tableOptions
        );
        $this->createIndex('menu_id','{{%menu_item}}',['menu_id'],false);
        $this->createIndex('parent_id','{{%menu_item}}',['parent_id'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('menu_id', '{{%menu_item}}');
        $this->dropIndex('parent_id', '{{%menu_item}}');
        $this->dropTable('{{%menu_item}}');
    }
}
