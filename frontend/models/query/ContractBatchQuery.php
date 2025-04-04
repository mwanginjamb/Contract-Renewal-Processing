<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\ContractBatch]].
 *
 * @see \app\models\ContractBatch
 */
class ContractBatchQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function init()
    {
        return $this->orderBy(['id' => SORT_DESC]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\ContractBatch[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\ContractBatch|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
