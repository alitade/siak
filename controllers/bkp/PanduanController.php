<?php

namespace app\controllers;

use app\models\Akses;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MahasiswaController implements the CRUD actions for Mahasiswa model.
 */
class PanduanController extends Controller{
	
	public function  actionJurusan(){return $this->redirect(Url::to("@web/panduan_prodi.pdf"));}
	public function  actionDosen(){return $this->redirect(Url::to("@web/panduan_dosen.pdf"));}
	public function  actionMahasiswa(){return $this->redirect(Url::to("@web/panduan_mahasiswa.pdf"));}

}
