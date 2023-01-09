<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%user}}`.
 */
class m230109_122702_drop_moderator_column_from_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%user}}', 'moderator');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%user}}', 'moderator', $this->tinyInteger(1));
    }
}
