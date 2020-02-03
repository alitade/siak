<?php
namespace app\models;
use Yii;

class Keuangan extends \yii\base\Model{
	public $searchString;

	public function rules(){
		return [
			[['searchString'],'required']
		];
	}

	public function attributeLabels(){
		return [
			'searchString'=>'Pencarian',
		];
	}
}
?>