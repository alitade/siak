<?php

namespace app\models;

use Yii;

class SubAksesDet extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName(){return 'sub_akses_det';}

    public function rules(){
        return [
            [['user_id','kode'], 'required'],
            [['kode',], 'string', 'max'=>'125'],
            [['user_id'], 'int',],
        ];
    }

/*	
    public function attributeLabels(){
        return [
            'kode' => 'Kode',
            'tbl' => 'Tabel',
            'nilai' => 'Nilai',
        ];
    }
*/
	public function getAkses(){return $this->hasOne(SubAkses::className(),['kode'=>'kode']);}
	public function getUser(){return $this->hasMany(User::className(),['id'=>'user_id']);}

}
