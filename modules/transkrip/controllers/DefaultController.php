<?php

namespace app\modules\transkrip\controllers;
use Yii;

use app\models\Jadwal;
use app\models\JadwalSearch;


use yii\filters\AccessControl;

use yii\web\Controller;

class DefaultController extends ModController{
	public function actionTes(){
		print_r(parent::Akses('128'));
	}

    public function actionIndex(){
        return $this->redirect(['nilai/mhs']);
    }

    public function actionLock($k){
		$Kode=\app\models\Funct::StatLock($k);
		if($Kode){print_r($Kode);}
    }

    public function actionJdw(){
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams());
        $_SESSION['query_jadwal'] = Yii::$app->request->getQueryParams();
        return $this->render('jdw_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }




}
