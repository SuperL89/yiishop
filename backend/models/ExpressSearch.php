<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Express;

/**
 * ExpressSearch represents the model behind the search form about `common\models\Express`.
 */
class ExpressSearch extends Express
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['name','created_at', 'updated_at'], 'safe'],
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
        $query = Express::find();

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
            'status' => $this->status,
//             'created_at' => $this->created_at,
//             'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        
        if($this->created_at){
            $query->andFilterWhere(['between', 'created_at', strtotime($this->created_at),strtotime($this->created_at) + 86400 - 1]);
        }
        if($this->updated_at){
            $query->andFilterWhere(['between', 'updated_at', strtotime($this->updated_at),strtotime($this->updated_at) + 86400 - 1]);
        }
        return $dataProvider;
    }
}
