<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "labsen_dosen_det".
 *
 * @property string $id
 * @property string $id_labsen
 * @property string $id_absen
 * @property string $ds_id
 * @property integer $ds_tipe
 * @property string $ds_nm
 * @property string $ds_get_id
 * @property string $pelaksana
 * @property string $masuk
 * @property string $keluar
 * @property integer $jdwl_id
 * @property string $jdwl_kls
 * @property string $jdwl_hari
 * @property string $jdwl_masuk
 * @property string $jdwl_keluar
 * @property string $mtk_kode
 * @property string $mtk_nama
 * @property integer $mtk_sks
 * @property string $sesi
 * @property string $tgl_perkuliahan
 * @property string $tipe
 * @property string $ltipe
 * @property string $GKode_
 * @property integer $totmhs
 */
class LabsenDosenDet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'labsen_dosen_det';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_labsen', 'id_absen', 'ds_id', 'ds_tipe', 'ds_get_id', 'jdwl_id', 'mtk_sks', 'totmhs'], 'integer'],
            [['id_labsen', 'id_absen'], 'required'],
            [['ds_nm', 'pelaksana', 'jdwl_kls', 'jdwl_hari', 'mtk_kode', 'mtk_nama', 'sesi', 'tipe', 'ltipe', 'GKode_'], 'string'],
            [['masuk', 'keluar', 'jdwl_masuk', 'jdwl_keluar', 'tgl_perkuliahan'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_labsen' => 'Id Labsen',
            'id_absen' => 'Id Absen',
            'ds_id' => 'Ds ID',
            'ds_tipe' => 'Ds Tipe',
            'ds_nm' => 'Ds Nm',
            'ds_get_id' => 'Ds Get ID',
            'pelaksana' => 'Pelaksana',
            'masuk' => 'Masuk',
            'keluar' => 'Keluar',
            'jdwl_id' => 'Jdwl ID',
            'jdwl_kls' => 'Jdwl Kls',
            'jdwl_hari' => 'Jdwl Hari',
            'jdwl_masuk' => 'Jdwl Masuk',
            'jdwl_keluar' => 'Jdwl Keluar',
            'mtk_kode' => 'Mtk Kode',
            'mtk_nama' => 'Mtk Nama',
            'mtk_sks' => 'Mtk Sks',
            'sesi' => 'Sesi',
            'tgl_perkuliahan' => 'Tgl Perkuliahan',
            'tipe' => 'Tipe',
            'ltipe' => 'Ltipe',
            'GKode_' => 'Gkode',
            'totmhs' => 'Totmhs',
        ];
    }
}
