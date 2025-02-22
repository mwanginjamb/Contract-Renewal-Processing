<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "duration_units".
 *
 * @property int $id
 * @property string|null $unit
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class DurationUnits extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'duration_units';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['unit', 'required'],
            [['unit', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['created_at', 'updated_at'], 'integer'],
            [['unit'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'unit' => Yii::t('app', 'Unit'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\DurationUnitsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\DurationUnitsQuery(get_called_class());
    }

}
