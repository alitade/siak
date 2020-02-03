<?php

namespace app\models;

use Yii;

class DaftarNpm extends Pendaftaran{

    public function rules(){
        return [
            [
                [
                    'id', 'No_Registrasi', 'asal_sekolah', 'status_sekolah', 'alamat_sekolah', 'nomor_sttb',  'program_studi',
                    'status_terima', 'ket_beasiswa', 'ket_program','npm'
                ]
                , 'string'
            ],
            [['npm'],'unique'],

            [[
                'kd_daftar',
                'asal_sekolah', 'status_sekolah', 'alamat_sekolah', 'nomor_sttb',
                'program_studi',
                'pr_kode',
                'kd_jenjang','jurusan_di_sekolah',
                #
                'semester_lanjutan','kurikulum','status_pendaftaran','id_tarif','npm',

            ], 'required'],
            [['tahun_lulus', 'tgl_daftar','ket','semester_akhir','kurikulum'], 'safe'],
            #[['jurusan_di_sekolah','pekerjaan_ortu',], 'integer'],
        ];
    }

}
