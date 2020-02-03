<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BiodataTemp;

/**
 * BiodataTempSearch represents the model behind the search form about `app\models\BiodataTemp`.
 */
class BiodataTempSearch extends BiodataTemp
{
    public function rules()
    {
        return [
            [['kode', 'no_ktp', 'nama', 'tempat_lahir', 'tanggal_lahir', 'jk', 'alamat_ktp', 'kota', 'kode_pos', 'propinsi', 'negara', 'agama', 'status_ktp', 'pekerjaan', 'kewarganegaraan', 'berlaku_ktp', 'ibu_kandung', 'photo', 'alamat_tinggal', 'kota_tinggal', 'kode_pos_tinggal', 'tlp', 'email', 'parent', 'ctgl', 'glr_depan', 'glr_belakang'], 'safe'],
            [['cuid', 'id_', 'kd_agama', 'kd_kerja'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = BiodataTemp::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'tanggal_lahir' => $this->tanggal_lahir,
            'berlaku_ktp' => $this->berlaku_ktp,
            'cuid' => $this->cuid,
            'ctgl' => $this->ctgl,
            'id_' => $this->id_,
            'kd_agama' => $this->kd_agama,
            'kd_kerja' => $this->kd_kerja,
        ]);

        $query->andFilterWhere(['like', 'kode', $this->kode])
            ->andFilterWhere(['like', 'no_ktp', $this->no_ktp])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'tempat_lahir', $this->tempat_lahir])
            ->andFilterWhere(['like', 'jk', $this->jk])
            ->andFilterWhere(['like', 'alamat_ktp', $this->alamat_ktp])
            ->andFilterWhere(['like', 'kota', $this->kota])
            ->andFilterWhere(['like', 'kode_pos', $this->kode_pos])
            ->andFilterWhere(['like', 'propinsi', $this->propinsi])
            ->andFilterWhere(['like', 'negara', $this->negara])
            ->andFilterWhere(['like', 'agama', $this->agama])
            ->andFilterWhere(['like', 'status_ktp', $this->status_ktp])
            ->andFilterWhere(['like', 'pekerjaan', $this->pekerjaan])
            ->andFilterWhere(['like', 'kewarganegaraan', $this->kewarganegaraan])
            ->andFilterWhere(['like', 'ibu_kandung', $this->ibu_kandung])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'alamat_tinggal', $this->alamat_tinggal])
            ->andFilterWhere(['like', 'kota_tinggal', $this->kota_tinggal])
            ->andFilterWhere(['like', 'kode_pos_tinggal', $this->kode_pos_tinggal])
            ->andFilterWhere(['like', 'tlp', $this->tlp])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'parent', $this->parent])
            ->andFilterWhere(['like', 'glr_depan', $this->glr_depan])
            ->andFilterWhere(['like', 'glr_belakang', $this->glr_belakang]);

        return $dataProvider;
    }
}
