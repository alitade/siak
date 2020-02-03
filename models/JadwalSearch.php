<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Jadwal;

/**
 * JadwalSearch represents the model behind the search form about `app\models\Jadwal`.
 */
class JadwalSearch extends Jadwal{

	public $TotSesi,$uts,$uas,$lolos;

    public function attributeLabels(){
        return [
            'mtk_nama' => 'Matakuliah',
            'mtk_kode' => 'Kode Matakuliah',
            'mtk_semester' => 'Semester',
			
            'jr_id' => 'Jurusan',
            'jr_nama' => 'Jurusan',
            'jr_jenjang' => 'Jenjang',
			
            'pr_kode' => 'Program',
            'pr_nama' => 'Program',
            'ds_nm' => 'Dosen',
            'jum' => 'Total Mahasiswa',
            'jdwl_hari' => 'Hari',

			'kr_kode'=>'Kode Kurikulum',
			
            'jdwl_id' => 'Jdwl ID',
            'bn_id' => 'Bn ID',
            'rg_kode' => 'Ruang',
            'semester' => 'Semester',
            'jdwl_hari' => 'Hari',
            'jdwl_masuk' => 'Jam Masuk',
            'jdwl_keluar' => 'Jam Keluar',
            'jdwl_kls' => 'Kelas',
            'jdwl_uts' => 'Uts',
            'jdwl_uas' => 'Uas',
            'jdwl_uts_out' => 'Akhir UTS',
            'jdwl_uas_out' => 'Akhir UAS',
            'rg_uts' 		=> 'Ruang Uts',
            'rg_uas' 	=> 'Ruang Uas',
            'jenis'		=>'Jenis',
            'kr_kode'=>'Kurikulum',
            'TotSesi'=>'Pertemuan'

        ];
    }

    public function rules(){
        return [
            [['jdwl_id', 'bn_id'], 'integer'],
            [
				[	'rg_kode', 'semester', 'jdwl_hari', 'jdwl_masuk', 'jdwl_keluar', 
					'jdwl_kls', 'jdwl_uts', 'jdwl_uas', 'jdwl_uts_out', 'jdwl_uas_out', 'rg_uts', 'rg_uas',
					
					'mtk_kode','mtk_nama','mtk_semester',
					'jr_id','jr_nama','jr_jenjang', 
					'pr_kode','pr_nama',
					'ds_nm','jum','jumabs',
					'kr_kode','jadwal','ujian',
					'uts','uas'
				],
			 	'safe'
			],
        ];
    }

    public function scenarios(){
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params,$kon='',$order=''){
        $query = Jadwal::find()->where(" (RStat='0' or RStat is null) ");
		
		
		if($order!=''){
			$query->orderBy($order);	
		}else{
			$query->orderBy(['jdwl_hari'=>SORT_ASC,'jdwl_masuk'=>SORT_ASC,]);	
		}
		
		if(isset($_SESSION['Ckln'])){
			$krkode= $_SESSION['Ckln'];
			$cek = Kurikulum::findOne($krkode);
			if($cek){
				$query->andWhere(" bn_id in( select id from tbl_bobot_nilai where kln_id in(select kln_id from tbl_kalender where kr_kode='$krkode') )");
			}
		}
		
		if($kon!=''){$query->andWhere($kon);}
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'jdwl_id' => $this->jdwl_id,
            'bn_id' => $this->bn_id,
            'jdwl_uts' => $this->jdwl_uts,
            'jdwl_uas' => $this->jdwl_uas,
            'jdwl_uts_out' => $this->jdwl_uts_out,
            'jdwl_uas_out' => $this->jdwl_uas_out,
        ]);

        $query->andFilterWhere(['like', 'rg_kode', $this->rg_kode])
		
            ->andFilterWhere(['like', 'semester', $this->semester])
            ->andFilterWhere(['like', 'jdwl_hari', $this->jdwl_hari])
            ->andFilterWhere(['like', 'jdwl_masuk', $this->jdwl_masuk])
            ->andFilterWhere(['like', 'jdwl_keluar', $this->jdwl_keluar])
            ->andFilterWhere(['=', 'jdwl_kls', $this->jdwl_kls])
            ->andFilterWhere(['like', 'rg_uts', $this->rg_uts])
            ->andFilterWhere(['like', 'rg_uas', $this->rg_uas]);

        return $dataProvider;
    }

    public function krs_171129($params,$kon='',$order=''){
        $query = JadwalSearch::find()
            ->select([
                'tbl_jadwal.*',
                'bn.mtk_kode',
                'bn.id',
                'lolos'=>"iif(pr.pr_kode in(6,7) or jr.jr_jenjang='s2',1,0)",
                'x'=>"iif( DATEADD(WEEK,3,kl.kln_masuk) > cast(getdate() as date) ,'1','0' )",//validasi berhubungan dangan tgl perkuliahan
                'TotSesi'=>"isnull(dbo.TotalPertemuanDosen(tbl_jadwal.jdwl_id,'t',default),0)",

                'uts'=>"dbo.TotalPertemuanDosen(tbl_jadwal.jdwl_id,'t',0)",
                'uas'=>"dbo.TotalPertemuanDosen(tbl_jadwal.jdwl_id,'a',0)",
                'mk.mtk_semester','mtk_sks','mk.penanggungjawab',"concat('[',bn.mtk_kode,'] ',mtk_nama) mtk_nama",'mtk_nama matkul',
                'jr.jr_id','jr_jenjang','jr_nama',
                'pr.pr_kode','pr_nama','ds_nm',
                "(
					select count(*) from tbl_krs 
					where jdwl_id=tbl_jadwal.jdwl_id
					and (RStat is null or RStat='0')
					
				) jum ",
                'jumabs'=>'(
					select count(distinct k.krs_id) from tbl_krs k,
					tbl_absensi a
					where 
					k.jdwl_id=tbl_jadwal.jdwl_id
					and a.krs_id=k.krs_id 
				)',
                'kl.kr_kode',
                "concat(jdwl_masuk,'-',jdwl_keluar) jadwal",
                "iif(
					jdwl_uts is not null and jdwl_uas is not null,
					  'TA',
					  iif(jdwl_uts is not null,
					    'T',
						 iif(jdwl_uas is not null,'A','')
					  )
				) as ujian",
            ])
            ->innerJoin('tbl_bobot_nilai bn',"bn.id = tbl_jadwal.bn_id and isnull(bn.RStat,0)=0")
            ->innerJoin('tbl_dosen ds',"bn.ds_nidn = ds.ds_id  and  isnull(ds.RStat,0)=0")
            ->innerJoin('tbl_kalender kl',"kl.kln_id = bn.kln_id  and  isnull(kl.RStat,0)=0 ")
            ->innerJoin('tbl_program pr',"pr.pr_kode = kl.pr_kode  and (pr.RStat='0' or pr.RStat is null)")
            ->innerJoin('tbl_jurusan jr','jr.jr_id= kl.jr_id ')
            ->innerJoin('tbl_matkul mk',"mk.mtk_kode = bn.mtk_kode  and  isnull(mk.RStat,0)=0 ")
            //->innerJoin('tbl_krs krs',"krs.jdwl_id= tbl_jadwal.jdwl_id  and  isnull(tbl_jadwal.RStat,0)=0 ")
            ->where(" isnull(tbl_jadwal.RStat,0)=0");


        if($order!=''){
            $query->orderBy($order);
        }else{
            $query->orderBy([
                'jr.jr_jenjang'=>SORT_DESC,
                'jr.jr_nama'=>SORT_ASC,
                'pr.pr_kode'=>SORT_ASC,
                'jdwl_hari'=>SORT_ASC,
                'jdwl_masuk'=>SORT_ASC,
            ]);
        }


        /*
        if(isset($_SESSION['Ckln'])){
            $krkode= $_SESSION['Ckln'];
            $cek = Kurikulum::findOne($krkode);
            if($cek){
                $query->andWhere(" bn_id in( select id from tbl_bobot_nilai where kln_id in(select kln_id from tbl_kalender where kr_kode='$krkode') )");
            }
        }
        */
        if($kon!=''){$query->andWhere($kon);}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'mtk_nama' => [
                    'asc' => ['mk.mtk_nama' => SORT_ASC],
                    'desc' => ['mk.mtk_nama' => SORT_DESC],
                    'label' => 'Matakuliah',
                    'default' => SORT_ASC
                ],
                'jr_id' => [
                    'asc' => ['jr.jr_id' => SORT_ASC],
                    'desc' => ['jr.jr_id' => SORT_DESC],
                    'label' => 'Jurusan',
                    'default' => SORT_ASC
                ],
                'pr_kode' => [
                    'asc' => ['pr.pr_kode' => SORT_ASC],
                    'desc' => ['pr.pr_kode' => SORT_DESC],
                    'label' => 'Program',
                    'default' => SORT_ASC
                ],
                'jdwl_hari' => [
                    'asc' => ['jdwl_hari' => SORT_ASC],
                    'desc' => ['jdwl_hari' => SORT_DESC],
                    'label' => 'Hari',
                    'default' => SORT_ASC
                ],
                'jdwl_masuk' => [
                    'asc' => ['jdwl_masuk' => SORT_ASC],
                    'desc' => ['jdwl_masuk' => SORT_DESC],
                    'label' => 'Jadwal Masuk',
                    'default' => SORT_ASC
                ],
                'jdwl_kls' => [
                    'asc' => ['jdwl_kls' => SORT_ASC],
                    'desc' => ['jdwl_kls' => SORT_DESC],
                    'label' => 'Kelas',
                    'default' => SORT_ASC
                ],
                'ds_nm' => [
                    'asc' => ['ds.ds_nm' => SORT_ASC],
                    'desc' => ['ds.ds_nm' => SORT_DESC],
                    'label' => 'Dosen',
                    'default' => SORT_ASC
                ],
                'rg_kode' => [
                    'asc' => ['rg_kode' => SORT_ASC],
                    'desc' => ['rg_kode' => SORT_DESC],
                    'label' => 'Ruangan',
                    'default' => SORT_ASC
                ],
            ]
        ]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'jdwl_id' => $this->jdwl_id,
            'bn_id' => $this->bn_id,
            'jdwl_uts' => $this->jdwl_uts,
            'jdwl_uas' => $this->jdwl_uas,
            'jdwl_uts_out' => $this->jdwl_uts_out,
            'jdwl_uas_out' => $this->jdwl_uas_out,

            'jum'=>$this->jum,

            "dbo.TotalPertemuanDosen(tbl_jadwal.jdwl_id,'t',0)"=>$this->uts,
            "dbo.TotalPertemuanDosen(tbl_jadwal.jdwl_id,'a',0)"=>$this->uas,


            'jr.jr_id'=>$this->jr_id,
            'pr.pr_kode'=>$this->pr_kode,
            'kl.kr_kode'=>$this->kr_kode,
        ]);

        $query->andFilterWhere(['like', 'rg_kode', $this->rg_kode])

            ->andFilterWhere(['like', "concat('[',bn.mtk_kode,'] ',mtk_nama)", $this->mtk_nama])
            ->andFilterWhere(['like', 'jr_nama', $this->jr_nama])
            ->andFilterWhere(['like', 'jadwal', $this->jadwal])
            ->andFilterWhere(['like', 'ds_nm', $this->ds_nm])
            ->andFilterWhere(['like', 'pr_nama', $this->pr_nama])
            ->andFilterWhere(['=', "
			iif(
					jdwl_uts is not null and jdwl_uas is not null,
					  'TA',
					  iif(jdwl_uts is not null,
					    'T',
						 iif(jdwl_uas is not null,'A','')
					  )
				)
			
			", $this->ujian])


            ->andFilterWhere(['like', 'semester', $this->semester])
            ->andFilterWhere(['like', 'jdwl_hari', $this->jdwl_hari])
            ->andFilterWhere(['like', 'jdwl_masuk', $this->jdwl_masuk])
            ->andFilterWhere(['like', 'jdwl_keluar', $this->jdwl_keluar])
            ->andFilterWhere(['=', 'jdwl_kls', $this->jdwl_kls])
            ->andFilterWhere(['like', 'rg_uts', $this->rg_uts])
            ->andFilterWhere(['like', 'rg_uas', $this->rg_uas]);

        return $dataProvider;
    }

    public function krs($params,$kon='',$order=''){
        $query = JadwalSearch::find()
            ->select([
                'tbl_jadwal.*',
                'bn.mtk_kode',
                'bn.id',
                'lolos'=>"iif(pr.pr_kode in(6,7) or jr.jr_jenjang='s2',1,0)",
                'x'=>"iif( DATEADD(WEEK,3,kl.kln_masuk) > cast(getdate() as date) ,'1','0' )",//validasi berhubungan dangan tgl perkuliahan
                'TotSesi'=>"isnull(dbo.TotalPertemuanDosen(tbl_jadwal.jdwl_id,'t',default),0)",

                'uts'=>"
                    ( SELECT iif(isnull(a.def,0)=0,a.nil,a1.nil) FROM aturan a
                        INNER JOIN aturan a1 on(a1.id=a.parent)
                        WHERE a.id= CASE when pr.pr_kode=1 THEN 21 when pr.pr_kode=2 THEN 22 when pr.pr_kode=4 THEN 23 when pr.pr_kode=5 THEN 24 ELSE 1 END AND a.aktif='1'
                    )                
                ",
                'uas'=>"
                    ( SELECT iif(isnull(a.def,0)=0,a.nil,a1.nil) FROM aturan a
                        INNER JOIN aturan a1 on(a1.id=a.parent)
                        WHERE a.id= CASE when pr.pr_kode=1 THEN 25 when pr.pr_kode=2 THEN 26 when pr.pr_kode=4 THEN 27 when pr.pr_kode=5 THEN 28 ELSE 2 END AND a.aktif='1'
                    )                
                ",
                'mk.mtk_semester','mtk_sks','mk.penanggungjawab',"concat('[',bn.mtk_kode,'] ',mtk_nama) mtk_nama",'mtk_nama matkul',
                'jr.jr_id','jr_jenjang','jr_nama',
                'pr.pr_kode','pr_nama','ds_nm',
                "(
					select count(*) from tbl_krs 
					where jdwl_id=tbl_jadwal.jdwl_id
					and (RStat is null or RStat='0')
					
				) jum ",
                'jumabs'=>'(
					select count(distinct k.krs_id) from tbl_krs k,
					tbl_absensi a
					where 
					k.jdwl_id=tbl_jadwal.jdwl_id
					and a.krs_id=k.krs_id 
				)',
                'kl.kr_kode',
                "concat(jdwl_masuk,'-',jdwl_keluar) jadwal",
                "iif(
					jdwl_uts is not null and jdwl_uas is not null,
					  'TA',
					  iif(jdwl_uts is not null,
					    'T',
						 iif(jdwl_uas is not null,'A','')
					  )
				) as ujian",
            ])
            ->innerJoin('tbl_bobot_nilai bn',"bn.id = tbl_jadwal.bn_id and isnull(bn.RStat,0)=0")
            ->innerJoin('tbl_dosen ds',"bn.ds_nidn = ds.ds_id  and  isnull(ds.RStat,0)=0")
            ->innerJoin('tbl_kalender kl',"kl.kln_id = bn.kln_id  and  isnull(kl.RStat,0)=0 ")
            ->innerJoin('tbl_program pr',"pr.pr_kode = kl.pr_kode  and (pr.RStat='0' or pr.RStat is null)")
            ->innerJoin('tbl_jurusan jr','jr.jr_id= kl.jr_id ')
            ->innerJoin('tbl_matkul mk',"mk.mtk_kode = bn.mtk_kode  and  isnull(mk.RStat,0)=0 ")
            //->innerJoin('tbl_krs krs',"krs.jdwl_id= tbl_jadwal.jdwl_id  and  isnull(tbl_jadwal.RStat,0)=0 ")
            ->where(" isnull(tbl_jadwal.RStat,0)=0");


        if($order!=''){
            $query->orderBy($order);
        }else{
            $query->orderBy([
                'jr.jr_jenjang'=>SORT_DESC,
                'jr.jr_nama'=>SORT_ASC,
                'pr.pr_kode'=>SORT_ASC,
                'jdwl_hari'=>SORT_ASC,
                'jdwl_masuk'=>SORT_ASC,
            ]);
        }


        /*
        if(isset($_SESSION['Ckln'])){
            $krkode= $_SESSION['Ckln'];
            $cek = Kurikulum::findOne($krkode);
            if($cek){
                $query->andWhere(" bn_id in( select id from tbl_bobot_nilai where kln_id in(select kln_id from tbl_kalender where kr_kode='$krkode') )");
            }
        }
        */
        if($kon!=''){$query->andWhere($kon);}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'mtk_nama' => [
                    'asc' => ['mk.mtk_nama' => SORT_ASC],
                    'desc' => ['mk.mtk_nama' => SORT_DESC],
                    'label' => 'Matakuliah',
                    'default' => SORT_ASC
                ],
                'jr_id' => [
                    'asc' => ['jr.jr_id' => SORT_ASC],
                    'desc' => ['jr.jr_id' => SORT_DESC],
                    'label' => 'Jurusan',
                    'default' => SORT_ASC
                ],
                'pr_kode' => [
                    'asc' => ['pr.pr_kode' => SORT_ASC],
                    'desc' => ['pr.pr_kode' => SORT_DESC],
                    'label' => 'Program',
                    'default' => SORT_ASC
                ],
                'jdwl_hari' => [
                    'asc' => ['jdwl_hari' => SORT_ASC],
                    'desc' => ['jdwl_hari' => SORT_DESC],
                    'label' => 'Hari',
                    'default' => SORT_ASC
                ],
                'jdwl_masuk' => [
                    'asc' => ['jdwl_masuk' => SORT_ASC],
                    'desc' => ['jdwl_masuk' => SORT_DESC],
                    'label' => 'Jadwal Masuk',
                    'default' => SORT_ASC
                ],
                'jdwl_kls' => [
                    'asc' => ['jdwl_kls' => SORT_ASC],
                    'desc' => ['jdwl_kls' => SORT_DESC],
                    'label' => 'Kelas',
                    'default' => SORT_ASC
                ],
                'ds_nm' => [
                    'asc' => ['ds.ds_nm' => SORT_ASC],
                    'desc' => ['ds.ds_nm' => SORT_DESC],
                    'label' => 'Dosen',
                    'default' => SORT_ASC
                ],
                'rg_kode' => [
                    'asc' => ['rg_kode' => SORT_ASC],
                    'desc' => ['rg_kode' => SORT_DESC],
                    'label' => 'Ruangan',
                    'default' => SORT_ASC
                ],
            ]
        ]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'jdwl_id' => $this->jdwl_id,
            'bn_id' => $this->bn_id,
            'jdwl_uts' => $this->jdwl_uts,
            'jdwl_uas' => $this->jdwl_uas,
            'jdwl_uts_out' => $this->jdwl_uts_out,
            'jdwl_uas_out' => $this->jdwl_uas_out,

            'jum'=>$this->jum,

            "dbo.TotalPertemuanDosen(tbl_jadwal.jdwl_id,'t',0)"=>$this->uts,
            "dbo.TotalPertemuanDosen(tbl_jadwal.jdwl_id,'a',0)"=>$this->uas,


            'jr.jr_id'=>$this->jr_id,
            'pr.pr_kode'=>$this->pr_kode,
            'kl.kr_kode'=>$this->kr_kode,
        ]);

        $query->andFilterWhere(['like', 'rg_kode', $this->rg_kode])

            ->andFilterWhere(['like', "concat('[',bn.mtk_kode,'] ',mtk_nama)", $this->mtk_nama])
            ->andFilterWhere(['like', 'jr_nama', $this->jr_nama])
            ->andFilterWhere(['like', 'jadwal', $this->jadwal])
            ->andFilterWhere(['like', 'ds_nm', $this->ds_nm])
            ->andFilterWhere(['like', 'pr_nama', $this->pr_nama])
            ->andFilterWhere(['=', "
			iif(
					jdwl_uts is not null and jdwl_uas is not null,
					  'TA',
					  iif(jdwl_uts is not null,
					    'T',
						 iif(jdwl_uas is not null,'A','')
					  )
				)
			
			", $this->ujian])


            ->andFilterWhere(['like', 'semester', $this->semester])
            ->andFilterWhere(['like', 'jdwl_hari', $this->jdwl_hari])
            ->andFilterWhere(['like', 'jdwl_masuk', $this->jdwl_masuk])
            ->andFilterWhere(['like', 'jdwl_keluar', $this->jdwl_keluar])
            ->andFilterWhere(['=', 'jdwl_kls', $this->jdwl_kls])
            ->andFilterWhere(['like', 'rg_uts', $this->rg_uts])
            ->andFilterWhere(['like', 'rg_uas', $this->rg_uas]);

        return $dataProvider;
    }

    public function dosen($params,$kon='',$order=''){
        $query = JadwalSearch::find()
		->select([
				'tbl_jadwal.jdwl_hari',
				'tbl_jadwal.jdwl_masuk',
				'tbl_jadwal.jdwl_keluar',
				'GKode'=>"isnull(GKode,tbl_jadwal.jdwl_id)",
				'jadwal'=>"concat(jdwl_masuk,'-',jdwl_keluar)",
				'jum'=>"sum(iif(krs.krs_id is null,0,1))",
				'jumjdw'=>"count(distinct tbl_jadwal.jdwl_id)",
			])
        ->innerJoin('tbl_bobot_nilai bn',"bn.id = tbl_jadwal.bn_id and (bn.RStat='0' or bn.RStat is null)")
        ->innerJoin('tbl_dosen ds',"bn.ds_nidn = ds.ds_id  and (ds.RStat='0' or ds.RStat is null)")
        ->innerJoin('tbl_kalender kl',"kl.kln_id = bn.kln_id  and (kl.RStat='0' or kl.RStat is null)")
        ->innerJoin('tbl_program pr',"pr.pr_kode = kl.pr_kode  and (pr.RStat='0' or pr.RStat is null)")
        ->innerJoin('tbl_jurusan jr','jr.jr_id= kl.jr_id ')    
        ->innerJoin('tbl_matkul mk',"mk.mtk_kode = bn.mtk_kode  and (mk.RStat='0' or mk.RStat is null)")                                 		
        ->join('full join','tbl_krs krs',"krs.jdwl_id = tbl_jadwal.jdwl_id and (krs.RStat='0' or krs.RStat is null)")                                 		
		->where("(tbl_jadwal.RStat='0' or tbl_jadwal.RStat is null)")
		->groupBy(["isnull(GKode,tbl_jadwal.jdwl_id)","jdwl_hari","jdwl_masuk","jdwl_keluar"]);
		
		
		if($order!=''){
			$query->orderBy($order);	
		}else{
			$query->orderBy([ 
				'jr.jr_jenjang'=>SORT_DESC,
				'jr.jr_nama'=>SORT_ASC,
				'pr.pr_kode'=>SORT_ASC,
							
				'jdwl_hari'=>SORT_ASC,
				'jdwl_masuk'=>SORT_ASC,
			]);	
		}
		if($kon!=''){$query->andWhere($kon);}
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function piket($params,$kon='',$order=''){
        $query = JadwalSearch::find()
		->select([
				'x'=>"iif( DATEADD(WEEK,4,kl.kln_masuk) > cast(getdate() as date) ,'1','0' )",//validasi berhubungan dangan tgl perkuliahan
				'tbl_jadwal.*',
				'lolos'=>"iif(pr.pr_kode in(6,7) or jr.jr_jenjang='s2',1,0)",
				'TotSesi'=>"isnull(dbo.TotalPertemuanDosen(tbl_jadwal.jdwl_id,'t',default),0)",
				'uts'=>"dbo.TotalPertemuanDosen(tbl_jadwal.jdwl_id,'t',0)",
				'uas'=>"dbo.TotalPertemuanDosen(tbl_jadwal.jdwl_id,'a',0)",
				
				'bn.mtk_kode',
				'bn.id',
				'mk.mtk_semester','mtk_sks','mk.penanggungjawab',"concat('[',bn.mtk_kode,'] ',mtk_nama) mtk_nama",'mtk_nama matkul',
				'jr.jr_id','jr_jenjang','jr_nama',
				'pr.pr_kode','pr_nama','ds_nm', 
				
				"(
					select count(*) from tbl_krs 
					where jdwl_id=tbl_jadwal.jdwl_id
					and (RStat is null or RStat='0')
					
				) jum ",
				'jumabs'=>'(
					select count(distinct k.krs_id) from tbl_krs k,
					tbl_absensi a
					where 
					k.jdwl_id=tbl_jadwal.jdwl_id
					and a.krs_id=k.krs_id 
				)',
				'kl.kr_kode',
				"concat(jdwl_masuk,'-',jdwl_keluar) jadwal",
				"iif(
					jdwl_uts is not null and jdwl_uas is not null,
					  'TA',
					  iif(jdwl_uts is not null,
					    'T',
						 iif(jdwl_uas is not null,'A','')
					  )
				) as ujian",
			])
        ->innerJoin('tbl_bobot_nilai bn','bn.id = tbl_jadwal.bn_id')
        ->innerJoin('tbl_dosen ds','bn.ds_nidn = ds.ds_id')
        ->innerJoin('tbl_kalender kl','kl.kln_id = bn.kln_id')
        ->innerJoin('tbl_program pr','pr.pr_kode = kl.pr_kode')
        ->innerJoin('tbl_jurusan jr','jr.jr_id= kl.jr_id')    
        ->innerJoin('tbl_matkul mk','mk.mtk_kode = bn.mtk_kode')                                 		
		->where(" (
				(tbl_jadwal.RStat='0' or tbl_jadwal.RStat is null)
			and (bn.RStat='0' or bn.RStat is null)
			and (ds.RStat='0' or ds.RStat is null)
			and (kl.RStat='0' or kl.RStat is null)
		) ")
		;
		
		
		if($order!=''){
			$query->orderBy($order);	
		}else{
			$query->orderBy([ 
				//"iif(jdwl_hari='".date('w')."',0,1)"=>SORT_ASC,
				'jr.jr_jenjang'=>SORT_DESC,
				'jr.jr_nama'=>SORT_ASC,
				'pr.pr_kode'=>SORT_ASC,
							
				'jdwl_hari'=>SORT_ASC,
				'jdwl_masuk'=>SORT_ASC,
			]);	
		}
		/*
		if(isset($_SESSION['Ckln'])){
			$krkode= $_SESSION['Ckln'];
			$cek = Kurikulum::findOne($krkode);
			if($cek){
				$query->andWhere(" bn_id in( select id from tbl_bobot_nilai where kln_id in(select kln_id from tbl_kalender where kr_kode='$krkode') )");
			}
		}
		*/	
		if($kon!=''){$query->andWhere($kon);}
		//$query->andWhere(['jdwl_hari'=>date('w')]);
        $dataProvider = new ActiveDataProvider(['query' => $query,]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'jdwl_id' => $this->jdwl_id,
            'bn_id' => $this->bn_id,
            'jdwl_uts' => $this->jdwl_uts,
            'jdwl_uas' => $this->jdwl_uas,
            'jdwl_uts_out' => $this->jdwl_uts_out,
            'jdwl_uas_out' => $this->jdwl_uas_out,

			"dbo.TotalPertemuanDosen(tbl_jadwal.jdwl_id,'t',0)"=>$this->uts,
			"dbo.TotalPertemuanDosen(tbl_jadwal.jdwl_id,'a',0)"=>$this->uas,

			'jum'=>$this->jum,
			'jr.jr_id'=>$this->jr_id,
			'pr.pr_kode'=>$this->pr_kode,
			'kl.kr_kode'=>$this->kr_kode,
        ]);

        $query->andFilterWhere(['like', 'rg_kode', $this->rg_kode])

            ->andFilterWhere(['like', "concat('[',bn.mtk_kode,'] ',mtk_nama)", $this->mtk_nama])
            ->andFilterWhere(['like', 'jr_nama', $this->jr_nama])
            ->andFilterWhere(['like', 'jadwal', $this->jadwal])
            ->andFilterWhere(['like', 'ds_nm', $this->ds_nm])
            ->andFilterWhere(['like', 'pr_nama', $this->pr_nama])
            ->andFilterWhere(['=', "
			iif(
					jdwl_uts is not null and jdwl_uas is not null,
					  'TA',
					  iif(jdwl_uts is not null,
					    'T',
						 iif(jdwl_uas is not null,'A','')
					  )
				)
			
			", $this->ujian])


            ->andFilterWhere(['like', 'semester', $this->semester])
            ->andFilterWhere(['like', 'jdwl_hari', $this->jdwl_hari])
            ->andFilterWhere(['like', 'jdwl_masuk', $this->jdwl_masuk])
            ->andFilterWhere(['like', 'jdwl_keluar', $this->jdwl_keluar])
            ->andFilterWhere(['=', 'jdwl_kls', $this->jdwl_kls])
            ->andFilterWhere(['like', 'rg_uts', $this->rg_uts])
            ->andFilterWhere(['like', 'rg_uas', $this->rg_uas]);

        return $dataProvider;
    }

    public function pergantian($params,$tgl,$kon='',$order=''){
		
        $query = JadwalSearch::find()
		->select([
				'tbl_jadwal.jdwl_masuk',
				'tbl_jadwal.jdwl_keluar',
				'tbl_jadwal.jdwl_hari',
				'bn.mtk_kode',
				'mk.mtk_semester',
				'mtk_sks',
				"concat('[',bn.mtk_kode,'] ',mk.mtk_nama,' (',jdwl_kls,')') mtk_nama",
				'mk.mtk_nama matkul',
				'sesi'=>'tf.sesi',
				'jr.jr_id','jr_jenjang','jr_nama',
				'pr.pr_kode','pr_nama','ds_nm', 

				'jAwal'=>"concat(dbo.cekHari(tbl_jadwal.jdwl_hari),', ',tbl_jadwal.jdwl_masuk,'-',tbl_jadwal.jdwl_keluar)",
				//'cast(tf.jdwl_masuk as varchar(5))',
				'pKeluar'=>'cast(tf.jdwl_keluar as varchar(5))',
				
				'pMasuk'=>'cast(tf.jdwl_masuk as varchar(5))',
				'pKeluar'=>'cast(tf.jdwl_keluar as varchar(5))',
				
				"(
					select count(*) from tbl_krs 
					where jdwl_id=tbl_jadwal.jdwl_id
					and (RStat is null or RStat='0')
					
				) jum ",
				'jumabs'=>'(
					select count(distinct k.krs_id) from tbl_krs k,
					tbl_absensi a
					where 
					k.jdwl_id=tbl_jadwal.jdwl_id
					and a.krs_id=k.krs_id 
				)',
				'kl.kr_kode',
				"concat(cast(tbl_jadwal.jdwl_masuk as varchar(5)),'-',cast(tbl_jadwal.jdwl_keluar as varchar(5))) jadwal",
				"iif(
					jdwl_uts is not null and jdwl_uas is not null,
					  'TA',
					  iif(jdwl_uts is not null,
					    'T',
						 iif(jdwl_uas is not null,'A','')
					  )
				) as ujian",
			])
		->distinct(true)
        ->innerJoin('tbl_bobot_nilai bn',"bn.id = tbl_jadwal.bn_id and isnull(bn.RStat,0)=0")
        ->innerJoin('tbl_kalender kl',"kl.kln_id = bn.kln_id  and  isnull(kl.RStat,0)=0 ")
        ->innerJoin('tbl_program pr',"pr.pr_kode = kl.pr_kode  and (pr.RStat='0' or pr.RStat is null)")
        ->innerJoin('tbl_jurusan jr','jr.jr_id= kl.jr_id ')    
        ->innerJoin('tbl_matkul mk',"mk.mtk_kode = bn.mtk_kode  and  isnull(mk.RStat,0)=0 ")
        ->innerJoin('t_finger_pengganti tf',"tf.jdwl_id= tbl_jadwal.jdwl_id and tf.tgl_ins='$tgl'")
        ->innerJoin('user_ u',"u.fid=tf.ds_fid and u.tipe=3")
        ->innerJoin('tbl_dosen ds',"ds.ds_user=u.username")
        ->join('left join','transaksi_finger tf1',"tf1.jdwl_id= tf.jdwl_id and tf1.tgl_ins=tf.tgl_ins")
		//->innerJoin('tbl_krs krs',"krs.jdwl_id= tbl_jadwal.jdwl_id  and  isnull(tbl_jadwal.RStat,0)=0 ")		
		->where(" (tbl_jadwal.RStat='0' or tbl_jadwal.RStat is null)")
		//->groupBy()
		;
		
		
		
		if($order!=''){
			$query->orderBy($order);	
		}else{
			$query->orderBy([ 
				'jr.jr_jenjang'=>SORT_DESC,
				'jr.jr_nama'=>SORT_ASC,
				'pr.pr_kode'=>SORT_ASC,
				'jdwl_hari'=>SORT_ASC,
				'jdwl_masuk'=>SORT_ASC,
			]);	
		}

	
		/*
		if(isset($_SESSION['Ckln'])){
			$krkode= $_SESSION['Ckln'];
			$cek = Kurikulum::findOne($krkode);
			if($cek){
				$query->andWhere(" bn_id in( select id from tbl_bobot_nilai where kln_id in(select kln_id from tbl_kalender where kr_kode='$krkode') )");
			}
		}
		*/	
		if($kon!=''){$query->andWhere($kon);}
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

         $dataProvider->setSort([
        'attributes' => [
            'mtk_nama' => [
                'asc' => ['mk.mtk_nama' => SORT_ASC],
                'desc' => ['mk.mtk_nama' => SORT_DESC],
                'label' => 'Matakuliah',
                'default' => SORT_ASC
            ],
            'jr_id' => [
                'asc' => ['jr.jr_id' => SORT_ASC],
                'desc' => ['jr.jr_id' => SORT_DESC],
                'label' => 'Jurusan',
                'default' => SORT_ASC
            ],
            'pr_kode' => [
                'asc' => ['pr.pr_kode' => SORT_ASC],
                'desc' => ['pr.pr_kode' => SORT_DESC],
                'label' => 'Program',
                'default' => SORT_ASC
            ],
            'jdwl_hari' => [
                'asc' => ['jdwl_hari' => SORT_ASC],
                'desc' => ['jdwl_hari' => SORT_DESC],
                'label' => 'Hari',
                'default' => SORT_ASC
            ],
            'jdwl_masuk' => [
                'asc' => ['jdwl_masuk' => SORT_ASC],
                'desc' => ['jdwl_masuk' => SORT_DESC],
                'label' => 'Jadwal Masuk',
                'default' => SORT_ASC
            ],
            'jdwl_kls' => [
                'asc' => ['jdwl_kls' => SORT_ASC],
                'desc' => ['jdwl_kls' => SORT_DESC],
                'label' => 'Kelas',
                'default' => SORT_ASC
            ],
            'ds_nm' => [
                'asc' => ['ds.ds_nm' => SORT_ASC],
                'desc' => ['ds.ds_nm' => SORT_DESC],
                'label' => 'Dosen',
                'default' => SORT_ASC
            ],
            'rg_kode' => [
                'asc' => ['rg_kode' => SORT_ASC],
                'desc' => ['rg_kode' => SORT_DESC],
                'label' => 'Ruangan',
                'default' => SORT_ASC
            ],
        ]
        ]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'jdwl_id' => $this->jdwl_id,
            'bn_id' => $this->bn_id,
            'jdwl_uts' => $this->jdwl_uts,
            'jdwl_uas' => $this->jdwl_uas,
            'jdwl_uts_out' => $this->jdwl_uts_out,
            'jdwl_uas_out' => $this->jdwl_uas_out,

			'jum'=>$this->jum,
			'jr.jr_id'=>$this->jr_id,
			'pr.pr_kode'=>$this->pr_kode,
			'kl.kr_kode'=>$this->kr_kode,
        ]);

        $query->andFilterWhere(['like', 'rg_kode', $this->rg_kode])

            ->andFilterWhere(['like', "concat('[',bn.mtk_kode,'] ',mtk_nama)", $this->mtk_nama])
            ->andFilterWhere(['like', 'jr_nama', $this->jr_nama])
            ->andFilterWhere(['like', 'jadwal', $this->jadwal])
            ->andFilterWhere(['like', 'ds_nm', $this->ds_nm])
            ->andFilterWhere(['like', 'pr_nama', $this->pr_nama])
            ->andFilterWhere(['=', "
			iif(
					jdwl_uts is not null and jdwl_uas is not null,
					  'TA',
					  iif(jdwl_uts is not null,
					    'T',
						 iif(jdwl_uas is not null,'A','')
					  )
				)
			
			", $this->ujian])
            ->andFilterWhere(['like', 'semester', $this->semester])
            ->andFilterWhere(['like', 'jdwl_hari', $this->jdwl_hari])
            ->andFilterWhere(['like', 'jdwl_masuk', $this->jdwl_masuk])
            ->andFilterWhere(['like', 'jdwl_keluar', $this->jdwl_keluar])
            ->andFilterWhere(['=', 'jdwl_kls', $this->jdwl_kls])
            ->andFilterWhere(['like', 'rg_uts', $this->rg_uts])
            ->andFilterWhere(['like', 'rg_uas', $this->rg_uas]);

        return $dataProvider;
    }

    public function perwalian($params,$kon='',$order=''){


        $query = Jadwal::find()
            ->select([
                'tbl_jadwal.*',
                'bn.mtk_kode',
                'bn.id',
                'peserta'=>'krs.tot',
                'app'=>'krs.app',
                'jum'=>'krs.unapp',
                'mk.mtk_semester','mtk_sks','mk.penanggungjawab',"mtk_nama",
                'jr.jr_id','jr_jenjang','jr_nama',
                'pr.pr_kode','pr_nama','ds_nm',
                'kl.kr_kode',
                "concat(jdwl_masuk,'-',jdwl_keluar) jadwal",
            ])
            ->innerJoin('tbl_bobot_nilai bn',"bn.id = tbl_jadwal.bn_id and isnull(bn.RStat,0)=0")
            ->innerJoin('tbl_dosen ds',"bn.ds_nidn = ds.ds_id  and  isnull(ds.RStat,0)=0")
            ->innerJoin('tbl_kalender kl',"
                kl.kln_id = bn.kln_id  
                and isnull(kl.RStat,0)=0
                -- and kl.kr_kode='11819'
                and (CAST(GETDATE() as date) BETWEEN kl.kln_krs and kl.kln_masuk)
            ")
            ->innerJoin('tbl_program pr',"pr.pr_kode = kl.pr_kode  and (pr.RStat='0' or pr.RStat is null)")
            ->innerJoin('tbl_jurusan jr','jr.jr_id= kl.jr_id ')
            ->innerJoin('tbl_matkul mk',"mk.mtk_kode = bn.mtk_kode  and  isnull(mk.RStat,0)=0 ")
            ->leftJoin('
            ( SELECT kr_kode,jdwl_id ,sum(iif(isnull(krs_stat,0)=2,0,1)) tot,sum(iif(krs_stat=1,1,0)) app,sum(iif(krs_stat=0,1,0)) unapp FROM t_krs_det where isnull(RStat,0)=0 GROUP BY jdwl_id,kr_kode ) krs
            ',"tbl_jadwal.jdwl_id=krs.jdwl_id")
            //->innerJoin('tbl_krs krs',"krs.jdwl_id= tbl_jadwal.jdwl_id  and  isnull(tbl_jadwal.RStat,0)=0 ")
            ->where(" isnull(tbl_jadwal.RStat,0)=0");


        /*$query = Kalender::find()
            ->innerJoin('tbl_bobot_nilai bn',"bn.kln_id=tbl_kalender.kln_id and isnull(bn.RStat,0)=0")
            ->innerJoin('tbl_dosen ds',"bn.ds_nidn = ds.ds_id  and  isnull(ds.RStat,0)=0")
            ->innerJoin('tbl_program pr',"pr.pr_kode = tbl_kalender.pr_kode  and (pr.RStat='0' or pr.RStat is null)")
            ->innerJoin('tbl_jurusan jr','jr.jr_id= tbl_kalender.jr_id ')
            ->innerJoin('tbl_matkul mk',"mk.mtk_kode = bn.mtk_kode  and  isnull(mk.RStat,0)=0 ")
            ->innerJoin('tbl_Jadwal jd',"jd.bn_id = bn.id and  isnull(jd.RStat,0)=0")*/

        ;
        if($order!=''){
            $query->orderBy($order);
        }else{
            $query->orderBy([
                'jr.jr_jenjang'=>SORT_DESC,
                'jr.jr_nama'=>SORT_ASC,
                'pr.pr_kode'=>SORT_ASC,
                'jdwl_hari'=>SORT_ASC,
                'jdwl_masuk'=>SORT_ASC,
            ]);
        }


        /*
        if(isset($_SESSION['Ckln'])){
            $krkode= $_SESSION['Ckln'];
            $cek = Kurikulum::findOne($krkode);
            if($cek){
                $query->andWhere(" bn_id in( select id from tbl_bobot_nilai where kln_id in(select kln_id from tbl_kalender where kr_kode='$krkode') )");
            }
        }
        */
        if($kon!=''){$query->andWhere($kon);}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'jdwl_id' => $this->jdwl_id,
            'bn_id' => $this->bn_id,
            'jdwl_uts' => $this->jdwl_uts,
            'jdwl_uas' => $this->jdwl_uas,
            'jdwl_uts_out' => $this->jdwl_uts_out,
            'jdwl_uas_out' => $this->jdwl_uas_out,
            'jum'=>$this->jum,
            'jr.jr_id'=>$this->jr_id,
            'pr.pr_kode'=>$this->pr_kode,
            'kl.kr_kode'=>$this->kr_kode,
        ]);

        $query->andFilterWhere(['like', 'rg_kode', $this->rg_kode])

            ->andFilterWhere(['like', "concat('[',bn.mtk_kode,'] ',mtk_nama)", $this->mtk_nama])
            ->andFilterWhere(['like', 'jr_nama', $this->jr_nama])
            ->andFilterWhere(['like', 'jadwal', $this->jadwal])
            ->andFilterWhere(['like', 'ds_nm', $this->ds_nm])
            ->andFilterWhere(['like', 'pr_nama', $this->pr_nama])

            ->andFilterWhere(['like', 'semester', $this->semester])
            ->andFilterWhere(['like', 'jdwl_hari', $this->jdwl_hari])
            ->andFilterWhere(['like', 'jdwl_masuk', $this->jdwl_masuk])
            ->andFilterWhere(['like', 'jdwl_keluar', $this->jdwl_keluar])
            ->andFilterWhere(['=', 'jdwl_kls', $this->jdwl_kls]);

        return $dataProvider;
    }


}