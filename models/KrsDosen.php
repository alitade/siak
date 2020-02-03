<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_krs".
 *
 * @property integer $krs_id
 * @property string $krs_tgl
 * @property integer $jdwl_id
 * @property string $mhs_nim
 * @property integer $krs_tgs1
 * @property integer $krs_tgs2
 * @property integer $krs_tgs3
 * @property integer $krs_tambahan
 * @property integer $krs_quis
 * @property integer $krs_uts
 * @property integer $krs_uas
 * @property string $krs_tot
 * @property string $krs_grade
 * @property string $krs_stat
 * @property string $krs_ulang
 * @property string $kr_kode_
 * @property string $ds_nidn_
 * @property string $ds_nm_
 * @property string $mtk_kode_
 * @property string $mtk_nama_
 * @property string $sks_
 *
 * @property TblAbsensi[] $tblAbsensis
 * @property TblJadwal $jdwl
 * @property TblMahasiswa $mhsNim
 */
class KrsDosen extends Krs
{
    
  public function attributeLabels()
    {
        return [
            'mhs_nim' => 'NIM',
            'Nama' => 'Mahasiswa',
        ];
    }
   
}
