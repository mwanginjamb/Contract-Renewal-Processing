<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%contracts}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%contracts}}`
 */
class m250227_072936_add_approval_status_column_to_contracts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%contracts}}', 'approval_status', $this->integer());

        // creates index for column `approval_status`
        $this->createIndex(
            '{{%idx-contracts-approval_status}}',
            '{{%contracts}}',
            'approval_status'
        );

        // add foreign key for table `{{%contracts}}`
        $this->addForeignKey(
            '{{%fk-contracts-approval_status}}',
            '{{%contracts}}',
            'approval_status',
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
            '{{%fk-contracts-approval_status}}',
            '{{%contracts}}'
        );

        // drops index for column `approval_status`
        $this->dropIndex(
            '{{%idx-contracts-approval_status}}',
            '{{%contracts}}'
        );

        $this->dropColumn('{{%contracts}}', 'approval_status');
    }
}
