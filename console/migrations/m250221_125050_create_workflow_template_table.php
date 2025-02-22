<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%workflow_template}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m250221_125050_create_workflow_template_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%workflow_template}}', [
            'id' => $this->primaryKey(),
            'sequence' => $this->integer(),
            'user_id' => $this->integer(),
            'workflow_name' => $this->string(150),
            'workflow_role' => $this->string(150),
            'created_at' => $this->integer(30),
            'updated_at' => $this->integer(30),
            'created_by' => $this->integer(30),
            'updated_by' => $this->integer(30),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-workflow_template-user_id}}',
            '{{%workflow_template}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-workflow_template-user_id}}',
            '{{%workflow_template}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-workflow_template-user_id}}',
            '{{%workflow_template}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-workflow_template-user_id}}',
            '{{%workflow_template}}'
        );

        $this->dropTable('{{%workflow_template}}');
    }
}
