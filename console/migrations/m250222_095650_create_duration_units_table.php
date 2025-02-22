<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%duration_units}}`.
 */
class m250222_095650_create_duration_units_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%duration_units}}', [
            'id' => $this->primaryKey(),
            'unit' => $this->string(),
            'created_at' => $this->integer(25),
            'updated_at' => $this->integer(25),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%duration_units}}');
    }
}
