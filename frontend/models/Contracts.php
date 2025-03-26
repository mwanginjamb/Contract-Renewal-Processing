<?php

namespace app\models;

use Yii;
use yii\base\Behavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "contracts".
 *
 * @property int $id
 * @property string|null $contract_number
 * @property string|null $employee_name
 * @property string|null $employee_number
 * @property string|null $original_contract_path
 * @property string|null $signed_contract_path
 * @property string|null $contract_duration
 * @property string|null $employee_workstation
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $duration_unit
 * @property int|null $approval_status
 * @property int|null $contract_batch_id
 *
 * @property WorkflowEntries[] $workflowEntries
 * @property ApprovalStatus[] $ApprovalStatus
 */
class Contracts extends \yii\db\ActiveRecord
{

    const EVENT_CONTRACT_ATTACHED = 'contractAttached';
    const EVENT_CONTRACT_FULLY_SIGNED = 'contractFullySigned';

    public $attachment;
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
        return 'contracts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['contract_number', 'employee_number', 'contract_duration', 'duration_unit', 'employee_workstation'], 'required'],
            [['contract_number', 'employee_name', 'employee_number', 'original_contract_path', 'signed_contract_path', 'contract_duration', 'employee_workstation', 'created_at', 'updated_at', 'created_by', 'updated_by', 'duration_unit'], 'default', 'value' => null],
            [['signed_contract_path'], 'string'],
            [['created_at', 'updated_at', 'created_by', 'updated_by', 'duration_unit'], 'integer'],
            [['contract_number'], 'string', 'max' => 50],
            [['employee_name'], 'string', 'max' => 200],
            [['employee_number'], 'string', 'max' => 35],
            [['original_contract_path'], 'string', 'max' => 450],
            [['contract_duration'], 'string', 'max' => 255],
            [['employee_workstation'], 'string', 'max' => 250],
            [['contract_number'], 'unique'],
            [['approval_status'], 'integer'],
            ['contract_batch_id', 'required'],

            // Validate that employee number exists in user table staff_id_number column
            [['employee_number'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['employee_number' => 'staff_id_number']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'contract_number' => Yii::t('app', 'Contract Number'),
            'employee_name' => Yii::t('app', 'Employee Name'),
            'employee_number' => Yii::t('app', 'Employee Number'),
            'original_contract_path' => Yii::t('app', 'Original Contract Path'),
            'signed_contract_path' => Yii::t('app', 'Signed Contract Path'),
            'contract_duration' => Yii::t('app', 'Contract Duration'),
            'employee_workstation' => Yii::t('app', 'Employee Workstation'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'duration_unit' => Yii::t('app', 'Duration Unit'),
        ];
    }

    /**
     * Gets query for [[WorkflowEntries]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\WorkflowEntriesQuery
     */
    public function getWorkflowEntries()
    {
        return $this->hasMany(WorkflowEntries::class, ['contract_id' => 'id']);
    }

    public function getDurationUnit()
    {
        return $this->hasOne(DurationUnits::class, ['id' => 'duration_unit']);
    }

    public function getApprovalStatus()
    {
        return $this->hasOne(ApprovalStatus::class, ['id' => 'approval_status']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['staff_id_number' => 'employee_number']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\ContractsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\ContractsQuery(get_called_class());
    }

    public function ismycontract($contractID)
    {
        /**
         * Check if the logged in user is the owner of the contract
         */
        $contract = self::find()->where(['id' => $contractID, 'approval_status' => NULL])->andWhere(['employee_number' => Yii::$app->user->identity->staff_id_number])->one();
        return $contract ? true : false;
    }

    public function icansign($contractID)
    {
        /**
         * Check if the logged in user can sign the contract and is in the workflow
         */
        $contract = self::findOne($contractID);
        if ($contract) {
            $approverID = Yii::$app->user->id;
            $approvalEntry = WorkflowEntries::find()->where(['contract_id' => $contract->id, 'approval_status' => 1])->andWhere(['approver_id' => $approverID])->one();
            return $approvalEntry ? true : false;
        }
        return false;
    }


    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        // check if original_contract_path has changed and is valid then fire the contract attached event
        if (
            !$insert &&
            $this->original_contract_path &&
            Yii::$app->utility->isValidSharepointLink($this->original_contract_path)
        ) {

            if (array_key_exists('original_contract_path', $changedAttributes) && $changedAttributes['original_contract_path'] === null) {
                Yii::info('Contract attached ' . $this->contract_number);
                $this->trigger(self::EVENT_CONTRACT_ATTACHED);
            } else {
                Yii::error('Contract notification not fired ' . $this->contract_number);
            }
        }

        // Trigger for a fully approved contract
        if (!$insert && !is_null($this->approval_status) && $this->approval_status == 2) {
            // Ensure you fire this event only when previous value was pending
            if (array_key_exists('approval_status', $changedAttributes) && $changedAttributes['approval_status'] == 1) {
                Yii::info('Contract  ' . $this->contract_number . ' has been fully approved.');
                $this->trigger(self::EVENT_CONTRACT_FULLY_SIGNED);
            } else {
                Yii::error('Contract full approval event not fired ' . $this->contract_number);
            }
        }
    }


}
