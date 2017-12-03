<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GoodCode;

/**
 * GoodCodeSearch represents the model behind the search form about `common\models\GoodCode`.
 */
class GoodCodesSearch extends GoodCode
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'good_id'], 'integer'],
            [['model_text', 'bar_code','created_at', 'updated_at'], 'safe'],
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
        $query = GoodCode::find()->where(['good_id' => $params['id']]);

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
            'good_id' => $this->good_id,
            'bar_code' => $this->bar_code,
            //'created_at' => $this->created_at,
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
