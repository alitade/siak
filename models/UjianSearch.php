<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ujian;

/**
 * UjianSearch represents the model behind the search form about `app\models\Ujian`.
 */
class UjianSearch extends Ujian
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Id', 'IdJadwal'], 'integer'],
            [['Kat', 'Tgl', 'Masuk', 'Keluar', 'RgKode','GKode','Dsn','Mtk','Jadwal'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Ujian::find()
		->select([
			"ujian.*",
			'Kls'=>'jd.jdwl_kls',
			'Mtk'=>"mk.mtk_nama",
			'Dsn'=>"ds.ds_nm",
			'Jrs'=>"concat(jr.jr_jenjang,' ',jr.jr_nama)",
			'Prg'=>"pr_nama",
			'Jadwal'=>"concat(ujian.Masuk,'-',ujian.Keluar)",
			'Jml'=>"(select count(*) from peserta_ujian where IdUjian=ujian.Id)",
			'Sisa'=>"
				(select count(*) from tbl_krs krs where krs.jdwl_id=jd.jdwl_id) - (select count(*) from peserta_ujian where IdUjian=ujian.Id)
			",
		])
		->innerJoin('tbl_jadwal jd','ujian.IdJadwal=jd.jdwl_id')
		->innerJoin('tbl_bobot_nilai bn','bn.id=jd.bn_id')
		->innerJoin('tbl_dosen ds','bn.ds_nidn=ds.ds_id')
		->innerJoin('tbl_matkul mk','bn.mtk_kode=mk.mtk_kode')
		->innerJoin('tbl_kalender kl','kl.kln_id=bn.kln_id')
		->innerJoin('tbl_jurusan jr','kl.jr_id=jr.jr_id')
		->innerJoin('tbl_program pr','kl.pr_kode=pr.pr_kode')
		->orderBy(['ujian.Tgl'=>SORT_ASC,"concat(ujian.Masuk,'-',ujian.Keluar)"=>SORT_ASC])
		
		//->innerJoin('tbl_jadwal jd','ujian.IdJadwal=jd.jdwl_id')
		//->innerJoin('tbl_jadwal jd','ujian.IdJadwal=jd.jdwl_id')
		
		;

        // add conditions that should always apply here
		
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'ujian.Id' => $this->Id,
            'IdJadwal' => $this->IdJadwal,
            'Tgl' => $this->Tgl,
        ]);

        $query->andFilterWhere(['like', 'Kat', $this->Kat])
            ->andFilterWhere(['like', 'Masuk', $this->Masuk])
            ->andFilterWhere(['like', 'ds.ds_nm', $this->Dsn])
			
            ->andFilterWhere(['like', "concat('[',mk.mtk_kode,'] ',mk.mtk_nama)", $this->Mtk])
            ->andFilterWhere(['like', "concat(ujian.Masuk,'-',ujian.Keluar)", $this->Jadwal])
			
            ->andFilterWhere(['like', 'Keluar', $this->Keluar])
            ->andFilterWhere(['like', 'ujian.GKode', $this->GKode])
            ->andFilterWhere(['like', 'RgKode', $this->RgKode]);

        return $dataProvider;
    }
}
