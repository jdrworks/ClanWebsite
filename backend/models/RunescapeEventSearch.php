<?php

namespace backend\models;

use Carbon\Carbon;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RunescapeEvent;

/**
 * RunescapeEventSearch represents the model behind the search form of `common\models\RunescapeEvent`.
 */
class RunescapeEventSearch extends RunescapeEvent
{
    public $host;
    public $cohost;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'host_id', 'cohost_id'], 'integer'],
            [['name', 'description', 'start_date', 'end_date', 'host', 'cohost'], 'safe'],
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
        $query = RunescapeEvent::find();
        $query->joinWith(['host']);
        $query->joinWith(['cohost']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['host'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['runescape_user.username' => SORT_ASC],
            'desc' => ['runescape_user.username' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['cohost'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['runescape_user.username' => SORT_ASC],
            'desc' => ['runescape_user.username' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'runescape_user.username', $this->host])
            ->andFilterWhere(['like', 'runescape_user.username', $this->cohost]);

        return $dataProvider;
    }
}
