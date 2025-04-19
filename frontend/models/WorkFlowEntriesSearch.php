<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WorkFlowEntries;

/**
 * WorkFlowEntriesSearch represents the model behind the search form of `app\models\WorkFlowEntries`.
 */
class WorkFlowEntriesSearch extends WorkFlowEntries
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'template_id', 'approver_id', 'approval_status', 'actioned_date', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            ['contract_id', 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = WorkFlowEntries::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // Add search for related contract table

        $query->joinWith('contract');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'template_id' => $this->template_id,
            'approver_id' => $this->approver_id,
            'approval_status' => $this->approval_status,
            'actioned_date' => $this->actioned_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            // 'contract_id' => $this->contract_id,
        ]);

        $query->andFilterWhere(['like', 'contracts.contract_number', $this->contract_id]);

        return $dataProvider;
    }
}
