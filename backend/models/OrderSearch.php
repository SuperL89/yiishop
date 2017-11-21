<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * OrderSearch represents the model behind the search form about `common\models\Order`.
 */
class OrderSearch extends Order
{
    public $username;
    public $created_at;
    public $pay_at;
    public $deliver_at;
    public $complete_at;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'business_id', 'good_id', 'mb_id', 'mbv_id', 'pay_type', 'pay_num', 'status', /*'created_at', 'pay_at', 'deliver_at', 'complete_at'*/], 'integer'],
            [['order_num', 'user_address', 'express_name', 'express_num', 'good_var', 'cancel_text', 'message','username','created_at','pay_at','deliver_at','complete_at'], 'safe'],
            [['good_price', 'good_total_price', 'order_fare', 'order_total_price'], 'number'],
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
        $query = Order::find();
        $query->joinWith(['user']);

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
            //'business_id' => $this->business_id,
            //'good_id' => $this->good_id,
            //'mb_id' => $this->mb_id,
            //'mbv_id' => $this->mbv_id,
            'pay_type' => $this->pay_type,
            'good_price' => $this->good_price,
            'pay_num' => $this->pay_num,
            'good_total_price' => $this->good_total_price,
            'order_fare' => $this->order_fare,
            'order_total_price' => $this->order_total_price,
            '{{%order}}.status' => $this->status,
//             'created_at' => $this->created_at,
//             'pay_at' => $this->pay_at,
//             'deliver_at' => $this->deliver_at,
//             'complete_at' => $this->complete_at,
        ]);

        $query->andFilterWhere(['like', 'order_num', $this->order_num])
            ->andFilterWhere(['like', 'username', $this->username])
            //->andFilterWhere(['like', 'user_address', $this->user_address])
            ->andFilterWhere(['like', 'express_name', $this->express_name])
            ->andFilterWhere(['like', 'express_num', $this->express_num])
            //->andFilterWhere(['like', 'good_var', $this->good_var])
            ->andFilterWhere(['like', 'cancel_text', $this->cancel_text])
            ->andFilterWhere(['like', 'message', $this->message]);
        
            if (!empty($this->created_at)) {
                $query->andFilterCompare('{{%order}}.created_at', strtotime(explode('/', $this->created_at)[0]), '>=');//起始时间
                $query->andFilterCompare('{{%order}}.created_at', (strtotime(explode('/', $this->created_at)[1]) + 86400), '<');//结束时间
            }
            
            if (!empty($this->pay_at)) {
                $query->andFilterCompare('{{%order}}.pay_at', strtotime(explode('/', $this->pay_at)[0]), '>=');//起始时间
                $query->andFilterCompare('{{%order}}.pay_at', (strtotime(explode('/', $this->pay_at)[1]) + 86400), '<');//结束时间
            }
            
            if (!empty($this->deliver_at)) {
                $query->andFilterCompare('{{%order}}.deliver_at', strtotime(explode('/', $this->deliver_at)[0]), '>=');//起始时间
                $query->andFilterCompare('{{%order}}.deliver_at', (strtotime(explode('/', $this->deliver_at)[1]) + 86400), '<');//结束时间
            }

            if (!empty($this->complete_at)) {
                $query->andFilterCompare('{{%order}}.complete_at', strtotime(explode('/', $this->complete_at)[0]), '>=');//起始时间
                $query->andFilterCompare('{{%order}}.complete_at', (strtotime(explode('/', $this->complete_at)[1]) + 86400), '<');//结束时间
            }
            
            $dataProvider->sort->defaultOrder=
            [
                'created_at' =>SORT_DESC,
            ];
        return $dataProvider;
    }
}
