<?php

namespace app\controllers;

use Yii;
use app\models\Akses;
use app\models\Funct;

use yii\data\SqlDataProvider; 
use app\controllers\CDbCriteria;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MahasiswaController implements the CRUD actions for Mahasiswa model.
 */
class SController extends Controller{
	public function actionIndex(){
        throw new ForbiddenHttpException("Forbidden access");

        print_r(Akses::acc('/mhs/index'));
		echo "</pre>";	

	}
	

}
