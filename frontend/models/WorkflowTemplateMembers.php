<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "workflow_template_members".
 *
 * @property int $id
 * @property string|null $approver_name
 * @property string|null $approver_email
 * @property string|null $approver_phone_number
 * @property int|null $sequence
 * @property int|null $user_id
 * @property int|null $workflow_id
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $created_by
 *
 * @property WorkflowTemplate $workflow
 */
class WorkflowTemplateMembers extends \yii\db\ActiveRecord
{


    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'workflow_template_members';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['approver_name', 'sequence'], 'required'],
            [['approver_name', 'approver_email', 'approver_phone_number', 'sequence', 'user_id', 'workflow_id', 'created_at', 'updated_at', 'updated_by', 'created_by'], 'default', 'value' => null],
            [['sequence', 'user_id', 'workflow_id', 'created_at', 'updated_at', 'updated_by', 'created_by'], 'integer'],
            [['approver_name'], 'string', 'max' => 200],
            [['approver_email'], 'string', 'max' => 150],
            [['approver_email'], 'email'],
            [['approver_phone_number'], 'string', 'max' => 100],
            [['workflow_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorkflowTemplate::class, 'targetAttribute' => ['workflow_id' => 'id']],


            // ['sequence', 'unique'],
            // [['approver_name'], 'unique', 'targetAttribute' => ['approver_name', 'approver_email', 'sequence'], 'message' => 'The combination of Approver Name, Approver Email, and Sequence must be unique.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'approver_name' => Yii::t('app', 'Approver Name'),
            'approver_email' => Yii::t('app', 'Approver Email'),
            'approver_phone_number' => Yii::t('app', 'Approver Phone Number'),
            'sequence' => Yii::t('app', 'Sequence'),
            'user_id' => Yii::t('app', 'User ID'),
            'workflow_id' => Yii::t('app', 'Workflow ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * Gets query for [[Workflow]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\WorkflowTemplateQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne(WorkflowTemplate::class, ['id' => 'workflow_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\WorkflowTemplateMembersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\WorkflowTemplateMembersQuery(get_called_class());
    }

}
