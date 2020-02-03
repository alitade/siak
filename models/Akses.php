<?php

namespace app\models;
use yii\helpers\ArrayHelper;
use Yii;

class Akses {
	public function akses(){
		$akses = Yii::$app->user->identity->aksesDet;
		$arr=[];
		foreach($akses as $d){
			if(empty(array_search($d->akses->nilai,$arr[$d->akses->tbl])) ){	
				$arr[$d->akses->tbl][]=$d->akses->nilai;
			}			
			
			foreach($d->akses->child as $d1){
				if(empty(array_search($d1->nilai,$arr[$d1->tbl])) ){	
					$arr[$d1->tbl][]=$d1->nilai;
				}				
			}
		}
		return $arr;
	}

	public function acc($akses){
		#akses data jurusan
		if(Funct::acc('SubAkses')){
			if(Akses::akses()){return Funct::acc($akses);}
			return false;
		}
		return Funct::acc($akses);
	}


}


