<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MAbsenMhs;

/**
 * MAbsenMhsSearch represents the model behind the search form about `app\models\MAbsenMhs`.
 */
class MAbsenMhsSearch extends MAbsenMhs
{
    public function rules()
    {
        return [
            [['id', 'id_absen_ds', 'mhs_fid', 'krs_id', 'jdwl_id', 'cuid', 'uuid', 'duid'], 'integer'],
            [['mhs_nim', 'mhs_masuk', 'mhs_keluar', 'mhs_stat', 'input_tipe', 'krs_stat', 'sesi', 'ctgl', 'utgl', 'dtgl', 'ket', 'RStat', 'kode'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = MAbsenMhs::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_absen_ds' => $this->id_absen_ds,
            'mhs_fid' => $this->mhs_fid,
            'mhs_masuk' => $this->mhs_masuk,
            'mhs_keluar' => $this->mhs_keluar,
            'krs_id' => $this->krs_id,
            'jdwl_id' => $this->jdwl_id,
            'cuid' => $this->cuid,
            'ctgl' => $this->ctgl,
            'uuid' => $this->uuid,
            'utgl' => $this->utgl,
            'duid' => $this->duid,
            'dtgl' => $this->dtgl,
        ]);

        $query->andFilterWhere(['like', 'mhs_nim', $this->mhs_nim])
            ->andFilterWhere(['like', 'mhs_stat', $this->mhs_stat])
            ->andFilterWhere(['like', 'input_tipe', $this->input_tipe])
            ->andFilterWhere(['like', 'krs_stat', $this->krs_stat])
            ->andFilterWhere(['like', 'sesi', $this->sesi])
            ->andFilterWhere(['like', 'ket', $this->ket])
            ->andFilterWhere(['like', 'RStat', $this->RStat])
            ->andFilterWhere(['like', 'kode', $this->kode]);

        return $dataProvider;
    }
}
