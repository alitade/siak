<?php

namespace app\models;

use Yii;

class Krs extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public $kurikulum;
	public $Mahasiswa;
	public $Matakuliah;
	public $Jurusan;
	public $Program;
	public $Nilai;

	public $jr_id;
	public $kr_kode;
	public $pr_kode,$AvKrs;

    public static function tableName(){return 'tbl_krs';}


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jdwl_id', 'mhs_nim'], 'required'],
            [['krs_tgl','AvKrs'], 'safe'],
            [['jdwl_id'], 'integer'],
            [['mhs_nim', 'krs_grade', 'krs_stat', 'krs_ulang', 'kr_kode_', 'ds_nidn_', 'ds_nm_', 'mtk_kode_', 'mtk_nama_', 'sks_'], 'string'],
            [['krs_tot', 'krs_tgs1', 'krs_tgs2', 'krs_tgs3', 'krs_tambahan', 'krs_quis', 'krs_uts', 'krs_uas'], 'number'],
            [['jdwl_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jadwal::className(), 'targetAttribute' => ['jdwl_id' => 'jdwl_id']],
            [['mhs_nim'], 'exist', 'skipOnError' => true, 'targetClass' => Mahasiswa::className(), 'targetAttribute' => ['mhs_nim' => 'mhs_nim']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'krs_id' => 'Krs ID',
            'krs_tgl' => 'Krs Tgl',
            'jdwl_id' => 'Jdwl ID',
            'mhs_nim' => 'Mhs Nim',
            'krs_tgs1' => 'Tgs1',
            'krs_tgs2' => 'Tgs2',
            'krs_tgs3' => 'Tgs3',
            'krs_tambahan' => 'Absensi',
            'krs_quis' => 'Quis',
            'krs_uts' => 'Uts',
            'krs_uas' => 'Uas',
            'krs_tot' => 'Total',
            'krs_grade' => 'Grade',
            'krs_stat' => 'Stat',
            'krs_ulang' => 'Ulang',
            'kr_kode_' => 'Kurikulum',
            'ds_nidn_' => 'Dosen',
            'ds_nm_' => 'Nama Dosen',
            'mtk_kode_' => 'Kode Mtk. ',
            'mtk_nama_' => 'Nama Mtk.',
            'sks_' => 'Sks',

            'pr_kode' => 'Program',
            'jr_id' => 'Jurusan',
            'kr_kode' => 'Kurikulum',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblAbsensis()
    {
        return $this->hasMany(Absensi::className(), ['krs_id' => 'krs_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJdwl()
    {
        return $this->hasOne(Jadwal::className(), ['jdwl_id' => 'jdwl_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMhsNim()
    {
        return $this->hasOne(Mahasiswa::className(), ['mhs_nim' => 'mhs_nim']);
    }
}
