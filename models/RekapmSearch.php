<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Rekap;

/**
 * TransaksiFingerSearch represents the model behind the search form about `app\models\TransaksiFinger`.
 */
class RekapmSearch extends Rekap
{
	public $kr_kode,$pr_kode,$jr_id,$jadwal,$pr_nama,$ds_nm;
	public $peserta,$pelaksana,$tgl,$hadir,$dMasuk,$dKeluar;
	public $xx,$sesi_;
    public $pMasuk,$pKeluar,$jAwal;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','sesi',  'ds_fid', 'ds_fid1', 'jdwl_id', 'jdwl_hari', 'ds_get_fid'], 'integer'],
            [[
				'krs_stat', 'mtk_kode', 'mtk_nama', 'jdwl_masuk', 'jdwl_keluar', 
				'tgl', 'mhs_fid', 'mhs_masuk', 'mhs_keluar', 'mhs_stat', 
				'ds_masuk', 'ds_keluar', 'ds_stat', 'tgl_ins',
				'dosen','status',
				'masuk','keluar','hadir','krkd',
				'pMasuk','pKeluar','jAwal','sesi'
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




    public function search($params,$kr,$kon='',$order=''){
        $query = Rekap::find()
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
				'sesi_'=>'m_transaksi_finger.sesi',
				'pr.pr_kode','pr_nama','ds.ds_nm', 
				'pelaksana'=>'tf.ds_nm',
				'tgl_'=>'tf.tgl',
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
			])->distinct(true)
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
