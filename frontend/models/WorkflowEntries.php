<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "workflow_entries".
 *
 * @property int $id
 * @property int|null $template_id
 * @property int|null $approver_id
 * @property int|null $approval_status
 * @property int|null $actioned_date
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $contract_id
 * @property int|null $sequence
 *
 * @property ApprovalStatus $approvalStatus
 * @property WorkflowTemplate $approver
 * @property Contracts $contract
 * @property WorkflowTemplate $template
 */
class WorkflowEntries extends \yii\db\ActiveRecord
{
    const EVENT_STATUS_PENDING = 'statusPending';

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
        return 'workflow_entries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['template_id', 'approver_id', 'approval_status', 'contract_id'], 'required'],
            [['template_id', 'approver_id', 'approval_status', 'actioned_date', 'created_at', 'updated_at', 'created_by', 'updated_by', 'contract_id'], 'default', 'value' => null],
            [['template_id', 'approver_id', 'approval_status', 'actioned_date', 'created_at', 'updated_at', 'created_by', 'updated_by', 'contract_id'], 'integer'],
            [['approval_status'], 'exist', 'skipOnError' => true, 'targetClass' => ApprovalStatus::class, 'targetAttribute' => ['approval_status' => 'id']],
            // [['approver_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorkflowTemplate::class, 'targetAttribute' => ['approver_id' => 'user_id']],
            [['contract_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contracts::class, 'targetAttribute' => ['contract_id' => 'id']],
            [['template_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorkflowTemplate::class, 'targetAttribute' => ['template_id' => 'id']],
            ['sequence', 'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'template_id' => Yii::t('app', 'Template ID'),
            'approver_id' => Yii::t('app', 'Approver ID'),
            'approval_status' => Yii::t('app', 'Approval Status'),
            'actioned_date' => Yii::t('app', 'Actioned Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'contract_id' => Yii::t('app', 'Contract ID'),
        ];
    }

    /**
     * Gets query for [[ApprovalStatus]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\ApprovalStatusQuery
     */
    public function getApprovalStatus()
    {
        return $this->hasOne(ApprovalStatus::class, ['id' => 'approval_status']);
    }

    /**
     * Gets query for [[Approver]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\WorkflowTemplateQuery
     */
    public function getApprover()
    {
        return $this->hasOne(WorkflowTemplateMembers::class, ['user_id' => 'approver_id']);
    }

    /**
     * Gets query for [[Contract]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\ContractsQuery
     */
    public function getContract()
    {
        return $this->hasOne(Contracts::class, ['id' => 'contract_id']);
    }

    /**
     * Gets query for [[Template]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\WorkflowTemplateQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(WorkflowTemplate::class, ['id' => 'template_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\WorkflowEntriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\WorkflowEntriesQuery(get_called_class());
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // check if approval_status is 1 (Pending) and has changed
        if ($this->approval_status == 1) {
            // Trigger the event only if the status changed To 1
            if ($insert || $changedAttributes['approval_status'] != 1) {
                $this->trigger(self::EVENT_STATUS_PENDING);
            }
        }
    }

}
