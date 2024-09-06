<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * RunescapeUserSearch represents the model behind the search form of `common\models\RunescapeUser`.
 */
class RunescapeUserSearch extends RunescapeUser
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'rank_id', 'capped', 'visited', 'private_profile', 'in_clan', 'old_name'], 'integer'],
            [['username', 'created_at', 'updated_at'], 'safe'],
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = RunescapeUser::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'rank_id' => $this->rank_id,
            'capped' => $this->capped,
            'visited' => $this->visited,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'private_profile' => $this->private_profile,
            'in_clan' => $this->in_clan,
            'old_name' => $this->old_name,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username]);

        return $dataProvider;
    }
}
