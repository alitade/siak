<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Beasiswajenis;

/**
 * BeasiswajenisSearch represents the model behind the search form about `app\models\Beasiswajenis`.
 */
class BeasiswajenisSearch extends Beasiswajenis
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['namabeasiswa', 'jenispotongan'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Beasiswajenis::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'namabeasiswa', $this->namabeasiswa])
            ->andFilterWhere(['like', 'jenispotongan', $this->jenispotongan]);

        return $dataProvider;
    }
}
