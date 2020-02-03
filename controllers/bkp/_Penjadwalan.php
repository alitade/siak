<?php

namespace app\controllers;
use Yii;

use app\models\Funct;

use app\models\Jadwal;
use app\models\JadwalSearch;

use app\models\BobotNilai;
use app\models\BobotNilaiSearch;


use \mPdf;
use kartik\mpdf\Pdf;
use yii\helpers\Url;


use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Session;
use yii\filters\VerbFilter;
use yii\helpers\Json;

use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

$connection = \Yii::$app->db;
/**/
class _Penjadwalan extends Controller{
	
	// Akademik
	public function index(){
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams());
        return $this->render('penjadwalan/index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

	}
	
	public function create($k){
        $model 		= new Jadwal;
		$modelBn 	= new BobotNilai;

        if ($model->load(Yii::$app->request->post())) {
			//\app\models\Funct::LOGS("Menambah Data Jadwal ($model->ds_id) ",new Dosen,$id,'u');
            //return $this->redirect(['jdw-view', 'id' => $model->jdwl_id]);
        } 
		
		return $this->render('penjadwalan/create', [
			'model' => $model,
			'modelBn' => $modelBn,
		]);
	}
	
	public function pengajar(){
		
	
	}
}
