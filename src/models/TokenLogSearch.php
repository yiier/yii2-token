<?php

namespace yiier\token\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TokenLogSearch represents the model behind the search form about `yiier\token\models\TokenLog`.
 */
class TokenLogSearch extends TokenLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'token_id', 'ip', 'created_at'], 'integer'],
            [['username', 'token_value', 'url', 'method'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = TokenLog::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'token_id' => $this->token_id,
            'ip' => $this->ip,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'token_value', $this->token_value])
            ->andFilterWhere(['like', 'method', $this->method])
            ->andFilterWhere(['like', 'url', $this->url]);


        return $dataProvider;
    }
}
