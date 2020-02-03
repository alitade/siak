<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaksi_finger".
 *
 * @property integer $id
 * @property integer $krs_id
 * @property string $krs_stat
 * @property integer $ds_fid
 * @property integer $ds_fid1
 * @property string $mtk_kode
 * @property string $mtk_nama
 * @property integer $jdwl_id
 * @property integer $jdwl_hari
 * @property string $jdwl_masuk
 * @property string $jdwl_keluar
 * @property string $tgl
 * @property string $mhs_fid
 * @property string $mhs_masuk
 * @property string $mhs_keluar
 * @property string $mhs_stat
 * @property string $ds_masuk
 * @property string $ds_keluar
 * @property string $ds_stat
 * @property integer $ds_get_fid
 * @property string $tgl_ins
 */
class TransaksiFinger extends \yii\db\ActiveRecord
{
	public $dosen;
	public $status;
	public $pengajar;
	public $mhs;
	public $absen;
	public $masuk;
	public $keluar;
	public $hadir;
	public $M;
	public $K;
	public $maxAbsen;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaksi_finger';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['krs_id', 'ds_fid', 'ds_fid1', 'jdwl_id', 'jdwl_hari', 'ds_get_fid'], 'integer'],
            [['krs_stat', 'mtk_kode', 'mtk_nama', 'mhs_fid', 'mhs_stat', 'ds_stat'], 'string'],
            [['jdwl_masuk', 'jdwl_keluar', 'tgl', 'mhs_masuk', 'mhs_keluar', 'ds_masuk', 'ds_keluar', 'tgl_ins'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'krs_id' => 'Krs ID',
            'krs_stat' => 'Krs Stat',
            'ds_fid' => 'Ds Fid',
            'ds_fid1' => 'Ds Fid1',
            'mtk_kode' => 'Kode',
            'mtk_nama' => 'Matakuliah',
            'jdwl_id' => 'Jdwl ID',
            'jdwl_hari' => 'Jdwl Hari',
            'jdwl_masuk' => 'Masuk',
            'jdwl_keluar' => 'Keluar',
            'tgl' => 'Tanggal',
            'mhs_fid' => 'Mhs Fid',
            'mhs_masuk' => 'Mhs Masuk',
            'mhs_keluar' => 'Mhs Keluar',
            'mhs_stat' => 'Mhs Stat',
            'ds_masuk' => 'Ds Masuk',
            'ds_keluar' => 'Ds Keluar',
            'ds_stat' => 'Ds Stat',
            'ds_get_fid' => 'Ds Get Fid',
            'tgl_ins' => 'Tgl Ins',
        ];
    }
}
