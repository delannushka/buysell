<?php

use yii\db\Migration;

/**
 * Class m230102_131935_create_fulltextindex_post_table_column_header
 */
class m230102_131935_create_fulltextindex_post_table_column_header extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE ticket ADD FULLTEXT INDEX search (header)");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230102_131935_create_fulltextindex_post_table_column_header cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230102_131935_create_fulltextindex_post_table_column_header cannot be reverted.\n";

        return false;
    }
    */
}
