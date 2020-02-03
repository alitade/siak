<?php

namespace app\controllers;

use app\models\Akses;
use app\models\KrsSearch;
use app\models\People;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\Url;

use app\models\Mahasiswa;
use app\models\KPembayarankrs;
use app\models\MahasiswaSearch;
use app\models\Funct;

use app\controllers\CDbCriteria;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use doamigos\qrcode\formats\MailTo;
use kartik\mpdf\Pdf;
use app\models\MatkulKr;
use app\models\MatkulKrDet;

/**
 * MahasiswaController implements the CRUD actions for Mahasiswa model.
 */
class MhsController extends Controller{
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

        if(!Akses::acc('/mhs/index')){throw new ForbiddenHttpException("Forbidden access");}
        $searchModel = new MahasiswaSearch;
		
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,(self::sub()?['jr.jr_id'=>self::sub()['jurusan']]:""));
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
			$content = $this->renderPartial('/mhs/mhs_pdf',[
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
		
        return $this->render('/mhs/mhs_index', [
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
            'subAkses'      => self::sub(),
        ]);
    }

    public function actionView($id){
        $model 	=  $this->findModel($id);
		$ModKe	=  KPembayarankrs::find()
		->where(['nim'=>$id])
		->orderBy(['substring(tahun,2,2)'=>SORT_ASC,'substring(tahun,1,1)'=>SORT_ASC,]);
		$ModKe = new ActiveDataProvider([
            'query' => $ModKe,
        ]);

        $searchKrs 	= new KrsSearch;
        $dataKrs 	= $searchKrs->search(Yii::$app->request->getQueryParams());

        #data matakuliah kurikulum
        $matkul = Yii::$app->db->createCommand("exec kurikulumMhs '$model->mhs_nim'")->queryAll();
        $dataMatkul='
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>NO</th>    
                        <th>Kode</th>    
                        <th>Matakuliah</th>    
                        <th>SKS</th>    
                        <!--th>PRASYARAT</th-->    
                        <th>Status</th>    
                        <th>   </th>    
                    </tr>
                </thead>
            ';
        $semester="";
        $n=0;
        foreach($matkul as $d){
            $n++;
            if($semester!=$d['SEMESTER']){$semester=$d['SEMESTER'];$dataMatkul.="<tr><td colspan='4'>Semester $d[SEMESTER]</td></tr>";}
            $dataMatkul.="
                <tr class='".($d[STATUS]=='0'?'danger':'')."'>
                    <td>$n</td>
                    <td>$d[KODE]</td>
                    <td>$d[MATAKULIAH]</td>
                    <td>$d[SKS]</td>
                    <!--td>$d[STATUS]</td-->
                    <td>$d[STATUS]</td>
                    <td>$d[KET]</td>
                </tr>";
        }
        $dataMatkul.='
             </tbody>
            </table>';



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
					'dataMatkul'=>$dataMatkul,
					'ThnAkdm'	=> $dataProvider,
					'ModKe'		=> $ModKe
				]
			);
		}
    }

    public function actionPeople($id){
        $r  	= explode("/",$this->getRoute());
        $con	= $r[0];
        $act	= $r[1];
        $model 		=  $this->findModel($id);
        $noReg 		= $model->mhs->people->No_Registrasi;
        $modPeople	= People::findOne($noReg);

        $db = Yii::$app->db1;
        $keuangan = Funct::getDsnAttribute('dbname', $db->dsn);
        if(!$keuangan){$keuangan = Funct::getDsnAttribute('Database', $db->dsn);}
        $sql="insert into $keuangan.dbo.logg(user_id,ipaddress,logtime,controller,action,detail)
		values('".Yii::$app->user->identity->username."','".$_SERVER['REMOTE_ADDR']."',getdate(),'$con','$act','Mengubah Data people')
		";
        if(strripos($modPeople->alamat,'||')){
            $alamat 	= explode("||",$modPeople->alamat);
            $modPeople->jln =@$alamat[0];
            $modPeople->rt =@$alamat[1];
            $modPeople->rw =@$alamat[2];
            $modPeople->dsn =@$alamat[3];
            $modPeople->kel =@$alamat[4];
            $modPeople->kec =@$alamat[5];
        }

        if ($modPeople->load(Yii::$app->request->post())) {
            if($modPeople->rt && $modPeople->rw && $modPeople->dsn && $modPeople->kel && $modPeople->kec && $modPeople->jln){
                $modPeople->alamat = $modPeople->jln."||".$modPeople->rt."||".$modPeople->rw."||".$modPeople->dsn."||".$modPeople->kel."||".$modPeople->kec;
            }

            if($modPeople->save()){
                Yii::$app->db->createCommand($sql)->execute();
                return $this->redirect(['view', 'id' => $model->mhs_nim]);
            }
            //return $this->redirect(['mhs-view', 'id' => $model->mhs_nim]);
        }

        return $this->render('mhs_people',[
            'modPeople'=>$modPeople,
            'model'=>$model,
        ]);

		//return _Mahasiswa::update($id);
    }

    public function actionKrs($id,$kode){
        $model 	=  Mahasiswa::findOne($id);

        $searchKrs 	= new KrsSearch;
        $dataKrs 	= $searchKrs->search(Yii::$app->request->getQueryParams());
		
		//$ModKrs = Krs::find()->
		$query = new Query;
		$query
			->select("
				krs.jdwl_id ,krs.krs_id ,kln.kr_kode, krs.mhs_nim , bn.mtk_kode ,mk.mtk_nama, mk.mtk_sks,krs_grade,ds_nidn,pr.pr_nama,jd.jdwl_kls
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
			$ITEM[$n]['Jadwal']	= Funct::HARI()[$d['jdwl_hari']].", ".$d['jdwl_masuk']."-".$d['jdwl_keluar'];
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

    public function actionAbsen($id,$kode)
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

    public function actionAbsenLog($id,$kode)
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

    public function actionKhs($id)
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

    public function actionCreate()
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

    public function actionUpdate($id){
        $model =  Mahasiswa::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->mhs_nim]);
        } else {
            return $this->render('mhs_update',[
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id){
        $model=Mahasiswa::findOne($id);
		$model->RStat=1;
		$model->save();
        return $this->redirect(['/akademik/mhs']);
    }

    protected function findModel($id){
        if (($model = Mahasiswa::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
