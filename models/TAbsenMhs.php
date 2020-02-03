<?php

namespace app\models;

use Yii;

class TAbsenMhs extends \yii\db\ActiveRecord
{
    public $Nama,$mtk_kode,$mtk_nama,$jdwl_kls;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_absen_mhs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_absen_ds', 'mhs_fid', 'krs_id', 'jdwl_id', 'cuid', 'uuid', 'duid'], 'integer'],
            [['mhs_nim', 'mhs_stat', 'input_tipe', 'krs_stat', 'sesi', 'ket', 'RStat'], 'string'],
            [['mhs_masuk', 'mhs_keluar', 'tgl_perkuliahan', 'ctgl', 'utgl', 'dtgl'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_absen_ds' => 'Id Absen Ds',
            'mhs_nim' => 'Mhs Nim',
            'mhs_fid' => 'Mhs Fid',
            'mhs_masuk' => 'Mhs Masuk',
            'mhs_keluar' => 'Mhs Keluar',
            'mhs_stat' => 'Mhs Stat',
            'input_tipe' => 'Input Tipe',
            'krs_id' => 'Krs ID',
            'krs_stat' => 'Krs Stat',
            'jdwl_id' => 'Jdwl ID',
            'sesi' => 'Sesi',
            'tgl_perkuliahan' => 'Tgl Perkuliahan',
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
}
