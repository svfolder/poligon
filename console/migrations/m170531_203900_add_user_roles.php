<?php

use yii\db\Migration;

class m170531_203900_add_user_roles extends Migration
{
    public function safeUp()
    {
        $this->batchInsert('{{%auth_item}}', ['type', 'name', 'description'], [
            [1, 'user', 'User'],
            [1, 'admin', 'Admin'],
            [1, 'manager', 'Manager'],
            [1, 'demo', 'Demo'],
            [1, 'super', 'Super'],
            [1, 'developer', 'Разработчик'],
        ]);

        $this->batchInsert('{{%auth_item_child}}', ['parent', 'child'], [
            ['manager', 'user'],
            ['admin', 'manager'],
            ['demo', 'manager'],
            ['super', 'admin'],
            ['developer', 'super'],
        ]);

        $this->execute('INSERT INTO {{%auth_assignment}} (item_name, user_id) SELECT \'user\', u.id FROM {{%users}} u ORDER BY u.id');
    }

    public function down()
    {
        $this->delete('{{%auth_item}}', ['name' => ['user', 'admin']]);
    }
}
