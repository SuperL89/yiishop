<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GoodMb;

/**
 * GoodMbSearch represents the model behind the search form about `common\models\GoodMb`.
 */
class GoodMbSearch extends GoodMb
{
    public $good_title;
    public $username;
    public $city_name;
    public $freight_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'place_id', 'freight_id', 'good_id', 'cate_id', 'brand_id', 'status'/*, 'created_at', 'updated_at'*/], 'integer'],
            [['username','good_title','city_name','freight_name', 'created_at', 'updated_at'], 'safe'],
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
        $query = GoodMb::find()->where(['<', '{{%good_mb}}.status', '3']);
        $query->joinWith(['user','good','place','freight']);
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
             //'user_id' => $this->user_id,
            'place_id' => $this->place_id,
            'freight_id' => $this->freight_id,
            'good_id' => $this->good_id,
            'cate_id' => $this->cate_id,
            'brand_id' => $this->brand_id,
            //'status' => $this->status,
            '{{%good_mb}}.status' => $this->status,
//             'created_at' => $this->created_at,
//             'updated_at' => $this->updated_at,
        ]);
        $query->andFilterWhere(['like', 'username', $this->username])
              ->andFilterWhere(['like', '{{%good}}.title', $this->good_title])
              ->andFilterWhere(['like', '{{%freight}}.title', $this->freight_name])
              ->andFilterWhere(['like', '{{%place}}.name', $this->city_name]);
        
        if (!empty($this->created_at)) {
            $query->andFilterCompare('{{%good_mb}}.created_at', strtotime(explode('/', $this->created_at)[0]), '>=');//起始时间
            $query->andFilterCompare('{{%good_mb}}.created_at', (strtotime(explode('/', $this->created_at)[1]) + 86400), '<');//结束时间
        }
        
        if (!empty($this->updated_at)) {
            $query->andFilterCompare('{{%good_mb}}.updated_at', strtotime(explode('/', $this->updated_at)[0]), '>=');//起始时间
            $query->andFilterCompare('{{%good_mb}}.updated_at', (strtotime(explode('/', $this->updated_at)[1]) + 86400), '<');//结束时间
        }
        
        $dataProvider->sort->defaultOrder=
        [
            'status' => SORT_DESC,
            'created_at' =>SORT_DESC,
        ];
        return $dataProvider;
    }
}
