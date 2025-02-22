<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "workflow_template".
 *
 * @property int $id
 * @property int|null $sequence
 * @property int|null $user_id
 * @property string|null $workflow_name
 * @property string|null $workflow_role
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property User $user
 * @property WorkflowEntries[] $workflowEntries
 * @property WorkflowEntries[] $workflowEntries0
 */
class WorkflowTemplate extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'workflow_template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sequence', 'user_id', 'workflow_name', 'workflow_role', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['sequence', 'user_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['workflow_name', 'workflow_role'], 'string', 'max' => 150],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sequence' => Yii::t('app', 'Sequence'),
            'user_id' => Yii::t('app', 'User ID'),
            'workflow_name' => Yii::t('app', 'Workflow Name'),
            'workflow_role' => Yii::t('app', 'Workflow Role'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[WorkflowEntries]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\WorkflowEntriesQuery
     */


    /**
     * Gets query for [[WorkflowEntries0]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\WorkflowEntriesQuery
     */
    public function getWorkflowEntries()
    {
        return $this->hasMany(WorkflowEntries::class, ['template_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\WorkflowTemplateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\WorkflowTemplateQuery(get_called_class());
    }

}
