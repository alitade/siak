<?php

namespace app\controllers;
use  yii\web\Session;
use yii\data\ActiveDataProvider;
use Yii;

use app\models\Fakultas;
use app\models\FakultasSearch;

use app\models\Matkul;
use app\models\MatkulSearch;
use app\models\Gedung;

use \mPdf;
use kartik\mpdf\Pdf;
use yii\helpers\Url;

use app\models\Kurikulum;
use app\models\KurikulumSearch;


use app\models\Kalender;
use app\models\KalenderSearch;

use app\models\Jadwal;
use app\models\JadwalSearch;

use app\models\Krs;
use app\models\KrsSearch;

use app\models\BobotNilai;
use app\models\BobotNilaiSearch;

use app\models\Mahasiswa;
use app\models\MahasiswaSearch;

use app\models\Dosen;
use app\models\DosenSearch;

use app\models\Wali;
use app\models\WaliSearch;


use app\models\KPembayarankrs;

use app\models\Funct;

use app\models\M119Search;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

use yii\db\Query;
use yii\data\ArrayDataProvider;

$connection = \Yii::$app->db;
/**/
use yii\app\controllers\AkademikGroupMk;
/**/

class C1Controller extends Controller
{
    public function actionD119(){
        $searchModel = new M119Search;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ];

//        return $this->render('index'));
        #return GedungController::actionIndex();
    }

    public function actionD113(){
        $searchModel = new \app\models\M113Search;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ];

        return $this->render('index');
    }
    
    // public function actionDetail($id)
    // {
    //     if (($model = Gedung::findOne($id)) !== null) {
    //         return $model;
    //     } else {
    //         throw new NotFoundHttpException('The requested page does not exist.');
    //     }
    //     $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams($id));
    //     return $this->render('view', [
    //         'model' => $model,
    //         'dataProvider' => $dataProvider,
            
    //     ]);
    // }
    
}
