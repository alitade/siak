<?php

namespace app\controllers;
use Yii;
use yii\web\Session;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\data\ArrayDataProvider;

use yii\helpers\Url;
use yii\helpers\Json;

use \mPdf;

use kartik\mpdf\Pdf;
use app\models\Funct;
use app\models\Akses;
use app\models\Transaksi;
use app\models\TransaksiSearch;




$connection = \Yii::$app->db;
/**/
use yii\app\controllers\AkademikGroupMk;
/**/

class TransaksiController extends Controller{
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    public function sub(){return Akses::akses();}
	
	public function actionIndex(){
        $searchModel = new TransaksiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('/transaksi/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
		
	}

	
}
