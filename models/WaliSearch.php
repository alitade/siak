<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Wali;

/**
 * WaliSearch represents the model behind the search form about `app\models\Wali`.
 */
class WaliSearch extends Wali
{
    public function rules()
    {
        return [
            [['Id', 'DsId'], 'integer'],
            [['JrId', 'KrKd', 'Status', 'RStat'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Wali::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'Id' => $this->Id,
            'DsId' => $this->DsId,
        ]);

        $query->andFilterWhere(['like', 'JrId', $this->JrId])
            ->andFilterWhere(['like', 'KrKd', $this->KrKd])
            ->andFilterWhere(['like', 'Status', $this->Status])
            ->andFilterWhere(['like', 'RStat', $this->RStat]);

        return $dataProvider;
    }
}
