<?php

namespace app\controllers;
use app\models\Program;
use yii\data\SqlDataProvider;

use yii\data\ActiveDataProvider;
use Yii;

use app\models\Fakultas;



use \mPdf;
use kartik\mpdf\Pdf;
use yii\helpers\Url;


use app\models\Kalender;
use app\models\KalenderSearch;

use app\models\Jadwal;
use app\models\JadwalSearch;

use app\models\Krs;
use app\models\KrsSearch;


use app\models\Mahasiswa;
use app\models\MahasiswaSearch;

use app\models\Dosen;


use app\models\KPembayarankrs;

use app\models\Funct;


use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\db\Query;
use yii\data\ArrayDataProvider;

$connection = \Yii::$app->db;
/**/
use yii\app\controllers\AkademikGroupMk;
/**/

class KlsjController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }


	/*Matakuliah*/

	#matakuliah

	public function actionIndex(){return $this->render('@app/views/site/index');}
	/* Kurikulum */
	/* End Kurikulum */

	 
	/* Matakul */	
	/* end Matkul */

	/* Kalender */
    public function actionKln(){
        $searchModel = new KalenderSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),['pr_kode'=>'6']);
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

    /* End Kalender */

	/* jadwal */
    public function actionJdw(){
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),['kl.pr_kode'=>6]);
		
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


    public function actionJdwView($id)
    {
        $model =  Jadwal::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	return $this->redirect(['jdw-view', 'id' => $model->jdwl_id]);
        } else {
        return $this->render('jdw_view', ['model' => $model]);
		}
    }

    /* end jadwal */


	/* Mahasiswa */
    public function actionMhs(){
        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),['pr.pr_kode'=>6]);
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
				krs.jdwl_id,krs.krs_id ,kln.kr_kode, krs.mhs_nim , bn.mtk_kode ,mk.mtk_nama, mk.mtk_sks,krs_grade,ds_nidn,pr.pr_nama,jd.jdwl_kls
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
			$ITEM[$n]['jdwl_id']	=$d['jdwl_id'];			
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

	/* end Mahasiswa */
	
	/* KRS */

    public function actionKrsT(){
        $db     = Yii::$app->db;
        $ID     = @$_GET['nim'];
        $krs    =new Krs;
        //$jr     =Jurusan::findOne($this->J());
        $mhs    = Mahasiswa::findOne(['mhs_nim'=>$ID,'pr_kode'=>'6']);

        #if($subAkses){if(!isset(array_flip($subAkses['jurusan'])[$mhs->jr_id])){throw new ForbiddenHttpException("Forbidden access");}}

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
                return $this->redirect(['perwalian/krs-t']);
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
			
            ORDER BY 
			j.jdwl_hari,j.jdwl_masuk,
			m.mtk_semester ASC
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
            'pr'=>$pr,
            'ds'=>$ds,
            'ID'=>$ID,
            'krs'=>$krs,
            'sks'=>$sks,
        );
        return $this->render('/klsj/KRS',$data);
    }

    public function actionTambahKrs($nim){
        $model  =	new Krs;
        $nim 	= $nim;//Yii::$app->user->identity->username;
        $mhs	= Mahasiswa::findOne(['mhs_nim'=>$nim,'pr_kode'=>6]);
        $jr=$mhs->jr_id;
        $pr=$mhs->pr_kode;


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

                    /*
                                            select tbl_jadwal.*,mt.*,pr.*,ds.*,r.*,k.*
                                                ,dbo.subJdwl(tbl_jadwal.jdwl_id) jadwal
                                                ,isnull(dbo.cekIgMk(bn.mtk_kode),0) Ig
                                                ,isnull(dbo.ValidasiKrs(tbl_jadwal.jdwl_id,'$nim'),0) AvKrs

                    */
                    $sql = "
                select tbl_jadwal.*,mt.*,pr.*,ds.*,r.*,k.*
					 ,dbo.subJdwl(tbl_jadwal.jdwl_id) jadwal
					 ,isnull(dbo.cekIgMk(bn.mtk_kode),0) Ig
					 ,isnull(dbo.ValidasiKrs(tbl_jadwal.jdwl_id,'$nim'),0) AvKrs
					 ,t.tot
                    from tbl_jadwal
                    INNER JOIN tbl_bobot_nilai bn ON bn.id=tbl_jadwal.bn_id and isnull(bn.RStat,0)=0
                    INNER JOIN tbl_kalender k ON k.kln_id=bn.kln_id  and isnull(k.RStat,0)=0
                    INNER JOIN tbl_program pr ON pr.pr_kode=k.pr_kode
                    INNER JOIN tbl_dosen ds ON ds.ds_id=bn.ds_nidn  and isnull(ds.RStat,0)=0
                    INNER JOIN tbl_jurusan jr ON k.jr_id=jr.jr_id
                    INNER JOIN tbl_matkul mt ON jr.jr_id=mt.jr_id  and isnull(mt.RStat,0)=0
                    INNER JOIN tbl_ruang r ON r.rg_kode=tbl_jadwal.rg_kode  and isnull(r.RStat,0)=0              
					LEFT JOIN (
						select isnull(jd.GKode,jd.jdwl_id) GKode, count(krs.krs_id) tot 
						from tbl_krs krs
						inner join tbl_jadwal jd on(jd.jdwl_id=krs.jdwl_id and isnull(jd.RStat,0)=0) 
						where kr_kode_='$kod' and isnull(krs.RStat,0)=0 group by isnull(jd.GKode,jd.jdwl_id)
					) t
					on(t.GKode= isnull(tbl_jadwal.GKode,tbl_jadwal.jdwl_id))
					

                where
						
                    pr.pr_kode='".$pr."' and k.kr_kode='".$kod."' and jr.jr_id='".$jr."' and bn.mtk_kode=mt.mtk_kode
					and isnull(tbl_jadwal.RStat,0)=0
                ORDER BY mt.mtk_semester ASC
            ";

                    $sql1 = "
						select tbl_jadwal.*,mt.*,pr.*,ds.*,r.*,k.*
							,dbo.subJdwl(tbl_jadwal.jdwl_id) jadwal
							,dbo.cekIgMk(bn.mtk_kode) Ig
							,isnull(dbo.ValidasiKrs(tbl_jadwal.jdwl_id,'$nim'),0) AvKrs
							,t.tot
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
						and (
							( bn.RStat is null or bn.RStat= 0 )
							and ( tbl_jadwal.RStat is null or tbl_jadwal.RStat= 0 )	
						)
						and isnull(tbl_jadwal.jdwl_parent,tbl_jadwal.jdwl_id)=tbl_jadwal.jdwl_id
						ORDER BY mt.mtk_semester ASC
					";
                    $dataProvider = new SqlDataProvider([
                        'sql'=>$sql,
                        'pagination' => [
                            'pageSize' => 0,
                        ],
                    ]);

                    // print_r($dataProvider->getModels());die();

                    $KR = \app\models\Kurikulum::findOne($kod);

                    return $this->render('ins2',array(
                        'model'=>$model,
                        'MHS'=>$mhs,
                        'data'=>$dataProvider,
                        'k'=>$k,
                        'KR'=>$KR,
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
            //die();
            $con = Yii::$app->db;
            $k = $_POST['kur'];
            $maks = $_POST['ambil'];
            $jd = $_POST['jdwl'];
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
            $cJdw="";$sJdw=1;
            $cuid=Yii::$app->user->identity->id;

            $tot = 0+$q['sks_'];
            //var_dump($query);die();
            $mhs=Mahasiswa::findOne(['mhs_nim'=>$nim,'pr_kode'=>6]);
            if($mhs===null){throw new NotFoundHttpException('The requested page does not exist.');}
            foreach($jd as $a){

                $cek = Krs::find()->where(" mhs_nim='$mhs->mhs_nim' and jdwl_id='$a' and (RStat is null or RStat='0')")->count();
                if($cek==0){
                    $que ="select 
					dbo.avKrs_v1('".$mhs->mhs_nim."','".$mtk[$a]."','".$kr[$a]."') avKrsMk
					,dbo.cekIgMk(bn.mtk_kode) Ig
					,isnull(dbo.ValidasiKrs(j.jdwl_id,'$mhs->mhs_nim'),0) AvKrs
					from tbl_jadwal j,tbl_bobot_nilai bn where bn.id=j.bn_id and jdwl_id='$a'
					";
                    $cekJdw=Yii::$app->db->createCommand($que)->queryOne();
                    if($cekJdw && $cekJdw['Ig']==0){if($cekJdw['AvKrs']==0){$sJdw=0;$cJdw.=" $mtk[$a], ";}}

                    if($sJdw==1){
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
                        $model->cuid =$cuid;

                        if($model->save(false)){
                            if($cekJdw && $cekJdw['Ig']==0){$tot +=$model->sks_;}
                            \app\models\Funct::LOGS("Manambah Data Krs ",$model,$model->krs_id,'c',false);
                        }
                    }

                    $sJdw=1;

                }
            }

            if($cJdw!=''){
                Yii::$app->getSession()->setFlash('error',"Bentrok Jam Perkuliah Dengan Kode MK. ".substr($cJdw,0,-1));
                return $this->redirect(['/klsj/tambah-krs','Krs[kurikulum]'=>$k]);
            }
            //return Yii::$app->getResponse()->redirect(['/perwalian/krs-t','Krs[kurikulum]'=>$k]);

            return Yii::$app->getResponse()->redirect(['/klsj/krs-t','Krs[kurikulum]'=>$k,'nim'=>$nim]);
        }else{
            return Yii::$app->getResponse()->redirect(['/klsj/tambah-krs','Krs[kurikulum]'=>$k,'nim'=>$nim]);
        }
    }

    public function actionDeleteKrs($id,$kurikulum=''){
        $con=Yii::$app->db;
        $duid   = Yii::$app->user->identity->id;
        $model  = Krs::findOne($id);
        $mhs    = Mahasiswa::findOne(['mhs_nim'=>$model->mhs_nim,'pr_kode'=>6]);
        if($mhs!==null){
            //$sql="delete from tbl_krs where krs_id=$id";
            $sql="update tbl_krs set RStat='1',krs_stat='0',duid='$duid',dtgl=getdate() where krs_id=$id";
            $command=$con->createCommand($sql)->execute();

            if($command){
                \app\models\Funct::LOGS("Menghapus Data Krs($id) ",new Krs,$id,'d');
            }
            return $this->redirect(array('klsj/krs-t','Krs[kurikulum]' => $kurikulum,'nim'=>$_GET['nim']));
        }else{throw new NotFoundHttpException('The requested page does not exist.');}

    }


    #====

	/* end KRS */
	

    protected function findModel($id)
    {
        if (($model = Fakultas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    

}
