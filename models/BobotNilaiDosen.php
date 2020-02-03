<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_bobot_nilai".
 *
 * @property integer $id
 * @property integer $kln_id
 * @property string $mtk_kode
 * @property string $ds_nidn
 * @property integer $nb_tgs1
 * @property integer $nb_tgs2
 * @property integer $nb_tgs3
 * @property integer $nb_tambahan
 * @property integer $nb_quis
 * @property integer $nb_uts
 * @property integer $nb_uas
 * @property string $B
 * @property string $C
 * @property string $D
 * @property string $E
 *
 * @property TblDosen $dsNidn
 * @property TblKalender $kln
 * @property TblJadwal[] $tblJadwals
 */


class BobotNilaiDosen extends BobotNilai
{
	public $program;
    public $jr_id;
	public $jr_nama;
	public $kr_kode;
    public $mtk_nama;
    public $pr_nama;
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


     public function search($params)
    {
        $params = Yii::$app->request->getQueryParams();

        $query =  BobotNilaiDosen::find()->select('tbl_bobot_nilai.*,kl.pr_kode,kl.kr_kode,jr.jr_id,jr_nama,mtk_nama,pr.pr_nama')
        ->innerJoin('tbl_kalender kl',"kl.kln_id = tbl_bobot_nilai.kln_id  AND (kl.RStat='0' or kl.RStat is null)")
        ->innerJoin('tbl_program pr',"kl.pr_kode= pr.pr_kode AND (pr.RStat='0' or pr.RStat is null)")    
        ->innerJoin('tbl_jurusan jr',"jr.jr_id= kl.jr_id AND (jr.RStat='0' or jr.RStat is null)")
        ->innerJoin('tbl_matkul mk',"mk.mtk_kode = tbl_bobot_nilai.mtk_kode AND (mk.RStat='0' or mk.RStat is null)")
        ->innerJoin('tbl_dosen ds',"tbl_bobot_nilai.ds_nidn = ds.ds_id and ds_user='".Yii::$app->user->identity->username."'  AND (ds.RStat='0' or ds.RStat is null)")
        ->where(" 
			(tbl_bobot_nilai.RStat='0' or tbl_bobot_nilai.RStat is null)
			and 	
			EXISTS(
				SELECT krs.krs_id, jd.bn_id from tbl_krs krs
				INNER JOIN tbl_jadwal jd on (jd.jdwl_id=krs.jdwl_id and(jd.RStat is null or jd.RStat='0'))
				INNER JOIN tbl_bobot_nilai bn on (bn.id=jd.bn_id and(bn.RStat is null or bn.RStat='0'))
				where tbl_bobot_nilai.id=bn.id
			)
		")
		/*
		and 	
			NOT EXISTS(
				SELECT krs.krs_id, jd.bn_id from tbl_krs krs
				INNER JOIN tbl_jadwal jd on (jd.jdwl_id=krs.jdwl_id and(jd.RStat is null or jd.RStat='0'))
				INNER JOIN tbl_bobot_nilai bn on (bn.id=jd.bn_id and(bn.RStat is null or bn.RStat='0'))
				where tbl_bobot_nilai.id=bn.id
			)
		*/
		->orderBy(['jr.jr_id'=>SORT_ASC,'pr.pr_kode'=>SORT_ASC,]);

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
            $query->andFilterWhere(['=','jr_nama', 'UDIN']);
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
