<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%workflow_entries}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%contracts}}`
 */
class m250222_065411_add_contract_id_column_to_workflow_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%workflow_entries}}', 'contract_id', $this->integer());

        // creates index for column `contract_id`
        $this->createIndex(
            '{{%idx-workflow_entries-contract_id}}',
            '{{%workflow_entries}}',
            'contract_id'
        );

        // add foreign key for table `{{%contracts}}`
        $this->addForeignKey(
            '{{%fk-workflow_entries-contract_id}}',
            '{{%workflow_entries}}',
            'contract_id',
            '{{%contracts}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%contracts}}`
        $this->dropForeignKey(
            '{{%fk-workflow_entries-contract_id}}',
            '{{%workflow_entries}}'
        );

        // drops index for column `contract_id`
        $this->dropIndex(
            '{{%idx-workflow_entries-contract_id}}',
            '{{%workflow_entries}}'
        );

        $this->dropColumn('{{%workflow_entries}}', 'contract_id');
    }
}
