<?php

namespace app\controllers;
use  yii\web\Session;
use yii\data\ActiveDataProvider;
use Yii;

use app\models\Fakultas;
use app\models\FakultasSearch;

use app\models\Matkul;
use app\models\MatkulSearch;

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

class C2Controller extends Controller
{
    public function D119(){
        $model = \app\models\M119::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$model->uuid	= Yii::$app->user->identity->id;
			$model->utgl 	= new  Expression("getdate()");
			if($model->save()){
				\app\models\Funct::LOGS("Mengubah Data Gedung($id) ",new Gedung,$id,'u');
				return $this->redirect(['view', 'id' => $model->Id]);
			}
        }
		return $this->render('update', [
			'model' => $model,
		]);
    }

    public function actionD113($id){
        $model = \app\models\M113::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->rg_kode]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
}

