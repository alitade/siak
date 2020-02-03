<?php

namespace app\models;

use Yii;

class GroupMatkul extends \yii\db\ActiveRecord{
	public $total;

    public static function tableName(){return 'groupmk';}

    public function rules(){
        return [
            [['kode', 'nama', 'sks',], 'required'],
            [['kode', 'nama',], 'string'],
            [['kode',], 'unique',],
            [['nama',], 'unique',],
            [['kode', 'nama',], 'filter','filter'=>'trim'],
        ];
    }

    public function attributeLabels(){
        return [
            'kode' => 'Kode',
            'nama' => 'Matakuliah',
            'sks' => 'Sks',
        ];
    }
}
