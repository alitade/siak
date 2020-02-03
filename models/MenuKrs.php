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


class MenuKrs extends BobotNilai
{
	public $program;
	public $jr_nama;
	public $kr_kode;
    public $mtk_nama;

 
    public function rules()
    {
        return [
			 
            [['mtk_nama','jr_nama','kr_kode'], 'safe'],
           
        ];
    }

     public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kr_kode' => 'Tahun',
            'jr_nama' => 'Jurusan',
            'mtk_kode' => 'Kode',
            'mtk_nama' => 'Matakuliah',
            'ds_nidn' => 'Dosen',
            'nb_tgs1' => 'Tgs1',
            'nb_tgs2' => 'Tgs2',
            'nb_tgs3' => 'Tgs3',
            'nb_tambahan' => 'Tbh',
            'nb_quis' => 'Quis',
            'nb_uts' => 'UTS',
            'nb_uas' => 'UAS',
            'B' => 'Range B',
            'C' => 'Range C',
            'D' => 'Range D',
            'E' => 'Range E',
        ];
    }


     public function search($params)
    {
        $params = Yii::$app->request->getQueryParams();

        $query =  MenuKrs::find()->select('tbl_bobot_nilai.* ,tbl_jadwal.* ,kr_kode,jr_nama,mtk_nama')
        ->innerJoin('tbl_kalender kl','kl.kln_id = tbl_bobot_nilai.kln_id')
        ->innerJoin('tbl_jurusan jr','jr.jr_id= kl.jr_id')    
        ->innerJoin('tbl_matkul mk','mk.mtk_kode = tbl_bobot_nilai.mtk_kode')                                 
        ->innerJoin('tbl_matkul mk','mk.mtk_kode = tbl_bobot_nilai.mtk_kode')                                 
        ->where(" (tbl_bobot_nilai.RStat='0' or tbl_bobot_nilai.RStat is null) and tbl_bobot_nilai.ds_nidn = '".Yii::$app->user->identity->idku."' ");



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
             
        ]
        ]);

        

        if (!($this->load($params) && $this->validate())) {
            $query->andFilterWhere(['=','jr_nama', 'UDIN']);
            return $dataProvider;
        }

        

        $query->andFilterWhere(['like', 'mk.mtk_kode', $this->mtk_kode])
            ->andFilterWhere(['like', 'mk.mtk_nama', $this->mtk_nama])
            ->andFilterWhere(['like', 'kr_kode', $this->kr_kode])
           ->andFilterWhere(['like', 'jr_nama', $this->jr_nama]);
            /*->andFilterWhere(['like', 'mhs_pass_kode', $this->mhs_pass_kode])
            ->andFilterWhere(['like', 'mhs_angkatan', $this->mhs_angkatan])
            ->andFilterWhere(['like', 'jr_id', $this->jr_id])
            ->andFilterWhere(['like', 'pr_kode', $this->pr_kode])
            ->andFilterWhere(['like', 'mhs_stat', $this->mhs_stat])
            ->andFilterWhere(['like', 'ds_wali', $this->ds_wali]);*/
 

        return $dataProvider;
    }

    
}
