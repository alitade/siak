<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "people".
 *
 * @property string $No_Registrasi
 * @property string $Nama
 * @property string $tempat_lahir
 * @property string $tanggal_lahir
 * @property string $jenis_kelamin
 * @property string $agama
 * @property integer $pekerjaan
 * @property string $kewarganegaraan
 * @property string $status
 * @property string $alamat
 * @property string $kota
 * @property string $kode_pos
 * @property string $propinsi
 * @property string $negara
 * @property string $asal_sekolah
 * @property string $status_sekolah
 * @property string $alamat_sekolah
 * @property string $tahun_lulus
 * @property string $nomor_sttb
 * @property integer $jurusan_di_sekolah
 * @property string $fakultas
 * @property string $program_studi
 * @property string $nama_ortu_wali
 * @property string $alamat_ortu_wali
 * @property string $kode_pos_ortu_wali
 * @property string $telepon_ortu_wali
 * @property integer $pekerjaan_ortu
 * @property integer $informasi_usb_ypkp
 * @property integer $id_admin
 * @property string $tgl_daftar
 * @property string $status_terima
 * @property string $ket_beasiswa
 * @property string $ket_program
 * @property string $ket_pendapat
 * @property string $no_ktp
 * @property string $no_telepon
 * @property string $ibu_kandung
 */
class People extends \yii\db\ActiveRecord
{
	public $jln,$rt,$rw,$dsn,$kel,$kec;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'people';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db1');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['No_Registrasi', 'Nama',], 'required'],
            [['No_Registrasi', 'Nama', 'tempat_lahir', 'jenis_kelamin', 'agama', 'kewarganegaraan', 'status', 'alamat', 'kota', 'kode_pos', 'propinsi', 'negara', 'asal_sekolah', 'status_sekolah', 'alamat_sekolah', 'nomor_sttb', 'fakultas', 'program_studi', 'nama_ortu_wali', 'alamat_ortu_wali', 'kode_pos_ortu_wali', 'telepon_ortu_wali', 'status_terima', 'ket_beasiswa', 'ket_program', 'ket_pendapat', 'no_ktp', 'no_telepon', 'ibu_kandung'], 'string'],
            [['tanggal_lahir', 'tahun_lulus', 'tgl_daftar',
				'jln','rt','rw','dsn','kel','kec'
			], 'safe'],
            [['pekerjaan', 'jurusan_di_sekolah', 'pekerjaan_ortu', 'informasi_usb_ypkp', 'id_admin'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'No_Registrasi' => 'No  Registrasi',
            'Nama' => 'Nama',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'jenis_kelamin' => 'Jenis Kelamin',
            'agama' => 'Agama',
            'pekerjaan' => 'Pekerjaan',
            'kewarganegaraan' => 'Kewarganegaraan',
            'status' => 'Status',
            'alamat' => 'Alamat',
            'kota' => 'Kota',
            'kode_pos' => 'Kode Pos',
            'propinsi' => 'Propinsi',
            'negara' => 'Negara',
            'asal_sekolah' => 'Asal Sekolah',
            'status_sekolah' => 'Status Sekolah',
            'alamat_sekolah' => 'Alamat Sekolah',
            'tahun_lulus' => 'Tahun Lulus',
            'nomor_sttb' => 'Nomor Sttb',
            'jurusan_di_sekolah' => 'Jurusan Di Sekolah',
            'fakultas' => 'Fakultas',
            'program_studi' => 'Program Studi',
            'nama_ortu_wali' => 'Nama Ortu Wali',
            'alamat_ortu_wali' => 'Alamat Ortu Wali',
            'kode_pos_ortu_wali' => 'Kode Pos Ortu Wali',
            'telepon_ortu_wali' => 'Telepon Ortu Wali',
            'pekerjaan_ortu' => 'Pekerjaan Ortu',
            'informasi_usb_ypkp' => 'Informasi Usb Ypkp',
            'id_admin' => 'Id Admin',
            'tgl_daftar' => 'Tgl Daftar',
            'status_terima' => 'Status Terima',
            'ket_beasiswa' => 'Ket Beasiswa',
            'ket_program' => 'Ket Program',
            'ket_pendapat' => 'Ket Pendapat',
            'no_ktp' => 'No Ktp',
            'no_telepon' => 'No Telepon',
            'ibu_kandung' => 'Ibu Kandung',
        ];
    }

    public function getProv(){
        return $this->hasOne(MasterProvinsi::className(), ['id' => 'propinsi']);
    }

}
