<?php

namespace backend\models;

use common\models\RunescapeUser;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * RunescapeUserSearch represents the model behind the search form of `common\models\RunescapeUser`.
 */
class RunescapeUserReportSearch extends RunescapeUser
{
    public $rank_rank_points;
    public $rank_promotion_points;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'rank_id', 'capped', 'visited', 'private_profile', 'in_clan', 'old_name', 'rank_points'], 'integer'],
            [['username', 'created_at', 'updated_at', 'last_active', 'rank_rank_points', 'rank_promotion_points'], 'safe'],
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
        $query->joinWith(['rank']);
        $query->Where(['in_clan' => true]);
        $query->andWhere(['not in', 'rank_id', [1, 2, 3, 4, 5, 6]]);
        $query->andWhere('(
            runescape_user.rank_points >= runescape_rank.promotion_points OR
            (runescape_user.rank_points < runescape_rank.rank_points AND runescape_user.rank_points >= 0) OR
            (runescape_user.rank_points < runescape_rank.rank_points AND last_active < DATE_SUB(now(), INTERVAL 6 MONTH)) OR
            runescape_user.private_profile = true
        )');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['rank_rank_points'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['runescape_rank.rank_points' => SORT_ASC],
            'desc' => ['runescape_rank.rank_points' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['rank_promotion_points'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['runescape_rank.promotion_points' => SORT_ASC],
            'desc' => ['runescape_rank.promotion_points' => SORT_DESC],
        ];

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
            'rank_points' => $this->rank_points,
            'runescape_rank.rank_points' => $this->rank_rank_points,
            'runescape_rank.promotion_points' => $this->rank_promotion_points
        ]);

        $query->andFilterWhere(['like', 'username', $this->username]);

        return $dataProvider;
    }
}
