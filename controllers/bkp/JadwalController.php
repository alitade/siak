<?php

namespace app\controllers;

use Yii;
use app\models\Jadwal;
use app\models\BobotNilai;
use app\models\Funct;
use app\models\Akses;
use app\models\JadwalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use yii\filters\VerbFilter;
use \mPdf;
use kartik\mpdf\Pdf;

/**
 * JadwalController implements the CRUD actions for Jadwal model.
 */
class JadwalController extends Controller{
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    #'delete' => ['post'],
                ],
            ],
        ];
    }
    public function sub(){return Akses::akses();}
    public function actionExpAbs(){return true;}

    public function actionIndex(){
		$subAkses=self::sub();
		if(Akses::acc('MasterJadwal')){$subAkses="";}
        if(!Akses::acc('/jadwal/index')){throw new ForbiddenHttpException("Forbidden access");}
				
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),($subAkses? ['kl.jr_id'=>$subAkses['jurusan']]:""));		
				
		
        $_SESSION['query_jadwal'] = Yii::$app->request->getQueryParams();
		if($_GET['c']==1){
			if($_GET['JadwalSearch']['kr_kode']!=''){
				$kr=(int)$_GET['JadwalSearch']['kr_kode'];
				$ModKur = \app\models\Kurikulum::find()->where(['kr_kode'=>$kr])->one();
				if($_GET['JadwalSearch']['kr_kode']!=''){
					$jr=(int)$_GET['JadwalSearch']['jr_id'];
					$ModJr = \app\models\Jurusan::find()->where(['jr_id'=>$jr])->one();
				}
			}
	        $this->layout = 'blank';
			$content = $this->renderPartial('/jadwal/jdw_pdf',[
				'dataProvider' => $dataProvider,
			]);

			$pdf = new Pdf([
				'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
				'content' => $content,
				'format'=>Pdf::FORMAT_LETTER,
				'marginLeft'=>5,
				'marginRight'=>5,
				'marginTop'=>20,
				'orientation'=>'L',
				'destination'=>'D',
				//'watermarkText'=>'asd',
				//'cssFile'=>Url::to('@web/css/kv-grid.css'),
				'cssInline'=>"
					a{
						TEXT-DECORATION:none;
					}
					
				",
				'filename'=>'JadwalPerkuliahan-'.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'All').'-'.$ModKur->kr_kode.'-'.date('YmdHis').'.pdf',
				'options' => [
					'title' => 'Jadwal Perkuliahan '.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'Semua Jurusan').' Tahun Akademik '.$ModKur->kr_kode.'/'.$ModKur->kr_nama,
					'subject' => 'Jadwal Perkuliahan '.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'Semua Jurusan').' Tahun Akademik '.$ModKur->kr_kode.'/'.$ModKur->kr_nama,
					'watermarkText'=>'DIREKTORAT SISTEM INFORMASI & MULTIMEDIA',
					'showWatermarkText'=>true,
					
				],
				'methods' => [
					'SetHeader' => ['DIREKTORAT SISTEM INFORMASI & MULTIMEDIA<br />Jadwal Perkuliahan '.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'Semua Jurusan').' Tahun Akademik '.$ModKur->kr_kode.'/'.$ModKur->kr_nama.'||' . date("r")],
					'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'||Page {PAGENO}'],
					//'SetWatermakText' =>"asd",
					//'ShowWatermarkText'=>true,
				]
			]);
			
			return $pdf->render();
		}

        return $this->render('/jadwal/jdw_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
			'subAkses'=>$subAkses,
        ]);
    }

    public function actionIndex1(){
		
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams());
        $_SESSION['query_jadwal'] = Yii::$app->request->getQueryParams();
        return $this->render('jdw_index1', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionDetail($id)
    {
        $model= Jadwal::findOne($id);
		$searchModel = new KrsSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),['jdwl_id'=>$id]);
        return $this->render('jdw_detail',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model' => $model,
        ]);
    }

    public function actionUts()
    {
		if(isset($_POST['kalender'])){
			Yii::$app->session->open();
			if(!empty($_POST['kalender']['tahun'])){
				$_SESSION['Ckln']=(int)$_POST['kalender']['tahun'];
			}else {unset($_SESSION['Ckln']);}
			//return $this->redirect(['jdw']);
		}

        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),'(jdwl_uts is not null)',["jdwl_uts"=>SORT_ASC]);

        return $this->render('jdw_uts', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionUas()
    {
		if(isset($_POST['kalender'])){
			Yii::$app->session->open();
			if(!empty($_POST['kalender']['tahun'])){
				$_SESSION['Ckln']=(int)$_POST['kalender']['tahun'];
			}else {unset($_SESSION['Ckln']);}
			//return $this->redirect(['jdw']);
		}

        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),'(jdwl_uas is not null)',["jdwl_uas"=>SORT_ASC]);

        return $this->render('jdw_uas', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id,$s=''){
        $model 	=  Jadwal::findOne($id);
        $modGab	=  Jadwal::find()
        ->select(['tbl_jadwal.*'])
        ->innerJoin("tbl_krs krs","(krs.jdwl_id=tbl_jadwal.jdwl_id and isnull(krs.RStat,0)=0)")
        ->where(['isnull(tbl_jadwal.Gkode,tbl_jadwal.jdwl_id)'=>$model->GKode])->andWhere("tbl_jadwal.jdwl_id!=$model->jdwl_id")
        ->all();
		$Detail="exec dbo.detailPerkuiahan $model->jdwl_id";
        $Detail=Yii::$app->db->createCommand($Detail)->QueryAll();
		
		$qCek="select DISTINCT sesi from m_transaksi_finger WHERE jdwl_id=$model->jdwl_id  and isnull(RStat,0)=0";
		$qCek=Yii::$app->db->createCommand($qCek)->QueryAll();
		$cSesi=[];
		$exMtf="";
		foreach($qCek as $d){$cSesi[$d['sesi']]=$d['sesi'];}
		foreach($Detail as $d){
			if($s&&$s==$d['sesi']){
				if(!isset($cSesi[$d['sesi']])&&$d['sesi']<=max($cSesi)&& Funct::acc('/jadwal/exp-abs')){
					$exMtf="EXEC tambahMtfManual 0, '$d[tgl]',$d[sesi] ,$model->jdwl_id";
					Yii::$app->db->createCommand($exMtf)->execute();
					$this->redirect(['/jadwal/view','id'=>$model->jdwl_id]);
				}
			}
		}
		
		
		return $this->render('jdw_view', [
			'model' => $model,
			'modGb' => $modGab,
			'Detail'=>$Detail,
			'SESI'=>$cSesi,
		]);

        /*if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	return $this->redirect(['jdw-view', 'id' => $model->jdwl_id]);
        }else{
		*/
        	return $this->render('jdw_view', ['model' => $model]);
		//}
    }

    public function actionBobot($id){
        $Jadwal = Jadwal::findOne($id);
        $subAkses=self::sub();
        if($subAkses){
            if(!isset(array_flip($subAkses['jurusan'])[$Jadwal->bn->kln->jr_id])){
                throw new ForbiddenHttpException("Forbidden access");
            }
        }

        if($Jadwal->jdwl_id){
            $model=BobotNilai::findOne($Jadwal->bn_id);
            if(isset($_POST['dev'])){
                $model->nb_tgs1     =   10;
                $model->nb_tgs2     =   10;
                $model->nb_tgs3     =   0;
                $model->nb_uts      =   40;
                $model->nb_uas      =   40;
                $model->nb_quis     =   0;
                $model->nb_tambahan =   0;
                $model->B           =   80.99;
                $model->C           =   70.99;
                $model->D           =   59.99;
                $model->E           =   34.99;
                $model->save(false);
                return $this->redirect(['/pengajar/nilait','id'=>$id]);
            }else{
                if ($model->load(Yii::$app->request->post())){
                    $model->save(false);
                    return $this->redirect(['/pengajar/nilait','id'=>$id]);
                }
            }
        }
        return $this->render('/jadwal/_bobot',[
            'model'=>$model,
            'jadwal'=>$Jadwal,
        ]);

    }



    public function actionCreate($id){
        $model = Jadwal::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//\app\models\Funct::LOGS("Menambah Data Jadwal ($model->ds_id) ",new Dosen,$id,'u');
            return $this->redirect(['jdw-view', 'id' => $model->jdwl_id]);
        } else {
            return $this->render('jdw_create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id){
        $model =  Jadwal::findOne($id);
        $model2 =  Jadwal::findOne($id);
        if ($model->load(Yii::$app->request->post())){
			//die($model->jdwl_uts." ".$_POST['Jadwal']['jdwl_uts']);
			
			if(	$model2->bn_id==$model->bn_id && 
				$model2->jdwl_hari==$model->jdwl_hari && 
				$model2->jdwl_masuk==$model->jdwl_masuk && 
				$model2->jdwl_keluar==$model->jdwl_keluar && 
				$model2->rg_kode==$model->rg_kode){
					
				\app\models\Funct::LOGS("Mengubah Data Jadwal ($id) ",new Jadwal,$id,'u');
				$model->save(false);
				/*
				if($model->save(false)){
					$sql="exec dbo.P_updateTransFinger $model->jdwl_id,'$model->jdwl_masuk','$model->jdwl_keluar";
					Yii::$app->db->createCommand($sql)->execute();
				}
				*/
			}else{				
				\app\models\Funct::LOGS("Mengubah Data Jadwal ($id) ",new Jadwal,$id,'u');
				if($model->save(false)){
					$sql="exec dbo.P_updateTransFinger $model->jdwl_id,'$model->jdwl_masuk','$model->jdwl_keluar";
					Yii::$app->db->createCommand($sql)->execute();
				}
			}
				return $this->redirect(['jdw-view', 'id' => $model->jdwl_id]);
        } else {
            return $this->render('jdw_update', [
                'model' => $model,
            ]);
        }
    }

	public function actionGabUpdate($id){
		return \app\controllers\_Jadwal::Jadwal($id);
	}


    public function actionDelete($id){
        $model=Jadwal::findOne($id);
		$model->RStat='1';
		$model->save(false);
		\app\models\Funct::LOGS("Menghaous Data Jadwal ($id) ",new Jadwal,$id,'d');
		// tambahan
		$sql="delete from transaksi_finger where jdwl_id=$model->jdwl_id";
		Yii::$app->db->createCommand($sql)->execute();
        return $this->redirect(Yii::$app->request->referrer);
    }


    protected function findModel($id){
        if (($model = Jadwal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


	public function actionJadwalMaster(){
		return true;	
	
	}


}
