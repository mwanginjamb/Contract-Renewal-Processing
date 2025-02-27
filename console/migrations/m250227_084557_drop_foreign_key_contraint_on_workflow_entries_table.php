<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%foreign_key_contraint_on_workflow_entries}}`.
 */
class m250227_084557_drop_foreign_key_contraint_on_workflow_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-workflow_entries-approver_id', 'workflow_entries');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // add foreign key for table `{{%workflow_template.user_id}}`
        $this->addForeignKey(
            '{{%fk-workflow_entries-approver_id}}',
            '{{%workflow_entries}}',
            'approver_id',
            '{{%workflow_template}}',
            'user_id',
            'CASCADE'
        );
    }
}
