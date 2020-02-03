<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tarif;

/**
 * TarifSearch represents the model behind the search form about `app\modules\keuangan\models\Tarif`.
 */
class TarifSearch extends Tarif
{
    public function rules()
    {
        return [
            [['id', 'program', 'jenjang', 'check', 'status_beban', 'kelas', 'tahun', 'jurusan'], 'safe'],
            [['maksimum', 'utama'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Tarif::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'maksimum' => $this->maksimum,
            'utama' => $this->utama,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'program', $this->program])
            ->andFilterWhere(['like', 'jenjang', $this->jenjang])
            ->andFilterWhere(['like', 'check', $this->check])
            ->andFilterWhere(['like', 'status_beban', $this->status_beban])
            ->andFilterWhere(['like', 'kelas', $this->kelas])
            ->andFilterWhere(['like', 'tahun', $this->tahun])
            ->andFilterWhere(['like', 'jurusan', $this->jurusan]);

        return $dataProvider;
    }
}
