<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Business;

/**
 * BusinessSearch represents the model behind the search form about `common\models\Business`.
 */
class BusinessSearch extends Business
{
    public $username;
    public $city_name;
    public $cate_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'city_id', 'status', 'score', /*'score_updated_at', 'created_at', 'updated_at'*/], 'integer'],
            [[/*'image_url',*/'username','city_name','cate_name','name', 'address', 'cate_id','score_updated_at', 'created_at', 'updated_at'], 'safe'],
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
        $query = Business::find();
        $query->joinWith(['user','place','category']);

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
            //'city_id' => $this->city_id,
            '{{%business}}.status' => $this->status,
            'score' => $this->score,
//             'score_updated_at' => $this->score_updated_at,
//             'created_at' => $this->created_at,
//             'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'image_url', $this->image_url])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', "{{%place}}.name", $this->city_name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'title', $this->cate_name]);
        
        if (!empty($this->score_updated_at)) {
            $query->andFilterCompare('score_updated_at', strtotime(explode('/', $this->score_updated_at)[0]), '>=');//起始时间
            $query->andFilterCompare('score_updated_at', (strtotime(explode('/', $this->score_updated_at)[1]) + 86400), '<');//结束时间
        }
            
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
            'status' => SORT_ASC,
            'created_at' =>SORT_DESC,
        ];
        
        return $dataProvider;
    }
}
