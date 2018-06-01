<?php

namespace yiier\token\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TokenSearch represents the model behind the search form about `yiier\token\models\Token`.
 */
class TokenSearch extends Token
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'ip', 'status', 'expires_in', 'created_at', 'updated_at', 'expired_at'], 'integer'],
            [['username', 'value', 'provider'], 'safe'],
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
        $query = Token::find();

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
            'expires_in' => $this->expires_in,
            'ip' => $this->ip,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'expired_at' => $this->expired_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'provider', $this->provider])
            ->andFilterWhere(['like', 'value', $this->value]);

        return $dataProvider;
    }
}
