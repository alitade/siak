<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AbsenKhusus;

/**
 * AbsenKhususSearch represents the model behind the search form about `app\models\AbsenKhusus`.
 */
class AbsenKhususSearch extends AbsenKhusus
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['mhs_nim', 'kr_kode', 'tgl_exp', 'tgl_ins', 'tipe'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = AbsenKhusus::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'tgl_exp' => $this->tgl_exp,
            'tgl_ins' => $this->tgl_ins,
        ]);

        $query->andFilterWhere(['like', 'mhs_nim', $this->mhs_nim])
            ->andFilterWhere(['like', 'kr_kode', $this->kr_kode])
            ->andFilterWhere(['like', 'tipe', $this->tipe]);

        return $dataProvider;
    }
}
