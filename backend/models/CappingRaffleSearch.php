<?php

namespace backend\models;

use Carbon\Carbon;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CappingRaffle;

/**
 * CappingRaffleSearch represents the model behind the search form of `common\models\CappingRaffle`.
 */
class CappingRaffleSearch extends CappingRaffle
{
    public $winner;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'winner_id'], 'integer'],
            [['reset_at', 'winner'], 'safe'],
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
        $query = CappingRaffle::find();
        $query->joinWith(['winner']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           'sort'=> ['defaultOrder' => ['reset_at' => SORT_ASC]],
        ]);

        // Important: here is how we set up the sorting
        // The key is the attribute name on our "TourSearch" instance
        $dataProvider->sort->attributes['winner'] = [
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
        $query->andFilterWhere(['like', 'runescape_user.username', $this->winner]);
        if (!empty($this->reset_at)) {
            $searchDate = Carbon::parse($this->reset_at);
            $startDate = $searchDate->clone()->startOfDay();
            $endDate = $searchDate->clone()->endOfDay();

            $query->andFilterWhere(['BETWEEN', 'reset_at', $startDate->toDateTimeString(), $endDate->toDateTimeString()]);
        }

        return $dataProvider;
    }
}
