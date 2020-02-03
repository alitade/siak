<?php

namespace app\models;

use Yii;

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


class Bobot extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bobotnilai';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['GKode','B', 'C', 'D', 'E','nb_tgs1', 'nb_tgs2','nb_uts', 'nb_uas'],'required','message'=>'Tidak Boleh Kosong'],
            [['nb_tgs1', 'nb_tgs2', 'nb_tgs3', 'nb_tambahan', 'nb_quis', 'nb_uts', 'nb_uas'], 'integer'],
            [['B', 'C', 'D', 'E'], 'number'],
        ];
    }

    public function attributeLabels(){
        return [
            'GKode' => 'Kode',
            'nb_tgs1' => '*Tugas 1',
            'nb_tgs2' => '*Tugas 2',
            'nb_tgs3' => 'Tugas 3',
            'nb_tambahan' => 'Absensi',
            'nb_quis' => 'Quis',
            'nb_uts' => '*UTS',
            'nb_uas' => '*UAS',
            'B' => '*Range B',
            'C' => '*Range C',
            'D' => '*Range D',
            'E' => '*Range E',
        ];
    }

    public function getJdw(){
		return $this->hasMany(Jadwal::className(), ['GKode' => 'GKode'])
		->andOnCondition(["isnull(tbl_jadwal.RStat,0)"=>0])
		#->orderBy(['isnull(tbl_jadwal.GKode,tbl_jadwal.jdwl_id)'=>SORT_DESC])
		;
	}
	
}
