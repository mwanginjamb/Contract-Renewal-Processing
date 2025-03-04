<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%contracts}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%contract_batch}}`
 */
class m250304_185510_add_contract_batch_id_column_to_contracts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%contracts}}', 'contract_batch_id', $this->integer());

        // creates index for column `contract_batch_id`
        $this->createIndex(
            '{{%idx-contracts-contract_batch_id}}',
            '{{%contracts}}',
            'contract_batch_id'
        );

        // add foreign key for table `{{%contract_batch}}`
        $this->addForeignKey(
            '{{%fk-contracts-contract_batch_id}}',
            '{{%contracts}}',
            'contract_batch_id',
            '{{%contract_batch}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%contract_batch}}`
        $this->dropForeignKey(
            '{{%fk-contracts-contract_batch_id}}',
            '{{%contracts}}'
        );

        // drops index for column `contract_batch_id`
        $this->dropIndex(
            '{{%idx-contracts-contract_batch_id}}',
            '{{%contracts}}'
        );

        $this->dropColumn('{{%contracts}}', 'contract_batch_id');
    }
}
