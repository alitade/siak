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

class AkademikController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }


	/*Group Mk*/
	public function actionGroupMtk(){
		$data = new \app\controllers\AkademikGroupMk;
        return $this->render('mtkg_index',$data->groupmk());
	}
	/*end group mk*/
	/*percobaan*/
	public function actionMtkV2(){
        $searchModel = new MatkulSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		// \app\models\Funct::LOGS('Mengakses Halaman Matakuliah','','','r');
		
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
		
        return $this->render('mtk_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
	/*end percobaan*/

	public function actionIndex(){return $this->render('@app/views/site/index');}
	/* Kurikulum */

    public function actionKr()
    {
        $searchModel = new KurikulumSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('kr_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionKrView($id)
    {
        $model=Kurikulum::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	return $this->redirect(['kr-view', 'id' => $model->kr_kode]);
        } else {
        	return $this->render('kr_view', ['model' => $model]);
		}
    }

    public function actionKrCreate()
    {
        $model = new Kurikulum;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['kr-view', 'id' => $model->kr_kode]);
        }
		return $this->render('kr_create', [
			'model' => $model,
		]);
    }

    public function actionKrUpdate($id)
    {
        $model=Kurikulum::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['kr-view', 'id' => $model->kr_kode]);
        } 
		return $this->render('kr_update',[
			'model' => $model,
		]);
    }
    public function actionKrDelete($id){
        $model=Kurikulum::findOne($id);
		$model->RStat=1;
		$model->save();
        return $this->redirect(['kr']);
    }



	/* End Kurikulum */

	 
	/* Matakul */	
	public function actionMtk(){
        $searchModel = new MatkulSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		// \app\models\Funct::LOGS('Mengakses Halaman Matakuliah','','','r');
		
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
		
        return $this->render('mtk_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
    public function actionMtkView($id){
        $model = Matkul::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['mtk_view', 'id' => $model->mtk_kode]);
        } else {
			\app\models\Funct::LOGS('Mengakses Halaman Detail Matakuliah ('."$model->mtk_kode : $model->mtk_nama)",new Matkul(),$id,'r');
        	return $this->render('mtk_view', ['model' => $model]);
		}
    }

    public function actionMtkCreate(){
        $model = new Matkul;
        if ($model->load(Yii::$app->request->post()) ){ 
			$model->mtk_stat='1';
			$model->mtk_stat='1';
			if( $model->save()) {
				\app\models\Funct::LOGS('Manambah Data Matakuliah ('."$model->mtk_kode : $model->mtk_nama)",$model,$model->mtk_kode,'c');
				return $this->redirect(['mtk-view', 'id' => $model->mtk_kode]);
			}
			
        }// else {
            return $this->render('mtk_create', ['model' => $model,]);
        //}
    }

    public function actionMtkUpdate($id){
        $model=Matkul::findOne($id);
        $KdMk=$model->mtk_kode;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $KdMk_=$model->mtk_kode;
			\app\models\Funct::LOGS('Mengubah Data Matakuliah ('."$model->mtk_kode)",$model,$model->mtk_kode,'u');
			//echo $KdMk .' '.$KdMk_;
           
            $que="update tbl_bobot_nilai set mtk_kode='$KdMk_' where mtk_kode='$KdMk'";
            $db = Yii::$app->db->createCommand($que)->execute();
            return $this->redirect(['mtk-view', 'id' => $model->mtk_kode]);
        } else {
            return $this->render('mtk_update', [
                'model' => $model,
            ]);
        }
    }

    public function actionMtkDelete($id){
        $model=Matkul::findOne($id);
		$model->RStat=1;
		if($model->save()){
			\app\models\Funct::LOGS('Menghapus Data Matakuliah('."$id)",new Matkul(),$id,'d');			
		}
        return $this->redirect(['mtk']);
    }
	/* end Matkul */

	/* Kalender */
    public function actionKln(){
        $searchModel = new KalenderSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
		//\app\models\Funct::LOGS('Mengakses ('."$id)",new Kalender(),$id,'d');
        return $this->render('kln_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionKlnView($id){
        $model = Kalender::findOne($id);
		if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
			\app\models\Funct::LOGS('Mengubah Data Kalender Akademik',new Kalender(),$id,'r');			
			return $this->redirect(['kln-view', 'id' => $model->kln_id]);
		}else{
			\app\models\Funct::LOGS('Mengakses Halaman Detail Kalender Akademik',new Kalender(),$id,'r');			
			return $this->render('kln_view', ['model' => $model]);	
		}
    }


    public function actionKlnCreate(){
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
				return $this->redirect(['kln']);	
			}else{die(print_r($model2->getErrors()));}            
        } else {
            return $this->render('kln_create', [
                'model' => $model,
            ]);
        }
		
		//die(print_r($model->getErrors()));
    }

    public function actionKlnUpdate($id){
        $model=Kalender::findOne($id);
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
	            return $this->redirect(['kln-view', 'id' => $model->kln_id]);
        } else {
            return $this->render('kln_update', [
                'model' => $model,
            ]);
        }
    }

    public function actionKlnDelete($id){
        $model=Kalender::findOne($id);
		$model->RStat=1;
		$model->save();
		\app\models\Funct::LOGS('Menghapus Data Kalender Akademik',new Kalender(),$id,'d');		
        return $this->redirect(['kln']);
    }
	/* End Kalender */

	/* Dosen */
    public function actionDsn(){
        $searchModel = new DosenSearch;
        $dataProvider = $searchModel->cari(Yii::$app->request->getQueryParams());
        return $this->render('dsn_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionDsnWaliList(){
        $model		 = new Wali();
        if ($model->load(Yii::$app->request->post())) {
			$jr=explode("#",$model->JrId);
			$model->JrId = $jr[1];
			if($model->save())
            return $this->redirect(['dsn-wali-list']);
        }

        $searchModel = new WaliSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('dsn_list_wali', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
			'model'=>$model
        ]);
    }


    public function actionDsnCreate()
    {
        $ModDsn		= new Dosen;
        $ModMhs 	= new Mahasiswa;

        if ($ModMhs->load(Yii::$app->request->post()) && $ModMhs->save()) {
            return $this->redirect(['ajr-view', 'id' => $ModMhs->id]);
        } else {
            return $this->render('dsn_create', [
                'ModDsn' => $ModDsn,
                'ModMhs' => $ModMhs,
            ]);
        }
    }

    public function actionDsnView($id){
        $model = Dosen::findOne($id);
		if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
			return $this->redirect(['dsn-view', 'id' => $model->kln_id]);
		}else{
			\app\models\Funct::LOGS('Melihat Detail Data Dosen $model->ds_nm',new Kalender(),$id,'r');
			return $this->render('dsn_view', ['model' => $model]);	
		}
    }

    public function actionDsnWali($id){
        $model = Dosen::findOne($id);
        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams()," ds_wali='$model->ds_id'");

		if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
			return $this->redirect(['dsn-wali', 'id' => $model->dsn_id]);
		}else{
			return $this->render('dsn_wali', [
				'model' => $model,
				'dataProvider' => $dataProvider,
				'searchModel' => $searchModel,
			]);	
		}
    }
	/* end Dosen */

	/* jadwal */
    public function actionJdw(){
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams());
		
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
			$content = $this->renderPartial('jdw_pdf',[
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

        return $this->render('jdw_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionJdw1()
    {
		
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams());
        $_SESSION['query_jadwal'] = Yii::$app->request->getQueryParams();
        return $this->render('jdw_index1', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    public function actionJdwDetail($id)
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

    public function actionJdwView($id)
    {
        $model =  Jadwal::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	return $this->redirect(['jdw-view', 'id' => $model->jdwl_id]);
        } else {
        return $this->render('jdw_view', ['model' => $model]);
		}
    }

    public function actionJdwCreate($id)
    {
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

    public function actionJdwUpdate($id)
    {
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

    public function actionJdwDelete($id){
        $model=Jadwal::findOne($id);
		$model->RStat='1';
		$model->save(false);
		\app\models\Funct::LOGS("Menghaous Data Jadwal ($id) ",new Jadwal,$id,'d');
		// tambahan
		$sql="delete from transaksi_finger where jdwl_id=$model->jdwl_id";
		Yii::$app->db->createCommand($sql)->execute();
        return $this->redirect(Yii::$app->request->referrer);
    }
	/* end jadwal */

	/* Pengajar */
    public function actionAjr()
    {
        $searchModel = new BobotNilaiSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('ajr_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


	// group
	public function actionJdwGroup($id){
		$modJdw	= Jadwal::findOne($id);
        $ModBn = BobotNilai::findOne($modJdw->bn_id);

        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),
		" ds.ds_id='$ModBn->ds_nidn' and kr_kode='".$ModBn->kln->kr_kode."' and( kl.jr_id !='".$ModBn->kln->jr_id."' and kl.pr_kode !='".$ModBn->kln->pr_kode."' )"
		,
		['jdwl_hari'=>SORT_ASC,"cast(concat('1',LEFT(jdwl_masuk,2),RIGHT(jdwl_masuk,2)) as int)"=>SORT_ASC]
		);
        $dataProvider2 = $searchModel->dosen(
			Yii::$app->request->getQueryParams(),['ds_id'=>$ModBn->ds_nidn,'kl.kr_kode'=>$ModBn->kln->kr_kode],
			['jdwl_hari'=>SORT_ASC,"cast(concat('1',LEFT(jdwl_masuk,2),RIGHT(jdwl_masuk,2)) as int)"=>SORT_ASC]
		);
		
		if(isset($_POST['jdwl'])){
			$vJdw=(int)$_POST['jdwl'];
			$nModJdw = Jadwal::findOne($vJdw);
			$modJdw->jdwl_hari=$nModJdw->jdwl_hari;
			$modJdw->jdwl_masuk=$nModJdw->jdwl_masuk;
			$modJdw->jdwl_keluar=$nModJdw->jdwl_keluar;
			$modJdw->GKode=($nModJdw->GKode?$nModJdw->GKode:$nModJdw->bn->kln->kr_kode.'.'.$nModJdw->bn->ds_nidn.'.'.date('dHis'));
			if($modJdw->save()){
				return $this->redirect(['ajr-view','id' => $modJdw->bn_id]);	
			}
		}
		return $this->render('jdw_group', [
			'dataProvider' => $dataProvider,
			'dataProvider2' => $dataProvider2,
			'ModBn'=>$ModBn,
			'ModJdw'=>$modJdw,
			'model2' => $model_jadwal,
			'searchModel' => $searchModel,
			'id'=>$id,
		]);
        
	}

	public function actionGroupingAll($id){
		$model = BobotNilai::findOne($id);
		$sql ="
			update jdw set GKode=concat(
				kln.kr_kode,'.',ds.ds_id,'.',jdw.jdwl_hari,'.',LEFT(jdw.jdwl_masuk,2),RIGHT(jdw.jdwl_masuk,2)
				,'.',LEFT(jdw.jdwl_keluar,2),RIGHT(jdw.jdwl_keluar,2)
			)
			from tbl_jadwal jdw
			INNER JOIN tbl_bobot_nilai bn on(bn.id=jdw.bn_id and(bn.RStat is NULL or bn.RStat='0'))
			INNER JOIN tbl_dosen ds on(ds.ds_id=bn.ds_nidn and(ds.RStat is NULL or ds.RStat='0'))
			INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and(mk.RStat is NULL or mk.RStat='0'))
			INNER JOIN tbl_kalender kln on(kln.kln_id=bn.kln_id and(kln.RStat is NULL or kln.RStat='0') and kr_kode='11617')
			WHERE (jdw.jdwl_id is NOT NULL)
			and ds.ds_id='".$model->ds_nidn."'
			AND kln.kr_kode='".$model->kln->kr_kode."'
			
		";
		//and (kln.jr_id !='".$model->kln->jr_id."' and kln.pr_kode!='".$model->kln->pr_kode."' )
		@Yii::$app->db->createCommand($sql)->execute();
		return $this->redirect(['ajr-view','id' => $model->id]);
	}

	
	public function actionUngroup($id){
		$model = Jadwal::findOne($id);
		$model->GKode=NULL;
		$model->save(false);
		return $this->redirect(['ajr-view','id' => $model->bn_id]);
	}
	//end group


	public function actionAjrView($id){
        $model_jadwal = new Jadwal;
        $model_jadwal->bn_id = $id;
        $ModBn = BobotNilai::findOne($id);
		
        $model_jadwal->semester = '1';
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),['bn_id'=>$id]);
		$dataProvider3=false;	
        $model = new BobotNilai;

        $dataProvider2 = $searchModel->dosen(Yii::$app->request->getQueryParams(),['ds_id'=>$ModBn->ds_nidn,'kl.kr_kode'=>$ModBn->kln->kr_kode],
		['jdwl_hari'=>SORT_ASC,"cast(concat('1',LEFT(jdwl_masuk,2),RIGHT(jdwl_masuk,2)) as int)"=>SORT_ASC]
		);
		
        if ($model_jadwal->load(Yii::$app->request->post())) {
			if(isset($_POST['GB'])){
				if(empty($_POST['GKode'])){
					echo "Kode Gabungan Belum Dipilih";
					die();
				}else{
					$nJdw = Jadwal::find()->where(['GKode'=>$_POST['GKode']])->one();
					$model_jadwal->jdwl_hari=$nJdw->jdwl_hari;
					$model_jadwal->jdwl_masuk=$nJdw->jdwl_masuk;
					$model_jadwal->jdwl_keluar=$nJdw->jdwl_keluar;
					$model_jadwal->GKode=$_POST['GKode'];
					//$model_jadwal->save();
					if($model_jadwal->save()){
						\app\models\Funct::LOGS("Menambah Data Penjadwalan  ($model_jadwal->jdwl_id) ",$model_jadwal,$model_jadwal->jdwl_id,'c');
						return $this->redirect(['ajr-view', 'id' => $id]);
					}					
				}
			}
				$vMasuk	= $model_jadwal->jdwl_masuk;
				$vKeluar= $model_jadwal->jdwl_keluar;
				$vHari 	= $model_jadwal->jdwl_hari;
				$vCount=0;
				if(strlen($vMasuk)==5){
					$vMasuk = $vHari.substr($vMasuk,0,2).substr($vMasuk,3,2);
					if(!is_numeric($vMasuk)){$vMasuk="";}
				}
				if(strlen($vKeluar)==5){
					$vKeluar = $vHari.substr($vKeluar,0,2).substr($vKeluar,3,2);
					if(!is_numeric($vKeluar)){$vKeluar="";}
				}
				if($vMasuk!=''&&$vKeluar!=''){
					
					$ModCekJdw = Jadwal::find()
					->select([
						'tbl_jadwal.jdwl_hari',
						'tbl_jadwal.jdwl_masuk',
						'tbl_jadwal.jdwl_keluar',
						'GKode'=>"isnull(GKode,tbl_jadwal.jdwl_id)",
						'jadwal'=>"concat(jdwl_masuk,'-',jdwl_keluar)",
						'jum'=>"sum(iif(krs.krs_id is null,0,1))",
						'jumjdw'=>"count(distinct tbl_jadwal.jdwl_id)",
					])
					->innerJoin('tbl_bobot_nilai bn',"(bn.id = tbl_jadwal.bn_id and (bn.RStat is null or bn.RStat='0') )")
					->innerJoin('tbl_dosen ds',"(bn.ds_nidn = ds.ds_id and (ds.RStat is null or ds.RStat='0') and ds.ds_id='$ModBn->ds_nidn')")
					->innerJoin('tbl_kalender kl',"(kl.kln_id = bn.kln_id and (kl.RStat is null or kl.RStat='0') and kl.kr_kode='".$ModBn->kln->kr_kode."')")
					->innerJoin('tbl_program pr',"(pr.pr_kode = kl.pr_kode)")
					->innerJoin('tbl_jurusan jr',"(jr.jr_id= kl.jr_id)")    
					->innerJoin('tbl_matkul mk',"(mk.mtk_kode = bn.mtk_kode and (mk.RStat is null or mk.RStat='0') )")
					->join('full join','tbl_krs krs',"krs.jdwl_id = tbl_jadwal.jdwl_id and (krs.RStat='0' or krs.RStat is null)")                                 		
					//->where("(tbl_jadwal.RStat='0' or tbl_jadwal.RStat is null)")
					->groupBy(["isnull(GKode,tbl_jadwal.jdwl_id)","jdwl_hari","jdwl_masuk","jdwl_keluar"]);
					$cJdw = $ModCekJdw->where("
						((($vMasuk +1 )
							BETWEEN CAST(CONCAT(jdwl_hari,LEFT(jdwl_masuk,2),RIGHT(jdwl_masuk,2)) as int )
							AND CAST(CONCAT(jdwl_hari,LEFT(jdwl_keluar,2),RIGHT(jdwl_keluar,2)) as int ))
							or(($vKeluar -1)
							BETWEEN CAST(CONCAT(jdwl_hari,LEFT(jdwl_masuk,2),RIGHT(jdwl_masuk,2)) as int )
							AND CAST(CONCAT(jdwl_hari,LEFT(jdwl_keluar,2),RIGHT(jdwl_keluar,2)) as int ))
						)
						and jdwl_hari='$vHari'
						and (tbl_jadwal.RStat is null or tbl_jadwal.RStat='0')
						and bn_id !='".$ModBn->id."'");
					if($cJdw->count()>0){
						$dataProvider3 = new ActiveDataProvider(['query' => $cJdw,]);
						return $this->render('../bisa/ajr_jdw', [
							'dataProvider' => $dataProvider,
							'dataProvider2' => $dataProvider2,
							'dataProvider3' => $dataProvider3,
							'model'=>$model,
							'ModBn'=>$ModBn,
							'model2' => $model_jadwal,
							'searchModel' => $searchModel,
							'id'=>$id,
						]);
					}
					$vCount = $ModCekJdw->where("
						((($vMasuk +1 )
							BETWEEN CAST(CONCAT(jdwl_hari,LEFT(jdwl_masuk,2),RIGHT(jdwl_masuk,2)) as int )
							AND CAST(CONCAT(jdwl_hari,LEFT(jdwl_keluar,2),RIGHT(jdwl_keluar,2)) as int ))
							or(($vKeluar -1)
							BETWEEN CAST(CONCAT(jdwl_hari,LEFT(jdwl_masuk,2),RIGHT(jdwl_masuk,2)) as int )
							AND CAST(CONCAT(jdwl_hari,LEFT(jdwl_keluar,2),RIGHT(jdwl_keluar,2)) as int ))
						)
						and jdwl_hari='$vHari'
						and(tbl_jadwal.RStat is null or tbl_jadwal.RStat='0')
						and bn_id='".$ModBn->id."'"
					)->count();
				}
				if($vCount==0){
					$model_jadwal->GKode=$ModBn->kln->kr_kode.".".$ModBn->ds_nidn.".".date("dHis");
					if($model_jadwal->save()){
						\app\models\Funct::LOGS("Menambah Data Penjadwalan  ($model_jadwal->jdwl_id) ",$model_jadwal,$model_jadwal->jdwl_id,'c');
						return $this->redirect(['ajr-view', 'id' => $id]);
					}
				}else{
					Yii::$app->getSession()->setFlash('error','Jadwal Bentrok');
				}
        }
		
		return $this->render('../bisa/ajr_jdw', [
			'dataProvider' => $dataProvider,
			'dataProvider2' => $dataProvider2,
			'dataProvider3' => $dataProvider3,
			'model'=>$model,
			'ModBn'=>$ModBn,
			'model2' => $model_jadwal,
			'searchModel' => $searchModel,
			'id'=>$id,
		]);
	
    }

    //cross schedule
    public function actionAjrCross($id,$jid){
        $krs = Krs::find()->where(["jdwl_id" => $id])->all();
        $limit_krs = count($krs);
        $model_jadwal = new Jadwal;
        $model_jadwal->bn_id = $jid;
        $model_jadwal->semester = '1';
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),['bn_id'=>$jid]);
        $ModBn = BobotNilai::findOne($jid);
        $model = new BobotNilai;

        if($limit_krs > 1){
                $lebih = 0; 
                foreach ($krs as $k => $kr) {      
                    if($k > 1){
                            $lebih = $lebih + 1;
                        } 
                }
                if ($model_jadwal->load(Yii::$app->request->post()) && $model_jadwal->save()) {

                    foreach ($krs as $k => $kr) {
                        if($k > 1){
                            $kr->jdwl_id = $model_jadwal->jdwl_id;
                            $kr->save(false);
                        }
                    }
                    return $this->redirect(['ajr-view', 'id' => $jid]);
                } 
                else {
                return $this->render('../bisa/ajr_jdw_cross', [
                    'dataProvider' => $dataProvider,
                    'model'=>$model,
                    'ModBn'=>$ModBn,
                    'model2' => $model_jadwal,
                    'searchModel' => $searchModel,
                    'limit_krs' => $limit_krs,
                    'lebih' => $lebih,
        
                    ]);
                }
            }
        else{
            echo 'not found';
        }
        
    }

    public function actionAjrUpdate($id){
        $model 		=  BobotNilai::findOne($id);
		$Kalender 	=  Kalender::findOne($model->kln_id);
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Mengubah Data Pengajar($id)",new BobotNilai,$id,'u');
			$model->save(false);
			/*
			if($model->save(false)){
				$sql="exec dbo.p_updateTransFingerBn $model->_id,$model->ds_nidn";
				Yii::$app->db->createCommand($sql)->execute();
			}
			*/
            return $this->redirect(['ajr-view', 'id' => $model->id]);
        }else{
            $model->jurusan = $Kalender->jr_id;
            $model->kr_kode = $Kalender->kr_kode;
            $model->program = $Kalender->pr_kode;
            $model->kalender= $Kalender->kln_id;
            return $this->render('ajr_update', [
                'model' => $model,
                'kalender' => $Kalender,
            ]);
        }
    }

    public function actionAjrCreate(){
        $model = new BobotNilai;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Menambah Data Pengajar($model->id)",$model,$model->id,'c');
            return $this->redirect(['ajr-view', 'id' => $model->id]);
        } else {
            return $this->render('ajr_create', [
                'model' => $model,
            ]);
        }
    }
	
    public function actionAjrDelete($id){
        $model=BobotNilai::findOne($id);
		$model->RStat=1;
		if($model->save()){
			\app\models\Funct::LOGS("Menghapus Data Pengajar ($id) ",new BobotNilai,$id,'d');
		}
	    return $this->redirect(['ajr']);
    }	
	/*end ajar */

	/* Mahasiswa */
    public function actionMhs(){
        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
		//\app\models\Funct::LOGS('Mengakses Halaman Mahasiswa');
		
		if($_GET['c']==1){
			$tahun="";$jurusan="";$program="";
			if($_GET['MahasiswaSearch']['jr_id']!=''){
				$jr=(int)$_GET['MahasiswaSearch']['jr_id'];
				$ModJr = \app\models\Jurusan::find()->where(['jr_id'=>$jr])->one();

			}
			if($_GET['MahasiswaSearch']['mhs_angkatan']!=''){
				$tahun= "-".((int) $_GET['MahasiswaSearch']['mhs_angkatan']);
			}

			if($_GET['MahasiswaSearch']['pr_kode']!=''){
				$pr=(int)$_GET['MahasiswaSearch']['pr_kode'];
				$ModPr = \app\models\Program::find()->where(['pr_kode'=>$pr])->one();
				$program="-".@$ModPr->pr_nama;
			}
			$Ket='Daftar Mahasiswa '.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'')." $program $tahun ";

	        $this->layout = 'blank';
			$content = $this->renderPartial('mhs_pdf',[
				'dataProvider' => $dataProvider,'Ket'=>$Ket
			]);
			
			$pdf = new Pdf([
				'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
				'content' => $content,
				'format'=>Pdf::FORMAT_LETTER,
				'marginLeft'=>5,
				'marginRight'=>5,
				'marginTop'=>20,
				'marginHeader'=>1,
				'orientation'=>'P',
				'destination'=>'I',
				'cssInline'=>'
					table{overlow:warp;font-size:12px;}
				',
				'filename'=>'Mahasiswa-'.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'All')." $program $tahun ".'-'.date('YmdHis').'.pdf',
				'options' => [
					'title' => 'Mahasiswa '.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'Semua Jurusan')." $program $tahun ",
					'subject' => 'Mahasiswa '.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'Semua Jurusan')." $program $tahun ",
					'watermarkText'=>'DIREKTORAT SISTEM INFORMASI & MULTIMEDIA',
					'showWatermarkText'=>true,
				],
				'methods' => [
					'SetHeader' => [
						'<table>
							<tr>
								<td><img src="'.Url::to('@web/ypkp.png').'" width="50"></td>
								<td>
								<b>UNIVERSITAS SANGGA BUANA YPKP</b>
								<p><small>Jl. PHH Mustopa No 68, Telp. (022) 7202233. Bandung 40124 
								&nbsp;E-mail : sim@usbypkp.ac.id. Homepage : www.usbypkp.ac.id</small></p>
									'.'
								</td>
							</tr>
						</table>'
					],
					'SetFooter' => [ substr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],0,80).'.../.. '.date('r').' [Hal.{PAGENO}]'],
				]
			]);			
			return $pdf->render();
		}
		
        return $this->render('mhs_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    public function actionMhsView($id){
        $model 	=  Mahasiswa::findOne($id);
		$ModKe	=  KPembayarankrs::find()
		->where(['nim'=>$id])
		->orderBy(['substring(tahun,2,2)'=>SORT_ASC,'substring(tahun,1,1)'=>SORT_ASC,])
		;
		$ModKe = new ActiveDataProvider([
            'query' => $ModKe,
        ]);

        $searchKrs 	= new KrsSearch;
        $dataKrs 	= $searchKrs->search(Yii::$app->request->getQueryParams());
		
		//$ModKrs = Krs::find()->
		$query = new Query;
		$query
			->select(' kln.kln_id,krs.mhs_nim ,kln.kr_kode, bn.mtk_kode , mk.mtk_sks,krs_grade')
			->from('tbl_krs krs, tbl_jadwal jd,tbl_bobot_nilai bn, tbl_kalender kln, tbl_matkul mk')
			->where("
				krs.jdwl_id=jd.jdwl_id 
				and bn.id=jd.bn_id
				and bn.mtk_kode=mk.mtk_kode
				and bn.kln_id=kln.kln_id
				and mhs_nim='$model->mhs_nim'
				and (
					(bn.RStat is null or bn.RStat='0')
					and (krs.RStat is null or krs.RStat='0')
					and (kln.RStat is null or kln.RStat='0')
				)					
			")
			->orderBy(['substring(kln.kr_kode,2,4)'=>SORT_ASC,'substring(kln.kr_kode,1,1)'=>SORT_ASC,'bn.mtk_kode'=>SORT_ASC]);

		$command = $query->createCommand();
		$data = $command->queryAll();  		

		$n=0;
		$kod="";
		$kod="";
		$ITEM=array();
		
		foreach($data as $d){
			if($kod!=$d['kr_kode']){
				$n++;
				$totmk=1;
				$TotSks=0;
				$GradeSks=0;
				$mk='';
				
				if($mk!=$d['mtk_kode']){
					$mk		= $d['mtk_kode'];
					$TotSks	= $d['mtk_sks'];					
				}
				$kod=$d['kr_kode'];
			}else{
				if($mk!=$d['mtk_kode']){
					$totmk=$totmk+1;
					$mk=$d['mtk_kode'];
					$TotSks = $TotSks + $d['mtk_sks'];	
				}
			}
			
			$ITEM[$n]['Tahun_Akademik']=$d['kr_kode'];
			$ITEM[$n]['Total_Matakuliah']=$totmk;
			$ITEM[$n]['Total_SKS']=$TotSks;
			$ITEM[$n]['kln_id']=$d['kln_id'];
			$ITEM[$n]['nim']=$d['mhs_nim'];
		}

		$dataProvider = new ArrayDataProvider([
			'allModels'=>$ITEM,
			'key'=>'kln_id',
			'pagination' => [
        		'pageSize' => 20,
    		],

		]);

		
		
        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {

        	return $this->redirect(['mhs-view', 'id' => $model->mhs_nim]);
        } else {
        	return $this->render('mhs_view', [
					'model' 	=> $model,
					'ThnAkdm'	=> $dataProvider,
					'ModKe'		=> $ModKe
				]
			);
		}
    }




    public function actionMhsKrs($id,$kode)
    {
        $model 	=  Mahasiswa::findOne($id);

        $searchKrs 	= new KrsSearch;
        $dataKrs 	= $searchKrs->search(Yii::$app->request->getQueryParams());
		
		//$ModKrs = Krs::find()->
		$query = new Query;
		$query
			->select("
				krs.krs_id ,kln.kr_kode, krs.mhs_nim , bn.mtk_kode ,mk.mtk_nama, mk.mtk_sks,krs_grade,ds_nidn,pr.pr_nama,jd.jdwl_kls
				,jd.jdwl_hari, jd.jdwl_masuk,jd.jdwl_keluar
				
			")
			->from('tbl_krs krs, tbl_jadwal jd,tbl_bobot_nilai bn, tbl_kalender kln, tbl_matkul mk,tbl_program pr')
			->where("
				krs.jdwl_id = jd.jdwl_id 
				and bn.id=jd.bn_id
				and bn.mtk_kode=mk.mtk_kode
				and bn.kln_id=kln.kln_id
				and kln.pr_kode=pr.pr_kode
				and krs.mhs_nim='$model->mhs_nim'
				and kln.kr_kode =(
					select distinct kr_kode from tbl_kalender where kln_id='".$kode."'
					and (RStat='0' or RStat is null )
				) 
				and (
						(krs.RStat='0' or krs.RStat is null )
					and (bn.RStat='0' or bn.RStat is null )
					and (kln.RStat='0' or kln.RStat is null )
					and (jd.RStat='0' or jd.RStat is null )
				)
			")
			->orderBy(['substring(kln.kr_kode,2,4)'=>SORT_ASC,'substring(kln.kr_kode,1,1)'=>SORT_ASC,'bn.mtk_kode'=>SORT_ASC])
			;

		$command = $query->createCommand();
		$data = $command->queryAll();  		

		$n=0;
		$kod="";
		$kod="";
		$ITEM=array();
		$InfTahun="";
		$TotKrs=0;
		$TotGrade=0;
		foreach($data as $d){
			
			$InfTahun=$d['kr_kode'];
			$grade=0;
			if($d['krs_grade']!='-' && !empty($d['krs_grade'])){
				$grade=Funct::Mutu($d['krs_grade']);
			}
			$ITEM[$n]['Id']	=$d['krs_id'];
			
			$ITEM[$n]['Kode']	=$d['mtk_kode'];
			$ITEM[$n]['Kelas']	=$d['jdwl_kls'];
			$ITEM[$n]['Jadwal']	= Funct::HARI($d['jdwl_hari']).", ".$d['jdwl_masuk']."-".$d['jdwl_keluar'];
			$ITEM[$n]['Program']=$d['pr_nama'];
			$ITEM[$n]['Matakuliah']=$d['mtk_nama'];
			$ITEM[$n]['SKS']	= $d['mtk_sks'];
			$ITEM[$n]['Dosen']	= Funct::DSN()[$d['ds_nidn']];
			$ITEM[$n]['Grade']	= $d['krs_grade'];
			$ITEM[$n]['Total']	= ($grade * $d['mtk_sks']);
			$ITEM[$n]['nim']	= $d['mhs_nim'];
			$ITEM[$n]['no']	= ($n+1);
			
			$TotKrs+=$ITEM[$n]['SKS'];
			$TotGrade+=$ITEM[$n]['Total'];
			$n++;
			
		}
		@$IPK =0;
		if($TotGrade>0){$IPK = $TotGrade/$TotKrs;}
		
		$dataProvider = new ArrayDataProvider([
			'allModels'=>$ITEM,
			'pagination' => [
        		'pageSize' => 20,
    		],

		]);


        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
        	return $this->redirect(['mhs-krs', 'id' => $model->mhs_nim]);
        } else {
        	return $this->render('mhs_krs', [
					'model' => $model,
					'ThnAkdm'=>$dataProvider,
					'Tahun'=>$InfTahun,
					'IP'=>$IPK,
					
				]
			);
		}
    }



    public function actionMhsAbsen($id,$kode)
    {
        $model 		= Mahasiswa::findOne($id);
        $searchKrs 	= new KrsSearch;
        $dataKrs 	= $searchKrs->search(Yii::$app->request->getQueryParams());
		$usr		= "select fid from user_ where username='$model->mhs_nim'";
		$user		=Yii::$app->db->createCommand($usr)->queryOne();

		$kehadiran="exec dbo.AbsensiMhs $user[fid],'$kode'";
		$kehadiran=Yii::$app->db->createCommand($kehadiran)->queryAll();
		$query="SELECT * FROM dbo.LogAbsenMhs('$model->mhs_nim','$kode')";
		$query=Yii::$app->db->createCommand($query)->queryAll();
		
		//$data = $command->queryAll();  		

		$n=0;
		$kod="";
		$kod="";
		$ITEM=array();

		$ItmKeadiran=[];
		$InfTahun="";
		$TotKrs=0;
		$TotGrade=0;
		foreach($kehadiran as $d){
			$ItmKeadiran[$n]['Kode']	=$d['mtk_kode'];
			$ItmKeadiran[$n]['Kelas']	=$d['jdwl_kls'];
			$ItmKeadiran[$n]['Jadwal']	= Funct::HARI($d['jdwl_hari']).", ".$d['jdwl_masuk']."-".$d['jdwl_keluar'];
			$ItmKeadiran[$n]['Matakuliah']=$d['mtk_nama'];
			
			$ItmKeadiran[$n]['Pertemuan']	= $d['pertemuan'];
			$ItmKeadiran[$n]['Total']	=$d['total'];
			$ItmKeadiran[$n]['Persen']	= $d['persen'];

			$ItmKeadiran[$n]['1']	= $d['1'];
			$ItmKeadiran[$n]['2']	= $d['2'];
			$ItmKeadiran[$n]['3']	= $d['3'];
			$ItmKeadiran[$n]['4']	= $d['4'];
			$ItmKeadiran[$n]['5']	= $d['5'];
			$ItmKeadiran[$n]['6']	= $d['6'];
			$ItmKeadiran[$n]['7']	= $d['7'];
			$ItmKeadiran[$n]['8']	= $d['8'];
			$ItmKeadiran[$n]['9']	= $d['9'];
			$ItmKeadiran[$n]['10']	= $d['10'];
			$ItmKeadiran[$n]['11']	= $d['11'];
			$ItmKeadiran[$n]['12']	= $d['12'];
			$ItmKeadiran[$n]['13']	= $d['13'];
			$ItmKeadiran[$n]['14']	= $d['14'];

			$ItmKeadiran[$n]['no']	= ($n+1);
			$n++;
			
		}
		@$IPK =0;
		if($TotGrade>0){$IPK = $TotGrade/$TotKrs;}
		
		$dataProvider = new ArrayDataProvider([
			'allModels'=>$ItmKeadiran,
			'pagination' => [
        		'pageSize' => 20,
    		],
		]);


        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
        	return $this->redirect(['mhs-krs', 'id' => $model->mhs_nim]);
        } else {
        	return $this->render('mhs_absen', [
					'model' => $model,
					'ThnAkdm'=>$dataProvider,
					'Tahun'=>$InfTahun,
					'IP'=>$IPK,
					'KODE'=>$kode,
					
				]
			);
		}
    }

    public function actionMhsAbsenLog($id,$kode)
    {
        $model 		= Mahasiswa::findOne($id);
        $searchKrs 	= new KrsSearch;
        $dataKrs 	= $searchKrs->search(Yii::$app->request->getQueryParams());
		$usr		= "select fid from user_ where username='$model->mhs_nim'";
		$user		=Yii::$app->db->createCommand($usr)->queryOne();

		$kehadiran="exec dbo.AbsensiMhs $user[fid],'$kode'";
		$kehadiran=Yii::$app->db->createCommand($kehadiran)->queryAll();
		$query="SELECT * FROM dbo.LogAbsenMhs('$model->mhs_nim','$kode')";
		$query=Yii::$app->db->createCommand($query)->queryAll();
		
		//$data = $command->queryAll();  		

		$n=0;
		$kod="";
		$kod="";
		$ITEM=array();

		$ItmKeadiran=[];
		$InfTahun="";
		$TotKrs=0;
		$TotGrade=0;
		foreach($kehadiran as $d){
			$ItmKeadiran[$n]['Kode']	=$d['mtk_kode'];
			$ItmKeadiran[$n]['Kelas']	=$d['jdwl_kls'];
			$ItmKeadiran[$n]['Jadwal']	= Funct::HARI($d['jdwl_hari']).", ".$d['jdwl_masuk']."-".$d['jdwl_keluar'];
			$ItmKeadiran[$n]['Matakuliah']=$d['mtk_nama'];
			
			$ItmKeadiran[$n]['Pertemuan']	= $d['pertemuan'];
			$ItmKeadiran[$n]['Total']	=$d['total'];
			$ItmKeadiran[$n]['Persen']	= $d['persen'];

			$ItmKeadiran[$n]['1']	= $d['1'];
			$ItmKeadiran[$n]['2']	= $d['2'];
			$ItmKeadiran[$n]['3']	= $d['3'];
			$ItmKeadiran[$n]['4']	= $d['4'];
			$ItmKeadiran[$n]['5']	= $d['5'];
			$ItmKeadiran[$n]['6']	= $d['6'];
			$ItmKeadiran[$n]['7']	= $d['7'];
			$ItmKeadiran[$n]['8']	= $d['8'];
			$ItmKeadiran[$n]['9']	= $d['9'];
			$ItmKeadiran[$n]['10']	= $d['10'];
			$ItmKeadiran[$n]['11']	= $d['11'];
			$ItmKeadiran[$n]['12']	= $d['12'];
			$ItmKeadiran[$n]['13']	= $d['13'];
			$ItmKeadiran[$n]['14']	= $d['14'];

			$ItmKeadiran[$n]['no']	= ($n+1);
			$n++;
			
		}
		@$IPK =0;
		if($TotGrade>0){$IPK = $TotGrade/$TotKrs;}
		
		$dataProvider = new ArrayDataProvider([
			'allModels'=>$ItmKeadiran,
			'pagination' => [
        		'pageSize' => 20,
    		],
		]);


        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
        	return $this->redirect(['mhs-krs', 'id' => $model->mhs_nim]);
        } else {
        	return $this->render('mhs_absen_log', [
					'model' => $model,
					'ThnAkdm'=>$dataProvider,
					'Tahun'=>$InfTahun,
					'IP'=>$IPK,
					'KODE'=>$kode,
					
				]
			);
		}
    }



    public function actionMhsKhs($id)
    {
        $model 	=  Mahasiswa::findOne($id);

        $searchKrs 	= new KrsSearch;
        $dataKrs 	= $searchKrs->search(Yii::$app->request->getQueryParams());
		
		//$ModKrs = Krs::find()->
		$query = new Query;
		$query
			->select(' kln.kr_kode,  bn.mtk_kode ,mk.mtk_nama, mk.mtk_sks,mk.mtk_semester,krs_grade,ds_nidn')
			->from('tbl_krs krs, tbl_jadwal jd,tbl_bobot_nilai bn, tbl_kalender kln, tbl_matkul mk')
			->where("
				krs.jdwl_id = jd.jdwl_id 
				and bn.id=jd.bn_id
				and bn.mtk_kode=mk.mtk_kode
				and bn.kln_id=kln.kln_id
				and mhs_nim='$model->mhs_nim'
				and krs_ulang='1'
				and krs.RStat='0'
			");
			
		$PerTahun 	= $query->orderBy(
			['substring(kln.kr_kode,2,4)'=>SORT_ASC,'substring(kln.kr_kode,1,1)'=>SORT_ASC,'bn.mtk_kode'=>SORT_ASC]
		)->createCommand()->queryAll();
		
		$PerSemester= $query->orderBy(['mk.mtk_semester'=>SORT_ASC,'mk.mtk_sks'=>SORT_ASC,])->createCommand()->queryAll();

		$command = $query->createCommand();
		$data = $query->orderBy(['mk.mtk_kode'=>SORT_ASC])->createCommand()->queryAll();

		$n=0;
		$kode="";
		$TotKrs=0;
		$TotGrade=0;
		foreach($data as $d){
			$grade=0;
			if($d['krs_grade']!='-' && !empty($d['krs_grade'])){
				$grade=Funct::Mutu($d['krs_grade']);
			}
	
			if($kode!=$d['mtk_kode']){
				$kode=$d['mtk_kode'];	
				$total 				= $d['mtk_sks'] * $grade;
				@$TotKrs		= $TotKrs+$d['mtk_sks'];
				@$TotGrade   	= $TotGrade+$total;
			}else{
				if($grade!=0){
				$total 				= $d['mtk_sks'] * $grade;
				
				@$TotKrs	= $TotKrs+$d['mtk_sks'];
				@$TotGrade	= $TotGrade+$total;
				}
			}
		}
		
		$IPK = " ( NA / Total SKS ) : $TotGrade/$TotKrs = ".number_format(($TotGrade/$TotKrs),2);
		return $this->render('mhs_khs', [
				'model' => $model,
				'IP'=>$IPK,
				'DataTahun'=>$PerTahun,
				'DataSemester'=>$PerSemester,
				
			]
		);
    }



    public function actionMhsCreate()
    {
        $model = new Mahasiswa;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['mhs-view', 'id' => $model->mhs_nim]);
        } else {
            return $this->render('mhs_create', [
                'model' => $model,
				
            ]);
        }
    }

    public function actionMhsUpdate($id)
    {
        $model =  Mahasiswa::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['mhs-view', 'id' => $model->mhs_nim]);
        } else {
            return $this->render('mhs_update', [
                'model' => $model,
            ]);
        }
    }

    public function actionMhsDelete($id)
    {
        $model=Mahasiswa::findOne($id);
		$model->RStat=1;
		$model->save();
        return $this->redirect(['/akademik/mhs']);
    }
	/* end Mahasiswa */
	
	/* KRS */
    public function actionKrs()
    {
        $searchModel = new KrsSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('krs_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionKrsView($id)
    {
        $model = Krs::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	return $this->redirect(['krs-view', 'id' => $model->krs_id]);
        } else {
        	return $this->render('krs_view', ['model' => $model]);
		}
    }

    public function actionKrsCreate()
    {
        $model = new Krs;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->krs_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionKrsUpdate($id)
    {
        $model=Krs::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->krs_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionKrsDelete($id)
    {
        $model=Krs::findOne($id);
		$model->RStat=1;
		$model->save();
        return $this->redirect(['index']);
    }
	
	/* end KRS */
	

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Fakultas();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->fk_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->fk_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model=$this->findModel($id);
		$model->RStat=1;
		$model->save();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Fakultas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    // protected function findModelthn($id)
    // {
    //     if (($model = BobotNilai::findOne($id)) !== null) {
    //         return $model;
    //     } else {
    //         throw new NotFoundHttpException('The requested page does not exist.');
    //     }
    // }    

// ========================= drop ==============================
    public function actionKlass() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
			$id=explode('|',$id);
			if(count($id)==1){
				$id = end($_POST['depdrop_parents']);
			}else if(count($id)>1){
				$id = $id[1];	
			}
			
            $list = Matkul::find()->andWhere(['jr_id'=>$id])->asArray()->all();
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
				//$out[0] = ['id' => NULL, 'name' => 'Pilih Matakuliah' ];
                foreach ($list as $i => $kota) {
                    $out[] = ['id' => $kota['mtk_kode'], 'name' => $kota['mtk_kode']." ". $kota['mtk_nama'] ];
                    //if ($i == 0) {$selected = $kota['mtk_kode'];}
                }
				$selected = '';
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }

    // public function actionThn($id) {
    //     $model=$this->findModelthn($id);
    //     $out = [];
    //     if (isset($_POST['depdrop_parents'])) {
    //         $id = end($_POST['depdrop_parents']);
    //         $list = BobotNilai::find()->select(' kln_id ')->distinct(true)->andWhere(['id'=>$id])->asArray()->all();
    //         $selected  = null;
    //         if ($id != null && count($list) > 0) {
    //             $selected = '';
    //             foreach ($list as $i => $kota) {
    //                 $kode=$kota['jr_id'];
    //                 $out[] = ['id' => $kota['kr_kode']."|".$kota['jr_id'], 'name' => Funct::JURUSAN()[$kode] ];
    //                 if ($i == 0) {
    //                     $selected = $kota['kr_kode']."|".$kota['jr_id'];
    //                 }
    //             }
    //             // Shows how you can preselect a value
    //             echo Json::encode(['output' => $out, 'selected'=>$selected]);
    //             return;
    //         }
    //     }
    //     echo Json::encode(['output' => '', 'selected'=>'']);
    // }

    public function actionKlnjur() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = Kalender::find()->select(' kr_kode, jr_id ')->distinct(true)->andWhere(['kr_kode'=>$id])->asArray()->all();
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $kota) {
					$kode=$kota['jr_id'];
                    $out[] = ['id' => $kota['kr_kode']."|".$kota['jr_id'], 'name' => Funct::JURUSAN()[$kode] ];
                    if ($i == 0) {
                        $selected = $kota['kr_kode']."|".$kota['jr_id'];
                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }

    public function actionKlnpro() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
			$id=explode('|',$id);
            $list = Kalender::find()->andWhere(['jr_id'=>$id[1],'kr_kode'=>$id[0],])->asArray()->all();
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $kota) {
					$kode=$kota['pr_kode'];
					
                    $out[] = ['id' => $kota['kln_id'], 'name' => Funct::PROGRAM()[$kode] ];
                    if ($i == 0) {
                        $selected = $kota['kln_id'];
                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }

    public function actionDropmhs() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = Mahasiswa::find()->andWhere(['jr_id'=>$id,])->asArray()->all();
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $kota) {
					$kode=$kota['mhs_nim'];
                    $out[] = ['id' => $kode, 'name' => $kode /*Funct::MHS()[$kode]*/ ];
                    if ($i == 0) {
                        $selected = $kota['mhs_nim'];
                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }


    public function actionDropwali() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = Funct::DataWali($jns='jr',$kon="and kr_kode='$id'");
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
				$i=0;
                foreach ($list as $k=>$v) {
                    $out[] = ['id' => $k, 'name' => $v/*Funct::MHS()[$kode]*/ ];
                    if ($i == 0) {
                        $selected = $k;
                    }
					$i++;
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }


    public function actionDropwalids() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = Funct::DataWali($jns='ds',$kon="and concat(kr_kode,'#',jr_id)='$id'");
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
				$i=0;
                foreach ($list as $k=>$v) {
                    $out[] = ['id' => $k, 'name' => $v/*Funct::MHS()[$kode]*/ ];
                    if ($i == 0) {
                        $selected = $k;
                    }
					$i++;
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }




//=================== end drop ===============


    public function actionReportMatakuliah() {
 
        $searchModel = Matkul::find()->select('*');
        $dataProvider = new ActiveDataProvider([
            'query' => $searchModel,
                'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $models = $dataProvider->getModels();
        
        $content = '
                <table class="table table-bordered">
                    <tr>
                        <td align="center"><img src="'. Url::to("@web/ypkp.png") .'" width="10%"></td>
                        <td align="center"><h4>Universitas Sangga Buana YPKP Bandung</h4><p><b>Direktorat Sistem Informasi & Multimedia</b></p></td>
                    </tr>
                </table>
                <br>
                    <h4><b>Data Matakuliah</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Jurusan</th>
                                <th>Kode</th>
                                <th>Matakuliah</th>
                                <th>SKS</th>
                                <th>Semester</th>
                                <th>Jenis</th>
                            </tr>
                        </thead>
                    ';
        foreach ($models as $key => $value) {
            $a=" ";
                    if($value->mtk_jenis=='0'){$a=" Teori ";}
                    if($value->mtk_jenis=='1'){$a=" Praktek ";}
                    if($value->mtk_jenis=='2'){$a=" Teori + Praktek";}
            $content .= '
                    <tbody>
                        <tr>
                            <td>'.$value->jr->jr_jenjang.'-'.$value->jr->jr_nama.'</td>
                            <td>'.$value->mtk_kode.'</td>
                            <td>'.$value->mtk_nama.'</td>
                            <td>'.$value->mtk_sks.'</td>
                            <td>'.$value->mtk_semester.'</td>
                            <td>
                                '.$a.'
                            </td>
                        </tr>
                    </tbody>
                    ';
        }

        $content.='</table>';

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Data Matakuliah - Universitas Sangga Buana YPKP Bandung'], 
                'SetFooter'=>[' ('.date('d-m-Y H:i:S').') Halaman-{PAGENO}'],
            ]
        ]);
 
        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
 
        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

//kehadiran

 public function actionReportKehadiran($id) {
        $ModBn = BobotNilai::findOne($id);
        $searchModel = Kalender::find()->select('*');
        $dataProvider = new ActiveDataProvider([
            'query' => $searchModel,
                'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $models = $dataProvider->getModels();
       
        $content = '
                <table class="table table-bordered">
                    <tr>
                        <td align="center"><img src="'. Url::to("@web/ypkp.png") .'" width="10%"></td>
                        <td align="center"><h4>Universitas Sangga Buana YPKP Bandung</h4><p><b>Direktorat Sistem Informasi & Multimedia</b></p></td>
                    </tr>
                </table>
                <table class="table table-striped table-bordered">
                    <tr>
                        <td colspan="4" style="text-align:center">
                            <h3>DAFTAR HADIR UJIAN AKHIR SEESTER</h3>
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            <h5>Kode Matakuliah</h5>
                            <h5>Mata Kuliah</h5>
                            <h5>Nama Dosen</h5>
                            <h5>Hari, Tanggal / Jam</h5>
                        </td>
                        <td>
                            <h5>'.$ModBn->mtk_kode.'</h5>
                            <h5>'.$ModBn->mtk->mtk_nama.'</h5>
                            <h5>'.$ModBn->ds->ds_nm.'</h5>
                            <h5>&nbsp;</h5>
                        </td>
                        <td>
                            <h5>Ruangan</h5>
                            <h5>Program</h5>
                            <h5>Semester</h5>
                        </td>
                        <td>
                            <h5>tes</h5>
                            <h5>tes</h5>
                            <h5>tes</h5>
                        </td>
                    </tr>
                </table>
                <br>
                    <h4><b>Data Kalender Akademik</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Jurusan</th>
                                <th>Program</th>
                                <th>KRS</th>
                                <th>Perkuliahan</th>
                                <th>UTS</th>
                                <th>UAS</th>
                            </tr>
                        </thead>
                    ';
        

        $content.='</table>';

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Data Kalendaer Akademik - Universitas Sangga Buana YPKP Bandung'], 
                'SetFooter'=>[' ('.date('d-m-Y H:i:S').') Halaman-{PAGENO}'],
            ]
        ]);
 
        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
 
        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

//




    public function actionReportKalenderAkademik() {
 
        $searchModel = Kalender::find()->select('*');
        $dataProvider = new ActiveDataProvider([
            'query' => $searchModel,
                'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $models = $dataProvider->getModels();
       
        $content = '
                <table class="table table-bordered">
                    <tr>
                        <td align="center"><img src="'. Url::to("@web/ypkp.png") .'" width="10%"></td>
                        <td align="center"><h4>Universitas Sangga Buana YPKP Bandung</h4><p><b>Direktorat Sistem Informasi & Multimedia</b></p></td>
                    </tr>
                </table>
                <br>
                    <h4><b>Data Kalender Akademik</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Jurusan</th>
                                <th>Program</th>
                                <th>KRS</th>
                                <th>Perkuliahan</th>
                                <th>UTS</th>
                                <th>UAS</th>
                            </tr>
                        </thead>
                    ';
        foreach ($models as $key => $value) {
            $content .= '
                    <tbody>
                        <tr>
                            <td>'.$value->kr_kode.'</td>
                            <td>'.$value->jr->jr_jenjang.'-'.$value->jr->jr_nama.'</td>
                            <td>'.$value->pr->pr_nama.'</td>
                            <td>'.$value->kln_krs.'</td>
                            <td>'.$value->kln_masuk.'</td>
                            <td>'.$value->kln_uts.'</td>
                            <td>'.$value->kln_uas.'</td>
                        </tr>
                    </tbody>
                    ';
        }

        $content.='</table>';

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Data Kalendaer Akademik - Universitas Sangga Buana YPKP Bandung'], 
                'SetFooter'=>[' ('.date('d-m-Y H:i:S').') Halaman-{PAGENO}'],
            ]
        ]);
 
        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
 
        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    public function actionReportMahasiswa() {
 
        $searchModel = Mahasiswa::find()->select('*');
        $dataProvider = new ActiveDataProvider([
            'query' => $searchModel,
                'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $models = $dataProvider->getModels();
        
        $content = '
                <table class="table table-bordered">
                    <tr>
                        <td align="center"><img src="'. Url::to("@web/ypkp.png") .'" width="10%"></td>
                        <td align="center"><h4>Universitas Sangga Buana YPKP Bandung</h4><p><b>Direktorat Sistem Informasi & Multimedia</b></p></td>
                    </tr>
                </table>
                <br>
                    <h4><b>Data Mahasiswa</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Angkatan</th>
                                <th>Nama</th>
                                <th>Jurusan</th>
                            </tr>
                        </thead>
                    ';
        foreach ($models as $key => $value) {
            $content .= '
                    <tbody>
                        <tr>
                            <td>'.@$value->mhs_nim.'</td>
                            <td>'.@$value->mhs_angkatan.'</td>
                            <td>'.@$value->mhs->people->Nama.'</td>
                            <td>'.@$value->jr->jr_jenjang.'-'.@$value->jr->jr_nama.'</td>
                        </tr>
                    </tbody>
                    ';
        }

        $content.='</table>';

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Data Mahasiswa - Universitas Sangga Buana YPKP Bandung'], 
                'SetFooter'=>[' ('.date('d-m-Y H:i:S').') Halaman-{PAGENO}'],
            ]
        ]);
 
        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
 
        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    public function actionReportPengajar() {
 
        $searchModel = BobotNilai::find()->select('*');
        $dataProvider = new ActiveDataProvider([
            'query' => $searchModel,
                'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $models = $dataProvider->getModels();
        
        $content = '
                <table class="table table-bordered">
                    <tr>
                        <td align="center"><img src="'. Url::to("@web/ypkp.png") .'" width="10%"></td>
                        <td align="center"><h4>Universitas Sangga Buana YPKP Bandung</h4><p><b>Direktorat Sistem Informasi & Multimedia</b></p></td>
                    </tr>
                </table>
                <br>
                    <h4><b>Data Pengajar</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Tahun Akademik</th>
                                <th>Jurusan</th>
                                <th>Program</th>
                                <th>Matakuliah</th>
                                <th>Dosen</th>
                            </tr>
                        </thead>
                    ';
        foreach ($models as $key => $value) {
            $content .= '
                    <tbody>
                        <tr>
                            <td>'.@$value->kln->kr_kode.'</td>
                            <td>'.@$value->kln->jr->jr_jenjang.'-'.@$value->kln->jr->jr_nama.'</td>
                            <td>'.@$value->kln->pr->pr_nama.'</td>
                            <td>'.@$value->mtk->mtk_nama .'</td>
                            <td>'.@$value->ds->ds_nm.'</td>
                        </tr>
                    </tbody>
                    ';
        }

        $content.='</table>';

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Data Pengajar - Universitas Sangga Buana YPKP Bandung'], 
                'SetFooter'=>[' ('.date('d-m-Y H:i:S').') Halaman-{PAGENO}'],
            ]
        ]);
 
        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
 
        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    public function actionReportJadwalKuliah() {

       


        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs($_SESSION['query_jadwal']);

        //var_dump($dataProvider->getModels());die();
 
        //$searchModel = Jadwal::find()->select('*');
       /* $dataProvider = new ActiveDataProvider([
            'query' => $searchModel,
                'pagination' => [
                'pageSize' => 0,
            ],
        ]);*/
        $models = $dataProvider->getModels();
        
        $content = '
                <table class="table table-bordered">
                    <tr>
                        <td align="center"><img src="'. Url::to("@web/ypkp.png") .'" width="10%"></td>
                        <td align="center"><h4>Universitas Sangga Buana YPKP Bandung</h4><p><b>Direktorat Sistem Informasi & Multimedia</b></p></td>
                    </tr>
                </table>
                <br>
                    <h4><b>Data Jadwal Kuliah</b></h4>
                    <table style="font-size:10px" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Jurusan</th>
                                <th>Program</th>
                                <th>Hari</th>
                                <th>Jadwal</th>
                                <th>Kelas</th>
                                <th>Matakuliah</th>
                                <th>Nama Dosen</th>
                                <th>Ruang</th>
                            </tr>
                        </thead>
                    ';
        foreach ($models as $key => $value) {
            $a=" ";
                    if($value->jdwl_hari=='0'){$a="Minggu";}
                    if($value->jdwl_hari=='1'){$a="Senin";}
                    if($value->jdwl_hari=='2'){$a="Selasa";}
                    if($value->jdwl_hari=='3'){$a="Rabu";}
                    if($value->jdwl_hari=='4'){$a="Kamis";}
                    if($value->jdwl_hari=='5'){$a="Jumat";}
                    if($value->jdwl_hari=='6'){$a="Sabtu";}
            $content .= '
                    <tbody>
                        <tr>
                            <td>'.@$value->bn->kln->jr->jr_jenjang.'-'.@$value->bn->kln->jr->jr_nama.'</td>
                            <td>'.@$value->bn->kln->pr->pr_nama.'</td>
                            <td>'.@$a.'</td>
                            <td>'.@$value->jdwl_masuk.'-'.@$value->jdwl_keluar.'</td>
                            <td>'.@$value->jdwl_kls .'</td>
                            <td>'.@$value->bn->mtk->mtk_nama .'</td>
                            <td>'.@$value->bn->ds->ds_nm .'</td>
                            <td>'.@$value->rg->rg_nama .'</td>
                        </tr>
                    </tbody>
                    ';
        }

        $content.='</table>';

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Data Jadwal Kuliah - Universitas Sangga Buana YPKP Bandung'], 
                'SetFooter'=>[' ('.date('d-m-Y H:i:S').') Halaman-{PAGENO}'],
            ]
        ]);
 
        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
 
        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    public function actionReportJadwalUts() {
 
        $searchModel = Jadwal::find()->select('*');
        $dataProvider = new ActiveDataProvider([
            'query' => $searchModel,
                'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $models = $dataProvider->getModels();
        
        $content = '
                <table class="table table-bordered">
                    <tr>
                        <td align="center"><img src="'. Url::to("@web/ypkp.png") .'" width="10%"></td>
                        <td align="center"><h4>Universitas Sangga Buana YPKP Bandung</h4><p><b>Direktorat Sistem Informasi & Multimedia</b></p></td>
                    </tr>
                </table>
                <br>
                    <h4><b>Data Jadwal UTS</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Jurusan</th>
                                <th>Program</th>
                                <th>Jadwal</th>
                                <th>Kelas</th>
                                <th>Matakuliah</th>
                                <th>UTS</th>
                                <th>Ruang</th>
                            </tr>
                        </thead>
                    ';
        foreach ($models as $key => $value) {
            $content .= '
                    <tbody>
                        <tr>
                            <td>'.@$value->bn->kln->jr->jr_jenjang.'-'.@$value->bn->kln->jr->jr_nama.'</td>
                            <td>'.@$value->bn->kln->pr->pr_nama.'</td>
                            <td>'.@$value->jdwl_masuk.'-'.@$value->jdwl_keluar.'</td>
                            <td>'.@$value->jdwl_kls .'</td>
                            <td>'.@$value->bn->mtk->mtk_nama .'</td>
                            <td>'.@$value->jdwl_uts.'<br>'.@$value->jdwl_uts_out.'</td>
                            <td>'.@$value->rg_uts .'</td>
                        </tr>
                    </tbody>
                    ';
        }

        $content.='</table>';

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Data Jadwal UTS - Universitas Sangga Buana YPKP Bandung'], 
                'SetFooter'=>[' ('.date('d-m-Y H:i:S').') Halaman-{PAGENO}'],
            ]
        ]);
 
        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
 
        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    public function actionReportJadwalUas() {
 
        $searchModel = Jadwal::find()->select('*');
        $dataProvider = new ActiveDataProvider([
            'query' => $searchModel,
                'pagination' => [
                'pageSize' => 120,
            ],
        ]);
        $models = $dataProvider->getModels();
        
        $content = '
                <table class="table table-bordered">
                    <tr>
                        <td align="center"><img src="'. Url::to("@web/ypkp.png") .'" width="10%"></td>
                        <td align="center"><h4>Universitas Sangga Buana YPKP Bandung</h4><p><b>Direktorat Sistem Informasi & Multimedia</b></p></td>
                    </tr>
                </table>
                <br>
                    <h4><b>Data Jadwal UAS</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Jurusan</th>
                                <th>Program</th>
                                <th>Jadwal</th>
                                <th>Kelas</th>
                                <th>Matakuliah</th>
                                <th>UAS</th>
                                <th>Ruang</th>
                            </tr>
                        </thead>
                    ';
        foreach ($models as $key => $value) {
            $content .= '
                    <tbody>
                        <tr>
                            <td>'.@$value->bn->kln->jr->jr_jenjang.'-'.@$value->bn->kln->jr->jr_nama.'</td>
                            <td>'.@$value->bn->kln->pr->pr_nama.'</td>
                            <td>'.@$value->jdwl_masuk.'-'.@$value->jdwl_keluar.'</td>
                            <td>'.@$value->jdwl_kls .'</td>
                            <td>'.@$value->bn->mtk->mtk_nama .'</td>
                            <td>'.@$value->jdwl_uas.'<br>'.@$value->jdwl_uas_out.'</td>
                            <td>'.@$value->rg_uas .'</td>
                        </tr>
                    </tbody>
                    ';
        }

        $content.='</table>';

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Data Jadwal UAS - Universitas Sangga Buana YPKP Bandung'], 
                'SetFooter'=>[' ('.date('d-m-Y H:i:S').') Halaman-{PAGENO}'],
            ]
        ]);
 
        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
 
        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    public function actionCreateAbsensi($id,$krs){
        $jw = Jadwal::find()->where(['jdwl_id'=>$id])->one();
        $bn = BobotNilai::find()->where(['id'=>$jw->bn_id])->one();
        $mtk = Matkul::find()->where(['mtk_kode'=>$bn->mtk_kode])->one();

        $db = Yii::$app->db;
        for ($i=1; $i <= $mtk->mtk_sesi ; $i++) { 
            $sql = "
                MERGE into tbl_absensi AS ab
                USING (SELECT $krs as krs,$id as jd,$i as sesi) as t on t.krs=ab.krs_id and t.jd=ab.jdwl_id_ and ab.jdwl_sesi=t.sesi
                WHEN NOT MATCHED THEN
                INSERT (krs_id,jdwl_id_,jdwl_stat,jdwl_sesi,RStat)
                VALUES (t.krs,t.jd,0,t.sesi,0);
            ";
            $db->createCommand($sql)->execute();
        }
        $this->redirect(['akademik/jdw-detail','id'=>$id]);
    }

    public function actionReportDosenWali() {
 
        $searchModel = Dosen::find()->select('*');
        $dataProvider = new ActiveDataProvider([
            'query' => $searchModel,
                'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $models = $dataProvider->getModels();
       
        $content = '
                <table class="table table-bordered">
                    <tr>
                        <td align="center"><img src="'. Url::to("@web/ypkp.png") .'" width="10%"></td>
                        <td align="center"><h4>Universitas Sangga Buana YPKP Bandung</h4><p><b>Direktorat Sistem Informasi & Multimedia</b></p></td>
                    </tr>
                </table>
                <br>
                    <h4><b>Data Wali Dosen</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Jurusan</th>
                                <th>Program</th>
                                <th>Dosen Wali</th>
                                <th>Total Mahasiswa</th>
                            </tr>
                        </thead>
                    ';
        foreach ($models as $key => $value) {
            $content .= '
                    <tbody>
                        <tr>
                            <td>'.$value->jr->jr_jenjang.'-'.$value->jr->jr_nama.'</td>
                            <td>'.$value->pr->pr_nama.'</td>
                            <td>'.$value->ds_nm.'</td>
                            <td>'.$value->tot.'</td>
                        </tr>
                    </tbody>
                    ';
        }

        $content.='</table>';

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Data Dosen Wali - Universitas Sangga Buana YPKP Bandung'], 
                'SetFooter'=>[' ('.date('d-m-Y H:i:S').') Halaman-{PAGENO}'],
            ]
        ]);
 
        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
 
        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }

    public function actionCetakAbsensi($id,$jenis){
			$tgl="jdwl_uts";
			$rg = 'jd.rg_uts';

            if ($jenis==1) {
                $jenis = "UJIAN TENGAH SEMESTER";
                $rg = 'jd.rg_uts';
            }else{
				$tgl="jdwl_uas";
                $jenis = "UJIAN AKHIR SEMESTER";
                $rg = 'jd.rg_uas';
            }
            
            //$this->layout = "";
            $sql ="SELECT
                    matakuliah = mk.mtk_kode + ' / ' + mk.mtk_nama,
                    dosen = ds.ds_nm,
                    tanggal = CAST (jd.$tgl AS DATE),
                    jam = CAST (jd.$tgl AS TIME(0)),
                    ruang = rg.rg_nama,
                    kode = jr.jr_id,
                    kelas = jd.jdwl_kls,
                    program = UPPER(pr.pr_nama),
                    jurusan = UPPER( jr.jr_jenjang +' '+ jr.jr_nama),
                    semester = kr.kr_nama,
                    kl.kr_kode
                FROM
                    tbl_jadwal jd
                JOIN tbl_bobot_nilai bn ON bn.id = jd.bn_id
                JOIN tbl_matkul mk ON mk.mtk_kode = bn.mtk_kode
                JOIN tbl_dosen ds ON ds.ds_id = bn.ds_nidn
                JOIN tbl_ruang rg ON rg.rg_kode = $rg
                JOIN tbl_kalender kl ON kl.kln_id = bn.kln_id
                JOIN tbl_program pr on pr.pr_kode = kl.pr_kode
                JOIN tbl_jurusan jr on jr.jr_id = kl.jr_id
                JOIN tbl_kurikulum kr on kr.kr_kode = kl.kr_kode
                WHERE
                    jd.jdwl_id = $id";

            $header = Yii::$app->db->createCommand($sql)->queryOne();

            if (empty($header)) {
                die("JADWAL UJIAN BELUM DI SETTING");
            }
                    

            $db = Yii::$app->db1;
            $keuangan = Funct::getDsnAttribute('dbname', $db->dsn);

            if ($jenis=='UJIAN AKHIR SEMESTER') {

            	$sql = "SELECT DISTINCT kr.mhs_nim, IIF(ISNULL(lunas,'')<>'' OR SUBSTRING(kr.mhs_nim,11,1) IN(3,4)
				OR SUBSTRING(kr.mhs_nim,1,4) in('B202','A201','A202','2212','1211','1212')
				, p.Nama,'')as nama, 
            			absen =( select sum(iif(jdwl_stat=1,1,0)) from tbl_absensi where kr.krs_id=krs_id )
						FROM tbl_absensi ab 
						JOIN tbl_krs kr on kr.krs_id = ab.krs_id 
						JOIN tbl_mahasiswa mh ON mh.mhs_nim = kr.mhs_nim 
						JOIN tbl_jadwal jd on jd.jdwl_id = kr.jdwl_id
						JOIN tbl_bobot_nilai bn on bn.id = jd.bn_id
						JOIN $keuangan.dbo.student s ON s.nim COLLATE Latin1_General_CI_AS = mh.mhs_nim 
						JOIN $keuangan.dbo.people p ON p.No_Registrasi = s.no_registrasi
						LEFT JOIN (SELECT nim, lunas ='OK' FROM $keuangan.dbo.pembayarankrs WHERE tahun = '$header[kr_kode]' 
							       AND (status = 'Lunas' or sisa  <= 0)) ht
						ON ht.nim COLLATE Latin1_General_CI_AS = kr.mhs_nim
						WHERE kr.jdwl_id = $id
						and(
							(kr.RStat is null or kr.RStat='0')
							and (mh.RStat is null or mh.RStat='0')
						)
						ORDER BY kr.mhs_nim
						";

            }else{
            	$sql =" 
				SELECT DISTINCT kr.mhs_nim,p.Nama as nama,
					absen =(
						select sum(iif(jdwl_stat=1,1,0)) 
						from tbl_absensi
						where kr.krs_id=krs_id 
					)
					FROM tbl_absensi ab
                    JOIN tbl_krs kr on kr.krs_id = ab.krs_id
                    JOIN tbl_mahasiswa mh ON mh.mhs_nim = kr.mhs_nim
                    JOIN $keuangan.dbo.student s ON s.nim COLLATE Latin1_General_CI_AS  = mh.mhs_nim
                    JOIN $keuangan.dbo.people p ON p.No_Registrasi = s.no_registrasi
                    WHERE kr.jdwl_id = $id 
					and (
						(kr.RStat is null or kr.RStat='0')
						and (mh.RStat is null or mh.RStat='0')
					)
					ORDER BY mhs_nim 
					";
            }
             
            $data = Yii::$app->db->createCommand($sql)->queryAll();             
            return $this->renderPartial('cetak_absensi', [
                'data' => $data,
                'header' => $header,
                'jenis' => $jenis,
            ]);
        
    }

      public function actionCetakAbsensiUjian($id,$jenis){
            $tgl="jdwl_uts";
            $rg = 'jd.rg_uts';

            if ($jenis==1) {
                $jenis = "UJIAN TENGAH SEMESTER";
                $rg = 'jd.rg_uts';
            }else{
                $tgl="jdwl_uas";
                $jenis = "UJIAN AKHIR SEMESTER";
                $rg = 'jd.rg_uas';
            }
            
            $this->layout = "blank";
            $sql ="SELECT
                    matakuliah = mk.mtk_kode + ' / ' + mk.mtk_nama,
                    dosen = ds.ds_nm,
                    tanggal = CAST (jd.$tgl AS DATE),
                    jam = CAST (jd.$tgl AS TIME(0)),
                    ruang = rg.rg_nama,
                    kode = jr.jr_id,
                    kelas = jd.jdwl_kls,
                    program = UPPER(pr.pr_nama),
                    jurusan = UPPER( jr.jr_jenjang +' '+ jr.jr_nama),
                    semester = kr.kr_nama,
                    kl.kr_kode
                FROM
                    tbl_jadwal jd
                JOIN tbl_bobot_nilai bn ON bn.id = jd.bn_id
                JOIN tbl_matkul mk ON mk.mtk_kode = bn.mtk_kode
                JOIN tbl_dosen ds ON ds.ds_id = bn.ds_nidn
                JOIN tbl_ruang rg ON rg.rg_kode = $rg
                JOIN tbl_kalender kl ON kl.kln_id = bn.kln_id
                JOIN tbl_program pr on pr.pr_kode = kl.pr_kode
                JOIN tbl_jurusan jr on jr.jr_id = kl.jr_id
                JOIN tbl_kurikulum kr on kr.kr_kode = kl.kr_kode
                WHERE
                    jd.jdwl_id = $id";



            $header =   Yii::$app->db->createCommand($sql)->queryOne();     
                    

            $db = Yii::$app->db1;
            $keuangan = Funct::getDsnAttribute('dbname', $db->dsn);

            if ($jenis=='UJIAN AKHIR SEMESTER') {

                $sql = "SELECT DISTINCT kr.mhs_nim, IIF(ISNULL(lunas,'')<>'' OR SUBSTRING(kr.mhs_nim,11,1) IN(3,4)
				or SUBSTRING(kr.mhs_nim,1,4) in('B202','A201','A202')
				, p.Nama,'')as nama, 
                        absen =( select sum(iif(jdwl_stat=1,1,0)) from tbl_absensi where kr.krs_id=krs_id )
                        FROM tbl_absensi ab 
                        JOIN tbl_krs kr on kr.krs_id = ab.krs_id 
                        JOIN tbl_mahasiswa mh ON mh.mhs_nim = kr.mhs_nim 
                        JOIN tbl_jadwal jd on jd.jdwl_id = kr.jdwl_id
                        JOIN tbl_bobot_nilai bn on bn.id = jd.bn_id
                        JOIN $keuangan.dbo.student s ON s.nim COLLATE Latin1_General_CI_AS = mh.mhs_nim 
                        JOIN $keuangan.dbo.people p ON p.No_Registrasi = s.no_registrasi
                        LEFT JOIN (SELECT nim, lunas ='OK' FROM $keuangan.dbo.pembayarankrs WHERE tahun = '$header[kr_kode]' 
                                   AND (status = 'Lunas' or sisa  <= 0)) ht
                        ON ht.nim COLLATE Latin1_General_CI_AS = kr.mhs_nim
                        WHERE kr.jdwl_id = $id
                        and(
                            (kr.RStat is null or kr.RStat='0')
                            and (mh.RStat is null or mh.RStat='0')
                        )
                        ORDER BY kr.mhs_nim
                        ";

            }else{
                $sql =" 
                SELECT DISTINCT kr.mhs_nim,p.Nama as nama,
                    absen =(
                        select sum(iif(jdwl_stat=1,1,0)) 
                        from tbl_absensi
                        where kr.krs_id=krs_id 
                    )
                    FROM tbl_absensi ab
                    JOIN tbl_krs kr on kr.krs_id = ab.krs_id
                    JOIN tbl_mahasiswa mh ON mh.mhs_nim = kr.mhs_nim
                    JOIN $keuangan.dbo.student s ON s.nim COLLATE Latin1_General_CI_AS  = mh.mhs_nim
                    JOIN $keuangan.dbo.people p ON p.No_Registrasi = s.no_registrasi
                    WHERE kr.jdwl_id = $id 
                    and (
                        (kr.RStat is null or kr.RStat='0')
                        and (mh.RStat is null or mh.RStat='0')
                    )
                    ORDER BY mhs_nim 
                    ";
            }

             echo $sql;

              die();

            $data = Yii::$app->db->createCommand($sql)->queryAll();             
            $table = $this->render('cetak_absensi_pdf', [
                'data' => $data,
                'header' => $header,
                'jenis' => $jenis,
            ]);

            //return $table;

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8, 
            // A4 paper format
            'format' => Pdf::FORMAT_LEGAL,
            'defaultFont' => 'Sans-Serif',
            //'defaultFontSize' => 12,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $table,  
            /*'cssInline' => '@page {  margin-top: 0.5cm;margin-left: 1.5cm;margin-bottom: 1cm;margin-right:0.9cm }
                            div.page { page-break-before: always }',*/
            'cssInline' => '* {
							  font-family: Sans-Serif;
							}
							@page {  margin-top: 0.1cm;margin-left: 0.3cm;margin-bottom: 1cm;margin-right:0cm }
                            @media all {
                                .page-break { display: none; }
                            }
                            @media print {
                                .page-break { display: block; page-break-before: always; }
                            }',
            
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
         
            'methods' => [ 
                'SetHeader'=>[''], 
                'SetFooter'=>[''],
            ]
        ]);
 
        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        $headers->add('Content-Disposition: inline; filename="Laporan Kehadiran"');
        $headers->add('Content-Transfer-Encoding: binary');
        $headers->add('Accept-Ranges: bytes');
 
        // return the pdf output as per the destination setting
        return $pdf->render(); 
        
    }

    public function actionCetakJadwal(){

    	$sql = "SELECT TOP 100 jdwl_hari, CONCAT(jdwl_masuk,' - ',jdwl_keluar) jam,
				mk.mtk_kode,mk.mtk_nama,ds.ds_nm, jdwl_kls, rg.rg_nama  FROM tbl_jadwal jd
				JOIN tbl_bobot_nilai bn on bn.id = jd.bn_id
				JOIN tbl_matkul mk on mk.mtk_kode = bn.mtk_kode
				LEFT JOIN tbl_dosen ds on ds.ds_id = bn.ds_nidn
				LEFT JOIN tbl_ruang rg on rg.rg_kode = jd.rg_kode
				ORDER BY jdwl_hari,jam";

		$data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $table ="<table width='100%'>
                  <tbody><tr>
                    <td style='width: 10%;''>PROGRAM</td>
                    <td style='width: 70%''>REGULER PAGI<br></td>
                    <td>TANGGAL CETAK<br></td>
                    <td>12-12-2016</td>
                  </tr>
                  <tr>
                    <td>ROGRAM STUDI<br></td>
                    <td>S1 - AKUNTANSI<br></td>
                    <td>SEMESTER</td>
                    <td>GENAP</td>
                  </tr>
                </tbody></table>";
		$table .=	"<table border='1' width='100%' colspacing='0'  style='font-size:10px' class='table table-striped table-bordered table-condensed'>
					  <tr>
					    <th align='center' style='padding: 10px;'>Hari</th>
					    <th align='center'>Jam</th>
					    <th align='center'>Kode</th>
					    <th align='center'>Matakuliah</th>
					    <th align='center'>Dosen</th>
					    <th align='center'>Kelas</th>
					    <th align='center'>Ruang</th>
					  </tr>";

					foreach ($data as $k) {
						
		$table .=  "<tr>
					    <td>".Funct::HARI()[$k['jdwl_hari']]."</td>
					    <td>$k[jam]</td>
					    <td>$k[mtk_kode]</td>
					    <td>$k[mtk_nama]</td>
					    <td>$k[ds_nm]</td>
					    <td>$k[jdwl_kls]</td>
					    <td>$k[rg_nama]</td>
					  </tr>";
					}

		$table .=	"</table>";

        //die($table);

		 
		// setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
             
            'defaultFontSize' => 12,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $table,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>[''], 
                'SetFooter'=>[' ('.date('d-m-Y H:i:S').') Halaman-{PAGENO}'],
            ]
        ]);
 
        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
		$headers->add('Content-Disposition: inline; filename="Jadwal Kuliah"');
		$headers->add('Content-Transfer-Encoding: binary');
		$headers->add('Accept-Ranges: bytes');
 
        // return the pdf output as per the destination setting
        return $pdf->render(); 


		$this->layout = "blank";
		return $this->render('cetak_jadwal',[
                'data' => $data,
                //'header' => $header,
                //'jenis' => $jenis,
            ]);


    }

	// absen harian
    public function actionCetakAbsen($id,$jenis=1){

           	$rg = 'jd.rg_kode';
            
            $this->layout = "blank";
            $sql ="SELECT
                    matakuliah = mk.mtk_kode + ' / ' + mk.mtk_nama,
                    dosen = ds.ds_nm,
                    tanggal = CAST (jd.jdwl_uts AS DATE),
                    jam = concat(convert(varchar(5),CAST (jd.jdwl_masuk AS TIME(0)),108), ' - ' , convert(varchar(5),CAST (jd.jdwl_keluar AS TIME(0)),108) ),
                    ruang = rg.rg_nama,
                    kode = jr.jr_id,
                    kelas = jd.jdwl_kls,
                    program = UPPER(pr.pr_nama),
                    jurusan = UPPER( jr.jr_jenjang +' '+ jr.jr_nama),
                    semester = kr.kr_nama,
					hari	= jd.jdwl_hari
                FROM
                    tbl_jadwal jd
                JOIN tbl_bobot_nilai bn ON bn.id = jd.bn_id
                JOIN tbl_matkul mk ON mk.mtk_kode = bn.mtk_kode
                JOIN tbl_dosen ds ON ds.ds_id = bn.ds_nidn
                JOIN tbl_ruang rg ON rg.rg_kode = $rg
                JOIN tbl_kalender kl ON kl.kln_id = bn.kln_id
                JOIN tbl_program pr on pr.pr_kode = kl.pr_kode
                JOIN tbl_jurusan jr on jr.jr_id = kl.jr_id
                JOIN tbl_kurikulum kr on kr.kr_kode = kl.kr_kode
                WHERE
                jd.jdwl_id = $id
				and(
					(bn.RStat is null or bn.RStat='0')
					and (ds.RStat is null or ds.RStat='0')
					and (kl.RStat is null or kl.RStat='0')
				)
				
				"

				;
            $header =   Yii::$app->db->createCommand($sql)->queryOne();                

            $db = Yii::$app->db1;
            $keuangan = Funct::getDsnAttribute('dbname', $db->dsn);
            $sql =" SELECT DISTINCT kr.mhs_nim,p.Nama as nama,
					absen =(
						select sum(iif(jdwl_stat=1,1,0)) 
						from tbl_absensi
						where kr.krs_id=krs_id 
					)
					FROM tbl_absensi ab
                    JOIN tbl_krs kr on kr.krs_id = ab.krs_id
                    JOIN tbl_mahasiswa mh ON mh.mhs_nim = kr.mhs_nim
                    JOIN $keuangan.dbo.student s ON s.nim COLLATE Latin1_General_CI_AS  = mh.mhs_nim
                    JOIN $keuangan.dbo.people p ON p.No_Registrasi = s.no_registrasi
                    WHERE kr.jdwl_id = '$id' 
					and(
						(kr.RStat is null or kr.RStat='0')
						and (mh.RStat is null or mh.RStat='0')
					)
					ORDER BY mhs_nim 
					
					
					";
            $data = Yii::$app->db->createCommand($sql)->queryAll();             
            return $this->render('cetak_absen',[
                'data' => $data,
                'header' => $header,
                'jenis' => $jenis,
            ]);
        
    }
	// ouo

}
