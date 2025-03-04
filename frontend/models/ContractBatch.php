<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "contract_batch".
 *
 * @property int $id
 * @property string|null $batch_description
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property Contracts[] $contracts
 */
class ContractBatch extends \yii\db\ActiveRecord
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
        return 'contract_batch';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['batch_description', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['batch_description'], 'string'],
            [['created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'batch_description' => Yii::t('app', 'Batch Description'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[Contracts]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\ContractsQuery
     */
    public function getContracts()
    {
        return $this->hasMany(Contracts::class, ['contract_batch_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\ContractBatchQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\ContractBatchQuery(get_called_class());
    }

}
