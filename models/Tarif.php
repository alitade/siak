<?php

namespace app\models;

use Yii;


class Tarif extends \yii\db\ActiveRecord{

    public static function tableName(){return 'tarif';}
    public static $ID=105;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode_tarif','jenis','satuan','aktif','maksimum'], 'required'],
            [['id', 'program', 'jenjang', 'check', 'status_beban', 'kelas', 'tahun', 'jurusan','aktif','total'], 'string'],
            [['ket','info'],'safe'],
            [['maksimum', 'utama'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'program' => 'Program',
            'jenjang' => 'Jenjang',
            'check' => 'Check',
            'status_beban' => 'Status Beban',
            'maksimum' => 'Maksimum',
            'utama' => 'Utama',
            'kelas' => 'Kelas',
            'tahun' => 'Tahun',
            'jurusan' => 'Jurusan',
        ];
    }
}
