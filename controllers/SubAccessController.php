<?php

namespace app\controllers;

use app\models\KrsSearch;
use app\models\People;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\Url;

use app\models\Mahasiswa;
use app\models\KPembayarankrs;
use app\models\MahasiswaSearch;
use app\models\Funct;

use app\controllers\CDbCriteria;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use doamigos\qrcode\formats\MailTo;
use kartik\mpdf\Pdf;


/**
 * MahasiswaController implements the CRUD actions for Mahasiswa model.
 */
class SubAccessController extends Controller{
    public  function  actionSubAkses(){
        return true;

    }


}
