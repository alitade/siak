<?php

namespace app\models;

use Yii;

class Rekap extends \yii\db\ActiveRecord
{
	public $dosen;
	public $status;
	public $pengajar;
	public $mhs;
	public $absen;
	public $masuk;
	public $keluar;
	public $M;
	public $K;
	public $krkd;
	public $sesi_;

	public $kr_kode,$pr_kode,$jr_id,$jadwal,$pr_nama,$ds_nm;
	public $peserta,$pelaksana,$tgl_,$hadir,$dMasuk,$dKeluar;
	public $xx;
    public $pMasuk,$pKeluar,$jAwal;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_transaksi_finger';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['krs_id', 'ds_fid', 'ds_fid1', 'jdwl_id', 'jdwl_hari', 'ds_get_fid','sesi'], 'integer'],
            [['krs_stat', 'mtk_kode', 'mtk_nama', 'mhs_fid', 'mhs_stat', 'ds_stat'], 'string'],
            [['jdwl_masuk', 'jdwl_keluar', 'tgl', 'mhs_masuk', 'mhs_keluar', 'ds_masuk', 'ds_keluar', 'tgl_ins','sesi'], 'safe'],
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
            'ds_fid' => 'Dosen',
            'ds_fid1' => 'Ds Fid1',
            'mtk_kode' => 'Kode',
            'mtk_nama' => 'Matakuliah',
            'jdwl_id' => 'Jdwl ID',
            'jdwl_hari' => 'Hari',
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
            'tgl_ins' => 'Tanggal',
        ];
    }
}
