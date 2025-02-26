<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WorkflowTemplateMembers;

/**
 * WorkflowTemplateMembersSearch represents the model behind the search form of `app\models\WorkflowTemplateMembers`.
 */
class WorkflowTemplateMembersSearch extends WorkflowTemplateMembers
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sequence', 'user_id', 'workflow_id', 'created_at', 'updated_at', 'updated_by', 'created_by'], 'integer'],
            [['approver_name', 'approver_email', 'approver_phone_number'], 'safe'],
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
        $query = WorkflowTemplateMembers::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'sequence' => $this->sequence,
            'user_id' => $this->user_id,
            'workflow_id' => $this->workflow_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'approver_name', $this->approver_name])
            ->andFilterWhere(['like', 'approver_email', $this->approver_email])
            ->andFilterWhere(['like', 'approver_phone_number', $this->approver_phone_number]);

        return $dataProvider;
    }
}
