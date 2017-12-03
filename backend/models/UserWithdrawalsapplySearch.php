<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserWithdrawalsapply;

/**
 * UserWithdrawalsapplySearch represents the model behind the search form about `common\models\UserWithdrawalsapply`.
 */
class UserWithdrawalsapplySearch extends UserWithdrawalsapply
{
    public $username;
    public $account;
    public $realname;
    public $account_bank;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'account_id', 'user_id', 'commission_fee', 'status'], 'integer'],
            [['money_w'], 'number'],
            [['commission_money', 'user_money','username','account','realname','account_bank', 'created_at', 'updated_at','complete_at'], 'safe'],
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
        $query = UserWithdrawalsapply::find();
        $query->joinWith(['user','userAccount']);
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
            //'account_id' => $this->account_id,
            //'user_id' => $this->user_id,
            '{{%user}}.username' => $this->username,
            '{{%user_account}}.account' => $this->account,
            'money_w' => $this->money_w,
            'commission_fee' => $this->commission_fee,
            '{{%user_withdrawalsapply}}.status' => $this->status,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
            //'complete_at' => $this->complete_at,
        ]);

        $query->andFilterWhere(['like', 'commission_money', $this->commission_money])
             ->andFilterWhere(['like', '{{%user_account}}.realname', $this->realname])
             ->andFilterWhere(['like', '{{%user_account}}.account_bank', $this->account_bank])
//             ->andFilterWhere(['like', '{{%category}}.title', $this->cate_name])
//             ->andFilterWhere(['like', '{{%category}}.title', $this->cate_name])
            ->andFilterWhere(['like', 'user_money', $this->user_money]);
        
            if (!empty($this->created_at)) {
                $query->andFilterCompare('{{%user_withdrawalsapply}}.created_at', strtotime(explode('/', $this->created_at)[0]), '>=');//起始时间
                $query->andFilterCompare('{{%user_withdrawalsapply}}.created_at', (strtotime(explode('/', $this->created_at)[1]) + 86400), '<');//结束时间
            }
            
            if (!empty($this->updated_at)) {
                $query->andFilterCompare('{{%user_withdrawalsapply}}.updated_at', strtotime(explode('/', $this->updated_at)[0]), '>=');//起始时间
                $query->andFilterCompare('{{%user_withdrawalsapply}}.updated_at', (strtotime(explode('/', $this->updated_at)[1]) + 86400), '<');//结束时间
            }
            
            if (!empty($this->complete_at)) {
                $query->andFilterCompare('{{%user_withdrawalsapply}}.complete_at', strtotime(explode('/', $this->complete_at)[0]), '>=');//起始时间
                $query->andFilterCompare('{{%user_withdrawalsapply}}.complete_at', (strtotime(explode('/', $this->complete_at)[1]) + 86400), '<');//结束时间
            }
            
            $dataProvider->sort->defaultOrder=
            [
                'status' =>SORT_ASC,
            ];

        return $dataProvider;
    }
}
