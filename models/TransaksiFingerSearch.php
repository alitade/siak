<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TransaksiFinger;

/**
 * TransaksiFingerSearch represents the model behind the search form about `app\models\TransaksiFinger`.
 */
class TransaksiFingerSearch extends TransaksiFinger
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'krs_id', 'ds_fid', 'ds_fid1', 'jdwl_id', 'jdwl_hari', 'ds_get_fid'], 'integer'],
            [[
				'krs_stat', 'mtk_kode', 'mtk_nama', 'jdwl_masuk', 'jdwl_keluar', 'tgl',
				'mhs_fid', 'mhs_masuk', 'mhs_keluar', 'mhs_stat',
				'ds_masuk', 'ds_keluar', 'ds_stat', 'tgl_ins',
				'dosen','status',
				'masuk','keluar','hadir',
			
			], 'safe'],
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
        $query = TransaksiFinger::find()->where("isnull(RStat,0)=0");

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
        $query = TransaksiFinger::find()
		->select([
			'transaksi_finger.jdwl_hari',
			'jdwl_id'		=>'max(transaksi_finger.jdwl_id)',
			'mtk_kode'		=>'transaksi_finger.mtk_kode',
			'mtk_nama'		=>"concat(transaksi_finger.mtk_kode,': ',transaksi_finger.mtk_nama)",
			'dosen'			=>'ds.ds_nm',
			//'pengajar'		=>'ds1.ds_nm',	
			'jdwl_masuk'	=>"concat(LEFT(transaksi_finger.jdwl_masuk,5),' - ',LEFT(transaksi_finger.jdwl_keluar,5) )",
			
			'status'		=> 'max(transaksi_finger.ds_stat)',//'iif(ds_get_fid is null,0,1)',
			'hadir'			=> 'iif(isnull(max(transaksi_finger.ds_stat),0)=1 ,1,0)',
			'jdwl_keluar'	=> 'LEFT(transaksi_finger.jdwl_keluar,5)',
			'mhs'			=> "count(transaksi_finger.krs_id)",
			'absen'			=> "sum(iif(transaksi_finger.mhs_stat='1',1,0))",
			'masuk'			=> 'left(max(transaksi_finger.ds_masuk),5)',
			'keluar'		=> 'left(max(transaksi_finger.ds_keluar),5)',
			'maxAbsen'		=>"isnull(datediff(minute,max(transaksi_finger.jdwl_masuk),max(transaksi_finger.ds_masuk)),0)",
			'M'		=> 'sum(iif(transaksi_finger.mhs_masuk is null,0,1))',
			'K'		=> 'sum(iif(transaksi_finger.mhs_keluar is null,0,1))',
			

			
		])->distinct(true)
		->innerJoin('transaksi_finger  tf',"(tf.id=transaksi_finger.id and isnull(tf.RStat,0)=0)")
		->innerJoin('user_ u',"(u.Fid=transaksi_finger.ds_fid and u.Fid is not null and u.tipe='3')")
		->innerJoin('tbl_dosen ds','(ds.ds_user=u.username)')
		
		->groupBy("
		 	transaksi_finger.jdwl_masuk
			,transaksi_finger.jdwl_keluar
			,transaksi_finger.mtk_kode
			,transaksi_finger.mtk_nama
			,transaksi_finger.ds_get_fid
			,transaksi_finger.tgl_ins
			,transaksi_finger.jdwl_hari
			,ds.ds_nm
			")
		->orderBy([
			"concat(LEFT(transaksi_finger.jdwl_masuk,5),' - ',LEFT(transaksi_finger.jdwl_keluar,5) )"=>SORT_ASC,
			'LEFT(transaksi_finger.jdwl_keluar,5)'=>SORT_ASC,
			'ds.ds_nm'=>SORT_ASC
		])
		
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
            'id' => $this->id,
            'krs_id' => $this->krs_id,
            'ds_fid' => $this->ds_fid,
            'ds_fid1' => $this->ds_fid1,
            'jdwl_id' => $this->jdwl_id,
            'jdwl_hari' => $this->jdwl_hari,
            'jdwl_masuk' => $this->jdwl_masuk,
            'jdwl_keluar' => $this->jdwl_keluar,
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
            //->andFilterWhere(['like', 'mtk_kode', $this->mtk_kode])
            ->andFilterWhere(['like',"concat(LEFT(transaksi_finger.jdwl_masuk,5),' - ',LEFT(transaksi_finger.jdwl_keluar,5) )", $this->jdwl_masuk])
            ->andFilterWhere(['like',"concat(transaksi_finger.mtk_kode,': ',transaksi_finger.mtk_nama)", $this->mtk_nama])
            ->andFilterWhere(['like', 'mhs_fid', $this->mhs_fid])
            ->andFilterWhere(['like', 'mhs_stat', $this->mhs_stat])            
            ->andFilterWhere(['like', 'ds_stat', $this->ds_stat])
			->orFilterWhere(['like', 'ds.ds_nm', $this->mtk_nama]);
			
			
			
			
        	return $dataProvider;
    }



}
