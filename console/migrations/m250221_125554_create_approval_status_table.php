<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%approval_status}}`.
 */
class m250221_125554_create_approval_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%approval_status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100),
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
        $this->dropTable('{{%approval_status}}');
    }
}
