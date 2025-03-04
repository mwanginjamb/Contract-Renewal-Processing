<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%contract_batch}}`.
 */
class m250304_185116_create_contract_batch_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contract_batch}}', [
            'id' => $this->primaryKey(),
            'batch_description' => $this->text(),
            'created_at' => $this->integer(25),
            'updated_at' => $this->integer(25),
            'created_by' => $this->integer(25),
            'updated_by' => $this->integer(25),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%contract_batch}}');
    }
}
