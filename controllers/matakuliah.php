<?php
namespace app\controllers;
use yii\web\Session;
use yii\data\ActiveDataProvider;
use Yii;

use app\models\GroupMatkul;
use app\models\GroupMatkulSearch;
use app\models\Matkul;
use app\models\MatkulSearch;

use app\models\Akses;

use \mPdf;
use kartik\mpdf\Pdf;
use yii\helpers\Url;


use app\models\Funct;
use yii\helpers\ArrayHelper;


use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

use yii\db\Query;
use yii\data\ArrayDataProvider;

$connection = \Yii::$app->db;


class matakuliah {
	/* function global */
	public function GMTK($tipe=1,$kon=''){
		$mod=GroupMatkul::find()->all();
		if($kon!=''){
			$mod=GroupMatkul::find()->where($kon)->all();
		}		
		$Var = ArrayHelper::map($mod,'kode',
			function($model,$defaultValue){return @$model->kode." : ".$model->nama;}		
		);
		
		if($tipe==2){
			$var=[];foreach($Var as $k=>$v){$var[]=$k;}$Var=$var;
		}else if($tipe==3){
			$Var = ArrayHelper::map($mod,'kode',
				function($model,$defaultValue){
						return "(".$model->kode.") : ".@$model->nama;
				}		
			);
		}else if($tipe==3){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
		return $Var;
	}	
	/**/
	
	
	#group matakuliah
	public function gpmk(){
		$data=[];
        $searchModel = new GroupMatkulSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		];
	}

	public function gmkadd(){
        $model = new GroupMatkul;
        if ($model->load(Yii::$app->request->post()) ){ 
			if( $model->save()) {
				return $this->redirect(['gmtk-view', 'id' => $model->kode]);
			}			
        }
        return $this->render('gmtk/create',['model'=>$model]);
	
	}

	public function gmkview($id){

        $model = GroupMatkul::find()->where(['kode'=>$id])->one();
		//$ModDet= 
		//\app\models\Funct::LOGS('Mengakses Halaman Detail Matakuliah ('."$model->mtk_kode : $model->mtk_nama)",new Matkul(),$id,'r');
        return $this->render('gmtk/view', ['model' => $model]);

	
	}

	#end group matakuliah
	
	public function akademikmatakuliah(){
        $searchModel = new MatkulSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);		
		$subAkses = Akses::akses();
		if($subAkses){var_dump($subAkses);}
		
		if($_GET['c']==1){
			$tahun="";$jurusan="";$program="";
			if($_GET['MatkulSearch']['jr_id']!=''){
				$jr=(int)$_GET['MatkulSearch']['jr_id'];
				$ModJr = \app\models\Jurusan::find()->where(['jr_id'=>$jr])->one();

			}
	        $this->layout = 'blank';
			$content = $this->renderPartial('mtk_pdf',[
				'dataProvider' => $dataProvider,
			]);

			$pdf = new Pdf([
				'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
				'content' => $content,
				'format'=>Pdf::FORMAT_LETTER,
				'marginLeft'=>5,
				'marginRight'=>5,
				'marginTop'=>20,
				'orientation'=>'P',
				'destination'=>'I',
				'cssInline'=>'
					table{overlow:warp;font-size:12px;}
				',
				'filename'=>'Matakuliah -'.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'All').'-'.date('YmdHis').'.pdf',
				'options' => [
					'title' => 'Matakuiah '.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'Semua Jurusan'),
					'subject' => 'Matakulah '.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'Semua Jurusan'),
					'watermarkText'=>"DIREKTORAT SISTEM INFORMASI & MULTIMEDIA;",
					'showWatermarkText'=>true,
				],
				'methods' => [
					'SetHeader' => ['DIREKTORAT SISTEM INFORMASI & MULTIMEDIA<br /> Matakuliah '.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'Semua Jurusan').'||' . date("r")."<br />Page {PAGENO}"],
					'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'||Page {PAGENO}'],
				]
			]);			
			return $pdf->render();
		}
		return [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		];

	}

	public function creatematakuliah(){
        $model = new Matkul;
        if ($model->load(Yii::$app->request->post()) ){ 
			$model->mtk_stat='1';
			if( $model->save()) {
				\app\models\Funct::LOGS('Manambah Data Matakuliah ('."$model->mtk_kode : $model->mtk_nama)",$model,$model->mtk_kode,'c');
				return $this->redirect(['mtk-view', 'id' => $model->mtk_kode]);
			}
			
        }
		return $model;
		 	
	}

}
