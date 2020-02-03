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
                    //'delete' => ['post'],
                ],
            ],
        ];
    }
    public function sub(){return Akses::akses();}
    public function actionExpAbs(){return true;}

    public function actionExcel(){

        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams());
        $dataProvider->pagination=false;
        $dataArray=[];
        $n=0;

        if(isset($_GET['JadwalSearch']['kr_kode']) && !empty($_GET['JadwalSearch']['kr_kode'])){
            foreach ($dataProvider->getModels() as $d){
                $n++;
                $dataArray[]=[
                    $n,
                    $d['jr_jenjang'].' '.$d['jr_nama'],
                    $d['pr_nama'],
                    $d['jdwl_kls'],
                    $d['mtk_kode'],
                    $d['mtk_nama'],
                    $d['mtk_sks'],
                    $d['ds_nm'],
                    Funct::HARI()[$d['jdwl_hari']].','.$d['jadwal'],
                    $d['jum']?:'0'
                ];
            }


            if($_GET['t']==1){

                if($_GET['JadwalSearch']['kr_kode']!=''){
                    $kr=(int)$_GET['JadwalSearch']['kr_kode'];
                    $ModKur = \app\models\Kurikulum::find()->where(['kr_kode'=>$kr])->one();
                    if($ModKur!==null){
                        $shetName=$kr.'-';
                        $jr=$_GET['JadwalSearch']['jr_id'];
                        $ModJr = \app\models\Jurusan::find()->where("jr_id=:jr",[':jr'=>$jr])->one();
                        if($ModJr!=null){$shetName=$kr.'-'.$jr.'-';}
                        $fileName="perkuliahan-".$shetName.date('Ymd');

                        $objPHPExcel=new \PHPExcel;
                        $objPHPExcel->getProperties()
                            ->setCreator("sia.usbypkp.ac.id")->setLastModifiedBy("sia.usbypkp.ac.id")->setTitle($fileName)
                            ->setSubject($fileName);

                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1",'No');
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1",'Program Studi');
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1",'Program Perkuliahan');
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D1",'Kls.');
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E1",'Kode');
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F1",'Matakuliah');
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G1",'SKS');
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H1",'Dosen');
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I1",'Jadwal');
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J1",'Tot.');

                        $objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');
                        $objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());

                        #END DATA
                        // Rename worksheet
                        $objPHPExcel->getActiveSheet()->setTitle($shetName);
                        #echo "<br>";die();

                        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                        $objPHPExcel->setActiveSheetIndex(0);

                        // Redirect output to a client’s web browser (Excel2007)
                        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                        header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');

                        // If you're serving to IE over SSL, then the following may be needed
                        #header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                        #header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                        header ('Pragma: public'); // HTTP/1.0

                        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                        $objWriter->save('php://output');
                        exit;
                    }
                }
            }else{throw new NotFoundHttpException('The requested page does not exist.');}
        }
    }

    public function actionIndex(){
		$subAkses=self::sub();
        #if(Akses::acc('MasterJadwal')){$subAkses="";}
        if(!Akses::acc('/jadwal/index')){throw new ForbiddenHttpException("Forbidden access");}
				
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

    public function actionUpdateUjian($id){
        $model= $this->findModel($id);
        if ($model->load(Yii::$app->request->post())){
            if($model->jdwl_uts){
                $UTS=$model->jdwl_uts;
                $model->jdwl_uts        = $UTS." ".$model->uts_masuk;
                $model->jdwl_uts_out    = $UTS." ".$model->uts_keluar;
            }
            if($model->jdwl_uas){
                $UAS=$model->jdwl_uas;
                $model->jdwl_uas = $UAS." ".$model->uas_masuk;
                $model->jdwl_uas_out = $UAS." ".$model->uas_keluar;
            }

            return $this->redirect(['view','id'=>$model->jdwl_id]);

        }
        #Funct::v($model);
        return $this->render('update_ujian',['model' => $model,]);

    }

	public function actionGabUpdate($id){
        //$subAkses=self::sub();
        //if($subAkses){if(!isset(array_flip($subAkses['jurusan'])[$mhs->jr_id])){throw new ForbiddenHttpException("Forbidden access");}}
        $err=false;
        $model		= Jadwal::findOne($id);
        $modInfo    = Jadwal::findOne($id);
        $gabungan	= Jadwal::find()->where(['GKode'=>$model->GKode,'jdwl_masuk'=>$model->jdwl_masuk,'jdwl_hari'=>$model->jdwl_hari])->all();
        $subAkses = Akses::akses();
        if($subAkses){if(!isset(array_flip($subAkses['jurusan'])[$model->bn->kln->jr_id])){throw new ForbiddenHttpException("Forbidden access");}}


        $a_hari=$model->jdwl_hari;
        $a_masuk=$model->jdwl_masuk;
        $a_keluar=$model->jdwl_keluar;
        $a_Gkode=$model->GKode;

        $dataBentrok="";
        $cBentrok=0;
        $countBentrok=0;
        if($model->load(Yii::$app->request->post())){
            $vMasuk	= $model->jdwl_masuk;
            $vKeluar= $model->jdwl_keluar;
            $vHari 	= $model->jdwl_hari;

            $tgl = date('Y-m-d');
            $tgl1 = date('Y-m-d',strtotime($model->bn->kln->kln_krs));
            $tgl2 = date('Y-m-d',strtotime($model->bn->kln->krs_akhir));
            if($tgl>=$tgl1 and $tgl<=$tgl2){
                $q = Yii::$app->db->createCommand("exec CekBentrokJadwalKrs $model->jdwl_id, '$vHari', '$vMasuk', '$vKeluar'")->queryAll();
                $dataBentrok='<table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tipe</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Dosen</th>
                            <th>Matakuliah</th>
                            <th>Jadwal</th>
                        </tr>        
                    </thead>
                    <tbody>
                ';
                foreach($q as $d){
                    $cBentrok++;
                    $dataBentrok.='
                    <tr>    
                        <td>'.$cBentrok.'</td>
                        <td>'.$d['tipe'].'</td>
                        <td>'.$d['nama'].'</td>
                        <td>'.$d['nim'].'</td>
                        <td>'.$d['dosen'].'</td>
                        <td>'.$d['mtk_kode'].' '.$d['mtk_nama'].'</td>
                        <td>'.Funct::HARI()[$d['h']].", ".substr($d[m],0,5)."-".substr($d[k],0,5).'</td>
                    </tr>';

                }
                $dataBentrok.='</tbody></<table>';
            }


            #echo $dataBentrok;
            #die($q);
            $tglUts=
            $UTS	= ($model->jdwl_uts? $model->jdwl_uts." ".$model->uts_masuk:NULL);
            $UTSOUT = ($model->jdwl_uts? $model->jdwl_uts." ".$model->uts_keluar:NULL);

            $UAS	= ($model->jdwl_uas? $model->jdwl_uas." ".$model->uas_masuk:NULL);
            $UASOUT	= ($model->jdwl_uas? $model->jdwl_uas." ".$model->uas_keluar:NULL);

            $vCount=0;

            if( $a_hari!=$model->jdwl_hari||$a_masuk!=$model->jdwl_masuk||$a_keluar!=$model->jdwl_keluar){
                #cek bentrok jadwal dosen
                $qBentrok="
                select t.jdwl_id,jd.* from tbl_jadwal jd
                inner join tbl_bobot_nilai bn on(bn.id=jd.bn_id and bn.ds_nidn=".$model->bn->ds_nidn." and isnull(bn.RStat,0)=0)
                inner join tbl_kalender kl on(kl.kln_id=bn.kln_id and kl.kr_kode=".$model->bn->kln->kr_kode." and isnull(kl.RStat,0)=0)
                LEFT JOIN(
                    SELECT t.* from tbl_jadwal t
                    INNER JOIN tbl_jadwal t1 on(t1.jdwl_id='$id' and t1.GKode=t.GKode)
                    and isnull(t.RStat,0)=0
                ) t on(t.jdwl_id=jd.jdwl_id)
                where
                t.jdwl_id is NULL and(
                    (CAST(DATEADD(MINUTE,1,'$vMasuk') as time(0))  BETWEEN CAST(DATEADD(MINUTE,0,jd.jdwl_masuk) as time(0)) 
                        AND CAST(DATEADD(MINUTE,0,jd.jdwl_keluar) as time(0))) 
                    or
                    (CAST(DATEADD(MINUTE,-1,'$vKeluar') as time(0))  BETWEEN CAST(DATEADD(MINUTE,0,jd.jdwl_masuk) as time(0)) 
                    AND CAST(DATEADD(MINUTE,0,jd.jdwl_keluar) as time(0)))
                )
                and jd.jdwl_hari = '1' and isnull(jd.RStat,0)=0
                
				";

                $countBentrok=Yii::$app->db->createCommand($qBentrok)->queryAll();
            }

            if($countBentrok){
                $err=true;
                Yii::$app->getSession()->setFlash('error',"Jadwal Hari ".Funct::HARI()[$model->jdwl_hari]." Jam ".$model->jdwl_masuk.'-'.$model->jdwl_keluar.' Bentrok');
            }else{
                $ModUbah=Jadwal::updateAll([
                    'jdwl_hari'=>$model->jdwl_hari,
                    'jdwl_masuk'=>$model->jdwl_masuk,
                    'jdwl_keluar'=>$model->jdwl_keluar,
                    'jdwl_uts'=>$UTS,
                    'jdwl_uts_out'=>$UTSOUT,
                    'rg_kode'=>$model->rg_kode,
                    'rg_uts'=>$model->rg_uts,
                    'jdwl_uas'=>$UAS,
                    'jdwl_uas_out'=>$UASOUT,
                    'rg_uas'=>$model->rg_uas,
                ],['GKode'=>$a_Gkode,'jdwl_hari'=>$a_hari,'jdwl_masuk'=>$a_masuk,]);
                $model->save(false);

                #Funct::v($ModUbah->);

                Yii::$app->getSession()->setFlash('success',"Jadwal Berhasil Di ubah");
                return $this->redirect(['jadwal/gab-update','id'=>$model->jdwl_id]);
            }

        }

        return $this->render('gkode/update',[
            'model'=>$model,
            'modInfo'=>$modInfo,
            'gabung'=>$gabungan,
            'cBentrok'=>$cBentrok,
            'dataBentrok'=>$dataBentrok,
        ]);
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
