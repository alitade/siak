<?php

namespace app\modules\transkrip\controllers;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use yii\web\Controller;

class ModController extends Controller
{
	
	protected function StatAkses($kode){
		$Lock=[	
				'1'=>'1',
				'10'=>'2',
				'100'=>'4',
				'1000'=>'8',
				'10000'=>'16',
				'100000'=>'32',
				'1000000'=>'64',
		];
		$kode	= decbin((int)$kode);
		$Arr="";
		foreach(str_split($kode) as $k=>$v){
			$n++;
			if($v!='0'){$Arr[$v.str_repeat('0',strlen($kode)-$n)]=$Lock[$v.str_repeat('0',strlen($kode)-$n)];}
		}
		if($Arr!=''){
			return $Arr;
		}
		return false;
	}


	protected function Akses($kode=0){
		$kode	= decbin((int)$kode);
		$model = \app\modules\transkrip\models\Ip::find()->All();
		$arr="";
		foreach($model as $data){
			if( array_key_exists($kode,self::StatAkses($data['Akses'])) ){$arr[]=$data['IP'];}
		}		
		if(is_array($arr)){return $arr;}
		return false;
	}	


    public function behaviors(){
		
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],

            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
						'allow'=>true,
						//'ips'=>['192.168.11.*'],//self::Akses('64')
						'ips'=>self::Akses('64')
                    ],
                ],
            ],
        ];
    }
}
