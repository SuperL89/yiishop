<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GoodMbv;

/**
 * GoodMbvSearch represents the model behind the search form about `common\models\GoodMbv`.
 */
class GoodMbvSearch extends GoodMbv
{
    //public $id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'mb_id', 'stock_num', 'bar_code_status', 'status', /*'created_at', 'updated_at'*/], 'integer'],
            [['model_text', 'bar_code','created_at', 'updated_at'], 'safe'],
            [['price'], 'number'],
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
        //print_r($params);exit();
        $query = GoodMbv::find()->where(['mb_id' => $params['id']]);

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
            'mb_id' => $this->mb_id,
            'price' => $this->price,
            'stock_num' => $this->stock_num,
            //'bar_code_status' => $this->bar_code_status,
            'status' => $this->status,
            'bar_code' => $this->bar_code,
            //'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'model_text', $this->model_text]);
        
      if (!empty($this->created_at)) {
          $query->andFilterCompare('created_at', strtotime(explode('/', $this->created_at)[0]), '>=');//起始时间
          $query->andFilterCompare('created_at', (strtotime(explode('/', $this->created_at)[1]) + 86400), '<');//结束时间
      }
      
      if (!empty($this->updated_at)) {
          $query->andFilterCompare('updated_at', strtotime(explode('/', $this->updated_at)[0]), '>=');//起始时间
          $query->andFilterCompare('updated_at', (strtotime(explode('/', $this->updated_at)[1]) + 86400), '<');//结束时间
      }

        return $dataProvider;
    }
}
