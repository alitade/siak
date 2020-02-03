<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Rekap;

/**
 * TransaksiFingerSearch represents the model behind the search form about `app\models\TransaksiFinger`.
 */
class RekapSearch extends Rekap
{
	public $kr_kode,$pr_kode,$jr_id,$jadwal,$pr_nama,$ds_nm;
	public $peserta,$pelaksana,$tgl,$hadir,$dMasuk,$dKeluar;
	public $xx,$sesi;
    public $pMasuk,$pKeluar,$jAwal;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','sesi', 'krs_id', 'ds_fid', 'ds_fid1', 'jdwl_id', 'jdwl_hari', 'ds_get_fid'], 'integer'],
            [[
				'krs_stat', 'mtk_kode', 'mtk_nama', 'jdwl_masuk', 'jdwl_keluar', 
				'tgl', 'mhs_fid', 'mhs_masuk', 'mhs_keluar', 'mhs_stat', 
				'ds_masuk', 'ds_keluar', 'ds_stat', 'tgl_ins',
				'dosen','status',
				'masuk','keluar','hadir','krkd',
				'pMasuk','pKeluar','jAwal'
				]
			, 'safe'],
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
    public function search($params){
        $query = Rekap::find()
		->where("isnull(RStat,'0')='0'");
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'krs_id' => $this->krs_id,
            'ds_fid' => $this->ds_fid,
            'ds_fid1' => $this->ds_fid1,
            'jdwl_id' => $this->jdwl_id,
            'jdwl_hari' => $this->jdwl_hari,
            'jdwl_masuk' => $this->jdwl_masuk,
            'jdwl_keluar' => $this->jdwl_keluar,
            'tgl' => $this->tgl,
            'mhs_masuk' => $this->mhs_masuk,
            'mhs_keluar' => $this->mhs_keluar,
            'ds_masuk' => $this->ds_masuk,
            'ds_keluar' => $this->ds_keluar,
            'ds_get_fid' => $this->ds_get_fid,
            'tgl_ins' => $this->tgl_ins,
        ]);

        $query->andFilterWhere(['like', 'krs_stat', $this->krs_stat])
            ->andFilterWhere(['like', 'mtk_kode', $this->mtk_kode])
            ->andFilterWhere(['like', 'mtk_nama', $this->mtk_nama])
            ->andFilterWhere(['like', 'mhs_fid', $this->mhs_fid])
            ->andFilterWhere(['like', 'mhs_stat', $this->mhs_stat])
            ->andFilterWhere(['like', 'ds_stat', $this->ds_stat]);

        return $dataProvider;
    }


    public function perkuliahan($params){
        $query = Rekap::find()
		->select([
			'tgl_ins','jdwl_hari','sesi',
			'jdwl_id'		=>'m_transaksi_finger.jdwl_id',
			'mtk_kode'		=>'m_transaksi_finger.mtk_kode',
			'mtk_nama'		=>"concat(m_transaksi_finger.mtk_kode,': ',m_transaksi_finger.mtk_nama)",
			'dosen'			=>'ds.ds_nm',
			//'pengajar'		=>'ds1.ds_nm',	
			'jdwl_masuk'	=>"concat(LEFT(m_transaksi_finger.jdwl_masuk,5),' - ',LEFT(m_transaksi_finger.jdwl_keluar,5) )",
/*
			status'		=>'iif(max(ds_stat) is null,0,1)',//'iif(ds_get_fid is null,0,1)',
			jdwl_keluar'	=>'LEFT(m_transaksi_finger.jdwl_keluar,5)',
			mhs'		=>"count(m_transaksi_finger.krs_id)",
			absen'		=>"sum(iif(m_transaksi_finger.mhs_stat='1',1,0))"
*/
			'status'		=> 'max(ds_stat)',//'iif(ds_get_fid is null,0,1)',
			'hadir'			=> 'iif(isnull(max(ds_stat),0)=1 ,1,0)',
			'jdwl_keluar'	=> 'LEFT(m_transaksi_finger.jdwl_keluar,5)',
			'mhs'			=> "count(m_transaksi_finger.krs_id)",
			'absen'			=> "sum(iif(m_transaksi_finger.mhs_stat='1',1,0))",
			'masuk'			=> 'left(max(ds_masuk),5)',
			'keluar'		=> 'left(max(ds_keluar),5)',

			'M'				=> 'sum(iif(mhs_masuk is null,0,1))',
			'K'				=> 'sum(iif(mhs_keluar is null,0,1))',
			'krkd' 			=> 'max(kr_kode_)',


		])
		->distinct(true)
		->innerJoin('user_ u',"(u.Fid=m_transaksi_finger.ds_fid and u.Fid is not null and u.tipe='3')")
		->innerJoin('tbl_dosen ds','(ds.ds_user=u.username)')
		->innerJoin('tbl_krs krs','(krs.krs_id=m_transaksi_finger.krs_id and isnull(krs.RStat,0)=0)')
		->groupBy("
		 	m_transaksi_finger.jdwl_masuk
			,m_transaksi_finger.jdwl_keluar
			,m_transaksi_finger.sesi
			,m_transaksi_finger.mtk_kode
			,m_transaksi_finger.mtk_nama
			,m_transaksi_finger.ds_get_fid
			
			,m_transaksi_finger.tgl_ins
			,m_transaksi_finger.jdwl_hari
			,ds.ds_nm
			,m_transaksi_finger.jdwl_id
			")
		->orderBy([
			"concat(LEFT(m_transaksi_finger.jdwl_masuk,5),' - ',LEFT(m_transaksi_finger.jdwl_keluar,5) )"=>SORT_ASC,
			'LEFT(m_transaksi_finger.jdwl_keluar,5)'=>SORT_ASC,
			'ds.ds_nm'=>SORT_ASC
		])
		->where("isnull(m_transaksi_finger.RStat,0)=0")
		;
		
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSize' => 20,
			],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'krs_id' => $this->krs_id,
            'ds_fid' => $this->ds_fid,
            'ds_fid1' => $this->ds_fid1,
            'jdwl_hari' => $this->jdwl_hari,
            'sesi' => $this->sesi,
			'iif(ds_get_fid is null,0,1)'=>$this->status,
            'tgl' => $this->tgl,
            
            'mhs_masuk' => $this->mhs_masuk,
            'mhs_keluar' => $this->mhs_keluar,
            'ds_masuk' => $this->ds_masuk,
            'ds_keluar' => $this->ds_keluar,
            'ds_get_fid' => $this->ds_get_fid,
            'tgl_ins' => $this->tgl_ins,
        ]);

        $query->andFilterWhere(['like', 'krs_stat', $this->krs_stat])
            ->andFilterWhere(['like', 'mtk_kode', $this->mtk_kode])
            ->andFilterWhere(['like',"concat(LEFT(m_transaksi_finger.jdwl_masuk,5),' - ',LEFT(m_transaksi_finger.jdwl_keluar,5) )", $this->jdwl_masuk])
            ->andFilterWhere(['or like',"concat(m_transaksi_finger.mtk_kode,': ',m_transaksi_finger.mtk_nama,' ',ds.ds_nm) ",
				$this->mtk_nama
			])
            ->andFilterWhere(['like', 'mhs_fid', $this->mhs_fid])
            ->andFilterWhere(['like', 'mhs_stat', $this->mhs_stat])
            ->andFilterWhere(['like', 'ds_stat', $this->ds_stat])
			->andHaving(['max(kr_kode_)' => $this->krkd,]);
	        return $dataProvider;
    }

    public function master($params,$kr,$kon='',$order=''){
        $query = RekapSearch::find()
		->select([
				'tf.jdwl_id',
				'jd.jdwl_masuk',
				'jd.jdwl_keluar',
				'jd.jdwl_hari',
				'bn.mtk_kode',
				'mk.mtk_semester',
				'mtk_sks',
				"concat('[',bn.mtk_kode,'] ',mk.mtk_nama,' (',jdwl_kls,')') mtk_nama",
				'mk.mtk_nama matkul',				
				'm_transaksi_finger.sesi',
				'pr.pr_kode','pr_nama','ds.ds_nm', 
				'pelaksana'=>'tf.ds_nm',
				'tgl'=>'tf.tgl',
				'dMasuk'=>'cast(tf.ds_masuk as varchar(5))',
				'dKeluar'=>'cast(tf.ds_keluar as varchar(5))',
				'jAwal'=>"concat(dbo.cekHari(jd.jdwl_hari),', ',jd.jdwl_masuk,'-',jd.jdwl_keluar)",
				//'cast(tf.jdwl_masuk as varchar(5))',
				'pMasuk'=>'cast(tf.jdwl_masuk as varchar(5))',
				'pKeluar'=>'cast(tf.jdwl_keluar as varchar(5))',
				'peserta'=>'tf.tot',
				'hadir'=>'tf.hdr',
				'xx'=>"iif(tf.ds_stat=1,1,0)",
				"concat(cast(jd.jdwl_masuk as varchar(5)),'-',cast(jd.jdwl_keluar as varchar(5))) jadwal",
			])
		->distinct(true)
        ->innerJoin('tbl_jadwal jd',"jd.jdwl_id = m_transaksi_finger.jdwl_id and isnull(jd.RStat,0)=0")
        ->innerJoin('tbl_bobot_nilai bn',"bn.id = jd.bn_id and isnull(bn.RStat,0)=0")
        ->innerJoin('tbl_dosen ds',"ds.ds_id=bn.ds_nidn and isnull(ds.RStat,0)=0")
        ->innerJoin('tbl_kalender kl',"kl.kln_id = bn.kln_id  and  isnull(kl.RStat,0)=0 ")
        ->innerJoin('tbl_program pr',"pr.pr_kode = kl.pr_kode  and isnull(pr.RStat,0)=0")
        ->innerJoin('tbl_jurusan jr','jr.jr_id= kl.jr_id ')    
        ->innerJoin('tbl_matkul mk',"mk.mtk_kode = bn.mtk_kode  and isnull(mk.RStat,0)=0 ")
        ->innerJoin("dbo.m_kahadiran_semester('$kr') tf","tf.jdwl_id=m_transaksi_finger.jdwl_id and tf.sesi=m_transaksi_finger.sesi")
		//->innerJoin('tbl_krs krs',"krs.jdwl_id= tbl_jadwal.jdwl_id  and  isnull(tbl_jadwal.RStat,0)=0 ")		
		//->where(" isnull(tbl_jadwal.RStat,0)=0");
		;
		
		
		if($order!=''){
			$query->orderBy($order);	
		}else{
			$query->orderBy([ 
				'tf.jdwl_id'=>SORT_DESC,
				'jd.jdwl_hari'=>SORT_ASC,
				'jd.jdwl_masuk'=>SORT_ASC,
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
