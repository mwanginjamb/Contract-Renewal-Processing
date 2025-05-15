<?php

use yii\db\Migration;

class m250515_081417_change_staff_id_number_column_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropIndex(
            '{{%idx-user-staff_id_number}}',
            '{{%user}}'
        );

        $this->dropColumn('{{%user}}', 'staff_id_number');
        $this->addColumn('{{%user}}', 'staff_id_number', $this->string(255)->after('email'));
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

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250515_081417_change_staff_id_number_column_type cannot be reverted.\n";

        return false;
    }
    */
}
