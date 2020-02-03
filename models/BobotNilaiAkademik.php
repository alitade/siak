<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

class BobotNilaiAkademik extends BobotNilai
{
	public $program;
    public $jr_id;
	public $jr_nama;
	public $kr_kode;
    public $mtk_nama;
    public $pr_kode;

    public static function Grade($x,$header){
        if(empty($header)){
            return '-';
        }
        if ($x >= (float)($header['B'] + 0.01)) {
            return "A";
        } elseif ($x >= (float)($header['C'] + 0.01)) {
            return "B";
        } elseif ($x >= (float)($header['D'] + 0.01)) {
            return "C";
        } elseif ($x >= (float)($header['E'] + 0.01)) {
            return "D";
        } else {
            return "E";
        }
    }

 
    public function rules()
    {
        return [
			 
            [['mtk_nama','jr_nama','jr_id','pr_kode','kr_kode'], 'safe'],
           
        ];
    }

     public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'pr_kode'       => 'Program',
            'kr_kode'       => 'Tahun',
            'jr_nama'       => 'Jurusan',
            'jr_id'         => 'Jurusan',
            'mtk_kode'      => 'Kode',
            'mtk_nama'      => 'Matakuliah',
            'ds_nidn'       => 'Dosen',
            'nb_tgs1'       => 'Tgs1',
            'nb_tgs2'       => 'Tgs2',
            'nb_tgs3'       => 'Tgs3',
            'nb_tambahan'   => 'Tbh',
            'nb_quis'       => 'Quis',
            'nb_uts'        => 'UTS',
            'nb_uas'        => 'UAS',
            'B'             => 'Range B',
            'C'             => 'Range C',
            'D'             => 'Range D',
            'E'             => 'Range E',
        ];
    }


     public function search($params,$id)
    {
        $params = Yii::$app->request->getQueryParams();

        $query =  BobotNilaiDosen::find()->select('tbl_bobot_nilai.*,kl.pr_kode,kr_kode,jr.jr_id,jr_nama,mtk_nama')
        ->innerJoin('tbl_kalender kl','kl.kln_id = tbl_bobot_nilai.kln_id')
        ->innerJoin('tbl_jurusan jr','jr.jr_id= kl.jr_id')    
        ->innerJoin('tbl_matkul mk','mk.mtk_kode = tbl_bobot_nilai.mtk_kode')                                 
        ->innerJoin('tbl_dosen ds',"tbl_bobot_nilai.ds_nidn = ds.ds_id") 
        ->where(" (tbl_bobot_nilai.RStat='0' or tbl_bobot_nilai.RStat is null) and tbl_bobot_nilai.id='$id'");

//        var_dump(Yii::$app->user->identity);die();
 
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
     
         $dataProvider->setSort([
        'attributes' => [
            
            'mtk_nama' => [
                'asc' => ['mtk_nama' => SORT_ASC],
                'desc' => ['mtk_nama' => SORT_DESC],
                'label' => 'Matakuliah',
                'default' => SORT_ASC
            ],

             'mtk_nama' => [
                'asc' => ['mtk_nama' => SORT_ASC],
                'desc' => ['mtk_nama' => SORT_DESC],
                'label' => 'Matakuliah',
                'default' => SORT_ASC
            ],

            'id' => [
                'asc' => ['tbl_bobot_nilai.id' => SORT_ASC],
                'desc' => ['tbl_bobot_nilai.id' => SORT_DESC],
                'label' => 'ID',
                'default' => SORT_DESC
            ],
             
        ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
       

        $query->andFilterWhere(['=',    'jr.jr_id', $this->jr_id])
              ->andFilterWhere(['=',    'kl.pr_kode', $this->pr_kode])
              ->andFilterWhere(['like', 'mk.mtk_kode', $this->mtk_kode])
              ->andFilterWhere(['like', 'mk.mtk_nama', $this->mtk_nama])
              ->andFilterWhere(['like', 'kr_kode', $this->kr_kode])
              ->andFilterWhere(['like', 'jr_nama', $this->jr_nama])
             ;

        return $dataProvider;
    }

    
}
