<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TKrsDet;

/**
 * TKrsDetSearch represents the model behind the search form about `app\models\TKrsDet`.
 */
class TKrsDetSearch extends TKrsDet
{
    public function rules()
    {
        return [
            [['id', 'jdwl_id', 'mtk_sks', 'cuid', 'uuid', 'duid'], 'integer'],
            [['mtk_kode', 'mtk_nama', 'mhs_nim', 'kr_kode', 'tgl', 'tgl_jdwl', 'krs_stat', 'ket', 'krs_ulang', 'RStat', 'ctgl', 'utgl', 'dtgl'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TKrsDet::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'jdwl_id' => $this->jdwl_id,
            'mtk_sks' => $this->mtk_sks,
            'tgl' => $this->tgl,
            'tgl_jdwl' => $this->tgl_jdwl,
            'cuid' => $this->cuid,
            'ctgl' => $this->ctgl,
            'uuid' => $this->uuid,
            'utgl' => $this->utgl,
            'duid' => $this->duid,
            'dtgl' => $this->dtgl,
        ]);

        $query->andFilterWhere(['like', 'mtk_kode', $this->mtk_kode])
            ->andFilterWhere(['like', 'mtk_nama', $this->mtk_nama])
            ->andFilterWhere(['like', 'mhs_nim', $this->mhs_nim])
            ->andFilterWhere(['like', 'kr_kode', $this->kr_kode])
            ->andFilterWhere(['like', 'krs_stat', $this->krs_stat])
            ->andFilterWhere(['like', 'ket', $this->ket])
            ->andFilterWhere(['like', 'krs_ulang', $this->krs_ulang])
            ->andFilterWhere(['like', 'RStat', $this->RStat]);

        return $dataProvider;
    }
}
