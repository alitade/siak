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

class DiritController extends Controller{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    public function sub(){return Akses::akses();}

	#penjadwalan
	public function actionJdw(){return Perkuliahan::index();}	
	public function actionJdwView($id){return Perkuliahan::peserta($id);}	
	#End Penjadwalan

	#perkuliahan
	public function actionSiap(){
		if(!\app\models\Funct::SIAP()){
			$q = " exec dbo.persiapanperkuliahan";
			$q =Yii::$app->db->createCommand($q)->execute();
		}
		return $this->redirect(['rekap-absen/kuliah']);
	}	

	public function actionTanggalKuliah($id=''){
		$kr='';		
		if(isset($_GET['kr']['kr'])){$kr=$_GET['kr']['kr'];}
		return Perkuliahan::TanggalKuliah($kr,$id);
	}	


	public function actionAbsenPdf($id,$jenis=1){return Perkuliahan::absenharianpdf($id,$jenis);}	
	public function actionAbsenDaruratPdf($id,$jenis=1){return Perkuliahan::absenhariandarurat($id,$jenis);}	
	public function actionCekPergantian($id){return Perkuliahan::cekpergantian($id);}	
	public function actionPesertaKuliah($id,$m){return Perkuliahan::PesertaKuliah($id,$m);}	
	public function actionPergantian($id){return Perkuliahan::pergantian($id);}	
	public function actionPergantianDel($id,$s,$d){return Perkuliahan::pergantiandel($id,$s,$d);}		
	public function actionJadwalPergantian(){return Perkuliahan::daftarPergantian();}		
	public function actionResend(){return Perkuliahan::resendPergantian();}		

	public function actionPerkuliahan(){return Perkuliahan::masterperkuliahan();}
	public function actionDetailPerkuliahan($id,$s,$u=0){return Perkuliahan::dmasterperkuliahan($id,$s,$u);}

	public function actionKehadiran(){
		if(!Akses::acc('/dirit/kehadiran')){throw new ForbiddenHttpException("Forbidden access");}		
		return Perkuliahan::kehadiranDosen();
	}

	#== Kelola Kehadiran Perkuliahan
	public function actionIMasuk($id,$m){return Perkuliahan::iMasuk($id,$m);}
	public function actionIKeluar($id,$m){return Perkuliahan::iKeluar($id,$m);}
	public function actionFixAbsen($id,$m){Perkuliahan::fixAbsen($id,$m);}
	public function actionAccAbsen($id,$m){Perkuliahan::accAbsen($id,$m);}
	#==	
    public function actionCetakKehadiranDosen($kr,$t='',$jr){return Perkuliahan::cetakKehadiranDosen($kr,$t,$jr);}

	
	

	#end perkuliahan
	/**/
	#Mahasiswa
	public function actionMhs(){return \app\controllers\mhs::mhs();}	
	/**/

	/*Matakuliah*/
	
	
	#group matakuliah
	public function actionGmtk(){
		$data = new \app\controllers\matakuliah;
        return $this->render('gmtk/index',$data->gmk());
	}

	public function actionGmtkAdd(){
		return \app\controllers\matakuliah::gmkadd();
	}

    public function actionGmtkView($id){
		return \app\controllers\matakuliah::gmkview($id);
    }

	#end group matakuliah

	public function actionCek(){

	
        $searchModel = new \app\models\MasterSearch;
		$_GET['MasterSearch']['kr_kode']="21617";
		$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),' as ',$krkd." adasd asdasda");
		print_r($dataProvider->getModels());
		die("asd");
		//$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),'','1=0');
		if( isset($_GET['MasterSearch']['kr_kode']) && !empty($_GET['MasterSearch']['kr_kode'])){
			$krkd=(int)$_GET['MasterSearch']['kr_kode'];
			$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),$krkd." asdddasd asdasd");
		}
        
        return $this->render('master_perkuliahan', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
		
		
	}

	
	
}
