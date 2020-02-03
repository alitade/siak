<?php

namespace app\modules\transkrip\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transkrip\models\Pejabat;

/**
 * PejabatSearch represents the model behind the search form about `app\modules\transkrip\models\Pejabat`.
 */
class PejabatSearch extends Pejabat
{
    public function rules()
    {
        return [
            [['id', 'ds_id'], 'integer'],
            [['nama', 'jabatan', 'kode_fakultas', 'kode_jurusan', 'status', 'thn_jabatan'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Pejabat::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'ds_id' => $this->ds_id,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'jabatan', $this->jabatan])
            ->andFilterWhere(['like', 'kode_fakultas', $this->kode_fakultas])
            ->andFilterWhere(['like', 'kode_jurusan', $this->kode_jurusan])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'thn_jabatan', $this->thn_jabatan]);

        return $dataProvider;
    }
}
