<?php

use yii\db\Migration;

class m170531_203800_change_auth_assignments_table extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%auth_assignment}}', 'user_id', $this->integer()->notNull());

        $this->createIndex('{{%idx-auth_assignment-user_id}}', '{{%auth_assignment}}', 'user_id');

        $this->addForeignKey('{{%fk-auth_assignment-user_id}}', '{{%auth_assignment}}', 'user_id', '{{%users}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('{{%fk-auth_assignment-user_id}}', '{{%auth_assignment}}');

        $this->dropIndex('{{%idx-auth_assignment-user_id}}', '{{%auth_assignment}}');

        $this->alterColumn('{{%auth_assignment}}', 'user_id', $this->string(64)->notNull());
    }
}
