<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Good;

/**
 * GoodSearch represents the model behind the search form about `common\models\Good`.
 */
class GoodSearch extends Good
{
    public $cate_name;
    public $brand_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'good_num', 'cate_id', 'brand_id', 'status', 'is_reco', 'is_hot', /*'created_at', 'updated_at', */'user_id', 'order'], 'integer'],
            [['title', 'description','created_at','cate_name','brand_name', 'updated_at'], 'safe'],
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
        $query = Good::find();
        $query->joinWith(['cate','brand']);

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
            '{{%good}}.id' => $this->id,
            'good_num' => $this->good_num,
            'cate_id' => $this->cate_id,
            'brand_id' => $this->brand_id,
            'status' => $this->status,
            'is_reco' => $this->is_reco,
            'is_hot' => $this->is_hot,
//             'created_at' => $this->created_at,
//             'updated_at' => $this->updated_at,
            //'user_id' => 0,
            'order' => $this->order,
            'is_del' => 0
        ]);

        $query->andFilterWhere(['like', '{{%good}}.title', $this->title])
            ->andFilterWhere(['like', '{{%category}}.title', $this->cate_name])
            ->andFilterWhere(['like', '{{%brand}}.title', $this->brand_name])
            ->andFilterWhere(['like', 'description', $this->description]);
        
        if (!empty($this->created_at)) {
            $query->andFilterCompare('created_at', strtotime(explode('/', $this->created_at)[0]), '>=');//起始时间
            $query->andFilterCompare('created_at', (strtotime(explode('/', $this->created_at)[1]) + 86400), '<');//结束时间
        }
        
        if (!empty($this->updated_at)) {
            $query->andFilterCompare('updated_at', strtotime(explode('/', $this->updated_at)[0]), '>=');//起始时间
            $query->andFilterCompare('updated_at', (strtotime(explode('/', $this->updated_at)[1]) + 86400), '<');//结束时间
        }
        
        $dataProvider->sort->defaultOrder=
        [
            'created_at' =>SORT_DESC,
        ];
        return $dataProvider;
    }
}
