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
 *
 * @property WorkflowEntries[] $workflowEntries
 */
class Contracts extends \yii\db\ActiveRecord
{

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
            //[['contract_number', 'employee_number', 'original_contract_path', 'contract_duration', 'duration_unit'], 'required'],
            [['contract_number', 'employee_name', 'employee_number', 'original_contract_path', 'signed_contract_path', 'contract_duration', 'employee_workstation', 'created_at', 'updated_at', 'created_by', 'updated_by', 'duration_unit'], 'default', 'value' => null],
            [['signed_contract_path'], 'string'],
            [['created_at', 'updated_at', 'created_by', 'updated_by', 'duration_unit'], 'integer'],
            [['contract_number'], 'string', 'max' => 50],
            [['employee_name'], 'string', 'max' => 200],
            [['employee_number'], 'string', 'max' => 35],
            [['original_contract_path'], 'string', 'max' => 450],
            [['contract_duration'], 'string', 'max' => 255],
            [['employee_workstation'], 'string', 'max' => 250],

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

    /**
     * {@inheritdoc}
     * @return \app\models\query\ContractsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\ContractsQuery(get_called_class());
    }

}
