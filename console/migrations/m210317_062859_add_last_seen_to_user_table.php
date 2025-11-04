<?php

use yii\db\Migration;

/**
 * Class m210317_062859_add_activity_at_to_user_table
 */
class m210317_062859_add_last_seen_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'activity_at', $this->integer(11)->notNull()->after('is_banned')->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'activity_at');
    }

}
