<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DosenPengganti;

/**
 * DosenPenggantiSearch represents the model behind the search form about `app\models\DosenPengganti`.
 */
class DosenPenggantiSearch extends DosenPengganti
{
    public function rules()
    {
        return [
            [['Id', 'ds_id'], 'integer'],
            [['Tgl'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = DosenPengganti::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'Id' => $this->Id,
            'ds_id' => $this->ds_id,
            'Tgl' => $this->Tgl,
        ]);

        return $dataProvider;
    }
}
