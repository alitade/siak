<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_absen_mhs".
 *
 * @property integer $id
 * @property integer $id_absen_ds
 * @property string $mhs_nim
 * @property integer $mhs_fid
 * @property string $mhs_masuk
 * @property string $mhs_keluar
 * @property string $mhs_stat
 * @property string $input_tipe
 * @property integer $krs_id
 * @property string $krs_stat
 * @property integer $jdwl_id
 * @property string $sesi
 * @property integer $cuid
 * @property string $ctgl
 * @property integer $uuid
 * @property string $utgl
 * @property integer $duid
 * @property string $dtgl
 * @property string $ket
 * @property string $RStat
 * @property string $kode
 */
class MAbsenMhs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_absen_mhs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'id_absen_ds', 'mhs_fid', 'krs_id', 'jdwl_id', 'cuid', 'uuid', 'duid'], 'integer'],
            [['mhs_nim', 'mhs_stat', 'input_tipe', 'krs_stat', 'sesi', 'ket', 'RStat', 'kode'], 'string'],
            [['mhs_masuk', 'mhs_keluar', 'ctgl', 'utgl', 'dtgl'], 'safe'],
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
            'cuid' => 'Cuid',
            'ctgl' => 'Ctgl',
            'uuid' => 'Uuid',
            'utgl' => 'Utgl',
            'duid' => 'Duid',
            'dtgl' => 'Dtgl',
            'ket' => 'Ket',
            'RStat' => 'Rstat',
            'kode' => 'Kode',
        ];
    }
}
