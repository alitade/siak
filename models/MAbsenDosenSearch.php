<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MAbsenDosen;

/**
 * MAbsenDosenSearch represents the model behind the search form about `app\models\MAbsenDosen`.
 */
class MAbsenDosenSearch extends MAbsenDosen{
    public function rules()
    {
        return [
            [['id', 'ds_id', 'ds_id1', 'ds_get_id', 'ds_get_fid', 'jdwl_id', 'cuid', 'uuid', 'duid'], 'integer'],
            [['ds_masuk', 'ds_keluar', 'ds_stat', 'input_tipe', 'jdwl_kls', 'jdwl_hari', 'jdwl_masuk', 'jdwl_keluar', 'mtk_kode', 'mtk_nama', 'sesi', 'tgl_normal', 'tgl_perkuliahan', 'tipe', 'ctgl', 'utgl', 'dtgl', 'ket', 'RStat', 'kr_kode_','pelaksana'], 'safe'],
        ];
    }

    public function scenarios(){
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params){
        $query = MAbsenDosen::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'ds_id' => $this->ds_id,
            'ds_id1' => $this->ds_id1,
            'ds_get_id' => $this->ds_get_id,
            'ds_get_fid' => $this->ds_get_fid,
            'ds_masuk' => $this->ds_masuk,
            'ds_keluar' => $this->ds_keluar,
            'jdwl_id' => $this->jdwl_id,
            'jdwl_masuk' => $this->jdwl_masuk,
            'jdwl_keluar' => $this->jdwl_keluar,
            'tgl_normal' => $this->tgl_normal,
            'tgl_perkuliahan' => $this->tgl_perkuliahan,
            'cuid' => $this->cuid,
            'ctgl' => $this->ctgl,
            'uuid' => $this->uuid,
            'utgl' => $this->utgl,
            'duid' => $this->duid,
            'dtgl' => $this->dtgl,
        ]);

        $query->andFilterWhere(['like', 'ds_stat', $this->ds_stat])
            ->andFilterWhere(['like', 'input_tipe', $this->input_tipe])
            ->andFilterWhere(['like', 'jdwl_kls', $this->jdwl_kls])
            ->andFilterWhere(['like', 'jdwl_hari', $this->jdwl_hari])
            ->andFilterWhere(['like', 'mtk_kode', $this->mtk_kode])
            ->andFilterWhere(['like', 'mtk_nama', $this->mtk_nama])
            ->andFilterWhere(['like', 'sesi', $this->sesi])
            ->andFilterWhere(['like', 'tipe', $this->tipe])
            ->andFilterWhere(['like', 'ket', $this->ket])
            ->andFilterWhere(['like', 'RStat', $this->RStat])
            ->andFilterWhere(['like', 'kr_kode_', $this->kr_kode_]);

        return $dataProvider;
    }

    public function master($params){
        $query = MAbsenDosen::find()
            ->select([
                'm_absen_dosen.*','ds.ds_nm',
                'pelaksana'=>'ds1.ds_nm',
                'pr.pr_kode','pr.pr_nama',
                'tMhs'=>'jd._totmhs',

            ])
            ->innerJoin("tbl_jadwal jd","(jd.jdwl_id=m_absen_dosen.jdwl_id)")
            ->innerJoin("tbl_bobot_nilai bn","(bn.id=jd.bn_id)")
            ->innerJoin("tbl_dosen ds","(ds.ds_id=bn.ds_nidn)")
            ->innerJoin("tbl_kalender kl","(kl.kln_id=bn.kln_id)")
            ->innerJoin("tbl_program pr","(pr.pr_kode=kl.pr_kode)")
            ->leftJoin("tbl_dosen ds1","(ds1.ds_id=m_absen_dosen.ds_get_id)")

        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'ds_id' => $this->ds_id,
            'ds_id1' => $this->ds_id1,
            'ds_get_id' => $this->ds_get_id,
            'ds_get_fid' => $this->ds_get_fid,
            'jd.jdwl_hari'=>$this->jdwl_hari,
            'ds_masuk' => $this->ds_masuk,
            'ds_keluar' => $this->ds_keluar,
            'm_absen_dosen.jdwl_id' => $this->jdwl_id,
            'jdwl_masuk' => $this->jdwl_masuk,
            'jdwl_keluar' => $this->jdwl_keluar,
            'tgl_normal' => $this->tgl_normal,
            'tgl_perkuliahan' => $this->tgl_perkuliahan,
        ]);

        $query->andFilterWhere(['like', 'ds_stat', $this->ds_stat])
            ->andFilterWhere(['like', 'input_tipe', $this->input_tipe])
            ->andFilterWhere(['like', 'jdwl_kls', $this->jdwl_kls])
            ->andFilterWhere(['like', "concat(m_absen_dosen.mtk_nama,' ',m_absen_dosen.mtk_kode,' ',ds.ds_nm)", $this->mtk_kode])
            ->andFilterWhere(['like', 'sesi', $this->sesi])
            ->andFilterWhere(['like', 'tipe', $this->tipe])
            ->andFilterWhere(['like', 'ket', $this->ket])
            ->andFilterWhere(['like', 'RStat', $this->RStat])
            ->andFilterWhere(['like', 'kr_kode_', $this->kr_kode_]);

        return $dataProvider;
    }



}
