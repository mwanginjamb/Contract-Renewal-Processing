<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%workflow_template_members}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%workflow_template}}`
 */
class m250225_100258_create_workflow_template_members_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%workflow_template_members}}', [
            'id' => $this->primaryKey(),
            'approver_name' => $this->string(200),
            'approver_email' => $this->string(150),
            'approver_phone_number' => $this->string(100),
            'sequence' => $this->integer(),
            'user_id' => $this->integer(),
            'workflow_id' => $this->integer(),
            'created_at' => $this->integer(25),
            'updated_at' => $this->integer(25),
            'updated_by' => $this->integer(25),
            'created_by' => $this->integer(),
        ]);

        // creates index for column `workflow_id`
        $this->createIndex(
            '{{%idx-workflow_template_members-workflow_id}}',
            '{{%workflow_template_members}}',
            'workflow_id'
        );

        // add foreign key for table `{{%workflow_template}}`
        $this->addForeignKey(
            '{{%fk-workflow_template_members-workflow_id}}',
            '{{%workflow_template_members}}',
            'workflow_id',
            '{{%workflow_template}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%workflow_template}}`
        $this->dropForeignKey(
            '{{%fk-workflow_template_members-workflow_id}}',
            '{{%workflow_template_members}}'
        );

        // drops index for column `workflow_id`
        $this->dropIndex(
            '{{%idx-workflow_template_members-workflow_id}}',
            '{{%workflow_template_members}}'
        );

        $this->dropTable('{{%workflow_template_members}}');
    }
}
