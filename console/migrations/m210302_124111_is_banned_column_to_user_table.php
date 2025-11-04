<?php

use yii\db\Migration;

/**
 * Class m210302_124111_is_banned_column_to_user_table
 */
class m210302_124111_is_banned_column_to_user_table extends Migration
{

    public function up()
    {
        $this->addColumn('{{%users}}', 'is_banned', $this->integer(2)->notNull()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%users}}', 'is_banned');
    }

}
