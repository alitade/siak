<?php
namespace app\components;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class vdComponent extends Component{
	public function vd(){
		$sql="select kd,nil from aturan WHERE parent is null and aktif=1";
		$sql =Yii::$app->db->createCommand($sql)->queryAll();
		$arr=[];
		foreach ($sql as $d){
			$arr[$d['kd']]=$d['nil'];
		}
		return $arr;
	}


}

?>
