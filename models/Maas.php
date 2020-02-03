<?php
namespace app\models;
use Yii;

class Maas extends \yii\base\Model {
	public $dt1;
	public $dt2;
	public $Total;

	public function rules(){
		return [
			[['dt1','dt2'],'required']
		];
	}

	public function attributeLabels(){
		return [
			'dt1'=>'Tanggal Awal',
			'dt2'=>'Tanggal Akhir'
		];
	}
} 
?>