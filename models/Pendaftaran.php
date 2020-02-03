<?php

namespace app\models;

use Yii;

class Pendaftaran extends \yii\db\ActiveRecord{
    public  $nama,$no_ktp;

    public static function tableName(){
        return 'pendaftaran';
    }

    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            [
                [
                    'id', 'No_Registrasi', 'asal_sekolah', 'status_sekolah', 'alamat_sekolah', 'nomor_sttb',  'program_studi',
                    'status_terima', 'ket_beasiswa', 'ket_program','npm'
                ]
                , 'string'],

            [[
                'kd_daftar',
                'asal_sekolah', 'status_sekolah', 'alamat_sekolah', 'nomor_sttb',
                'program_studi',
                'pr_kode',
                'kd_jenjang','jurusan_di_sekolah',

            ], 'required'],
            [['tahun_lulus', 'tgl_daftar','ket','semester_akhir','kurikulum'], 'safe'],
            #[['jurusan_di_sekolah','pekerjaan_ortu',], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'No_Registrasi' => 'No  Registrasi',
            'asal_sekolah' => 'Asal Sekolah',
            'status_sekolah' => 'Status Sekolah',
            'alamat_sekolah' => 'Alamat Sekolah',
            'tahun_lulus' => 'Tahun Lulus',
            'nomor_sttb' => 'Nomor Sttb',
            'jurusan_di_sekolah' => 'Jurusan Di Sekolah',
            'program_studi' => 'Program Studi',
            'informasi_usb_ypkp' => 'Informasi Usb Ypkp',
            'tgl_daftar' => 'Tgl Daftar',
            'status_terima' => 'Status Terima',
            'ket_beasiswa' => 'Ket Beasiswa',
            'ket_program' => 'Ket Program',
            'pr_kode' => 'Program',
            'ket_pendapat' => 'Ket Pendapat',
        ];
    }

    public function getJr(){return $this->hasOne(Jurusan::className(), ['jr_id' => 'program_studi']);}
    public function getBio(){return $this->hasOne(Biodata::className(), ['id' => 'id']);}
    public function getPr(){return $this->hasOne(Program::className(), ['pr_kode' => 'pr_kode']);}
    public function getPrdaftar(){return $this->hasOne(ProgramDaftar::className(), ['program_id' =>'ket_program']);}

    #public function getKpr(){return $this->hasOne(Program::className(), ['pr_kode' => 'pr_kode']);}
    public function getJenjang(){return $this->hasOne(Jenjang::className(), ['id' => 'kd_jenjang']);}


}
