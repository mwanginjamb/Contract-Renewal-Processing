<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%workflow_entries}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%workflow_template}}`
 * - `{{%approval_status}}`
 */
class m250221_130033_create_workflow_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%workflow_entries}}', [
            'id' => $this->primaryKey(),
            'template_id' => $this->integer(),
            'approver_id' => $this->integer(),
            'approval_status' => $this->integer(),
            'actioned_date' => $this->integer(30),
            'created_at' => $this->integer(30),
            'updated_at' => $this->integer(30),
            'created_by' => $this->integer(30),
            'updated_by' => $this->integer(30),
        ]);

        // creates index for column `template_id`
        $this->createIndex(
            '{{%idx-workflow_entries-template_id}}',
            '{{%workflow_entries}}',
            'template_id'
        );

        // creates index for column `approver_id`
        $this->createIndex(
            '{{%idx-workflow_entries-approver_id}}',
            '{{%workflow_entries}}',
            'approver_id'
        );

        // add foreign key for table `{{%workflow_template}}`
        $this->addForeignKey(
            '{{%fk-workflow_entries-template_id}}',
            '{{%workflow_entries}}',
            'template_id',
            '{{%workflow_template}}',
            'id',
            'CASCADE'
        );
        // add foreign key for table `{{%workflow_template.user_id}}`
        $this->addForeignKey(
            '{{%fk-workflow_entries-approver_id}}',
            '{{%workflow_entries}}',
            'approver_id',
            '{{%workflow_template}}',
            'user_id',
            'CASCADE'
        );

        // creates index for column `approval_status`
        $this->createIndex(
            '{{%idx-workflow_entries-approval_status}}',
            '{{%workflow_entries}}',
            'approval_status'
        );

        // add foreign key for table `{{%approval_status}}`
        $this->addForeignKey(
            '{{%fk-workflow_entries-approval_status}}',
            '{{%workflow_entries}}',
            'approval_status',
            '{{%approval_status}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%workflow_template}}`
        $this->dropForeignKey(
            '{{%fk-workflow_entries-template_id}}',
            '{{%workflow_entries}}'
        );

        // drops index for column `template_id`
        $this->dropIndex(
            '{{%idx-workflow_entries-template_id}}',
            '{{%workflow_entries}}'
        );

        // drops foreign key for table `{{%approval_status}}`
        $this->dropForeignKey(
            '{{%fk-workflow_entries-approval_status}}',
            '{{%workflow_entries}}'
        );

        // drops index for column `approval_status`
        $this->dropIndex(
            '{{%idx-workflow_entries-approval_status}}',
            '{{%workflow_entries}}'
        );

        $this->dropTable('{{%workflow_entries}}');
    }
}
