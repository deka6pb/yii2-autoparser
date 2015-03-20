<?php

namespace deka6pb\autoparser\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PostsSearch represents the model behind the search form about `backend\models\Posts`.
 */
class PostsSearch extends Posts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'status', 'sid'], 'integer'],
            [['text', 'tags', 'provider', 'created', 'published', 'url'], 'safe'],
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
        $query = Posts::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'status' => $this->status,
            'sid' => $this->sid,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'tags', $this->tags])
            ->andFilterWhere(['like', 'provider', $this->provider])
            ->andFilterWhere(['like', 'created', $this->created])
            ->andFilterWhere(['like', 'published', $this->published])
            ->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }
}
