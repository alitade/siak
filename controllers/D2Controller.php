<?php

namespace app\controllers;
use  yii\web\Session;
use yii\data\ActiveDataProvider;
use Yii;

use \mPdf;
use kartik\mpdf\Pdf;
use yii\helpers\Url;
use app\models\Funct;
use app\models\Akses;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use yii\filters\VerbFilter;
use yii\helpers\Json;

use yii\db\Query;
use yii\data\ArrayDataProvider;

$connection = \Yii::$app->db;
/**/
use yii\app\controllers\AkademikGroupMk;
/**/

class D2Controller extends Controller{


    #Data Gedung
    public function actionGedung(){return GedungController::actionIndex();}
    public function actionGedungView($id){return GedungController::actionView($id);}
    public function actionGedungUpdate($id){return GedungController::actionUpdate($id);}
    public function actionGedungDelete($id){return GedungController::actionDelete($id);}





}
