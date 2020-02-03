<?php

namespace app\models;

use Yii;

class BiodataTemp extends \yii\db\ActiveRecord{
    public $sama;
    public $rt,$rw,$add,$kec,$kabkot,$keldes;
    public $rt1,$rw1,$add1,$kec1,$kabkot1,$keldes1;

    public static function tableName(){
        return 'biodata_temp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode', 'no_ktp', 'nama', 'tempat_lahir', 'jk', 'alamat_ktp', 'kota', 'kode_pos', 'propinsi', 'negara', 'agama', 'status_ktp', 'pekerjaan', 'kewarganegaraan', 'ibu_kandung', 'photo', 'alamat_tinggal', 'kota_tinggal', 'kode_pos_tinggal', 'tlp', 'email', 'parent', 'glr_depan', 'glr_belakang'], 'string'],
            [[
                'no_ktp','alamat_ktp','tempat_lahir','tanggal_lahir','jk','kode_pos','agama','pekerjaan','kewarganegaraan',
                'nama','rt','rw','add','keldes','kec','kota','propinsi'
                ,'alamat_tinggal','rt1','rw1','add1','keldes1','kec1','kota_tinggal','kode_pos_tinggal','ibu_kandung','tlp',
            ],'required','message'=>'{attribute} Tidak Boleh Kosong'],
            [['tanggal_lahir', 'berlaku_ktp','status_data', 'ctgl'], 'safe'],
            [['cuid', 'id_', 'kd_agama', 'kd_kerja'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
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
            'status_ktp' => 'Status Ktp',
            'pekerjaan' => 'Pekerjaan',
            'kewarganegaraan' => 'Kewarganegaraan',
            'berlaku_ktp' => 'Berlaku Ktp',
            'ibu_kandung' => 'Ibu Kandung',
            'photo' => 'Photo',
            'alamat_tinggal' => 'Alamat Tinggal',
            'kota_tinggal' => 'Kota Tinggal',
            'kode_pos_tinggal' => 'Kode Pos Tinggal',
            'tlp' => 'Tlp',
            'email' => 'Email',
            'parent' => 'Parent',
            'cuid' => 'Cuid',
            'ctgl' => 'Ctgl',
            'id_' => 'Id',
            'kd_agama' => 'Kd Agama',
            'kd_kerja' => 'Kd Kerja',
            'glr_depan' => 'Glr Depan',
            'glr_belakang' => 'Glr Belakang',
        ];
    }

    public function getAgm(){return $this->hasOne(MasterAgama::className(), ['id' => 'kd_agama']);}
    public function getC(){return $this->hasOne(User::className(),['id'=>'cuid']);}


}
