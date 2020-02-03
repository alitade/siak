<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Beasiswalist;

/**
 * BeasiswalistSearch represents the model behind the search form about `app\models\Beasiswalist`.
 */
class BeasiswalistSearch extends Beasiswalist
{
    public function rules()
    {
        return [
            [['id', 'jenis', 'status', 'jumlah', 'counter'], 'integer'],
            [['nim', 'tahun'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Beasiswalist::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'jenis' => $this->jenis,
            'status' => $this->status,
            'jumlah' => $this->jumlah,
            'counter' => $this->counter,
        ]);

        $query->andFilterWhere(['like', 'nim', $this->nim])
            ->andFilterWhere(['like', 'tahun', $this->tahun]);

        return $dataProvider;
    }
}
