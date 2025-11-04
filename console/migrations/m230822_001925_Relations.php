<?php

use yii\db\Schema;
use yii\db\Migration;

class m230822_001925_Relations extends Migration
{

    public function init()
    {
       $this->db = 'db';
       parent::init();
    }

    public function safeUp()
    {
        $this->addForeignKey('fk_menu_item_menu_id',
            '{{%menu_item}}','menu_id',
            '{{%menu}}','id',
            'CASCADE','CASCADE'
         );
        $this->addForeignKey('fk_menu_item_parent_id',
            '{{%menu_item}}','parent_id',
            '{{%menu_item}}','id',
            'CASCADE','CASCADE'
         );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_menu_item_menu_id', '{{%menu_item}}');
        $this->dropForeignKey('fk_menu_item_parent_id', '{{%menu_item}}');
    }
}
