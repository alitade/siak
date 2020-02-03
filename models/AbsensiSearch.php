<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Absensi;

/**
 * AbsensiSearch represents the model behind the search form about `app\models\Absensi`.
 */
class AbsensiSearch extends Absensi
{
    public function rules()
    {
        return [
            [['id', 'krs_id', 'jdwl_id_'], 'integer'],
            [['jdwl_stat', 'jdwal_tgl', 'jdwl_sesi'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Absensi::find()->where(" (RStat='0' or RStat is null) ");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'krs_id' => $this->krs_id,
            'jdwl_id_' => $this->jdwl_id_,
            'jdwal_tgl' => $this->jdwal_tgl,
        ]);

        $query->andFilterWhere(['like', 'jdwl_stat', $this->jdwl_stat])
            ->andFilterWhere(['like', 'jdwl_sesi', $this->jdwl_sesi]);

        return $dataProvider;
    }
}
