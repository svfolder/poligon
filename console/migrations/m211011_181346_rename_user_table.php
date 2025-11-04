<?php

use yii\db\Migration;

/**
 * Class m211011_181346_rename_user_table
 */
class m211011_181346_rename_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('{{%users}}', '{{%user}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('{{%user}}', '{{%users}}');
    }

}
