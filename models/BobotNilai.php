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


class BobotNilai extends \yii\db\ActiveRecord
{
	public $program;
	public $jurusan;
	public $kalender;
	public $kr_kode;
	
	public $tot;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_bobot_nilai';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['kln_id','mtk_kode', 'ds_nidn'],'required','message'=>'Tidak Boleh Kosong'],
			
			[['kln_id'],'unique','targetAttribute' => ['kln_id','mtk_kode','ds_nidn','RStat'],'message'=>'Data Pengajar Sudah ada'],
			
            [['kln_id', 'nb_tgs1', 'nb_tgs2', 'nb_tgs3', 'nb_tambahan', 'nb_quis', 'nb_uts', 'nb_uas'], 'integer'],
            [['mtk_kode'], 'string'],
            [['B', 'C', 'D', 'E'], 'number'],
            [['ds_nidn'], 'exist', 'skipOnError' => true, 'targetClass' => Dosen::className(), 'targetAttribute' => ['ds_nidn' => 'ds_id']],
            [['kln_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kalender::className(), 'targetAttribute' => ['kln_id' => 'kln_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kln_id' => 'Tahun Akademik',
            'mtk_kode' => 'Matakuliah',
            'ds_nidn' => 'Dosen',
            'nb_tgs1' => '% Tgs1',
            'nb_tgs2' => '% Tgs2',
            'nb_tgs3' => '% Tgs3',
            'nb_tambahan' => '% Tambahan',
            'nb_quis' => '% Quis',
            'nb_uts' => '% Uts',
            'nb_uas' => '% Nb Uas',
            'B' => 'Range B',
            'C' => 'Range C',
            'D' => 'Range D',
            'E' => 'Range E',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDs()
    {
        return $this->hasOne(Dosen::className(), ['ds_id' => 'ds_nidn']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKln()
    {
        return $this->hasOne(Kalender::className(), ['kln_id' => 'kln_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJdw(){
		return $this->hasMany(Jadwal::className(), ['bn_id' => 'id'])
		->andOnCondition(["isnull(tbl_jadwal.RStat,0)"=>0])
		->orderBy(['isnull(tbl_jadwal.GKode,tbl_jadwal.jdwl_id)'=>SORT_DESC])
		;
	}

    public function getMtk()
    {
        return $this->hasOne(Matkul::className(), ['mtk_kode' => 'mtk_kode']);
    }
}
