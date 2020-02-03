<?php
namespace app\controllers;
use Yii;
use app\models\Akses;
use app\models\Matkul;
use app\models\MatkulSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

class MatkulController extends Controller{
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                  //  'delete' => ['post'],
                ],
            ],
        ];
    }


    public function sub(){return Akses::akses();}


    public function actionIndex(){
        if(!Akses::acc('/matkul/index')){throw new ForbiddenHttpException("Forbidden access");}

		$subAkses = Akses::akses();
        $searchModel = new MatkulSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,($subAkses?['jr_id'=>$subAkses['jurusan']]:""));
				
		if($_GET['c']==1){
			$tahun="";$jurusan="";$program="";
			if($_GET['MatkulSearch']['jr_id']!=''){
				$jr=(int)$_GET['MatkulSearch']['jr_id'];
				$ModJr = \app\models\Jurusan::find()->where(['jr_id'=>$jr])->one();

			}
	        $this->layout = 'blank';
			$content = $this->renderPartial('/matkul/mtk_pdf',[
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

        return $this->render('/matkul/mtk_index',[
			'subAkses'=>$subAkses,
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
		
    }

	public function actionCreate(){
		$subAkses = Akses::akses();
        $model = new Matkul;
        if ($model->load(Yii::$app->request->post()) ){
            $model->mtk_stat='1';
            if( $model->save()) {
                \app\models\Funct::LOGS('Manambah Data Matakuliah ('."$model->mtk_kode : $model->mtk_nama)",$model,$model->mtk_kode,'c');
                return $this->redirect(['/matkul/view', 'id' => $model->mtk_kode]);
            }else{\app\models\Funct::v($model->getErrors());}
        }
		return $this->render('/matkul/mtk_create', ['model' => $model,'subAkses'=>$subAkses]);		
	}

	public function actionUpdate($id){
        $model=$this->findModel($id);
        $KdMk=$model->mtk_kode;
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $KdMk_=$model->mtk_kode;
			\app\models\Funct::LOGS('Mengubah Data Matakuliah ('."$model->mtk_kode)",$model,$model->mtk_kode,'u');
            $que="update tbl_bobot_nilai set mtk_kode='$KdMk_' where mtk_kode='$KdMk'";
            $db = Yii::$app->db->createCommand($que)->execute();
            return $this->redirect(['/matkul/view', 'id' => $model->mtk_kode]);
        } 
		
		return $this->render('/matkul/mtk_update', [
			'model' => $model,
			'subAkses'=>self::sub()
		]);
	
	}

    public function actionView($id){
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['/matkul/view', 'id' => $model->mtk_kode]);
        } else {
			\app\models\Funct::LOGS('Mengakses Halaman Detail Matakuliah ('."$model->mtk_kode : $model->mtk_nama)",new Matkul(),$id,'r');
        	return $this->render('/matkul/mtk_view', [
				'model' => $model,
				'subAkses'=>self::sub()
			]);
		}
    }

    public function actionDelete($id){
        $model=$this->findModel($id);
		$model->RStat=1;
		if($model->save()){
			\app\models\Funct::LOGS('Menghapus Data Matakuliah('."$id)",new Matkul(),$id,'d');			
		}
        return $this->redirect(['/matkul/index']);
    }
	
    protected function findModel($id){
        if (($model = Matkul::findOne($id)) !== null) {
            return $model;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
