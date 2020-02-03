<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Biodata;

/**
 * BiodataSearch represents the model behind the search form about `app\models\Biodata`.
 */
class BiodataSearch extends Biodata
{
    public function rules()
    {
        return [
            [['id', 'kode', 'no_ktp', 'nama', 'tempat_lahir', 'tanggal_lahir', 'jk', 'alamat_ktp', 'kota', 'kode_pos', 'propinsi', 'negara', 'agama', 'status_ktp', 'kewarganegaraan', 'berlaku_ktp', 'ibu_kandung', 'photo', 'parent', 'ctgl', 'utgl', 'dtgl', 'Rstat'], 'safe'],
            [['pekerjaan', 'cuid', 'uuid', 'duid'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Biodata::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'tanggal_lahir' => $this->tanggal_lahir,
            'pekerjaan' => $this->pekerjaan,
            'berlaku_ktp' => $this->berlaku_ktp,
            'cuid' => $this->cuid,
            'ctgl' => $this->ctgl,
            'uuid' => $this->uuid,
            'utgl' => $this->utgl,
            'duid' => $this->duid,
            'dtgl' => $this->dtgl,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'kode', $this->kode])
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
            ->andFilterWhere(['like', 'kewarganegaraan', $this->kewarganegaraan])
            ->andFilterWhere(['like', 'ibu_kandung', $this->ibu_kandung])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'parent', $this->parent])
            ->andFilterWhere(['like', 'Rstat', $this->Rstat]);

        return $dataProvider;
    }
}
