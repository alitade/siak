<?php

namespace app\models;

use Yii;

class MAbsenDosen extends \yii\db\ActiveRecord{

    public $ds_nm,$pelaksana,$pr_kode,$pr_nama,$tMhs;

    public static function tableName(){
        return 'm_absen_dosen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'ds_id', 'ds_id1', 'ds_get_id', 'ds_get_fid', 'jdwl_id', 'cuid', 'uuid', 'duid'], 'integer'],
            [['ds_masuk', 'ds_keluar', 'jdwl_masuk', 'jdwl_keluar', 'tgl_normal', 'tgl_perkuliahan', 'ctgl', 'utgl', 'dtgl'], 'safe'],
            [['ds_stat', 'input_tipe', 'jdwl_kls', 'jdwl_hari', 'mtk_kode', 'mtk_nama', 'sesi', 'tipe', 'ket', 'RStat','pelaksana'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'ds_id' => 'Ds ID',
            'ds_id1' => 'Ds Id1',
            'ds_get_id' => 'Ds Get ID',
            'ds_get_fid' => 'Ds Get Fid',
            'ds_masuk' => 'Ds Masuk',
            'ds_keluar' => 'Ds Keluar',
            'ds_stat' => 'Ds Stat',
            'input_tipe' => 'Input Tipe',
            'jdwl_id' => 'Jdwl ID',
            'jdwl_kls' => 'Jdwl Kls',
            'jdwl_hari' => 'Jdwl Hari',
            'jdwl_masuk' => 'Jdwl Masuk',
            'jdwl_keluar' => 'Jdwl Keluar',
            'mtk_kode' => 'Mtk Kode',
            'mtk_nama' => 'Mtk Nama',
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
            'kr_kode_'=>'Tahun Akademik'
        ];
    }

    public function getJdwl(){return $this->hasOne(Jadwal::className(), ['jdwl_id' => 'jdwl_id']);}
    public function getPengajar(){return $this->hasOne(Dosen::className(),['ds_id' => 'ds_get_id']);}



}
