<?php

namespace app\models;

use Yii;


class Biodata extends \yii\db\ActiveRecord{
    #pecahan alamat
    public $sama;
    public $rt,$rw,$add,$kec,$kabkot,$keldes;
    public $rt1,$rw1,$add1,$kec1,$kabkot1,$keldes1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'biodata';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','parent'],'string'],
            #[['pekerjaan', 'id', 'kode', 'no_ktp', 'nama', 'tempat_lahir', 'alamat_ktp', 'kota', 'kode_pos', 'propinsi', 'negara', 'agama', 'status_ktp', 'kewarganegaraan', 'ibu_kandung','parent','Rstat'], 'trim'],
            [
                ['id'],'unique'
            ],
            [
                [
                    'no_ktp','alamat_ktp','tempat_lahir','tanggal_lahir','jk','kode_pos','agama','pekerjaan','kewarganegaraan',
                    'nama','rt','rw','add','keldes','kec','kota','propinsi'
                    ,'alamat_tinggal','rt1','rw1','add1','keldes1','kec1','kota_tinggal','kode_pos_tinggal','ibu_kandung','tlp',

                ],
                'required','message'=>'Tidak Boleh Kosong'
            ],

            [['tanggal_lahir', 'berlaku_ktp', 'ctgl', 'utgl', 'dtgl','id_','glr_depan','glr_belakang'], 'safe'],
            [['cuid', 'uuid', 'duid','jk'], 'integer'],


        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode' => 'Kode',
            'no_ktp' => 'No Ktp',
            'nama' => 'Nama',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'jk' => 'Jk',
            'alamat_ktp' => 'Alamat Ktp',
            'kota' => 'Kota',
            'kode_pos' => 'Kode Pos',
            'propinsi' => 'Propinsi',
            'negara' => 'Negara',
            'agama' => 'Agama',
            'status_ktp' => 'Status',
            'pekerjaan' => 'Pekerjaan',
            'kewarganegaraan' => 'Kewarganegaraan',
            'berlaku_ktp' => 'Masa Berlaku',
            'ibu_kandung' => 'Ibu Kandung',
            'photo' => 'Photo',
            'parent' => 'Parent',
            'cuid' => 'Cuid',
            'ctgl' => 'Ctgl',
            'uuid' => 'Uuid',
            'utgl' => 'Utgl',
            'duid' => 'Duid',
            'dtgl' => 'Dtgl',
            'Rstat' => 'Rstat',
        ];
    }
}
