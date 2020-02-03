<?php

namespace app\models;

use Yii;

class TAbsenDosen extends \yii\db\ActiveRecord{
    public $ds_nm,$pelaksana,$pr_kode,$pr_nama,$tMhs,$tHdr,$dM,$dK,$durasi;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_absen_dosen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ds_id', 'ds_id1', 'ds_get_id', 'ds_fid', 'ds_fid1', 'ds_get_fid', 'jdwl_id', 'cuid', 'uuid', 'duid'], 'integer'],
            [['ds_masuk', 'ds_keluar', 'jdwl_masuk', 'jdwl_keluar', 'tgl_normal', 'tgl_perkuliahan', 'ctgl', 'utgl', 'dtgl'], 'safe'],
            [['ds_stat', 'input_tipe', 'jdwl_kls', 'jdwl_hari', 'mtk_kode', 'mtk_nama', 'sesi', 'tipe', 'ket', 'RStat','pelaksana','tHdr','totMhs'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ds_id' => 'Ds ID',
            'ds_id1' => 'Ds Id1',
            'ds_get_id' => 'Ds Get ID',
            'ds_fid' => 'Ds Fid',
            'ds_fid1' => 'Ds Fid1',
            'ds_get_fid' => 'Ds Get Fid',
            'ds_masuk' => 'Masuk',
            'ds_keluar' => 'Keluar',
            'ds_stat' => 'Status',
            'input_tipe' => 'Input Tipe',
            'jdwl_id' => 'ID Jadwal',
            'jdwl_kls' => 'Kls',
            'jdwl_hari' => 'Hari',
            'jdwl_masuk' => 'Jam Masuk',
            'jdwl_keluar' => 'Jam Keluar',
            'mtk_kode' => 'Kode Mk.',
            'mtk_nama' => 'Mk.',
            'sesi' => 'Sesi',
            'tgl_normal' => 'Tgl Normal',
            'tgl_perkuliahan' => 'Tgl Perkuliahan',
            'tipe' => 'Tipe',
            'cuid' => 'Cuid',
            'ctgl' => 'Ctgl',
            'uuid' => 'Uuid',
            'utgl' => 'Utgl',
            'duid' => 'Duid',
            'dtgl' => 'Dtgl',
            'ket' => 'Ket',
            'RStat' => 'Rstat',
        ];
    }

    public function getJdwl(){return $this->hasOne(Jadwal::className(), ['jdwl_id' => 'jdwl_id']);}
    public function getDosen(){return $this->hasOne(Dosen::className(), ['ds_id' => 'ds_id']);}
    public function getPengajar(){return $this->hasOne(Dosen::className(), ['ds_id' => 'ds_get_id']);}
    public function getPengganti(){return $this->hasOne(Dosen::className(), ['ds_id' => 'ds_id1']);}

}
