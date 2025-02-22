<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%contracts}}`.
 */
class m250221_132221_create_contracts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contracts}}', [
            'id' => $this->primaryKey(),
            'contract_number' => $this->string(50),
            'employee_name' => $this->string(200),
            'employee_number' => $this->string(35),
            'original_contract_path' => $this->string(450),
            'signed_contract_path' => $this->text(),
            'contract_duration' => $this->string(),
            'employee_workstation' => $this->string(250),
            'created_at' => $this->integer(30),
            'updated_at' => $this->integer(30),
            'created_by' => $this->integer(30),
            'updated_by' => $this->integer(30),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%contracts}}');
    }
}
