<?php

namespace app\controllers;

use Yii;
use app\models\Akses;
use app\models\Kalender;
use app\models\KalenderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use yii\filters\VerbFilter;

/**
 * KalenderController implements the CRUD actions for Kalender model.
 */
class KalenderController extends Controller{
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function sub(){return Akses::akses();}

    public function actionIndex(){
		if(!Akses::acc('/kalender/index')){throw new ForbiddenHttpException("Forbidden access");}				

        $searchModel = new KalenderSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,(self::sub()?['jr_id'=>self::sub()['jurusan']]:""));
		//\app\models\Funct::LOGS('Mengakses ('."$id)",new Kalender(),$id,'d');
        return $this->render('/kalender/kln_index',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
			'subAkses'=>self::sub(),
        ]);
    }

    public function actionView($id){
        $model = $this->findModel($id);
		if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
			\app\models\Funct::LOGS('Mengubah Data Kalender Akademik',new Kalender(),$id,'r');			
			return $this->redirect(['/kalender/view', 'id' => $model->kln_id]);
		}else{
			//\app\models\Funct::LOGS('Mengakses Halaman Detail Kalender Akademik',new Kalender(),$id,'r');			
			return $this->render('/kalender/kln_view', ['model' => $model]);	
		}
    }

    public function actionCreate(){
        $model = new Kalender;
		$ok=0;
        if ($model->load(Yii::$app->request->post())) {
			$JR =$model->jr_id;
			foreach($JR as $k=>$v){
				$model2 = new Kalender;
				$model2->jr_id			=$v;
				$model2->kln_stat		='1';
				$model2->pr_kode		=	$model->pr_kode;
				$model2->kln_krs		=	$model->kln_krs;
				$Dkrs 	= date_create($model->kln_krs);
				$Dkrs1 	= date_create($model->kln_krs_lama);
				$diff	= date_diff($Dkrs,$Dkrs1);
				$model2->kln_krs_lama=$diff->format("%R%a");
				
				//$model2->kln_krs_lama=$Dkrs1->format("%R%a days");
				//die($Dkrs1->format("%R%a days"));
				$model2->kln_masuk		=	$model->kln_masuk;

				$model2->kln_uts		=	$model->kln_uts;
				$Duts 	= date_create($model->kln_uts);
				$Duts1 	= date_create($model->kln_uts_lama);
				$diff	= date_diff($Duts,$Duts1);
				$model2->kln_uts_lama=$diff->format("%R%a");

				$model2->kln_uas		=	$model->kln_uas;
				$Duas 	= date_create($model->kln_uas);
				$Duas1 	= date_create($model->kln_uas_lama);
				$diff	= date_diff($Duas,$Duas1);
				$model2->kln_uas_lama = $diff->format("%R%a");

				$model2->kln_sesi		=	$model->kln_sesi;
				$model2->kr_kode		=	$model->kr_kode;
				if($model2->save(false)){
					$ok++;
					\app\models\Funct::LOGS('Menambah Data Kalender Akademik',new Kalender(),$model2->kln_id,'c');
				}else{
					//die(print_r($model2->getErrors()));
				}
			}
			if($ok>0){
				return $this->redirect(['/kalender/index']);	
			}else{die(print_r($model2->getErrors()));}            
        } 
		
		return $this->render('/kalender/kln_create', [
			'model' => $model,
			'subAkses'=>self::sub(),
		]);
	
		
		//die(print_r($model->getErrors()));
    }

    public function actionUpdate($id){
        $model=$this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
                $date1 = new \DateTime($model->kln_krs);
                $date2 = new \DateTime(Yii::$app->request->post('kln_krs_lama-kalender-kln_krs_lama'));
                $diff = $date2->diff($date1)->format("%a");
                $model->kln_krs_lama = (int) $diff;

                $date1 = new \DateTime($model->kln_uts);
                $date2 = new \DateTime(Yii::$app->request->post('kln_uts_lama-kalender-kln_uts_lama'));
                $diff = $date2->diff($date1)->format("%a");
                $model->kln_uts_lama = (int) $diff;

                $date1 = new \DateTime($model->kln_uas);
                $date2 = new \DateTime(Yii::$app->request->post('kln_uas_lama-kalender-kln_uas_lama'));
                $diff = $date2->diff($date1)->format("%a");
                $model->kln_uas_lama = (int) $diff;
					\app\models\Funct::LOGS('Mengubah Data Kalender Akademik [r]',new Kalender(),$id,'u');
				if($model->save(false)){
					\app\models\Funct::LOGS('Mengubah Data Kalender Akademik',new Kalender(),$id,'u');
				}
	            return $this->redirect(['/kalender/view', 'id' => $model->kln_id]);
        } else {
            return $this->render('/kalender/kln_update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id){
        $model=Kalender::findOne($id);
		$model->RStat=1;
		$model->save();
		\app\models\Funct::LOGS('Menghapus Data Kalender Akademik',new Kalender(),$id,'d');		
        return $this->redirect(['/kalender/index']);
    }


    protected function findModel($id){
        if (($model = Kalender::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
