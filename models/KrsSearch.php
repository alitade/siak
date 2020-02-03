<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Krs;

/**
 * KrsSearch represents the model behind the search form about `app\models\Krs`.
 */
class KrsSearch extends Krs
{
	public $Mahasiswa,$AvKrs;

    public function rules()
    {
        return [
            [['krs_id', 'jdwl_id', 'krs_tgs1', 'krs_tgs2', 'krs_tgs3', 'krs_tambahan', 'krs_quis', 'krs_uts', 'krs_uas'], 'integer'],
            [['krs_tgl', 'mhs_nim', 'krs_grade', 'krs_stat', 'krs_ulang', 'kr_kode_', 'ds_nidn_', 'ds_nm_', 'mtk_kode_', 'mtk_nama_', 'sks_',
				'kr_kode','jr_id','pr_kode','Matakuliah','Mahasiswa','AvKrs'
			
			], 'safe'],
            [['krs_tot'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params,$kon=''){
        $query = Krs::find()->where(" (RStat='0' or RStat is null) ");
		if($kon!=''){
			$query->andWhere($kon);
		}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'krs_id' => $this->krs_id,
            'krs_tgl' => $this->krs_tgl,
            'jdwl_id' => $this->jdwl_id,
            'krs_tgs1' => $this->krs_tgs1,
            'krs_tgs2' => $this->krs_tgs2,
            'krs_tgs3' => $this->krs_tgs3,
            'krs_tambahan' => $this->krs_tambahan,
            'krs_quis' => $this->krs_quis,
            'krs_uts' => $this->krs_uts,
            'krs_uas' => $this->krs_uas,
            'krs_tot' => $this->krs_tot,
        ]);

        $query->andFilterWhere(['like', 'mhs_nim', $this->mhs_nim])
            ->andFilterWhere(['like', 'krs_grade', $this->krs_grade])
            ->andFilterWhere(['like', 'krs_stat', $this->krs_stat])
            ->andFilterWhere(['like', 'krs_ulang', $this->krs_ulang])
            ->andFilterWhere(['like', 'kr_kode_', $this->kr_kode_])
            ->andFilterWhere(['like', 'ds_nidn_', $this->ds_nidn_])
            ->andFilterWhere(['like', 'ds_nm_', $this->ds_nm_])
            ->andFilterWhere(['like', 'mtk_kode_', $this->mtk_kode_])
            ->andFilterWhere(['like', 'mtk_nama_', $this->mtk_nama_])
            ->andFilterWhere(['like', 'sks_', $this->sks_]);

        return $dataProvider;
    }

    public function search2($params,$kon=''){
        $query = Krs::find()
		->select(
			[	'tbl_krs.*',
				'AvKrs'=>"isnull(dbo.ValidasiKrs(tbl_krs.jdwl_id,tbl_krs.mhs_nim),0)"
			]
		)
		->where(" (RStat='0' or RStat is null) ");
		if($kon!=''){
			$query->andWhere($kon);
		}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'krs_id' => $this->krs_id,
            'krs_tgl' => $this->krs_tgl,
            'jdwl_id' => $this->jdwl_id,
            'krs_tgs1' => $this->krs_tgs1,
            'krs_tgs2' => $this->krs_tgs2,
            'krs_tgs3' => $this->krs_tgs3,
            'krs_tambahan' => $this->krs_tambahan,
            'krs_quis' => $this->krs_quis,
            'krs_uts' => $this->krs_uts,
            'krs_uas' => $this->krs_uas,
            'krs_tot' => $this->krs_tot,
        ]);

        $query->andFilterWhere(['like', 'mhs_nim', $this->mhs_nim])
            ->andFilterWhere(['like', 'krs_grade', $this->krs_grade])
            ->andFilterWhere(['like', 'krs_stat', $this->krs_stat])
            ->andFilterWhere(['like', 'krs_ulang', $this->krs_ulang])
            ->andFilterWhere(['like', 'kr_kode_', $this->kr_kode_])
            ->andFilterWhere(['like', 'ds_nidn_', $this->ds_nidn_])
            ->andFilterWhere(['like', 'ds_nm_', $this->ds_nm_])
            ->andFilterWhere(['like', 'mtk_kode_', $this->mtk_kode_])
            ->andFilterWhere(['like', 'mtk_nama_', $this->mtk_nama_])
            ->andFilterWhere(['like', 'sks_', $this->sks_]);

        return $dataProvider;
    }



    public function nilai($params,$kon='')
    {

		$db = Yii::$app->db1;
		$keuangan 	= Funct::getDsnAttribute('dbname', $db->dsn);
		if(!$keuangan){$keuangan = Funct::getDsnAttribute('Database', $db->dsn);}


        $query = Krs::find()
//		->distinct(true)
		->select([
			'tbl_krs.*',
			'Mahasiswa'=>'p.Nama',
			'Jurusan'=>"concat(jr.jr_jenjang,' ',jr.jr_nama)",
			'Program'=>"pr.pr_nama",
			'Matakuliah'=>"concat(mk.mtk_kode,' : ',mk.mtk_nama)",
			'kl.kr_kode','jr.jr_id','pr.pr_kode'
			

		])
		// biodata
		->innerJoin('tbl_mahasiswa mhs','mhs.mhs_nim=tbl_krs.mhs_nim'." and (mhs.RStat='0' or mhs.RStat is NULL )")		
		->innerJoin("$keuangan.dbo.student s"," s.nim COLLATE Latin1_General_CI_AS = mhs.mhs_nim ")
		->innerJoin("$keuangan.dbo.people p"," p.No_Registrasi = s.no_registrasi ")

		->innerJoin('tbl_jadwal jd','jd.jdwl_id=tbl_krs.jdwl_id'." and (jd.RStat='0' or jd.RStat is NULL )")
		->innerJoin('tbl_bobot_nilai bn','bn.id=jd.bn_id'." and (bn.RStat='0' or bn.RStat is NULL )")
		->innerJoin('tbl_matkul mk','mk.mtk_kode=bn.mtk_kode'." and (mk.RStat='0' or mk.RStat is NULL )")
		->innerJoin('tbl_dosen ds','ds.ds_id=bn.ds_nidn'." and (ds.RStat='0' or ds.RStat is NULL )")
		->innerJoin('tbl_kalender kl','kl.kln_id=bn.kln_id'." and (kl.RStat='0' or kl.RStat is NULL )")
		->innerJoin('tbl_jurusan jr','jr.jr_id=kl.jr_id'." and (jr.RStat='0' or jr.RStat is NULL )")
		->innerJoin('tbl_program pr','pr.pr_kode=kl.pr_kode'." and (pr.RStat='0' or pr.RStat is NULL )")
			
		->where(" (tbl_krs.RStat='0' or tbl_krs.RStat is null) ")
		->orderBy([
			'Jurusan'=>SORT_DESC,
			'Program'=>SORT_DESC,
		])

		;
		
		if($kon!=''){
			$query->andWhere($kon);
		}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'defaultPageSize' => 50,
				'pageSizeLimit'=>50
			],
 
        ]);

        if (!($this->load($params) && $this->validate())) {
			$query->andFilterWhere(['krs_id'=>0]);
            return $dataProvider;
        }

        $query->andFilterWhere([
            'krs_id' => $this->krs_id,
            'jr.jr_id' => $this->jr_id,
            'sks_' => $this->sks_,
            'kl.kr_kode' => $this->kr_kode,
			
        ]);

        $query->andFilterWhere(['like', 'mhs_nim', $this->mhs_nim])
            ->andFilterWhere(['like', 'krs_grade', $this->krs_grade])
            ->andFilterWhere(['like', 'krs_stat', $this->krs_stat])
            ->andFilterWhere(['like', 'krs_ulang', $this->krs_ulang])
            ->andFilterWhere(['like', 'kr_kode_', $this->kr_kode_])
            ->andFilterWhere(['like', 'ds_nidn_', $this->ds_nidn_])
            ->andFilterWhere(['like', 'ds_nm_', $this->ds_nm_])
            ->andFilterWhere(['like', 'mtk_kode_', $this->mtk_kode_])
            ->andFilterWhere(['like', 'mtk_nama_', $this->mtk_nama_])
            ->andFilterWhere(['like', "concat(mk.mtk_kode,' : ',mk.mtk_nama)", $this->Matakuliah])
            ->andFilterWhere(['like', 'p.Nama', $this->Mahasiswa]);

        return $dataProvider;
    }


    public function wali($params,$kon='')
    {

		$db = Yii::$app->db1;
		$keuangan 	= Funct::getDsnAttribute('dbname', $db->dsn);
		if(!$keuangan){$keuangan = Funct::getDsnAttribute('Database', $db->dsn);}

        $query = Krs::find()
		->distinct(true)
		->select([
//			'tbl_krs.*',
			'mhs.mhs_nim',
			'Mahasiswa'=>'p.Nama',
			'Jurusan'=>"concat(jr.jr_jenjang,' ',jr.jr_nama)",
			'Program'=>"pr.pr_nama",
			'kl.kr_kode','jr.jr_id','pr.pr_kode',
			'sks_'=>"sum(cast(sks_ as int))",
			'Matakuliah'=>"sum(iif(sks_ is null,0,1))",
			

		])
		// biodata
		->innerJoin('tbl_mahasiswa mhs','mhs.mhs_nim=tbl_krs.mhs_nim'." and (mhs.RStat='0' or mhs.RStat is NULL )")		
		->innerJoin("$keuangan.dbo.student s"," s.nim COLLATE Latin1_General_CI_AS = mhs.mhs_nim ")
		->innerJoin("$keuangan.dbo.people p"," p.No_Registrasi = s.no_registrasi ")

		->innerJoin('tbl_jadwal jd','jd.jdwl_id=tbl_krs.jdwl_id'." and (jd.RStat='0' or jd.RStat is NULL )")
		->innerJoin('tbl_bobot_nilai bn','bn.id=jd.bn_id'." and (bn.RStat='0' or bn.RStat is NULL )")
		->innerJoin('tbl_matkul mk','mk.mtk_kode=bn.mtk_kode'." and (mk.RStat='0' or mk.RStat is NULL )")
		->innerJoin('tbl_dosen ds','ds.ds_id=bn.ds_nidn'." and (ds.RStat='0' or ds.RStat is NULL )")
		->innerJoin('tbl_kalender kl','kl.kln_id=bn.kln_id'." and (kl.RStat='0' or kl.RStat is NULL )")
		->innerJoin('tbl_jurusan jr','jr.jr_id=kl.jr_id'." and (jr.RStat='0' or jr.RStat is NULL )")
		->innerJoin('tbl_program pr','pr.pr_kode=kl.pr_kode'." and (pr.RStat='0' or pr.RStat is NULL )")
			
		->where(" (tbl_krs.RStat='0' or tbl_krs.RStat is null) ")
		->orderBy([
			'Jurusan'=>SORT_DESC,
			'Program'=>SORT_DESC,
		])
		->groupBy([
			'p.Nama',
			'mhs.mhs_nim',
			'pr.pr_nama',
			"concat(jr.jr_jenjang,' ',jr.jr_nama)",
			'kr_kode',
			'kl.kr_kode','jr.jr_id','pr.pr_kode',
		])
		;
		
		if($kon!=''){
			$query->andWhere($kon);
		}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'defaultPageSize' => 50,
				'pageSizeLimit'=>50
			],
 
        ]);

        if (!($this->load($params) && $this->validate())) {
			$query->andFilterWhere(['krs_id'=>0]);
            return $dataProvider;
        }

        $query->andFilterWhere([
            'krs_id' => $this->krs_id,
            'jr.jr_id' => $this->jr_id,
            'sks_' => $this->sks_,
            'kl.kr_kode' => $this->kr_kode,
			
        ]);

        $query->andFilterWhere(['like', 'mhs_nim', $this->mhs_nim])
            ->andFilterWhere(['like', 'krs_grade', $this->krs_grade])
            ->andFilterWhere(['like', 'krs_stat', $this->krs_stat])
            ->andFilterWhere(['like', 'krs_ulang', $this->krs_ulang])
            ->andFilterWhere(['like', 'kr_kode_', $this->kr_kode_])
            ->andFilterWhere(['like', 'ds_nidn_', $this->ds_nidn_])
            ->andFilterWhere(['like', 'ds_nm_', $this->ds_nm_])
            ->andFilterWhere(['like', 'mtk_kode_', $this->mtk_kode_])
            ->andFilterWhere(['like', 'mtk_nama_', $this->mtk_nama_])
            ->andFilterWhere(['like', 'p.Nama', $this->Mahasiswa]);

        return $dataProvider;
    }

    public function detail($params,$kon='')
    {

        $db = Yii::$app->db1;
        $keuangan 	= Funct::getDsnAttribute('dbname', $db->dsn);
        if(!$keuangan){$keuangan = Funct::getDsnAttribute('Database', $db->dsn);}

        $query = Krs::find()
            //->distinct(true)
            ->select([
                'tbl_krs.*',
                #'mhs.mhs_nim',
                'Mahasiswa'=>'p.Nama',
                'Jurusan'=>"concat(jr.jr_jenjang,' ',jr.jr_nama)",
                'Program'=>"pr.pr_nama",
                'kl.kr_kode','jr.jr_id','pr.pr_kode',
                'mk.mtk_kode','mk.mtk_nama','mk.mtk_sks','tbl_krs.sks_',
                'ds.ds_nm',
                //'sks_'=>"sum(cast(sks_ as int))",
                //'Matakuliah'=>"sum(iif(sks_ is null,0,1))",


            ])
            // biodata
            ->innerJoin('tbl_mahasiswa mhs','mhs.mhs_nim=tbl_krs.mhs_nim'." and isnull(mhs.RStat,0)=0")
            ->innerJoin("$keuangan.dbo.student s"," s.nim COLLATE Latin1_General_CI_AS = mhs.mhs_nim ")
            ->innerJoin("$keuangan.dbo.people p"," p.No_Registrasi = s.no_registrasi ")
            ->innerJoin('tbl_jadwal jd','jd.jdwl_id=tbl_krs.jdwl_id'." and isnull(jd.RStat,0)=0")
            ->innerJoin('tbl_bobot_nilai bn','bn.id=jd.bn_id'." and isnull(bn.RStat,0)=0")
            ->innerJoin('tbl_matkul mk','mk.mtk_kode=bn.mtk_kode'." and isnull(mk.RStat,0)=0")
            ->innerJoin('tbl_dosen ds','ds.ds_id=bn.ds_nidn'." and isnull(ds.RStat,0)=0")
            ->innerJoin('tbl_kalender kl','kl.kln_id=bn.kln_id'." and isnull(kl.RStat,0)=0")
            ->innerJoin('tbl_jurusan jr','jr.jr_id=kl.jr_id'." and isnull(jr.RStat,0)=0")
            ->innerJoin('tbl_program pr','pr.pr_kode=kl.pr_kode'." and isnull(pr.RStat,0)=0")
            ->where("  isnull(tbl_krs.RStat,0)=0 and isnull(tbl_krs.krs_stat,0)=1 ")
            //--
            ->orderBy([
                'Jurusan'=>SORT_DESC,
                'Program'=>SORT_DESC,
                'mhs.Mhs_nim'=>SORT_DESC,
            ]);

        if($kon!=''){
            $query->andWhere($kon);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 50,
                'pageSizeLimit'=>50
            ],

        ]);

        if (!($this->load($params) && $this->validate())) {
            $query->andFilterWhere(['krs_id'=>0]);
            return $dataProvider;
        }

        $query->andFilterWhere([
            'krs_id' => $this->krs_id,
            'jr.jr_id' => $this->jr_id,
            'sks_' => $this->sks_,
            'kl.kr_kode' => $this->kr_kode,
            'tbl_krs.mhs_nim'=>$this->mhs_nim,

        ]);

        $query->andFilterWhere(['like', 'krs_grade', $this->krs_grade])
            ->andFilterWhere(['like', 'krs_stat', $this->krs_stat])
            ->andFilterWhere(['like', 'krs_ulang', $this->krs_ulang])
            ->andFilterWhere(['like', 'kr_kode_', $this->kr_kode_])
            ->andFilterWhere(['like', 'ds_nidn_', $this->ds_nidn_])
            ->andFilterWhere(['like', 'ds_nm_', $this->ds_nm_])
            ->andFilterWhere(['like', 'mtk_kode_', $this->mtk_kode_])
            ->andFilterWhere(['like', 'mtk_nama_', $this->mtk_nama_])
            ->andFilterWhere(['like', 'p.Nama', $this->Mahasiswa]);

        return $dataProvider;
    }


}
