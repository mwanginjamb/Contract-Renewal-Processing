<?php

use yii\db\Migration;

class m250325_164542_add_indexes_on_workflow_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex(
            '{{%idx-workflow_entries-sequence}}',
            '{{%workflow_entries}}',
            'sequence'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%idx-workflow_entries-sequence}}', '{{%workflow_entries}}');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250325_164542_add_indexes_on_workflow_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
