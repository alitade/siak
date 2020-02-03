<?php

namespace app\controllers;
use yii\web\Session;
use yii\data\ActiveDataProvider;
use Yii;
use app\models\Fakultas;
use app\models\FakultasSearch;


use app\models\Matkul;
use app\models\MatkulSearch;

use kartik\mpdf\Pdf;
use yii\helpers\Url;

use app\models\Kalender;
use app\models\KalenderSearch;

use app\models\Jurusan;

use app\models\Jadwal;
use app\models\JadwalSearch;

use app\models\Krs;
use app\models\KrsSearch;

use app\models\BobotNilai;
use app\models\BobotNilaiSearch;

use app\models\Program;

use app\models\Mahasiswa;
use app\models\MahasiswaSearch;

use app\models\Dosen;
use app\models\DosenSearch;

use app\models\KPembayarankrs;

use app\models\Funct;


use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

use yii\db\Query;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;

$connection = \Yii::$app->db;


class PascaController extends Controller
{
	public function J(){
		return    yii::$app->user->identity->target;		
	}
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


	public function actionIndex(){return $this->render('@app/views/site/index');}
	 
	/* Matakul */	
	public function actionMtk(){
        $searchModel = new MatkulSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,['jr_id'=>$this->J()]);
        return $this->render('mtk_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
    public function actionMtkView($id)
    {
        $model = Matkul::findOne(['mtk_kode'=>$id,'jr_id'=>$this->J()]);
		if($model){
			return $this->render('mtk_view', ['model' => $model]);
		}else{
			throw new NotFoundHttpException();
		}
			
    }


    public function actionMtkCreate(){
        $model = new Matkul;
        if ($model->load(Yii::$app->request->post()) ){ 
			$model->jr_id=$this->J();
			$model->mtk_stat='1';
			if( $model->save()) {
				\app\models\Funct::LOGS("Manambah Data Matakuliah ",$model,$model->mtk_kode,'c');
				return $this->redirect(['mtk-view', 'id' => $model->mtk_kode]);
			}
        } 
            return $this->render('mtk_create', [
                'model' => $model,
				'J'=>$this->J()
            ]);

    }

    public function actionMtkUpdate($id){
        $model=Matkul::findOne(['mtk_kode'=>$id,'jr_id'=>$this->J()]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Mengubah Data Matakuliah ",new Matkul,$id,'d');
            return $this->redirect(['mtk-view', 'id' => $model->mtk_kode]);
        } else {
            return $this->render('mtk_update', [
                'model' => $model,
            ]);
        }
    }

    public function actionMtkDelete($id){
        $model=Matkul::findOne(['mtk_kode'=>$id,'jr_id'=>$this->J()]);
		$model->RStat=1;
		$model->save();
		\app\models\Funct::LOGS("Manghapus Data Matakuliah ($id)",$model,$id,'d');
        return $this->redirect(['mtk_index']);
    }
	/* end Matkul */

	/* Kalender */
    public function actionKln()
    {
        $searchModel = new KalenderSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),['jr_id'=>$this->J()]);

        return $this->render('kln_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionKlnView($id)
    {
       
	    $model = Kalender::findOne(['kln_id'=>$id,'jr_id'=>$this->J()]);
		if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
			return $this->redirect(['kln-view', 'id' => $model->kln_id]);
		}else{
			return $this->render('kln_view', ['model' => $model]);	
		}
    }

    public function actionKlnCreate(){
        $model = new Kalender;
        if ($model->load(Yii::$app->request->post()) ) {
			$model->kln_stat		='1';
			$model->jr_id=$this->J();
			$Dkrs 	= date_create($model->kln_krs);
			$Dkrs1 	= date_create($model->kln_krs_lama);
			$Dkrs1 	= date_diff($Dkrs,$Dkrs1);
			$model->kln_krs_lama= $Dkrs1->format('%R%a');

			$Duts 	= date_create($model->kln_uts);
			$Duts1 	= date_create($model->kln_uts_lama);
			$Duts1 	= date_diff($Duts,$Duts1);
			$model->kln_uts_lama=$Duts1->format("%R%a");
			
			$Duas 	= date_create($model->kln_uas);
			$Duas1 	= date_create($model->kln_uas_lama);
			$Duas1 	= date_diff($Duas,$Duas1);
			$model->kln_uas_lama=$Duts1->format("%R%a");
			if($model->save()){
				\app\models\Funct::LOGS("Manambah Data Kalender Akademik ",$model,$model->kln_id,'c');
				return $this->redirect(['kln']);
			}
        }
		return $this->render('/pasca/kln_create', ['model' => $model,]);
    }
	
    public function actionKlnUpdate($id){
        $model=Kalender::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Mangubah Data Kalender Akademik ",new Kalender,$model->kln_id,'u');
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
		\app\models\Funct::LOGS("Menghapus Data Kalender Akademik ",new Kalender,$model->kln_id,'d');
        return $this->redirect(['kln']);
    }
	/* End Kalender */

	/* Dosen */
    public function actionDsn(){
        $searchModel = new DosenSearch;
        $dataProvider = $searchModel->cari(Yii::$app->request->getQueryParams(),['mhs.jr_id'=>$this->J()]);
        return $this->render('dsn_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionDsnCreate(){
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
			return $this->render('dsn_view', ['model' => $model]);	
		}
    }

    public function actionDsnWali($id,$p){
        $model = Dosen::findOne($id);

        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),
				['ds_wali'=>$model->ds_id,'jr_id'=>$this->J(),'pr_kode'=>$p]
		);
		
		if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
			return $this->redirect(['dsn-wali', 'id' => $model->ds_id,'p'=>$p]);
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
    public function actionJdw()
    {
		
		if(isset($_POST['kalender'])){
			Yii::$app->session->open();
			if(!empty($_POST['kalender']['tahun'])){
				$_SESSION['Ckln']=(int)$_POST['kalender']['tahun'];
			}else {unset($_SESSION['Ckln']);}
			//return $this->redirect(['jdw']);
		}

        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),['jr.jr_id'=>$this->J()]);

        return $this->render('jdw_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
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
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),"(jdwl_uts is not null and jr.jr_id='".$this->J()."' )",["jdwl_uts"=>SORT_ASC]);

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
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),"(jdwl_uas is not null)",["jdwl_uas"=>SORT_ASC]);

        return $this->render('jdw_uas', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionJdwView($id)
    {
        $model =  Jadwal::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Mengubah Data Penjadwalan",new Jadwal,$id,'u');
        	return $this->redirect(['jdw-view', 'id' => $model->jdwl_id]);
        } else {
        return $this->render('jdw_view', ['model' => $model]);
		}
    }

    public function actionJdwCreate()
    {
        $model = new Jadwal;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Menambah Data Penjadwalan",$model,$model->jdwl_id,'c');
            return $this->redirect(['view', 'id' => $model->jdwl_id]);
        }// else {
            return $this->render('create', [
                'model' => $model,
            ]);
        //}
    }

    public function actionJdwUpdate($id){
        $model =  Jadwal::findOne($id);
        $model2 =  Jadwal::findOne($id);
        if ($model->load(Yii::$app->request->post())){
			//die($model->jdwl_uts." ".$_POST['Jadwal']['jdwl_uts']);
			
			if(	$model2->bn_id==$model->bn_id && 
				$model2->jdwl_hari==$model->jdwl_hari && 
				$model2->jdwl_masuk==$model->jdwl_masuk && 
				$model2->jdwl_keluar==$model->jdwl_keluar && 
				$model2->rg_kode==$model->rg_kode){
				$model->save(false);
				\app\models\Funct::LOGS("Mengubah Data Penjadwalan",new Jadwal,$id,'u');
				//die(print_r($model->getErrors()));
			}else{
				$model->save(false);
				\app\models\Funct::LOGS("Mengubah Data Penjadwalan",new Jadwal,$id,'u');
			}
				return $this->redirect(['jdw-view', 'id' => $model->jdwl_id]);
        } else {
            return $this->render('jdw_update', [
                'model' => $model,
            ]);
        }
    }

    public function actionJdwDelete($id){
        $model=$this->findModel($id);
		$model->RStat=1;
		if($model->save()){
			\app\models\Funct::LOGS("Menghapus Data Penjadwalan",new Jadwal,$id,'d');
		}
        return $this->redirect(['jdw']);
    }

    public function actionJdwDetail($id)
    {
        $model= Jadwal::findOne($id);
		$searchModel = new KrsSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),['jdwl_id'=>$id]);

        return $this->render('jdw_detail', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model' => $model,
        ]);
    }
	/* end jadwal */

	/* Pengajar */
    public function actionAjr()
    {
        $searchModel = new BobotNilaiSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),"tbl_bobot_nilai.kln_id in(select kln_id from tbl_kalender where jr_id='".$this->J()."')");

        return $this->render('ajr_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
			'J' => $this->J(),
        ]);
    }

	public function actionAjrCreate(){
        $model = new BobotNilai;

        if ($model->load(Yii::$app->request->post())) {
			if($model->save()){
				\app\models\Funct::LOGS("Manambah Data Pengajar",$model,$model->id,'c');
				return $this->redirect(['ajr-view', 'id' => $model->id]);
			}
			
        }
            return $this->render('ajr_create', [
                'model' => $model,
				'J' => $this->J(),
            ]);
    }

   	public function actionAjrView($id){
        $model_jadwal = new Jadwal;
        $model_jadwal->bn_id = $id;
        $model_jadwal->semester = '1';
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),['bn_id'=>$id,'kl.jr_id'=>$this->J()]);
        $ModBn = BobotNilai::find()->where("id='$id' and kln_id in (select kln_id from tbl_kalender where jr_id='".$this->J()."')")->one();
		//die(print_r($ModBn));
		//if(!$ModBn)$ModBn=NULL;
        $model = new BobotNilai;
		
        if ($model_jadwal->load(Yii::$app->request->post()) && $model_jadwal->save()) {
			\app\models\Funct::LOGS("Menambah Data Penjadwalan ",$model_jadwal,$model_jadwal->jdwl_id,'c');
            return $this->redirect(['ajr-view', 'id' => $id]);
        } 
        else {
        return $this->render('ajr_jdw', [
            'dataProvider' => $dataProvider,
            'model'=>$model,
            'ModBn'=>$ModBn,
            'model2' =>$model_jadwal,
            'searchModel' =>$searchModel,
            'id'=>$id,
            ]);
        }
        
    }

    public function actionAjrUpdate($id){
        $model 		=  BobotNilai::find()->where("id='$id' and kln_id in (select kln_id from tbl_kalender where jr_id='".$this->J()."')")->one();
		//die(print_r($model));
		$Kalender 	=  Kalender::findOne($model->kln_id);
		
		if($model){
			if (@$model->load(Yii::$app->request->post()) && $model->save()) {
				\app\models\Funct::LOGS("Mengubah  Data Pengajar",new BobotNilai,$id,'u');
				return $this->redirect(['ajr-view', 'id' => $model->id]);
			} else {
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
		return $this->redirect(['ajr']);

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
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),['jr.jr_id'=>$this->J()]);

        return $this->render('mhs_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    public function actionMhsView($id)
    {
        $model 	=  Mahasiswa::findOne($id);

        $searchKrs 	= new KrsSearch;
        $dataKrs 	= $searchKrs->search(Yii::$app->request->getQueryParams());

		$ModKe	=  KPembayarankrs::find()
		->where(['nim'=>$id])
		->orderBy(['substring(tahun,2,2)'=>SORT_ASC,'substring(tahun,1,1)'=>SORT_ASC,])
		;
		$ModKe = new ActiveDataProvider([
            'query' => $ModKe,
        ]);

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
				
			")
			->orderBy(['substring(kln.kr_kode,2,4)'=>SORT_ASC,'substring(kln.kr_kode,1,1)'=>SORT_ASC,'bn.mtk_kode'=>SORT_ASC])
			;

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
					'model' => $model,
					'ThnAkdm'=>$dataProvider,
					'ModKe'=>$ModKe,
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
			->select(' kln.kr_kode, krs.mhs_nim , bn.mtk_kode ,mk.mtk_nama, mk.mtk_sks,krs_grade,ds_nidn')
			->from('tbl_krs krs, tbl_jadwal jd,tbl_bobot_nilai bn, tbl_kalender kln, tbl_matkul mk')
			->where("
				krs.jdwl_id = jd.jdwl_id 
				and bn.id=jd.bn_id
				and bn.mtk_kode=mk.mtk_kode
				and bn.kln_id=kln.kln_id
				and mhs_nim='$model->mhs_nim'
				and bn.kln_id='$kode'
				
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
			$ITEM[$n]['Kode']=$d['mtk_kode'];
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
		
		$IPK = $TotGrade/$TotKrs;
		$dataProvider = new ArrayDataProvider([
			'allModels'=>$ITEM,
			'pagination' => [
        		'pageSize' => 20,
    		],

		]);


        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
			\app\models\Funct::LOGS("Mengubah Data Mahasiswa($model->mhs_nim) ",$model,$model->mhs_nim,'u');
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



    public function actionMhsCreate(){
        $model = new Mahasiswa;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Manambah Data Mahasiswa($model->mhs_nim) ",$model,$model->mhs_nim,'c');
            return $this->redirect(['mhs-view', 'id' => $model->mhs_nim]);
        } //else {
            return $this->render('mhs_create', [
                'model' => $model,
				
            ]);
        //}
    }

    public function actionMhsUpdate($id){
        $model =  Mahasiswa::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \app\models\Funct::LOGS("Mengubah Data Mahasiswa($model->mhs_nim) ",new Mahasiswa(),$id,'u');
			return $this->redirect(['mhs-view', 'id' => $model->mhs_nim]);
        } //else {
            return $this->render('mhs_update', [
                'model' => $model,
            ]);
        //}
    }

    public function actionMhsDelete($id){
        $model=$this->findModel($id);
		$model->RStat=1;
		$model->save();
		\app\models\Funct::LOGS("Manghapus Data Mahasiswa($model->mhs_nim)",new Mahasiswa(),$id,'d');
        return $this->redirect(['index']);
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

    public function actionKrsCreate(){
        $model = new Krs;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Menambah Data Krs($model->krs_id) ",$model,$model->krs_id,'c');
            return $this->redirect(['view', 'id' => $model->krs_id]);
        } //else {
            return $this->render('create', [
                'model' => $model,
            ]);
        //}
    }

    public function actionKrsUpdate($id){
        $model=Krs::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Mengubah Data KRS($model->krs_id) ",new krs,$id,'u');
            return $this->redirect(['view', 'id' => $model->krs_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionKrsDelete($id){
        $model=Krs::findOne($id);
		$model->RStat=1;
		$model->save();
		\app\models\Funct::LOGS("Menghapus Data KRS($model->krs_id) ",new krs,$id,'u');
        return $this->redirect(['index']);
    }
	/* end KRS */
	

    public function actionView($id){
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate(){
        $model = new Fakultas();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->fk_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id){
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->fk_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id){
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
                foreach ($list as $i => $kota) {
                    $out[] = ['id' => $kota['mtk_kode'], 'name' => $kota['mtk_kode']." ". $kota['mtk_nama'] ];
                    if ($i == 0) {
                        $selected = $kota['mtk_kode'];
                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }

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
            $list = Kalender::find()->andWhere(['jr_id'=>$this->J(),'kr_kode'=>$id,])->asArray()->all();
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
                    <h4><b>Data Jadwal Kuliah</b></h4>
                    <table class="table table-striped table-bordered">
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
	// input KRS
	public function actionKrsT(){
    $db     = Yii::$app->db;
    $ID     = @$_GET['nim']; 
	
    $krs    =new Krs;
    $jr     =Jurusan::findOne($this->J());

    $mhs    = Mahasiswa::findOne(['mhs_nim'=>$ID,'jr_id'=>$this->J()]);
	
    $pr     =Program::findOne(@$mhs->pr_kode);
	$ds     =Dosen::findOne(@$mhs->ds_wali);
    $sqlon = "
        select 
            tbl_jadwal.*
        from 
            tbl_jadwal join tbl_krs on (tbl_krs.jdwl_id=tbl_jadwal.jdwl_id)
        where
            tbl_krs.mhs_nim='".$ID."'
    ";

    if(isset($_GET['Krs']['kurikulum'])){
        if(empty($_GET['Krs']['kurikulum'])){
        	return $this->redirect(['bisa/krs-t']);
        }else{
        //$kr=explode('#',$_GET['Krs']['kurikulum']);
        $kr = $_GET['Krs']['kurikulum'];
        $sql="Select * from 
            tbl_krs join tbl_jadwal j on(tbl_krs.jdwl_id=j.jdwl_id)
            left join tbl_bobot_nilai bn on(j.bn_id=bn.id)
            left join tbl_kalender k on(k.kln_id=bn.kln_id)
            left join tbl_matkul m on(m.mtk_kode=bn.mtk_kode)
            left join tbl_dosen d on(d.ds_nidn=bn.ds_nidn) 
            where tbl_krs.mhs_nim='".$ID."' and k.kr_kode='$kr' and 
			(tbl_krs.RStat='0' or tbl_krs.RStat is null)
			
            ORDER BY m.mtk_semester ASC
            ";
            $model = new SqlDataProvider([
                    'sql'=>$sql,
                    'pagination' => [
                          'pageSize' => 0,
                     ],
                ]);            
            $model1 = new SqlDataProvider([
                    'sql'=>$sqlon,
                ]);
            }
        $sks = "
        Select  
            sum(m.mtk_sks) as sks
        from 
            tbl_krs join tbl_jadwal j on(tbl_krs.jdwl_id=j.jdwl_id)
            left join tbl_bobot_nilai bn on(j.bn_id=bn.id)
            left join tbl_kalender k on(k.kln_id=bn.kln_id)
            left join tbl_matkul m on(m.mtk_kode=bn.mtk_kode)
            left join tbl_dosen d on(d.ds_nidn=bn.ds_nidn) 
            where tbl_krs.mhs_nim='".$ID."' and k.kr_kode='$kr'";
        $sks = $db->createCommand($sks)
            ->queryOne();
                                        
        }else{

            $sql="Select * from 
            tbl_krs join tbl_jadwal j on(tbl_krs.jdwl_id=j.jdwl_id)
            left join tbl_bobot_nilai bn on(j.bn_id=bn.id)
            left join tbl_kalender k on(k.kln_id=bn.kln_id)
            left join tbl_matkul m on(m.mtk_kode=bn.mtk_kode)
            left join tbl_dosen d on(d.ds_nidn=bn.ds_nidn) 
            where tbl_krs.mhs_nim='$ID' and k.kr_kode=NULL";
            //$sql="";
            $model = new SqlDataProvider([
                    'sql'=>$sql,
                ]);            
            $model1 = new SqlDataProvider([
                    'sql'=>$sqlon,
                ]);
        $sks = "
        Select  
            sum(m.mtk_sks) as sks
        from 
            tbl_krs join tbl_jadwal j on(tbl_krs.jdwl_id=j.jdwl_id)
            left join tbl_bobot_nilai bn on(j.bn_id=bn.id)
            left join tbl_kalender k on(k.kln_id=bn.kln_id)
            left join tbl_matkul m on(m.mtk_kode=bn.mtk_kode)
            left join tbl_dosen d on(d.ds_nidn=bn.ds_nidn) 
            where tbl_krs.mhs_nim='".$ID."' and k.kr_kode=NULL";
        $sks = $db->createCommand($sks)
            ->queryOne();            
        }

	if(@$model){
		$ThnId='';
		foreach($model->getModels() as $dat){
			$ThnId=$dat['kln_id'];
		}
	}

    $data = array(
        'model'=>$model,
		'ThnId'=>$ThnId,
        'model1'=>$model1,
        'mhs'=>$mhs,
        'J'=>$this->J(),
        'jr'=>$jr,
        'pr'=>$pr,
        'ds'=>$ds,
        'ID'=>$ID,
        'krs'=>$krs,
        'sks'=>$sks,
    );
    return $this->render('/pasca/KRS',$data);	
	}
	

	public function actionTambahKrs($nim)
    {
        $model  =	new Krs;
        $nim 	= $nim;//Yii::$app->user->identity->username;
        $pr		=Mahasiswa::findOne($nim);
        $jr=$pr->jr_id;
        $pr=$pr->pr_kode;
        
        if(isset($_GET['Krs']['kurikulum'])){
            if($_GET['Krs']['kurikulum']=='empty'){
                $this->redirect(array('getkrs'));
            }else{
                $model->attributes=$_GET['Krs'];
                $id=$_GET['Krs']['kurikulum'];
                //$kod=explode("#",$id);
                $kod = $id;
                if(empty($kod)){
                    $this->redirect(array('getkrs'));
                }
                $con    = Yii::$app->db;
                if(substr($kod,0,1)=='1')
                {
                    $gg=substr($kod,0,1);
                    $thn=substr($kod,1,4);
                    $cokot="select TOP 1 kr_kode_ from tbl_krs where SUBSTRING(kr_kode_,1,1)>$gg and SUBSTRING(kr_kode_,2,4)<$thn";
                    $cokot=$con->createCommand($cokot)->queryOne();
                }else if(substr($kod,0,1)=='2'){
                    $gg=substr($kod,0,1);
                    $thn=substr($kod,1,4);
                    $cokot="select  TOP 1 kr_kode_ from tbl_krs where SUBSTRING(kr_kode_,1,1)<$gg and SUBSTRING(kr_kode_,2,4)=$thn";
                    $cokot=$con->createCommand($cokot)->queryOne();
                } else if (substr($kod,0,1)=='3'){
                    $gg=substr($kod,0,1);
                    $thn=substr($kod,1,4);
                    $cokot="select  TOP 1 kr_kode_ from tbl_krs where SUBSTRING(kr_kode_,1,1)<$gg and SUBSTRING(kr_kode_,2,4)=$thn ORDER BY kr_kode_ desc";
                    $cokot=$con->createCommand($cokot)->queryOne();
                }
                if(empty($cokot['kurikulum'])){
                $asli="NULL";
                }else{
                    $asli=$cokot['kurikulum'];
                }

            if(substr($kod,0,1)=='2' OR substr($kod,0,1)=='1'){     
            $query = "
                select 
                    sum(CAST(tbl_matkul.mtk_sks AS INT)) as sks_  
                from 
                    tbl_krs 
                join 
                    tbl_jadwal on (tbl_jadwal.jdwl_id=tbl_krs.jdwl_id)
                    join
                    tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
                    join
                    tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
                    join
                    tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
                where
                    tbl_krs.mhs_nim='".$nim."' and tbl_krs.krs_stat='1' and tbl_kalender.kr_kode=$asli      
            ";
            $k=$con->createCommand($query)->queryOne();

            $mutu = "
             select 
                        sum(dbo.TotalMutu(krs_grade)*CAST(tbl_matkul.mtk_sks AS INTEGER)) as krs_grade 
                    from 
                        tbl_krs 
                    join 
                        tbl_jadwal on (tbl_jadwal.jdwl_id=tbl_krs.jdwl_id)
                        join
                        tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
                        join
                        tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
                        join
                        tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
                    where
                        tbl_kalender.kr_kode<$kod and
                         tbl_krs.mhs_nim='$nim'
            ";
            $mutu=$con->createCommand($mutu)->queryOne();
            
            $ada="SKS $pr,$kod,$jr";
            $ada=$con->createCommand($ada)->queryOne();
            $ambil="sksambil $nim,$kod";
            $ambil=$con->createCommand($ambil)->queryOne();

           $sql = "
                select tbl_jadwal.*,mt.*,pr.*,ds.*,r.*,k.*
                    from 
                    tbl_jadwal
                    JOIN tbl_bobot_nilai bn ON bn.id=tbl_jadwal.bn_id 
                    JOIN tbl_kalender k ON k.kln_id=bn.kln_id
                    JOIN tbl_program pr ON pr.pr_kode=k.pr_kode
                    JOIN tbl_dosen ds ON ds.ds_id=bn.ds_nidn
                    JOIN tbl_jurusan jr ON k.jr_id=jr.jr_id
                    JOIN tbl_matkul mt ON jr.jr_id=mt.jr_id
                    JOIN tbl_ruang r ON r.rg_kode=tbl_jadwal.rg_kode                    
                where
                    pr.pr_kode='".$pr."' and k.kr_kode='".$kod."' and jr.jr_id='".$jr."' and bn.mtk_kode=mt.mtk_kode
                ORDER BY mt.mtk_semester ASC
            ";

                $dataProvider = new SqlDataProvider([
                    'sql'=>$sql,
                    'pagination' => [
                          'pageSize' => 0,
                     ],
                ]);

           // print_r($dataProvider->getModels());die();

            return $this->render('ins2',array(
            'model'=>$model,
            'data'=>$dataProvider,
            'k'=>$k,
            'mutu'=>$mutu,
            'ada'=>$ada,
            'ambil'=>$ambil
        ));
        }else{
            
            $query = "
                select 
                    sum(CAST(tbl_matkul.mtk_sks AS INT)) as sks_  
                from 
                    tbl_krs 
                join 
                    tbl_jadwal on (tbl_jadwal.jdwl_id=tbl_krs.jdwl_id)
                    join
                    tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
                    join
                    tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
                    join
                    tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
                where
                    tbl_krs.mhs_nim='".$nim."' and tbl_krs.krs_stat='1' and tbl_kalender.kr_kode=$asli      
            ";
            $k=$con->createCommand($query)->queryOne();

            $mutu = "
             select 
                        sum(dbo.TotalMutu(krs_grade)*CAST(tbl_matkul.mtk_sks AS INTEGER)) as krs_grade 
                    from 
                        tbl_krs 
                    join 
                        tbl_jadwal on (tbl_jadwal.jdwl_id=tbl_krs.jdwl_id)
                        join
                        tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
                        join
                        tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
                        join
                        tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
                    where
                        tbl_kalender.kr_kode=NULL and tbl_krs.mhs_nim='$nim'
            ";
            $mutu=$con->createCommand($mutu)->queryOne();

            $ada="SKS $pr,$kod[1],$jr";
            $ada=$con->createCommand($ada)->queryOne();
            $ambil="sksambil $nim,$kod[1]";
            $ambil=$con->createCommand($ambil)->queryOne();


           $sql = "
                select tbl_jadwal.*,mt.*,pr.*,ds.*,r.*,k.*
                    from 
                    tbl_jadwal
                    JOIN tbl_bobot_nilai bn ON bn.id=tbl_jadwal.bn_id 
                    JOIN tbl_kalender k ON k.kln_id=bn.kln_id
                    JOIN tbl_program pr ON pr.pr_kode=k.pr_kode
                    JOIN tbl_dosen ds ON ds.ds_id=bn.ds_nidn
                    JOIN tbl_jurusan jr ON k.jr_id=jr.jr_id
                    JOIN tbl_matkul mt ON jr.jr_id=mt.jr_id
                    JOIN tbl_ruang r ON r.rg_kode=tbl_jadwal.rg_kode                    
                where
                    pr.pr_kode='".$pr."' and k.kr_kode='".$kod."' and jr.jr_id='".$jr."' and bn.mtk_kode=mt.mtk_kode
                ORDER BY mt.mtk_semester ASC
            ";

                $dataProvider = new SqlDataProvider([
                    'sql'=>$sql,
                    'pagination' => [
                          'pageSize' => 0,
                     ],
                ]);            

            $con    = Yii::$app->db;
            $ada="SKS $pr,NULL,$jr";
            $ada=$con->createCommand($ada)->queryOne();
            $ambil="sksambil $nim,NULL";
            $ambil=$con->createCommand($ambil)->queryOne();
            
             return $this->render('ins2',array(
            'model'=>$model,
            'data'=>$dataProvider,
            'k'=>$k,
            'mutu'=>$mutu,
            'ada'=>$ada,
            'ambil'=>$ambil
        ));  
        }
    }

        }else{
            
            $sql="
                select tbl_jadwal.* 
                from 
                    tbl_jadwal
                INNER JOIN 
                   tbl_bobot_nilai bn ON bn.id=tbl_jadwal.bn_id 
                    INNER JOIN tbl_kalender k ON k.kln_id=bn.kln_id
                    INNER JOIN tbl_program pr ON pr.pr_kode=k.pr_kode
                where
                    pr.pr_kode='".$pr."' and k.kr_kode=NULL
            ";
            $dataProvider = new SqlDataProvider([
                    'sql'=>$sql,
                ]);

            $con    = Yii::$app->db;
            $ada="SKS $pr,NULL,$jr";
            //$ada=$con->createCommand($ada)->queryRow();
            $ada = $con->createCommand($ada)
                   ->queryOne();            
            $ambil="sksambil $nim,NULL";
            //$ambil=$con->createCommand($ambil)->queryRow();
            $ambil = $con->createCommand($ada)
                   ->queryOne();
            
            return $this->render('ins2',array(
            'model'=>$model,
            'data'=>$dataProvider,
            'ada'=>$ada,
            'ambil'=>$ambil
        ));    
    }
}    
     public function actionSimpanKrs(){
        $k = $_POST['kur'];
        $nim = $_POST['nim'];
		if(isset($_POST['jdwl'])){
            $con = Yii::$app->db;
            $k = $_POST['kur'];
            $maks = $_POST['ambil'];
            $jd = $_POST['jdwl'];
			$nim = $_POST['nim'];
            $sks = array();
            $sks = $_POST['sks'];
            $mtk = array();
            $mtk = $_POST['mtk'];
            $mtk_nm = array();
            $mtk_nm = $_POST['mtk_nm'];
            $kr = array();
            $kr = $_POST['kr'];
            $nidn = array();
            $nidn = $_POST['nidn'];
            $ds_nm = array();
            $ds_nm = $_POST['ds_nm'];
            $query = "
                select 
                    sum(CAST(tbl_matkul.mtk_sks AS INT)) as sks_  
                from 
                    tbl_krs 
                join 
                    tbl_jadwal on (tbl_jadwal.jdwl_id=tbl_krs.jdwl_id)
                    join
                    tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
                    join
                    tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
                    join
                    tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
                where
                    tbl_krs.mhs_nim='".$nim."' and tbl_kalender.kr_kode=$k     
            ";
            $q=$con->createCommand($query)->queryOne();
            $tot = 0+$q['sks_'];
			//var_dump($query);die();
            $mhs=Mahasiswa::findOne($nim);
             foreach($jd as $a)
                 {
                    $model=new Krs;        
                     
                     $model->jdwl_id=$a;
                     $model->mhs_nim=$mhs->mhs_nim;
                     $model->krs_tgl=date('Y-m-d h:i:s');
                     $model->kr_kode_ = $kr[$a];
                     $model->ds_nidn_ = $nidn[$a];
                     $model->ds_nm_ = $ds_nm[$a];
                     $model->mtk_kode_ = $mtk[$a];
                     $model->mtk_nama_ = $mtk_nm[$a];
                     $model->sks_ = $sks[$a];
                     $model->krs_stat ='1';
                        
                     $tot += $model->sks_;
                     if($model->save()){
						  \app\models\Funct::LOGS("Manambah Data Krs Mahasiswa ",$model,$model->krs_id,'c');
						 $KRSid=$model->krs_id;
						for($i=1;$i<=12;$i++){
							$ins="
								insert into tbl_absensi(krs_id,jdwl_id_,jdwl_stat,jdwl_sesi,RStat) 
								values('$KRSid', '$a','0','$i','0')
							";
							$INSERT =$con->createCommand($ins)->execute();
							 //\app\models\Funct::LOGS("Manambah Data Krs Mahasiswa ",$model,$model->krs_id,'c');
							
						}
					 }
					 
					 
					 /*if($tot<=24){
                        $model->save(); 
                     }else{
                        //echo "maaf muka anda cilepeng";
                      Yii::$app->getSession()->setFlash(
                            'error','Jumlah Sks Melebihi jumlah maksimum,mohon cek kembali matakuliah yang diambil'
                        );
                       return $this->redirect(['/bisa/tambah-krs','Krs[kurikulum]'=>$k,'nim'=>$nim]);
                     }*/
                 }     
            return Yii::$app->getResponse()->redirect(['/pasca/krs-t','Krs[kurikulum]'=>$k,'nim'=>$nim]);
        }else{
            return Yii::$app->getResponse()->redirect(['/pasca/tambah-krs','Krs[kurikulum]'=>$k,'nim'=>$nim]);
        }
    }

	public function actionDeleteKrs($id,$kurikulum=''){
		
        $con=Yii::$app->db;
        //$sql="delete from tbl_krs where krs_id=$id";
        $sql="update tbl_krs set RStat='1',krs_stat='0' where krs_id=$id";
        $command=$con->createCommand($sql)->execute();
		\app\models\Funct::LOGS("Menghapus Data Krs ($id) ",new Krs,$id,'d');
        return $this->redirect(array('pasca/krs-t','Krs[kurikulum]' => $kurikulum,'nim'=>$_GET['nim']));
    }	

    public function actionCetakKrs($kurikulum,$nim)
    {
        $db = Yii::$app->db;
        $ID = $nim;   
        $mhs= Mahasiswa::findOne($ID);
        $jr= Jurusan::findOne($this->J());
        $pr= Program::findOne($mhs->pr_kode);
        $ds= Dosen::findOne($mhs->ds_wali);
        
        $sql = "
                select 
                    tbl_krs.*,tbl_matkul.*,tbl_jadwal.*
                from 
                    tbl_krs 
                join 
                    tbl_jadwal on (tbl_jadwal.jdwl_id=tbl_krs.jdwl_id)
                    join
                    tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
                    join
                    tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
                    join
                    tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
                where
                    tbl_krs.mhs_nim='".$ID."' and tbl_kalender.kr_kode='".$kurikulum."'
					and (tbl_krs.RStat=0 or tbl_krs.RStat is null)					
        ";

        $krs = $db->createCommand($sql)->queryAll();         
        
        $data = [
            'krs'=>$krs,
            'mhs'=>$mhs,
            'jr'=>$jr,
            'pr'=>$pr,
            'ds'=>$ds,
            'ID'=>$ID,
            'kr'=>$kurikulum,
        ];
        return $this->render('cetakkrs',$data);
    }
	//

    public function actionCetakAbsensi($id,$jenis){

            if ($jenis==1) {
                $jenis = "UJIAN TENGAH SEMESTER";
            }else{
                $jenis = "UJIAN AKHIR SEMESTER";
            }
            
            $this->layout = "blank";
            $sql ="SELECT
                    matakuliah = mk.mtk_kode + ' / ' + mk.mtk_nama,
                    dosen = ds.ds_nm,
                    tanggal = CAST (jd.jdwl_uts AS DATE),
                    jam = CAST (jd.jdwl_uts AS TIME(0)),
                    ruang = rg.rg_nama,
                    kode = jr.jr_id,
                    kelas = jd.jdwl_kls,
                    program = UPPER(pr.pr_nama),
                    jurusan = UPPER( jr.jr_jenjang +' '+ jr.jr_nama),
                    semester = kr.kr_nama
                FROM
                    tbl_jadwal jd
                JOIN tbl_bobot_nilai bn ON bn.id = jd.bn_id
                JOIN tbl_matkul mk ON mk.mtk_kode = bn.mtk_kode
                JOIN tbl_dosen ds ON ds.ds_id = bn.ds_nidn
                JOIN tbl_ruang rg ON rg.rg_kode = jd.rg_kode
                JOIN tbl_kalender kl ON kl.kln_id = bn.kln_id
                JOIN tbl_program pr on pr.pr_kode = kl.pr_kode
                JOIN tbl_jurusan jr on jr.jr_id = kl.jr_id
                JOIN tbl_kurikulum kr on kr.kr_kode = kl.kr_kode
                WHERE
                    jd.jdwl_id = $id
					and jr.jr_id='".$this->J()."'";
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
                    WHERE kr.jdwl_id = $id and mh.jr_id='".$this->J()."' ORDER BY mhs_nim ";
            $data = Yii::$app->db->createCommand($sql)->queryAll();             
            return $this->render('cetak_absensi', [
                'data' => $data,
                'header' => $header,
                'jenis' => $jenis,
            ]);
        
    }

	// absen harian
    public function actionCetakAbsen($id,$jenis=1){

            if ($jenis==1) {
                $jenis = "UJIAN TENGAH SEMESTER";
            }else{
                $jenis = "UJIAN AKHIR SEMESTER";
            }
            
            $this->layout = "blank";
            $sql ="SELECT
                    matakuliah = mk.mtk_kode + ' / ' + mk.mtk_nama,
                    dosen = ds.ds_nm,
                    tanggal = CAST (jd.jdwl_uts AS DATE),
                    jam = CAST (jd.jdwl_masuk AS TIME(0)),
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
                JOIN tbl_ruang rg ON rg.rg_kode = jd.rg_kode
                JOIN tbl_kalender kl ON kl.kln_id = bn.kln_id
                JOIN tbl_program pr on pr.pr_kode = kl.pr_kode
                JOIN tbl_jurusan jr on jr.jr_id = kl.jr_id
                JOIN tbl_kurikulum kr on kr.kr_kode = kl.kr_kode
                WHERE
                    jd.jdwl_id = $id
				and jr.jr_id='".$this->J()."'
					";
					
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
                    WHERE kr.jdwl_id = $id 
					and mh.jr_id='".$this->J()."'
					ORDER BY mhs_nim ";
            $data = Yii::$app->db->createCommand($sql)->queryAll();             
            return $this->render('cetak_absen',[
                'data' => $data,
                'header' => $header,
                'jenis' => $jenis,
            ]);
        
    }
	// ouo

}
