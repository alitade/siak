<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pkrs;

/**
 * PkrsSearch represents the model behind the search form about `app\models\Pkrs`.
 */
class PkrsSearch extends Pkrs
{
    public function rules()
    {
        return [
            [['kr_kode', 'mhs_nim', 'tgl_awal', 'tgl_akhir'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Pkrs::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'tgl_awal' => $this->tgl_awal,
            'tgl_akhir' => $this->tgl_akhir,
        ]);

        $query->andFilterWhere(['like', 'kr_kode', $this->kr_kode])
            ->andFilterWhere(['like', 'mhs_nim', $this->mhs_nim]);

        return $dataProvider;
    }
}
