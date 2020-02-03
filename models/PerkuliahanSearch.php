<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Jadwal;

/**
 * JadwalSearch represents the model behind the search form about `app\models\Jadwal`.
 */
class PerkuliahanSearch extends Jadwal{
	public $peserta,$pelaksana,$tgl,$hadir,$dMasuk,$dKeluar;
	public $xx;

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
            'kr_kode'=>'Kurikulum'

        ];
    }

    public function rules(){
        return [
            [['jdwl_id','sesi', 'bn_id'], 'integer'],
            [
				[	'rg_kode', 'semester', 'jdwl_hari', 'jdwl_masuk', 'jdwl_keluar', 
					'jdwl_kls', 'jdwl_uts', 'jdwl_uas', 'jdwl_uts_out', 'jdwl_uas_out', 'rg_uts', 'rg_uas',
					
					'mtk_kode','mtk_nama','mtk_semester',
					'jr_id','jr_nama','jr_jenjang', 
					'pr_kode','pr_nama',
					'ds_nm','jum','jumabs',
					'kr_kode','jadwal','ujian',
					'sesi','pMasuk','pKeluar','jAwal'
				],
			 	'safe'
			],
        ];
    }

    public function scenarios(){
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function pergantian($params,$tgl,$kon='',$order=''){
		
        $query = PerkuliahanSearch::find()
		->select([
				'tbl_jadwal.jdwl_id',
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
				'pMasuk'=>'cast(tf.jdwl_masuk as varchar(5))',
				'pKeluar'=>'cast(tf.jdwl_keluar as varchar(5))',
				'peserta'=>'count(tf.jdwl_id)',
				'xx'=>"max(tf1.jdwl_id)",
				"concat(cast(tbl_jadwal.jdwl_masuk as varchar(5)),'-',cast(tbl_jadwal.jdwl_keluar as varchar(5))) jadwal",
			])
        ->innerJoin('tbl_bobot_nilai bn',"bn.id = tbl_jadwal.bn_id and isnull(bn.RStat,0)=0")
        ->innerJoin('tbl_kalender kl',"kl.kln_id = bn.kln_id  and  isnull(kl.RStat,0)=0 ")
        ->innerJoin('tbl_program pr',"pr.pr_kode = kl.pr_kode  and (pr.RStat='0' or pr.RStat is null)")
        ->innerJoin('tbl_jurusan jr','jr.jr_id= kl.jr_id ')    
        ->innerJoin('tbl_matkul mk',"mk.mtk_kode = bn.mtk_kode  and  isnull(mk.RStat,0)=0 ")
        ->innerJoin('t_finger_pengganti tf',"tf.jdwl_id= tbl_jadwal.jdwl_id and tf.tgl_ins='$tgl'")
        ->innerJoin('user_ u',"u.fid=tf.ds_fid and u.tipe=3")
        ->innerJoin('tbl_dosen ds',"ds.ds_user=u.username")
        ->join('left join','transaksi_finger tf1',"tf1.krs_id= tf.krs_id and tf1.sesi=tf.sesi and tf1.tgl_ins=tf.tgl_ins")
		//->innerJoin('tbl_krs krs',"krs.jdwl_id= tbl_jadwal.jdwl_id  and  isnull(tbl_jadwal.RStat,0)=0 ")		
		->where(" (tbl_jadwal.RStat='0' or tbl_jadwal.RStat is null)")
		->groupBy([
			'tbl_jadwal.jdwl_id',
			'tbl_jadwal.jdwl_hari',
			'tbl_jadwal.jdwl_masuk',
			'tbl_jadwal.jdwl_keluar',
			'bn.mtk_kode',
			'mk.mtk_semester',
			'mtk_sks',
			"concat('[',bn.mtk_kode,'] ',mk.mtk_nama,' (',jdwl_kls,')')",
			'mk.mtk_nama',
			'tf.sesi',
			'jr.jr_id','jr_jenjang','jr_nama',
			'pr.pr_kode','pr_nama','ds_nm', 
			"concat(dbo.cekHari(tbl_jadwal.jdwl_hari),', ',tbl_jadwal.jdwl_masuk,'-',tbl_jadwal.jdwl_keluar)",
			//'cast(tf.jdwl_masuk as varchar(5))',
			'cast(tf.jdwl_keluar as varchar(5))',
			'cast(tf.jdwl_masuk as varchar(5))',
			"concat(cast(tbl_jadwal.jdwl_masuk as varchar(5)),'-',cast(tbl_jadwal.jdwl_keluar as varchar(5)))",
			
		])
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
            ->andFilterWhere(['like', "concat(ds.ds_nm,' [',bn.mtk_kode,'] ',mk.mtk_nama,' (',jdwl_kls,')')", $this->mtk_nama])
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

    public function master($params,$kr,$kon='',$order=''){
		
        $query = Jadwal::find()
		->select([
				'tf.jdwl_id',
				'tbl_jadwal.jdwl_masuk',
				'tbl_jadwal.jdwl_keluar',
				'tbl_jadwal.jdwl_hari',
				'bn.mtk_kode',
				'mk.mtk_semester',
				'mtk_sks',
				"concat('[',bn.mtk_kode,'] ',mk.mtk_nama,' (',jdwl_kls,')') mtk_nama",
				'mk.mtk_nama matkul',				
				'sesi'=>'tf.sesi',
				'pr.pr_kode','pr_nama','ds.ds_nm', 
				'pelaksana'=>'tf.ds_nm',
				'tgl'=>'tf.tgl',
				'dMasuk'=>'cast(tf.ds_masuk as varchar(5))',
				'dKeluar'=>'cast(tf.ds_keluar as varchar(5))',

				'jAwal'=>"concat(dbo.cekHari(tbl_jadwal.jdwl_hari),', ',tbl_jadwal.jdwl_masuk,'-',tbl_jadwal.jdwl_keluar)",
				//'cast(tf.jdwl_masuk as varchar(5))',
				'pMasuk'=>'cast(tf.jdwl_masuk as varchar(5))',
				'pKeluar'=>'cast(tf.jdwl_keluar as varchar(5))',
				'peserta'=>'tf.tot',
				'hadir'=>'tf.hdr',
				'xx'=>"iif(tf.ds_stat=1,1,0)",
				"concat(cast(tbl_jadwal.jdwl_masuk as varchar(5)),'-',cast(tbl_jadwal.jdwl_keluar as varchar(5))) jadwal",
			])
		//->distinct(true)
        ->innerJoin('tbl_bobot_nilai bn',"bn.id = tbl_jadwal.bn_id and isnull(bn.RStat,0)=0")
        ->innerJoin('tbl_dosen ds',"ds.ds_id=bn.ds_nidn and isnull(ds.RStat,0)=0")
        ->innerJoin('tbl_kalender kl',"kl.kln_id = bn.kln_id  and  isnull(kl.RStat,0)=0 ")
        ->innerJoin('tbl_program pr',"pr.pr_kode = kl.pr_kode  and isnull(pr.RStat,0)=0")
        ->innerJoin('tbl_jurusan jr','jr.jr_id= kl.jr_id ')    
        ->innerJoin('tbl_matkul mk',"mk.mtk_kode = bn.mtk_kode  and isnull(mk.RStat,0)=0 ")
        ->innerJoin("dbo.m_kahadiran_semester('$kr') tf","tf.jdwl_id=tbl_jadwal.jdwl_id")
		//->innerJoin('tbl_krs krs',"krs.jdwl_id= tbl_jadwal.jdwl_id  and  isnull(tbl_jadwal.RStat,0)=0 ")		
		->where(" isnull(tbl_jadwal.RStat,0)=0");
		
		
		
		if($order!=''){
			$query->orderBy($order);	
		}else{
			$query->orderBy([ 
				'tf.jdwl_id'=>SORT_DESC,
				'tbl_jadwal.jdwl_hari'=>SORT_ASC,
				'tbl_jadwal.jdwl_masuk'=>SORT_ASC,
			]);	
		}

		if($kon!=''){$query->andWhere($kon);}
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'tf.jdwl_id' => $this->jdwl_id,
            'bn_id' => $this->bn_id,
            'jdwl_uts' => $this->jdwl_uts,
            'jdwl_uas' => $this->jdwl_uas,
            'jdwl_uts_out' => $this->jdwl_uts_out,
            'jdwl_uas_out' => $this->jdwl_uas_out,
    		'tf.sesi' => $this->sesi,
			'jr.jr_id'=>$this->jr_id,
			'pr.pr_kode'=>$this->pr_kode,
			'kl.kr_kode'=>$this->kr_kode,
        ]);

        $query
            ->andFilterWhere(['like', "concat(ds.ds_nm,' [',bn.mtk_kode,'] ',mk.mtk_nama,' (',jdwl_kls,')')", $this->mtk_nama])
            ->andFilterWhere(['like', 'jadwal', $this->jadwal])
            ->andFilterWhere(['like', 'pr_nama', $this->pr_nama])
            ->andFilterWhere(['like', 'jdwl_hari', $this->jdwl_hari])
            ->andFilterWhere(['like', 'jdwl_masuk', $this->jdwl_masuk])
            ->andFilterWhere(['like', 'jdwl_keluar', $this->jdwl_keluar]);
        return $dataProvider;
    }



}
