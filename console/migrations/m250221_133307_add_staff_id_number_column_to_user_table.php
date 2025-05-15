<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m250221_133307_add_staff_id_number_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // First drop unique index if exists (MySQL/Postgres can't change type directly with unique)
        $this->dropIndex('staff_id_number', '{{%user}}');
        // Then alter the column type
        $this->alterColumn('{{%user}}', 'staff_id_number', $this->integer()->notNull());
        // Optionally re-add unique index if required on integer
        $this->createIndex('staff_id_number', '{{%user}}', 'staff_id_number', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Revert the column back to string with 150 length
        $this->dropIndex('staff_id_number', '{{%user}}');
        $this->alterColumn('{{%user}}', 'staff_id_number', $this->string(150)->notNull());
        $this->createIndex('staff_id_number', '{{%user}}', 'staff_id_number', true);
    }
}
