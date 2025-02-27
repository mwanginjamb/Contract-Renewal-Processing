<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%workflow_entries}}`.
 */
class m250227_213602_add_sequence_column_to_workflow_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%workflow_entries}}', 'sequence', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%workflow_entries}}', 'sequence');
    }
}
