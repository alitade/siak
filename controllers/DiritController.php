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


	public function actionAbsenPdf($id,$jenis=1){
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
                FROM tbl_jadwal jd
                JOIN tbl_bobot_nilai bn ON (bn.id = jd.bn_id and isnull(bn.RStat,0)=0)
                JOIN tbl_matkul mk ON (mk.mtk_kode = bn.mtk_kode and isnull(mk.RStat,0)=0)
                JOIN tbl_dosen ds ON (ds.ds_id = bn.ds_nidn and isnull(ds.RStat,0)=0)
                JOIN tbl_ruang rg ON (rg.rg_kode = $rg)
                JOIN tbl_kalender kl ON (kl.kln_id = bn.kln_id and isnull(kl.RStat,0)=0)
                JOIN tbl_program pr on (pr.pr_kode = kl.pr_kode )
                JOIN tbl_jurusan jr on (jr.jr_id = kl.jr_id)
                JOIN tbl_kurikulum kr on (kr.kr_kode = kl.kr_kode)
                WHERE jd.jdwl_id = $id
				and isnull(jd.RStat,0)=0"

        ;
        $header =   Yii::$app->db->createCommand($sql)->queryOne();

        $db = Yii::$app->db1;
        $keuangan = Funct::getDsnAttribute('Database', $db->dsn);
        $sql =" SELECT DISTINCT kr.mhs_nim,p.Nama as nama
					FROM tbl_krs kr 
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

        $tmp='cetak_absen';
        if($jenis==3){$tmp='cetak_absen_darurat';}

        $content = $this->renderPartial($tmp,[
            'data' => $data,
            'header' => $header,
            'jenis' => $jenis,
        ]);


        //die();

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'content' => $content,
            'format'=>Pdf::FORMAT_LETTER,
            'marginLeft'=>5,
            'marginRight'=>5,
            'marginTop'=>16,
            'marginHeader'=>1,
            'orientation'=>'P',
            'destination'=>'I',
            //'watermarkText'=>'asd',
            //'cssFile'=>Url::to('@web/css/kv-grid.css'),
            'cssInline'=>"
					a{
						TEXT-DECORATION:none;
					}
					
				",
            'filename'=>'AbsensiPerkuliahan-'.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'All').'-'.$ModKur->kr_kode.'-'.date('YmdHis').'.pdf',
            'options' => [
                'title' => 'Jadwal Perkuliahan '.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'Semua Jurusan').' Tahun Akademik '.$ModKur->kr_kode.'/'.$ModKur->kr_nama,
                'subject' => 'Jadwal Perkuliahan '.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'Semua Jurusan').' Tahun Akademik '.$ModKur->kr_kode.'/'.$ModKur->kr_nama,
                'watermarkText'=>'DIREKTORAT SISTEM INFORMASI & MULTIMEDIA',
                'showWatermarkText'=>true,
            ],
            'methods' => [
                'SetHeader' => [
                    '<table>
							<tr>
								<td><img src="'.Url::to ('@web/images/logo.jpg').'" width="50"></td>
								
								<td>
								<b>UNIVERSITAS SANGGA BUANA YPKP</b>
								<p><small>Jl. PHH Mustopa No 68, Telp. (022) 7202233. Bandung 40124 
								&nbsp;E-mail : sim@usbypkp.ac.id. Homepage : www.usbypkp.ac.id</small></p>
									'.'
								</td>
							</tr>
						</table>'
                ],
                'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'||'.date('r').' - Page {PAGENO}'],
                //'SetWatermakText' =>"asd",
                //'ShowWatermarkText'=>true,
            ]
        ]);



        return $pdf->render();
	}
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
