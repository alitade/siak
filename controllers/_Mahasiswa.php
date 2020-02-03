<?php

# only view action
namespace app\controllers;
use  yii\web\Session;
use yii\data\ActiveDataProvider;
use Yii;

use app\models\Ruang;

use kartik\mpdf\Pdf;


use app\models\Funct;

use app\models\People;
use app\models\Mahasiswa;
use app\models\MahasiswaSearch;
use app\models\Jadwal;
use app\models\JadwalSearch;
use app\models\KrsSearch;


use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\db\Query;
use yii\data\ArrayDataProvider;

use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\Html;


$connection = \Yii::$app->db;


class _Mahasiswa extends Controller{
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

	public function update($id){
		
		$r  	= explode("/",$this->getRoute());
		$con	= $r[0];
		$act	= $r[1];
        $model 		=  Mahasiswa::findOne($id);
		$noReg 		= $model->mhs->people->No_Registrasi;
		$modPeople	= People::findOne($noReg);

		$db = Yii::$app->db1;
		$keuangan = Funct::getDsnAttribute('dbname', $db->dsn);
		if(!$keuangan){$keuangan = Funct::getDsnAttribute('Database', $db->dsn);}
		$sql="insert into $keuangan.dbo.logg(user_id,ipaddress,logtime,controller,action,detail)
		values('".Yii::$app->user->identity->username."','".$_SERVER['REMOTE_ADDR']."',getdate(),'$con','$act','Mengubah Data people')
		";
		if(strripos($modPeople->alamat,'||')){
			$alamat 	= explode("||",$modPeople->alamat);
			$modPeople->jln =@$alamat[0];
			$modPeople->rt =@$alamat[1];
			$modPeople->rw =@$alamat[2];
			$modPeople->dsn =@$alamat[3];
			$modPeople->kel =@$alamat[4];
			$modPeople->kec =@$alamat[5];
		}
		
		if ($modPeople->load(Yii::$app->request->post())) {
			if($modPeople->rt && $modPeople->rw && $modPeople->dsn && $modPeople->kel && $modPeople->kec && $modPeople->jln){
				$modPeople->alamat = $modPeople->jln."||".$modPeople->rt."||".$modPeople->rw."||".$modPeople->dsn."||".$modPeople->kel."||".$modPeople->kec;
			}	
			
			if($modPeople->save()){
				Yii::$app->db->createCommand($sql)->execute();
				return $this->redirect(['mhs-view', 'id' => $model->mhs_nim]);
			}
            //return $this->redirect(['mhs-view', 'id' => $model->mhs_nim]);
        }
		
		return $this->render('mhs_people',[
			'modPeople'=>$modPeople,
			'model'=>$model,
		]);
		
	}
	 


}
