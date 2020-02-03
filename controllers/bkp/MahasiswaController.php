<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Json;

use app\models\Mahasiswa;
use app\models\Kalender;
use app\models\KPembayarankrs;
use app\models\MahasiswaSearch;
use app\models\JadwalSearch;
use app\models\Jadwal;
use app\models\Jurusan;
use app\models\Dosen;
use app\models\Program;
use app\models\Krs;
use app\models\Funct;
use app\models\Student;

use app\controllers\CDbCriteria;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider; 

use doamigos\qrcode\formats\MailTo;
use dosamigos\qrcode\QrCode;

use \mPdf;
use kartik\mpdf\Pdf;


/**
 * MahasiswaController implements the CRUD actions for Mahasiswa model.
 */
class MahasiswaController extends Controller{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

	# Akses  Terbatas
    public function actionKurikulum(){
        $model=$this->findModel(Yii::$app->user->identity->username);
        $kurikulum = Yii::$app->db->createCommand("exec kurikulumMhs '$model->mhs_nim'")->queryAll();

        $dataMatkul=($model->mk_kr?'':'<div class="alert alert-danger" style="padding:2px;"> Kurikulum Matakuliah Belum Ditentukan </div>').'
            <table class="table table-bordered">
                <thead>
                <tr><th colspan="6">
                    <div class="col-sm-6"><span class="badge" style="background:green">Kurikulum Sesuai</span> <span class="badge" style="background:red">Kurikulum Tidak Sesuai</span></div>
                    <div class="col-sm-6"><b>[K:Konversi | S:Selesai | B:Berjalan | U:Mengulang | -:Belum Diambil]</b> </div>
                </th>
                </tr>
                <tr>
                    <th width="1%">NO</th>    
                    <th width="1%">Kode</th>    
                    <th>Matakuliah</th>    
                    <th width="1%">SKS</th>    
                    <th>PRASYARAT</th>    
                    <th>   </th>    
                </tr>
                </thead>
            ';
        $semester="";
        $n=0;
        foreach($kurikulum as $d){
            $n++;
            if($semester!=$d['SEMESTER']){$semester=$d['SEMESTER'];$dataMatkul.="<tr><td colspan='4'>Semester $d[SEMESTER]</td></tr>";}
            $dataMatkul.="
                <tr class='".($d[STATUS]=='0'?'bg-danger':'bg-success')."'>
                    <td>$n</td>
                    <td><span class='badge' style='background: ".($d[STATUS]==1?'green':'red')."'>$d[KODE]</span></td>
                    <td>$d[MATAKULIAH]</td>
                    <td>$d[SKS]</td>
                    <td>$d[PRASYARAT]</td>
                    <td> <span class='badge' style='background: ".($d[KET]=='S'||$d[KET]=='K'?'green':'red')."'>$d[KET]</span></td>
                </tr>";
        }
        $dataMatkul.='
             </tbody>
            </table>';


        return $this->render('/mahasiswa/mhs_kurikulum',[
            'dataMatkul'=>$dataMatkul,
            'model'=>$model,
        ]);
    }





	
	#


    public function actionQr()
    {
        header('Content-type: image/png'); //Set the content type to image/jpg
        $text = "NPM : ".Yii::$app->user->identity->username."";
        $Nama = "Nama : ".Funct::profMhs(Yii::$app->user->identity->username,"Nama")."";
        QrCode::png($text.$Nama);
    }

    /**
     * Lists all Mahasiswa models.
     * @return mixed
     */







    public function actionIndex(){
        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->redirect(['/site/index']);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    public function actionIndex1()
    {
        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

       
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Mahasiswa model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id){
        $model = $this->findModel($id);
        return $this->render('view', ['model' => $model]);
    }

    protected function findModel($id)
    {
        if (($model = Mahasiswa::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSchedule()
    {
        $model = New Jadwal();
        return $this->render('schedule', ['model' => $model]);
    }

    public function actionScheduleDetail()
    {   
        $model=new Jadwal;
        if($_GET['Jadwal']['kr_kode']=='NULL%23NULL' && empty($_GET['Jadwal']['jenis'])){
          return $this->redirect(array('schedule'));         
        }else if (empty($_GET['Jadwal']['jenis']) OR $_GET['Jadwal']['kr_kode']=='NULL%23NULL' ){
          return $this->redirect(array('schedule'));     
        }else if ($_GET['Jadwal']['jenis']=='empty' || $_GET['Jadwal']['kr_kode']=='empty' )
        {
          return $this->redirect(array('schedule'));     
        }
        else{
            $id=$_GET['Jadwal']['kr_kode'];
            $jn=$_GET['Jadwal']['jenis'];
        }
        //$kod=explode("#",$id);
        //var_dump($id);die();
        $kod=$id;
        $nim 		= Yii::$app->user->identity->username;
        $mhs    	= Mahasiswa::findOne($nim);//->where([' mhs_nim'=>'".$nim."']);
		$Kalender	= Kalender::findOne($kod);
		$lunas	=false;
		
		$stat	= KPembayarankrs::find()->where("nim='$nim' and tahun='".$Kalender->kr_kode."' and (sisa <=0 OR status='Lunas')")->One();
        
		if( $stat || substr($nim, 10,1) == '3' || substr($nim, 10,1) == '4' || $mhs->mhs_angkatan='2016'){
			$lunas=true;
		}
		
		
        $nim    = $mhs->mhs_nim;
        $sql 	= "select tbl_jadwal.*,tbl_bobot_nilai.mtk_kode,tbl_matkul.*,tbl_dosen.ds_nm from tbl_jadwal 
                left join tbl_krs on (tbl_krs.jdwl_id=tbl_jadwal.jdwl_id)
                left join tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
                left join tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
                left join tbl_program on (tbl_program.pr_kode=tbl_kalender.pr_kode)
				left join tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
				left join tbl_dosen on (tbl_bobot_nilai.ds_nidn=tbl_dosen.ds_id) 
                where tbl_krs.mhs_nim = '".$nim."' and tbl_kalender.kln_id='".$kod."' 
				and krs_stat=1
				and (

						(tbl_krs.RStat='0' or tbl_krs.RStat is null)
					and (tbl_bobot_nilai.RStat='0' or tbl_bobot_nilai.RStat is null)
					and (tbl_jadwal.RStat='0' or tbl_jadwal.RStat is null)
					and (tbl_kalender.RStat='0' or tbl_kalender.RStat is null)
					
				)
				order by tbl_matkul.mtk_kode

          ";
        //print_r($sql);die(); 
        $dataProvider = new SqlDataProvider([
                'sql'=>$sql,
            ]);
        
        return $this->render('scheduledetail',array(
            'dataProvider'=>$dataProvider,
            'mhs'=>$mhs,
            'jn'=>$jn,
			'lunas'=>$lunas,
            'model'=>$model,
            'kr'=>$id,  
        ));
     }

    public function actionIndekPrestasiKumulatif()
    {
		return $this->redirect(['index']);
	
    $nim    =Yii::$app->user->identity->username;
    $con    =Yii::$app->db;
    $mhs    =Mahasiswa::findOne($nim);
    //var_dump($mhs);
    //die();
    $jr     =Jurusan::findOne($mhs->jr_id);
    $pr     =Program::findOne($mhs->pr_kode);    
    $ds     =Dosen::findOne($mhs->ds_wali);

    $sql = "
        select tbl_krs.*,tbl_matkul.* from tbl_krs 
            join tbl_jadwal j on(tbl_krs.jdwl_id=j.jdwl_id)
            join tbl_bobot_nilai bn on(j.bn_id=bn.id)
            join tbl_Kalender k on(bn.kln_id=k.kln_id)
            join tbl_matkul on (tbl_matkul.mtk_kode=bn.mtk_kode)
        where
            tbl_krs.mhs_nim='".$nim."' and krs_stat='1'
        ORDER BY tbl_matkul.mtk_semester ASC
    ";
    
    $model = new SqlDataProvider([
            'sql'=>$sql,
        ]);

    
    $db = Yii::$app->db;
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
            tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
        where
            tbl_krs.mhs_nim='".$nim."' and tbl_krs.krs_stat='1'      
    ";
    $k = $db->createCommand($query)
            ->queryOne();
    
    $querymutu = "
        select 
            sum(dbo.TotalMutu(krs_grade)*CAST(tbl_matkul.mtk_sks AS INTEGER)) as krs_grade 
        from 
            tbl_krs 
        join 
            tbl_jadwal on (tbl_jadwal.jdwl_id=tbl_krs.jdwl_id)
            join
            tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
            join
            tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
        where
            tbl_krs.mhs_nim='".$nim."' and tbl_krs.krs_stat='1'
    ";

    $mutu=$db->createCommand($querymutu)
            ->queryOne();

    return $this->render('IPK',array(
            'model'=>$model,
            'mhs'=>$mhs,
            'jr'=>$jr,
            'pr'=>$pr,
            'ds'=>$ds,
            'sk'=>$k,
            'mt'=>$mutu));
}

    public function actionKartuHasilStudi($kr="")
    {
        $db    = Yii::$app->db;
        $nim    =Yii::$app->user->identity->username;
        $mhs    =Mahasiswa::findOne($nim);
        $jr     =Jurusan::findOne($mhs->jr_id);
        $pr     =Program::findOne($mhs->pr_kode);
        $ds     =Dosen::findOne($mhs->ds_wali);
        $model2= new Krs;
		$lunas=false;

		$NIL=Funct::NilaiAkhir($nim);		
        if(isset($_GET['Krs'])){
            $id=$_GET['Krs']['kurikulum'];
            //$kr=explode('#',$id);
            $kr = $_GET['Krs']['kurikulum'];
            $Kalender	= Kalender::findOne($kr);
			$stat	= KPembayarankrs::find()->where("nim='$nim' and tahun='".$Kalender->kr_kode."' and (sisa <=0 OR status='Lunas')")->One();
			$sql = "
                select tbl_krs.*,tbl_matkul.* from tbl_krs 
                    join tbl_jadwal j on(tbl_krs.jdwl_id=j.jdwl_id and (j.RStat is null or j.RStat='0'))
                    join tbl_bobot_nilai bn on(j.bn_id=bn.id and (bn.RStat is null or bn.RStat='0'))
                    join tbl_Kalender k on(bn.kln_id=k.kln_id and (k.RStat is null or k.RStat='0'))
                    join tbl_matkul on (tbl_matkul.mtk_kode=bn.mtk_kode and (tbl_matkul.RStat is null or tbl_matkul.RStat='0'))
                where
                    tbl_krs.mhs_nim='".$nim."' and k.kr_kode =(select distinct kr_kode from tbl_kalender where kln_id='".$kr."') and krs_stat='1'
					and (tbl_krs.RStat is null or tbl_krs.RStat='0')
					
                ORDER BY tbl_matkul.mtk_semester ASC
            ";
			
			if($stat){
				$lunas=true;
			}else{
				$st = Student::find()->where(['nim'=>$nim])->one();
				if(substr($st->program_id,0,3)=='KHU' || $st->program_id=="REG-LINTAS"){
					$lunas=true;
				}
			}

            $model = new SqlDataProvider(['sql'=>$sql,]);

        
        }else{
            $sql = "
                select tbl_krs.*,tbl_matkul.* from tbl_krs 
                    join tbl_jadwal j on(tbl_krs.jdwl_id=j.jdwl_id)
                    join tbl_bobot_nilai bn on(j.bn_id=bn.id)
                    join tbl_Kalender k on(bn.kln_id=k.kln_id)
                    join tbl_matkul on (tbl_matkul.mtk_kode=bn.mtk_kode)
                where
                    tbl_krs.mhs_nim='".$nim."' and k.kr_kode=NULL and krs_stat='1'
            ";
            
            $model = new SqlDataProvider(['sql'=>$sql,]);

            }
			

		    return $this->render('KHS',array(
				'model'=>$model2,
				'model2'=>$model,
				'mhs'=>$mhs,
				'jr'=>$jr,
				'pr'=>$pr,
				'ds'=>$ds,
				'sk'=>$k,
				'lunas'=>$lunas,
				'NIL'=>$NIL,
				
				'Kalender'=>@$Kalender
			));
      }

public function actionKartuRancanganStudi(){
    $db     = Yii::$app->db;
    $ID     = Yii::$app->user->identity->username; 
    $krs    = new Krs;
    $mhs    = Mahasiswa::findOne($ID);
    $jr     = Jurusan::findOne($mhs->jr_id);
    $pr     = Program::findOne($mhs->pr_kode);
    $ds     = Dosen::findOne($mhs->ds_wali);
	$ThnId	='';
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
        	return $this->redirect(['mahasiswa/kartu-rancangan-studi']);
        }else{
			
			$kr 	= $_GET['Krs']['kurikulum'];
			$KLN	= Kalender::findOne($kr);//->where(['kr_kode'=>$kr,'jr_id'=>$mhs->jr_id,'pr_kode'=>$mhs->pr_kode])->one();
			/*
			echo "<!-- else 1 
			";
			if($mhs->pr_kode){echo " prkode ".$mhs->pr_kode;}
			if($mhs->jr_id){echo " jr_id ".$mhs->jr_id;	}
			if(@$KLN->kln_id){echo " kln_id ".@$KLN->kln_id;	}
			echo "-->";
			*/
			$ThnId	=$KLN->kln_id;

			$sql="Select * 
			,dbo.subJdwl(j.jdwl_id) jadwal
			from 
            tbl_krs join tbl_jadwal j on(tbl_krs.jdwl_id=j.jdwl_id)
            left join tbl_bobot_nilai bn on(j.bn_id=bn.id)
            left join tbl_kalender k on(k.kln_id=bn.kln_id)
            left join tbl_matkul m on(m.mtk_kode=bn.mtk_kode)
            left join tbl_dosen d on(d.ds_nidn=bn.ds_nidn) 
            where tbl_krs.mhs_nim='".$ID."' 
			and k.kr_kode=(select kr_kode from tbl_kalender where kln_id='$kr') and 

			(
					(tbl_krs.RStat='0' or tbl_krs.RStat is null)
				and (bn.RStat='0' or bn.RStat is null)
				and (j.RStat='0' or j.RStat is null)
				and (k.RStat='0' or k.RStat is null)
			)
			
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
            where tbl_krs.mhs_nim='".$ID."' and k.kln_id='$kr'";
        $sks = $db->createCommand($sks)
		->queryOne();
                                        
	}else{
		$sql="Select * 
		,dbo.subJdwl(j.jdwl_id) jadwal
		from 
		tbl_krs join tbl_jadwal j on(tbl_krs.jdwl_id=j.jdwl_id)
		left join tbl_bobot_nilai bn on(j.bn_id=bn.id)
		left join tbl_kalender k on(k.kln_id=bn.kln_id)
		left join tbl_matkul m on(m.mtk_kode=bn.mtk_kode)
		left join tbl_dosen d on(d.ds_nidn=bn.ds_nidn) 
		where tbl_krs.mhs_nim='$ID' and k.kr_kode=NULL";
		//$sql="";
		$model = new SqlDataProvider(['sql'=>$sql,]);
		$model1 = new SqlDataProvider(['sql'=>$sqlon,]);
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
		//$ThnId='';
		foreach($model->getModels() as $dat){
			$ThnId=$dat['kln_id'];
		}
	}

    $data = array(
        'model'=>$model,
		'ThnId'=>$ThnId,
        'model1'=>$model1,
        'mhs'=>$mhs,
        'jr'=>$jr,
        'pr'=>$pr,
        'ds'=>$ds,
        'ID'=>$ID,
        'krs'=>$krs,
        'sks'=>$sks,
    );
    return $this->render('KRS',$data);
    }

	// KRS //
	public function actionKrs($id)
    {
		$ID     = Yii::$app->user->identity->username; 
		$mhs    = Mahasiswa::findOne($ID);
		$id=(int)$id;
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(
			Yii::$app->request->getQueryParams(),
			['jr.jr_id'=>$mhs->jr_id,'kl.kln_id'=>$id],
			[
				'mtk_semester'=>SORT_ASC,
				'jdwl_hari'=>SORT_ASC,
				'jdwl_masuk'=>SORT_ASC,
			]
		);
	
	
		if(isset($_POST['selection'])){
			$insert =0;		
            //$v=0;	
			foreach($dataProvider->getModels() as $data){
				$cek=(string)array_search($data['jdwl_id'],$_POST['selection']);
				if($cek!=''){
					$insert++;
					$krs    = Krs::find()->where(['jdwl_id'=>$data['jdwl_id'],'mhs_nim'=>$ID])->one();
					if(!$krs){
						$krs= new Krs;
						$krs->jdwl_id	= $data['jdwl_id'];
						$krs->mhs_nim	= $ID;
						$krs->kr_kode_	= $data['kr_kode'];
						$krs->ds_nidn_ 	= $data['penanggungjawab'];
						$krs->ds_nm_	= $data['ds_nm'];
						$krs->mtk_kode_	= $data['mtk_kode'];
						$krs->mtk_nama_	= $data['matkul'];
						$krs->sks_		= $data['mtk_sks'];
                        //$v +=$krs->sks_;
						$krs->save();
						 \app\models\Funct::LOGS("Manambah Data Krs",$krs,$krs->krs_id,'c');
					}
				}
			}
			
			if($insert>0){
				return $this->redirect(['kartu-rancangan-studi']);
			}
			
		}
		return $this->render('MenuKrs',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
		]);
	}
	// end KRS //
	
	
	// tambah krs
	public function actionTambahKrs($id=''){		
        $model  =	new Krs;
        $nim 	= 	Yii::$app->user->identity->username;
        $pr		=	Mahasiswa::findOne($nim);
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
                if(empty($kod)){$this->redirect(array('getkrs'));}
				$gg=(empty(substr($kod,0,1))?0:substr($kod,0,1));
				$thn=(empty(substr($kod,1,4))?0:substr($kod,1,4));
				
                $con    = Yii::$app->db;
                if(substr($kod,0,1)=='1'){
                    $cokot="select TOP 1 kr_kode_ from tbl_krs where SUBSTRING(kr_kode_,1,1)>$gg and SUBSTRING(kr_kode_,2,4)<$thn";
                    $cokot=$con->createCommand($cokot)->queryOne();
                }else if(substr($kod,0,1)=='2'){
                    $cokot="select  TOP 1 kr_kode_ from tbl_krs where SUBSTRING(kr_kode_,1,1)<$gg and SUBSTRING(kr_kode_,2,4)=$thn";
                    $cokot=$con->createCommand($cokot)->queryOne();
                } else if (substr($kod,0,1)=='3'){
                    $cokot="select  TOP 1 kr_kode_ from tbl_krs where SUBSTRING(kr_kode_,1,1)<$gg and SUBSTRING(kr_kode_,2,4)=$thn ORDER BY kr_kode_ desc";
                    $cokot=$con->createCommand($cokot)->queryOne();
                }
                if(empty($cokot['kurikulum'])){
                	$asli="NULL";
                }else{$asli=$cokot['kurikulum'];}

				if(substr($kod,0,1)=='2' OR substr($kod,0,1)=='1'){
					
					$query = "
						select 
							sum(CAST(tbl_matkul.mtk_sks AS INT)) as sks_  
						from tbl_krs 
						join tbl_jadwal on (tbl_jadwal.jdwl_id=tbl_krs.jdwl_id)
						join tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
						join tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
						join tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
						where
							tbl_krs.mhs_nim='".$nim."' and tbl_krs.krs_stat='1' and tbl_kalender.kln_id=$asli      
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
							pr.pr_kode='".$pr."' and k.kln_id='".$kod."' and jr.jr_id='".$jr."' and bn.mtk_kode=mt.mtk_kode
						and (
							( bn.RStat is null or bn.RStat= 0 )
							and ( tbl_jadwal.RStat is null or tbl_jadwal.RStat= 0 )	
						)
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
					//default
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
							tbl_krs.mhs_nim='".$nim."' and tbl_krs.krs_stat='1' and tbl_kalender.kln_id=$asli      
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
								tbl_kalender.kln_id=NULL and tbl_krs.mhs_nim='$nim'
					";
					$mutu=$con->createCommand($mutu)->queryOne();
					$ada="SKS $pr,$kod[1],$jr";
					$ada=$con->createCommand($ada)->queryOne();
					$ambil="sksambil $nim,$kod[1]";
					$ambil=$con->createCommand($ambil)->queryOne();
					
					$sql = "
						select tbl_jadwal.*,mt.*,pr.*,ds.*,r.*,k.*
							,dbo.subJdwl(tbl_jadwal.jdwl_id) jadwal
							,mt.Ig
							,isnull(dbo.ValidasiKrs(tbl_jadwal.jdwl_id,'$nim'),0) AvKrs
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
							pr.pr_kode='".$pr."' and k.kln_id='".$kod."' and jr.jr_id='".$jr."' and bn.mtk_kode=mt.mtk_kode
						and (
							( bn.RStat is null or bn.RStat= 0 )
							and ( tbl_jadwal.RStat is null or tbl_jadwal.RStat= 0 )	
						)
						and isnull(tbl_jadwal.jdwl_parent,tbl_jadwal.jdwl_id)=tbl_jadwal.jdwl_id
						ORDER BY mt.mtk_semester ASC
					";
					
					echo "<!-- galih2";
					echo "";
					echo "-->";
					//echo "<pre>";
					//print_r($sql);
					//echo "</pre>";
					$dataProvider = new SqlDataProvider(['sql'=>$sql,'pagination' => ['pageSize' => 0,],]);            
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
				and (
					( bn.RStat is null or bn.RStat= 0 )
					and ( tbl_jadwal.RStat is null or tbl_jadwal.RStat= 0 )	
				)
            ";
            $dataProvider = new SqlDataProvider(['sql'=>$sql,]);
            $con    = Yii::$app->db;
            $ada="SKS $pr,NULL,$jr";
            //$ada=$con->createCommand($ada)->queryRow();
            $ada = $con->createCommand($ada)->queryOne();            
            $ambil="sksambil $nim,NULL";
            //$ambil=$con->createCommand($ambil)->queryRow();
            $ambil = $con->createCommand($ada)->queryOne();
            
            return $this->render('ins2',array(
				'model'=>$model,
				'data'=>$dataProvider,
				'ada'=>$ada,
				'ambil'=>$ambil
			));    
		}
	}    
	// end tambah krs


	// test tambah krs
	public function actionTambahKrsV2($id=''){		
        $model  =	new Krs;
        $nim 	= Yii::$app->user->identity->username;
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
                if(empty($kod)){$this->redirect(array('getkrs'));}
				$gg=(empty(substr($kod,0,1))?0:substr($kod,0,1));
				$thn=(empty(substr($kod,1,4))?0:substr($kod,1,4));
				
                $con    = Yii::$app->db;
                if(substr($kod,0,1)=='1'){
                    $cokot="select TOP 1 kr_kode_ from tbl_krs where SUBSTRING(kr_kode_,1,1)>$gg and SUBSTRING(kr_kode_,2,4)<$thn";
                    $cokot=$con->createCommand($cokot)->queryOne();
                }else if(substr($kod,0,1)=='2'){
                    $cokot="select  TOP 1 kr_kode_ from tbl_krs where SUBSTRING(kr_kode_,1,1)<$gg and SUBSTRING(kr_kode_,2,4)=$thn";
                    $cokot=$con->createCommand($cokot)->queryOne();
                } else if (substr($kod,0,1)=='3'){
                    $cokot="select  TOP 1 kr_kode_ from tbl_krs where SUBSTRING(kr_kode_,1,1)<$gg and SUBSTRING(kr_kode_,2,4)=$thn ORDER BY kr_kode_ desc";
                    $cokot=$con->createCommand($cokot)->queryOne();
                }
                if(empty($cokot['kurikulum'])){
                	$asli="NULL";
                }else{$asli=$cokot['kurikulum'];}

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
							tbl_krs.mhs_nim='".$nim."' and tbl_krs.krs_stat='1' and tbl_kalender.kln_id=$asli      
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
							pr.pr_kode='".$pr."' and k.kln_id='".$kod."' and jr.jr_id='".$jr."' and bn.mtk_kode=mt.mtk_kode
						and (
							( bn.RStat is null or bn.RStat= 0 )
							and ( tbl_jadwal.RStat is null or tbl_jadwal.RStat= 0 )	
						)
						ORDER BY mt.mtk_semester ASC
					";
		
					$dataProvider = new SqlDataProvider([
						'sql'=>$sql,
						'pagination' => [
							  'pageSize' => 0,
						 ],
					]);
				   // print_r($dataProvider->getModels());die();
					return $this->render('ins2_v2',array(
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
							tbl_krs.mhs_nim='".$nim."' and tbl_krs.krs_stat='1' and tbl_kalender.kln_id=$asli      
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
								tbl_kalender.kln_id=NULL and tbl_krs.mhs_nim='$nim'
					";
					$mutu=$con->createCommand($mutu)->queryOne();
					$ada="SKS $pr,$kod[1],$jr";
					$ada=$con->createCommand($ada)->queryOne();
					$ambil="sksambil $nim,$kod[1]";
					$ambil=$con->createCommand($ambil)->queryOne();
					$sql = "
						select tbl_jadwal.*,mt.*,pr.*,ds.*,r.*,k.*
								,isnull(dbo.cekIgMk(bn.mtk_kode),0) Ig
								,isnull(dbo.avKrs('$nim',bn.mtk_kode,k.kr_kode),0) avKrsMk
								,isnull(dbo.avKrs('$nim',tbl_jadwal.jdwl_id,k.kr_kode),0) avKrsJd
								,isnull(dbo.avKrsTime_v1('$nim',tbl_jadwal.jdwl_hari,tbl_jadwal.jdwl_masuk,tbl_jadwal.jdwl_keluar,k.kr_kode),0) avKrsTime
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
							pr.pr_kode='".$pr."' and k.kln_id='".$kod."' and jr.jr_id='".$jr."' and bn.mtk_kode=mt.mtk_kode
						and (
							( bn.RStat is null or bn.RStat= 0 )
							and ( tbl_jadwal.RStat is null or tbl_jadwal.RStat= 0 )	
						)
						ORDER BY mt.mtk_semester ASC
					";
					//echo "<!-- galih2 $sql";
					echo "
					";
					echo "-->";
	
					$dataProvider = new SqlDataProvider(['sql'=>$sql,'pagination' => ['pageSize' => 0,],]);            
					$con    = Yii::$app->db;
					$ada="SKS $pr,NULL,$jr";
					$ada=$con->createCommand($ada)->queryOne();
					$ambil="sksambil $nim,NULL";
					$ambil=$con->createCommand($ambil)->queryOne();
				
					 return $this->render('ins2_v2',array(
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
				and (
					( bn.RStat is null or bn.RStat= 0 )
					and ( tbl_jadwal.RStat is null or tbl_jadwal.RStat= 0 )	
				)
            ";
            $dataProvider = new SqlDataProvider(['sql'=>$sql,]);
            $con    = Yii::$app->db;
            $ada="SKS $pr,NULL,$jr";
			
            //$ada=$con->createCommand($ada)->queryRow();
            //$ada = $con->createCommand($ada)->queryOne();            
            $ambil="sksambil $nim,NULL";
            //$ambil=$con->createCommand($ambil)->queryRow();
            //$ambil = $con->createCommand($ada)->queryOne();
            
            return $this->render('ins2_v2',array(
				'model'=>$model,
				'data'=>$dataProvider,
				'ada'=>$ada,
				'ambil'=>$ambil
			));    
		}
	}    
	
    public function actionSimpanKrsV2(){
		die();
        $k = $_POST['kur'];
		
        if(isset($_POST['jdwl'])){
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
                    tbl_jadwal on (tbl_jadwal.jdwl_id=tbl_krs.jdwl_id and (tbl_jadwal.RStat is null or tbl_jadwal.RStat='0') )
                    join
                    tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id  and (tbl_bobot_nilai.RStat is null or tbl_bobot_nilai.RStat='0') )
                    join
                    tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id  and (tbl_kalender.RStat is null or tbl_kalender.RStat='0') )
                    join
                    tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode  and (tbl_matkul.RStat is null or tbl_matkul.RStat='0') )
                where
                    tbl_krs.mhs_nim='".Yii::$app->user->identity->username."' and tbl_kalender.kln_id=$k
					and (tbl_krs.RStat is null or tbl_krs.RStat='0')
            ";
			//and tbl_krs.mtk_nama_ not like '%TB'

            $q=$con->createCommand($query)->queryOne();
            $tot = 0+$q['sks_'];
			
            $mhs=Mahasiswa::findOne(Yii::$app->user->identity->username);
			$cJdw="";$sJdw=1;
            foreach($jd as $a){
				 $model=new Krs;        
				 $cek = Krs::find()->where(" mhs_nim='$mhs->mhs_nim' and jdwl_id='$a' and (RStat is null or RStat='0')")->count();
				 if($cek==0){
					 $que ="select 
						dbo.avKrs('".$mhs->mhs_nim."','".$mtk[$a]."','".$kr[$a]."') avKrsMk
						,dbo.cekIgMk(bn.mtk_kode) Ig
						,dbo.avKrsTime_v1('".$mhs->mhs_nim."',j.jdwl_hari,j.jdwl_masuk,j.jdwl_keluar,'".$kr[$a]."') avKrsTime
					 from tbl_jadwal j,tbl_bobot_nilai bn where bn.id=j.jdwl_id and jdwl_id='$a'
					 ";
					 echo "$que<br />";
					 $cekJdw=Yii::$app->db->createCommand($que)->queryOne();
					 echo "asd ".$cekJdw['Ig']." ".$cekJdw['avKrsMk']." ".$cekJdw['avKrsJd'].$cekJdw['avKrsTime']."<br />";
					 if($cekJdw && $cekJdw['Ig']==0){
						 print_r($cekJdw['Ig']);
						if($cekJdw['avKrsMk']==0||$cekJdw['avKrsTime']==0){
							$sJdw=0;$cJdw.=" $mtk[$a], ";
						}
					 }
					 
					 $model->jdwl_id=$a;
					 $model->mhs_nim=$mhs->mhs_nim;
					 $model->krs_tgl=date('Y-m-d h:i:s');
					 $model->kr_kode_ = $kr[$a];
					 $model->ds_nidn_ = $nidn[$a];
					 $model->ds_nm_ = $ds_nm[$a];
					 $model->mtk_kode_ = $mtk[$a];
					 $model->mtk_nama_ = $mtk_nm[$a];
					 $model->sks_ = $sks[$a];
						
					 $tot += $model->sks_;
					 if($tot<=24){
						if($sJdw==1){
							$model->save();
							/*
							if(\app\models\Funct::BOLEH()>0){
								$q="update heleup set f2='1' where f1='$mhs->mhs_nim'";
								Yii::$app->db->createCommand($q)->execute();
							}
							*/
							//\app\models\Funct::LOGS("Manambah Data Krs ",$model,$model->krs_id,'c',false);
						} 
					 }else{
						Yii::$app->getSession()->setFlash('error','Jumlah Sks Melebihi jumlah maksimum,mohon cek kembali matakuliah yang diambil');
						return $this->redirect(['/mahasiswa/tambah-krs-v2','Krs[kurikulum]'=>$k]);
					 }
					 $sJdw=1;
				 }
			}
			echo " $cJdw";
			die();
			if($cJdw!=''){
				Yii::$app->getSession()->setFlash('error',"Bentrok Jam Perkuliah Dengan Kode MK. ".substr($cJdw,0,-1));
				return $this->redirect(['/mahasiswa/tambah-krs-v2','Krs[kurikulum]'=>$k]);
			}
            return Yii::$app->getResponse()->redirect(['/mahasiswa/kartu-rancangan-studi','Krs[kurikulum]'=>$k]);
        }else{
            return Yii::$app->getResponse()->redirect(['/mahasiswa/tambah-krs-v2','Krs[kurikulum]'=>$k]);
        }
    }


	
	
	// end test
    public function actionSimpanKrs(){
        $k = $_POST['kur'];
        if(isset($_POST['jdwl'])){
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
                    tbl_jadwal on (tbl_jadwal.jdwl_id=tbl_krs.jdwl_id and (tbl_jadwal.RStat is null or tbl_jadwal.RStat='0') )
                    join
                    tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id  and (tbl_bobot_nilai.RStat is null or tbl_bobot_nilai.RStat='0') )
                    join
                    tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id  and (tbl_kalender.RStat is null or tbl_kalender.RStat='0') )
                    join
                    tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode  and (tbl_matkul.RStat is null or tbl_matkul.RStat='0') )
                where
                    tbl_krs.mhs_nim='".Yii::$app->user->identity->username."' and tbl_kalender.kln_id=$k
					and (tbl_krs.RStat is null or tbl_krs.RStat='0')
            ";
			//and tbl_krs.mtk_nama_ not like '%TB'

			
            $q=$con->createCommand($query)->queryOne();
            $tot = 0+$q['sks_'];
			
            $mhs=Mahasiswa::findOne(Yii::$app->user->identity->username);
			$cJdw="";$sJdw=1;
			$cuid=Yii::$app->user->identity->id;
			echo "<pre>";
			print_r($query);
			//print_r($tot);
			echo "</pre>";
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
					 if($cekJdw && $cekJdw['Ig']==0){
						if($cekJdw['AvKrs']==0){
							$sJdw=0;$cJdw.=" $mtk[$a], ";
						}
					 }

					 $tot +=$sks[$a];
					 if($tot<=15){
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
							$model->cuid = $cuid;
							if($model->save(false)){
								\app\models\Funct::LOGS("Manambah Data Krs ",$model,$model->krs_id,'c',false);
							}
						} 
					 }else{
						Yii::$app->getSession()->setFlash('error','Jumlah Sks Melebihi jumlah maksimum,mohon cek kembali matakuliah yang diambil');
						return $this->redirect(['/mahasiswa/tambah-krs','Krs[kurikulum]'=>$k]);
					 }
					 
				 }
				 $sJdw=1;

			}
			
			if($cJdw!=''){
				Yii::$app->getSession()->setFlash('error',"Bentrok Jam Perkuliah Dengan Kode MK. ".substr($cJdw,0,-1));
				return $this->redirect(['/mahasiswa/tambah-krs','Krs[kurikulum]'=>$k]);
			}
			else{return Yii::$app->getResponse()->redirect(['/mahasiswa/kartu-rancangan-studi','Krs[kurikulum]'=>$k]);}
        }else{
            return Yii::$app->getResponse()->redirect(['/mahasiswa/tambah-krs','Krs[kurikulum]'=>$k]);
        }
    }

    public function actionDeleteKrs($id,$kurikulum='')
    {
        $con=Yii::$app->db;		
        $duid = Yii::$app->user->identity->id;

        //$sql="delete from tbl_krs where krs_id=$id";
        $sql="update tbl_krs set RStat='1',krs_stat='0',duid='$duid',dtgl=getdate() where krs_id=$id and mhs_nim='".Yii::$app->user->identity->username."'";
        $command=$con->createCommand($sql)->execute();
		if($command){
			\app\models\Funct::LOGS("Menghapus Data Krs($id) ",new Krs,$id,'d');
		}
        return $this->redirect(array('mahasiswa/kartu-rancangan-studi',   'Krs[kurikulum]' => $kurikulum));
    }
	
	
	// tanpa dosen
    public function actionCetakKrs($kurikulum){
        $db = Yii::$app->db;
        $ID = Yii::$app->user->identity->username;   
        $mhs= Mahasiswa::findOne($ID);
        $jr= Jurusan::findOne($mhs->jr_id);
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
                    tbl_krs.mhs_nim='".$ID."' and tbl_kalender.kln_id='".$kurikulum."'
					and
					(
							(tbl_krs.RStat='0' or tbl_krs.RStat is null)
						and (tbl_bobot_nilai.RStat='0' or tbl_bobot_nilai.RStat is null)
						and (tbl_jadwal.RStat='0' or tbl_jadwal.RStat is null)
						and (tbl_kalender.RStat='0' or tbl_kalender.RStat is null)
					)
					
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

	public function actionQrKd(){
		return $this->renderPhpFile('qr.php',['text'=>'q']);
	}
	
	public function actionKrsPdf($kurikulum){
        $db = Yii::$app->db;
        $ID = Yii::$app->user->identity->username;   
        $mhs= Mahasiswa::findOne($ID);
        $kr= Kalender::findOne($kurikulum);
        $jr= Jurusan::findOne($mhs->jr_id);
        $pr= Program::findOne($mhs->pr_kode);
        $ds= Dosen::findOne($mhs->ds_wali);
        $sql = "
                select 
                    tbl_krs.*,tbl_matkul.*,tbl_jadwal.*
					,dbo.subJdwl(tbl_jadwal.jdwl_id) jadwal
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
                    tbl_krs.mhs_nim='".$ID."' and tbl_kalender.kln_id='".$kurikulum."'
					and
					(
							(tbl_krs.RStat='0' or tbl_krs.RStat is null)
						and (tbl_bobot_nilai.RStat='0' or tbl_bobot_nilai.RStat is null)
						and (tbl_jadwal.RStat='0' or tbl_jadwal.RStat is null)
						and (tbl_kalender.RStat='0' or tbl_kalender.RStat is null)
					)
					
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
            'kln'=>$kr,
        ];
		
	        $this->layout = 'blank';
			//return 
			$content = $this->renderPartial('krs_pdf',$data);

			$pdf = new Pdf([
				'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
				'content' => $content,
				//'format'=>[148,210],
				'marginLeft'=>5,
				'marginRight'=>5,
				'marginTop'=>20,
				'marginHeader'=>5,
				'orientation'=>'P',
				'destination'=>'I',
				'cssInline'=>'
					table{overlow:warp;font-size:12px;}
					body{font-size: 12px;}
				',
				'filename'=>'KRS-'.$ID.'-'.date('YmdHis').'.pdf',
				'options' => [
					'title' => 'KRS '.$ID,
					'subject' => 'KRS '.$ID,
					'watermarkText'=>'DIREKTORAT SISTEM INFORMASI & MULTIMEDIA',
					'watermarkTextAlpha'=>0.2,
					'showWatermarkText'=>true,
					//'debug'=>true,
				],
				'methods' => [
					'SetHeader' => ['
					<table>
						<tr>
							<td><img src="'.Url::to('@web/ypkp.png').'" width="50"></td>
							<td>
							<b>UNIVERSITAS SANGGA BUANA YPKP</b>
							<p><small>Jl. PHH Mustopa No 68, Telp. (022) 7202233. Bandung 40124 
							&nbsp;E-mail : sim@usbypkp.ac.id. Homepage : www.usbypkp.ac.id</small></p>
								'.'
							</td>
						</tr>
					</table>'],
					'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'|Page {PAGENO}'.'|'.date("r")],
				]
			]);			
			return $pdf->render();
	}


	// tanpa dosen
    public function actionCetakKrs2($kurikulum){
        $db = Yii::$app->db;
        $ID = Yii::$app->user->identity->username;   
        $mhs= Mahasiswa::findOne($ID);
        $jr= Jurusan::findOne($mhs->jr_id);
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
                    tbl_krs.mhs_nim='".$ID."' and tbl_kalender.kln_id='".$kurikulum."'
					and
					(
							(tbl_krs.RStat='0' or tbl_krs.RStat is null)
						and (tbl_bobot_nilai.RStat='0' or tbl_bobot_nilai.RStat is null)
						and (tbl_jadwal.RStat='0' or tbl_jadwal.RStat is null)
						and (tbl_kalender.RStat='0' or tbl_kalender.RStat is null)
					)
					
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
        return $this->render('cetakkrs2',$data);
    }

////////////////////////////	
	
    public function actionCetakKhs($kurikulum)
    {
        $nim    =Yii::$app->user->identity->username;
        $con    =Yii::$app->db;
        $mhs    =Mahasiswa::findOne($nim);
        $jr     =Jurusan::findOne($mhs->jr_id);
        $pr     =Program::findOne($mhs->pr_kode);
        $ds     =Dosen::findOne($mhs->ds_wali);
        
        $sql = "
            select 
                tbl_krs.*,tbl_matkul.*
                from tbl_krs
                    join
                    tbl_jadwal on (tbl_jadwal.jdwl_id=tbl_krs.jdwl_id)
                    join
                    tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
                    join
                    tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
                    join
                    tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
            where
                tbl_krs.mhs_nim='".$nim."' and tbl_kalender.kr_kode='$kurikulum' and tbl_krs.krs_stat='1'
				and (
						(tbl_krs.RStat='0' or tbl_krs.RStat is null)
					and (tbl_bobot_nilai.RStat='0' or tbl_bobot_nilai.RStat is null)
					and (tbl_jadwal.RStat='0' or tbl_jadwal.RStat is null)
					and (tbl_kalender.RStat='0' or tbl_kalender.RStat is null)
					
				)
				
        ";
        $model = new SqlDataProvider([
                'sql'=>$sql,
            ]);

        
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
                tbl_krs.mhs_nim='".$nim."' and tbl_krs.krs_stat='1' and tbl_kalender.kr_kode='$kurikulum'      
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
                    tbl_kalender.kr_kode='$kurikulum' and tbl_krs.mhs_nim='$nim' and krs_stat='1'
        ";
        $mutu=$con->createCommand($mutu)->queryOne();
        
        return $this->render('cetakKHS',array('model'=>$model,'mhs'=>$mhs,
                'jr'=>$jr,
                'pr'=>$pr,
                'ds'=>$ds,
                'sk'=>$k,
                'mt'=>$mutu,));
    }
	
    public function actionCetakIpk(){
        $con    =Yii::$app->db;
        $nim    =Yii::$app->user->identity->username;
        $mhs    =Mahasiswa::findOne($nim);
        $jr     =Jurusan::findOne($mhs->jr_id);
        $pr     =Program::findOne($mhs->pr_kode);
        $ds     =Dosen::findOne($mhs->ds_wali);

        $sql = "
            select 
            tbl_krs.*,tbl_matkul.*
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
                tbl_krs.mhs_nim='".$nim."' and tbl_krs.krs_stat='1'
				and (
						(tbl_krs.RStat='0' or tbl_krs.RStat is null)
					and (tbl_bobot_nilai.RStat='0' or tbl_bobot_nilai.RStat is null)
					and (tbl_jadwal.RStat='0' or tbl_jadwal.RStat is null)
					and (tbl_kalender.RStat='0' or tbl_kalender.RStat is null)
					
				)
        ";
        $model = new SqlDataProvider([
                'sql'=>$sql,
            ]);
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
                tbl_krs.mhs_nim='".$nim."' and tbl_krs.krs_stat='1'      
				and (
						(tbl_krs.RStat='0' or tbl_krs.RStat is null)
					and (tbl_bobot_nilai.RStat='0' or tbl_bobot_nilai.RStat is null)
					and (tbl_jadwal.RStat='0' or tbl_jadwal.RStat is null)
					and (tbl_kalender.RStat='0' or tbl_kalender.RStat is null)
					
				)
				
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
                    tbl_krs.mhs_nim='$nim' 
				and krs_stat=1
				and (
						(tbl_krs.RStat='0' or tbl_krs.RStat is null)
					and (tbl_bobot_nilai.RStat='0' or tbl_bobot_nilai.RStat is null)
					and (tbl_jadwal.RStat='0' or tbl_jadwal.RStat is null)
					and (tbl_kalender.RStat='0' or tbl_kalender.RStat is null)
					
				)
					
        ";
        $mutu=$con->createCommand($mutu)->queryOne();

        return $this->render('CetakIPK',['model'=>$model,'mhs'=>$mhs,
                'jr'=>$jr,
                'pr'=>$pr,
                'ds'=>$ds,
                'sk'=>$k,
                'mt'=>$mutu]);
    }

     public function actionCetakUts($jenis,$kr)
     {
        $model=new Jadwal;
        //$kod=explode("#",$kr);
        $kod=$kr;
        $nim    =Yii::$app->user->identity->username;
        $mhs    =Mahasiswa::findOne($nim);
        $jr     =Jurusan::findOne($mhs->jr_id);
        $pr     =Program::findOne($mhs->pr_kode);
        $ds     =Dosen::findOne($mhs->ds_wali);

        $sql = "
            select tbl_jadwal.*,tbl_bobot_nilai.mtk_kode,tbl_matkul.*,tbl_dosen.ds_nm from tbl_jadwal 
                left join tbl_krs on (tbl_krs.jdwl_id=tbl_jadwal.jdwl_id)
                left join tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
                left join tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
                left join tbl_program on (tbl_program.pr_kode=tbl_kalender.pr_kode)
                left join tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
                left join tbl_dosen on (tbl_matkul.penanggungjawab=tbl_dosen.ds_id) 
                where tbl_krs.mhs_nim = '".$nim."' and tbl_kalender.kln_id='".$kod."'
				and krs_stat=1
				and (
						(tbl_krs.RStat='0' or tbl_krs.RStat is null)
					and (tbl_bobot_nilai.RStat='0' or tbl_bobot_nilai.RStat is null)
					and (tbl_jadwal.RStat='0' or tbl_jadwal.RStat is null)
					and (tbl_kalender.RStat='0' or tbl_kalender.RStat is null)
					
				)
				
                order by tbl_matkul.mtk_kode
        ";
        $dataProvider = new SqlDataProvider([
                'sql'=>$sql,
            ]);
        
        return $this->render('cetakUts',array(
            'dataProvider'=>$dataProvider,
            'mhs'=>$mhs,
            'model'=>$model,
            'jr'=>$jr,
            'pr'=>$pr,
            'ds'=>$ds,
            ));
     }

     public function actionCetakJadwal($jenis,$kr){
         $model=new Jadwal;
         $this->layout='blank';
         //$kod=explode("#",$kr);
         $kod=$kr;
         $nim    =Yii::$app->user->identity->username;
         $mhs    =Mahasiswa::findOne($nim);
         $jr     =Jurusan::findOne($mhs->jr_id);
         $pr     =Program::findOne($mhs->pr_kode);
         $ds     =Dosen::findOne($mhs->ds_wali);

         $sql =   "SELECT kr_nama from tbl_kalender k
                JOIN tbl_kurikulum kr on kr.kr_kode = k.kr_kode
                where kln_id= '".$kod."'";
         $smt =  Yii::$app->db->createCommand($sql)->queryScalar();


         $sql = "
            select tbl_jadwal.*,CAST(jdwl_uas as DATE) uas, SUBSTRING( convert(varchar, jdwl_uas,108),1,5) uas_masuk, IIF(jdwl_uas_out IS NULL, 'BELUM DI SET', SUBSTRING( convert(varchar, jdwl_uas_out,108),1,5))  uas_keluar,tbl_bobot_nilai.mtk_kode,tbl_matkul.*,tbl_dosen.ds_nm,tbl_dosen.ds_id,tbl_matkul.penanggungjawab from tbl_jadwal 
                left join tbl_krs on (tbl_krs.jdwl_id=tbl_jadwal.jdwl_id)
                left join tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
                left join tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
                left join tbl_program on (tbl_program.pr_kode=tbl_kalender.pr_kode)
                left join tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
                left join tbl_dosen on (tbl_bobot_nilai.ds_nidn=tbl_dosen.ds_id) 
                where tbl_krs.mhs_nim = '".$nim."' and 
					( tbl_kalender.kln_id='".$kod."' or tbl_kalender.kr_kode=(select kr_kode from tbl_kurikulum where kr_nama='$smt') )
				and krs_stat=1
				and (
						(tbl_krs.RStat='0' or tbl_krs.RStat is null)
					and (tbl_bobot_nilai.RStat='0' or tbl_bobot_nilai.RStat is null)
					and (tbl_jadwal.RStat='0' or tbl_jadwal.RStat is null)
					and (tbl_kalender.RStat='0' or tbl_kalender.RStat is null)
					
				)
				
                order by tbl_matkul.mtk_kode
        ";
         //echo "<!-- $sql -->";
         $dataProvider = new SqlDataProvider(['sql'=>$sql,]);
         $data = [
             'dataProvider'=>$dataProvider,
             'mhs'=>$mhs,
             'model'=>$model,
             'jr'=>$jr,
             'pr'=>$pr,
             'ds'=>$ds,
             'smt' => $smt,
         ];


         $this->layout = 'blank';
         //return
         $content = $this->renderPartial('cetakJadwalPdf',$data);

         $pdf = new Pdf([
             'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
             'content' => $content,
             'format'=>'A4',
             'marginLeft'=>5,
             'marginRight'=>5,
             'marginTop'=>20,
             'marginHeader'=>5,
             'orientation'=>'P',
             'destination'=>'I',
             'cssInline'=>'
					table{overlow:warp;font-size:12px;}
					body{font-size: 12px;}
				',
             'filename'=>'JADWAL-'.$ID.'-'.date('YmdHis').'.pdf',
             'options' => [
                 'title' => 'JADWAL '.$ID,
                 'subject' => 'JADWAL '.$ID,
                 'watermarkText'=>'DIREKTORAT SISTEM INFORMASI & MULTIMEDIA',
                 'watermarkTextAlpha'=>0.2,
                 'showWatermarkText'=>true,
                 //'debug'=>true,
             ],
             'methods' => [
                 'SetHeader' => ['
					<table>
						<tr>
							<td><img src="'.Url::to('@web/ypkp.png').'" width="50"></td>
							<td>
							<b>UNIVERSITAS SANGGA BUANA YPKP</b>
							<p><small>Jl. PHH Mustopa No 68, Telp. (022) 7202233. Bandung 40124 
							&nbsp;E-mail : sim@usbypkp.ac.id. Homepage : www.usbypkp.ac.id</small></p>
								'.'
							</td>
						</tr>
					</table>'],
                 'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'|'.'|'.date("r")],
             ]
         ]);
         return $pdf->render();
     }

    public function actionCetakKpu_($kr,$t){
        $model=new Jadwal;
        $this->layout='blank';
        //$kod=explode("#",$kr);
        $kod=$kr;
        $nim    =Yii::$app->user->identity->username;
        $mhs    =Mahasiswa::findOne($nim);
        $jr     =Jurusan::findOne($mhs->jr_id);
        $pr     =Program::findOne($mhs->pr_kode);
        $ds     =Dosen::findOne($mhs->ds_wali);

        $sql =   "SELECT kr_nama from tbl_kalender k
                JOIN tbl_kurikulum kr on kr.kr_kode = k.kr_kode
                where kln_id= '".$kod."'";
        $smt =  Yii::$app->db->createCommand($sql)->queryScalar();


        $sql = "
            select tbl_jadwal.*,CAST(jdwl_uas as DATE) uas, SUBSTRING( convert(varchar, jdwl_uas,108),1,5) uas_masuk, IIF(jdwl_uas_out IS NULL, 'BELUM DI SET', SUBSTRING( convert(varchar, jdwl_uas_out,108),1,5))  uas_keluar,tbl_bobot_nilai.mtk_kode,tbl_matkul.*,tbl_dosen.ds_nm,tbl_dosen.ds_id,tbl_matkul.penanggungjawab from tbl_jadwal 
                left join tbl_krs on (tbl_krs.jdwl_id=tbl_jadwal.jdwl_id)
                left join tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
                left join tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
                left join tbl_program on (tbl_program.pr_kode=tbl_kalender.pr_kode)
                left join tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
                left join tbl_dosen on (tbl_bobot_nilai.ds_nidn=tbl_dosen.ds_id) 
                where tbl_krs.mhs_nim = '".$nim."' and 
					( tbl_kalender.kln_id='".$kod."' or tbl_kalender.kr_kode=(select kr_kode from tbl_kurikulum where kr_nama='$smt') )
				and krs_stat=1
				and (
						(tbl_krs.RStat='0' or tbl_krs.RStat is null)
					and (tbl_bobot_nilai.RStat='0' or tbl_bobot_nilai.RStat is null)
					and (tbl_jadwal.RStat='0' or tbl_jadwal.RStat is null)
					and (tbl_kalender.RStat='0' or tbl_kalender.RStat is null)
					
				)
				
                order by tbl_matkul.mtk_kode
        ";
        //echo "<!-- $sql -->";
        $dataProvider = new SqlDataProvider([
            'sql'=>$sql,
        ]);

        return $this->render('cetakKPU',array(
            'dataProvider'=>$dataProvider,
            'mhs'=>$mhs,
            'model'=>$model,
            'jr'=>$jr,
            'pr'=>$pr,
            'ds'=>$ds,
            'smt' => $smt,
            'jenis' => $t
        ));
    }

     public function actionCetakKpu($kr,$t,$s='4'){
        $model=new Jadwal;
		$this->layout='blank';
        //$kod=explode("#",$kr);
        $kod=$kr;
        $nim    =Yii::$app->user->identity->username;
        $mhs    =Mahasiswa::findOne($nim);
        $jr     =Jurusan::findOne($mhs->jr_id);
        $pr     =Program::findOne($mhs->pr_kode);
        $ds     =Dosen::findOne($mhs->ds_wali);

        $sql =   "SELECT kr_nama from tbl_kalender k
                JOIN tbl_kurikulum kr on kr.kr_kode = k.kr_kode
                where kln_id= '".$kod."'";
        $smt =  Yii::$app->db->createCommand($sql)->queryScalar();
        
        
        $sql = "
            select tbl_jadwal.*,CAST(jdwl_uas as DATE) uas, SUBSTRING( convert(varchar, jdwl_uas,108),1,5) uas_masuk, IIF(jdwl_uas_out IS NULL, 'BELUM DI SET', SUBSTRING( convert(varchar, jdwl_uas_out,108),1,5))  uas_keluar,tbl_bobot_nilai.mtk_kode,tbl_matkul.*,tbl_dosen.ds_nm,tbl_dosen.ds_id,tbl_matkul.penanggungjawab from tbl_jadwal 
                left join tbl_krs on (tbl_krs.jdwl_id=tbl_jadwal.jdwl_id)
                left join tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
                left join tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
                left join tbl_program on (tbl_program.pr_kode=tbl_kalender.pr_kode)
                left join tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
                left join tbl_dosen on (tbl_bobot_nilai.ds_nidn=tbl_dosen.ds_id) 
                where tbl_krs.mhs_nim = '".$nim."' and 
					( tbl_kalender.kln_id='".$kod."' or tbl_kalender.kr_kode=(select kr_kode from tbl_kurikulum where kr_nama='$smt') )
				and krs_stat=1
				and (
						(tbl_krs.RStat='0' or tbl_krs.RStat is null)
					and (tbl_bobot_nilai.RStat='0' or tbl_bobot_nilai.RStat is null)
					and (tbl_jadwal.RStat='0' or tbl_jadwal.RStat is null)
					and (tbl_kalender.RStat='0' or tbl_kalender.RStat is null)
					
				)
				
                order by tbl_matkul.mtk_kode
        ";
		//echo "<!-- $sql -->";
        $dataProvider = new SqlDataProvider(['sql'=>$sql,]);
			$data = [
				'dataProvider'=>$dataProvider,
				'mhs'=>$mhs,
				'model'=>$model,
				'jr'=>$jr,
				'pr'=>$pr,
				'ds'=>$ds,
				'smt' => $smt,
				'jenis' => $t
			];


	        $this->layout = 'blank';
			//return 
			$content = $this->renderPartial('cetakKPUpdf',$data);

			$pdf = new Pdf([
				'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
				'content' => $content,
				'format'=>($s==4?'A4':[148,210]),
				'marginLeft'=>5,
				'marginRight'=>5,
				'marginTop'=>20,
				'marginHeader'=>5,
				'orientation'=>($s==4?'P':'L'),
				'destination'=>'I',
				'cssInline'=>'
					table{overlow:warp;font-size:12px;}
					body{font-size: 12px;}
				',
				'filename'=>'KPU-'.$ID.'-'.date('YmdHis').'.pdf',
				'options' => [
					'title' => 'KPU '.$ID,
					'subject' => 'KPU '.$ID,
					'watermarkText'=>'DIREKTORAT SISTEM INFORMASI & MULTIMEDIA',
					'watermarkTextAlpha'=>0.2,
					'showWatermarkText'=>true,
					//'debug'=>true,
				],
				'methods' => [
					'SetHeader' => ['
					<table>
						<tr>
							<td><img src="'.Url::to('@web/ypkp.png').'" width="50"></td>
							<td>
							<b>UNIVERSITAS SANGGA BUANA YPKP</b>
							<p><small>Jl. PHH Mustopa No 68, Telp. (022) 7202233. Bandung 40124 
							&nbsp;E-mail : sim@usbypkp.ac.id. Homepage : www.usbypkp.ac.id</small></p>
								'.'
							</td>
						</tr>
					</table>'],
					'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'|'.'|'.date("r")],
				]
			]);			
			return $pdf->render();
     }

     public function actionCetakUas($jenis,$kr){
     
        $model=new Jadwal;
        $nim    =Yii::$app->user->identity->username;
        $mhs    =Mahasiswa::findOne($nim);
        $jr     =Jurusan::findOne($mhs->jr_id);
        $pr     =Program::findOne($mhs->pr_kode);
        $ds     =Dosen::findOne($mhs->ds_wali);
        //$kod=explode("#",$kr);
        $kod = $kr;

        $sql = "
            select tbl_jadwal.*,tbl_bobot_nilai.mtk_kode,tbl_matkul.*,tbl_dosen.ds_nm from tbl_jadwal 
                left join tbl_krs on (tbl_krs.jdwl_id=tbl_jadwal.jdwl_id)
                left join tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
                left join tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
                left join tbl_program on (tbl_program.pr_kode=tbl_kalender.pr_kode)
                left join tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
                left join tbl_dosen on (tbl_matkul.penanggungjawab=tbl_dosen.ds_id) 
                where tbl_krs.mhs_nim = '".$nim."' and tbl_kalender.kln_id='".$kod."' and krs_stat=1
				and (
						(tbl_krs.RStat='0' or tbl_krs.RStat is null)
					and (tbl_bobot_nilai.RStat='0' or tbl_bobot_nilai.RStat is null)
					and (tbl_jadwal.RStat='0' or tbl_jadwal.RStat is null)
					and (tbl_kalender.RStat='0' or tbl_kalender.RStat is null)
					
				)
                order by tbl_matkul.mtk_kode
        ";
        $dataProvider = new SqlDataProvider([
                'sql'=>$sql,
            ]);        

        return $this->render('cetakUas',array(
            'dataProvider'=>$dataProvider,
            'mhs'=>$mhs,
            'model'=>$model,
            'mhs'=>$mhs,
            'jr'=>$jr,
            'pr'=>$pr,
            'ds'=>$ds,
            ));
     }


	public function actionAbsenKhusus($n,$s=1){
		
        $db = Yii::$app->db;
        $sql = "SELECT dbo.cekHari(idH)Hari, * from dbo.absenKhususMhs('$n') order by idH,masuk";
        $krs = $db->createCommand($sql)->queryAll();    
		//print_r($krs);
		$mhs=Mahasiswa::findOne($n);
		//echo $mhs->mhs_nim;
        
        $data = [
            'krs'=>$krs,
			'mhs'=>$mhs,
			's'=>$s
        ];
		
	        $this->layout = 'blank';
			//return 
			$content = $this->renderPartial('absen_khusus',$data);
			//die();
			$pdf = new Pdf([
				'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
				'content' => $content,
				//'format'=>[148,210],
				'marginLeft'=>5,
				'marginRight'=>5,
				'marginTop'=>20,
				'marginHeader'=>5,
				'orientation'=>'P',
				'destination'=>'I',
				'cssInline'=>'
					table{overlow:warp;font-size:12px;}
					body{font-size: 12px;}
				',
				'filename'=>'absen-'.$mhs->mhs_nim.'-'.date('YmdHis').'.pdf',
				'options' => [
					'title' => 'KRS '.$ID,
					'subject' => 'KRS '.$ID,
					'watermarkText'=>'DIREKTORAT SISTEM INFORMASI & MULTIMEDIA',
					'watermarkTextAlpha'=>0.2,
					'showWatermarkText'=>true,
					//'debug'=>true,
				],
				'methods' => [
					'SetHeader' => ['
					<table>
						<tr>
							<td><img src="'.Url::to('@web/ypkp.png').'" width="50"></td>
							<td>
							<b>UNIVERSITAS SANGGA BUANA YPKP</b>
							<p><small>Jl. PHH Mustopa No 68, Telp. (022) 7202233. Bandung 40124 
							&nbsp;E-mail : sim@usbypkp.ac.id. Homepage : www.usbypkp.ac.id</small></p>
								'.'
							</td>
						</tr>
					</table>'],
					//'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'|Page {PAGENO}'.'|'.date("r")],
				]
			]);			
			return $pdf->render();
	
	}

    public function actionProv() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = \app\models\MasterKota::find()->andWhere(['provinsi_id'=>$id,])->asArray()->all();
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $kota) {
					$kode=$kota['id'];
                    $out[] = ['id' => $kota['id'], 'name' => $kota['kota']];
                    if ($i == 0) {
                        $selected = $kota['id'];
                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }


}
