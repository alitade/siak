<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TAbsenDosen;

/**
 * TAbsenDosenSearch represents the model behind the search form about `app\models\TAbsenDosen`.
 */
class TAbsenDosenSearch extends TAbsenDosen
{
    public function rules()
    {
        return [
            [['id', 'ds_id', 'ds_id1', 'ds_get_id', 'ds_fid', 'ds_fid1', 'ds_get_fid', 'jdwl_id', 'cuid', 'uuid', 'duid'], 'integer'],
            [['ds_masuk', 'ds_keluar', 'ds_stat', 'input_tipe', 'jdwl_kls', 'jdwl_hari', 'jdwl_masuk', 'jdwl_keluar', 'mtk_kode', 'mtk_nama', 'sesi', 'tgl_normal', 'tgl_perkuliahan', 'tipe', 'ctgl', 'utgl', 'dtgl', 'ket', 'RStat',], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params){
        $query = TAbsenDosen::find()
            ->select([
                't_absen_dosen.*','ds.ds_nm',
                'tMhs'=>'jd._totmhs',
                'tHdr'=>"(select sum(iif(mhs_stat=1,1,0)) from t_absen_mhs where id_absen_ds=t_absen_dosen.id)",
                'durasi'=>"datediff(minute,t_absen_dosen.jdwl_masuk,t_absen_dosen.ds_masuk)",
                'dM'=>"datediff(minute,t_absen_dosen.jdwl_masuk,t_absen_dosen.ds_masuk)",
                'dK'=>"datediff(minute,t_absen_dosen.jdwl_keluar,t_absen_dosen.ds_keluar)",
            ])
            ->innerJoin("tbl_jadwal jd","(jd.jdwl_id=t_absen_dosen.jdwl_id)")
            ->innerJoin("tbl_dosen ds","(ds.ds_id=t_absen_dosen.ds_id)")
            ->orderBy(['t_absen_dosen.jdwl_masuk'=>SORT_ASC,'ds.ds_nm'=>SORT_ASC])
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
            'ds_fid' => $this->ds_fid,
            'ds_fid1' => $this->ds_fid1,
            'ds_get_fid' => $this->ds_get_fid,
            'ds_masuk' => $this->ds_masuk,
            'ds_keluar' => $this->ds_keluar,
            't_absen_dosen.jdwl_id' => $this->jdwl_id,
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
            ->andFilterWhere(['like', "concat(t_absen_dosen.mtk_nama,' ',t_absen_dosen.mtk_kode,' ',ds.ds_nm)", $this->mtk_kode])
            ->andFilterWhere(['like', 't_absen_dosen.jdwl_masuk', $this->jdwl_masuk])
            ->andFilterWhere(['like', 'sesi', $this->sesi])
            ->andFilterWhere(['like', 'tipe', $this->tipe])
            ->andFilterWhere(['like', 'ket', $this->ket])
            ->andFilterWhere(['like', 'RStat', $this->RStat]);

        return $dataProvider;
    }
}
