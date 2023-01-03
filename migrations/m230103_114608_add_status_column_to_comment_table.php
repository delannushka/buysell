<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%comment}}`.
 */
class m230103_114608_add_status_column_to_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%comment}}', 'status', $this->tinyInteger(1)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%comment}}', 'status');
    }
}
