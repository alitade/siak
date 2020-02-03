<?php

namespace app\models;

use Yii;

class SubAkses extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName(){return 'sub_akses';}

    public function rules(){
        return [
            [['kode','tbl','nilai'], 'required'],
            [['kode','tbl','parent'], 'string', 'max'=>'125'],
            [['nilai'], 'string', 'max'=>'255'],            
        ];
    }
	
    public function attributeLabels(){
        return [
            'kode' => 'Kode',
            'tbl' => 'Tabel',
            'nilai' => 'Nilai',
        ];
    }

    public function getDet(){return $this->hasMany(SubAksesDet::className(),['kode'=>'kode']);}
    public function getChild(){return $this->hasMany(SubAkses::className(),['parent'=>'kode']);}
    //public function getParent(){return $this->hasMany(SubAkses::className(),['kode'=>'parent']);}
	
	
	
}
