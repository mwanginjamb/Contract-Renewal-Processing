<?php

use yii\db\Migration;

class m250314_121235_drop_foreign_key_fk_contracts_approval_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-contracts-approval_status', 'contracts');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addForeignKey('fk-contracts-approval_status', 'contracts', 'approval_status', 'approval_status', 'id', 'CASCADE');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250314_121235_drop_foreign_key_fk_contracts_approval_status cannot be reverted.\n";

        return false;
    }
    */
}
