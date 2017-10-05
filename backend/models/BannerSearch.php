<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Banner;

/**
 * BannerSearch represents the model behind the search form about `common\models\Banner`.
 */
class BannerSearch extends Banner
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', /*'created_at', 'updated_at',*/ 'status','order'], 'integer'],
            [['title', 'created_at', 'updated_at', 'image_url','ad_url'], 'safe'],
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
        $query = Banner::find();

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
//             'created_at' => $this->created_at,
//             'updated_at' => $this->updated_at,
            'status' => $this->status,
            'order' => $this->order,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'image_url', $this->image_url])
            ->andFilterWhere(['like', 'ad_url', $this->ad_url]);
        if($this->created_at){
            $query->andFilterWhere(['between', 'created_at', strtotime($this->created_at),strtotime($this->created_at) + 86400 - 1]);
        }
        if($this->updated_at){
            $query->andFilterWhere(['between', 'updated_at', strtotime($this->updated_at),strtotime($this->updated_at) + 86400 - 1]);
        }

        return $dataProvider;
    }
}
