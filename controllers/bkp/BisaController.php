<?php
namespace app\controllers;
use yii\web\Session;
use yii\data\ActiveDataProvider;
use Yii;

use app\models\Fakultas;
use app\models\FakultasSearch;


use app\models\Rekap;

use app\models\Absensi;
use app\models\Matkul;
use app\models\MatkulSearch;

use kartik\mpdf\Pdf;
use yii\helpers\Url;

use app\models\Kalender;
use app\models\KalenderSearch;

use app\models\Jadwal;
use app\models\JadwalSearch;

use app\models\Jurusan;
use app\models\Program;

use app\models\Krs;
use app\models\KrsDosen;
use app\models\KrsSearch;
use app\models\JadwalDosen;

use app\models\BobotNilai;
use app\models\BobotNilaiAkademik;
use app\models\BobotNilaiSearch;

use app\models\Mahasiswa;
use app\models\MahasiswaSearch;

use app\models\Dosen;
use app\models\DosenSearch;

use app\models\Wali;
use app\models\WaliSearch;

use yii\data\SqlDataProvider;


use app\models\KPembayarankrs;

use app\models\Funct;


use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

use yii\db\Query;
use yii\data\ArrayDataProvider;
use app\models\Akses;


$connection = \Yii::$app->db;


class BisaController extends Controller
{
    public function sub(){return Akses::akses();}
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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('mtk_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	
	}
	
    public function actionMtkView($id)
    {
        $model = Matkul::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	return $this->redirect(['mtk_view', 'id' => $model->mtk_kode]);
        } else {
        	return $this->render('mtk_view', ['model' => $model]);
		}
    }


    public function actionMtkCreate()
    {
        $model = new Matkul;
        if ($model->load(Yii::$app->request->post()) ){ 
			$model->mtk_stat='1';
			$model->mtk_stat='1';
			if( $model->save()) {
				return $this->redirect(['mtk_view', 'id' => $model->mtk_kode]);
			}
        } else {
            return $this->render('mtk_create', [
                'model' => $model,
            ]);
        }
    }


    public function actionMtkUpdate($id)
    {
        $model=Matkul::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['mtk-view', 'id' => $model->mtk_kode]);
        } else {
            return $this->render('mtk_update', [
                'model' => $model,
            ]);
        }
    }

    public function actionMtkDelete($id)
    {
        $model=Matkul::findOne($id);
		$model->RStat=1;
		$model->save();
        return $this->redirect(['mtk_index']);
    }
	/* end Matkul */

	/* Kalender */
    public function actionKln()
    {
        $searchModel = new KalenderSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('kln_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionKlnView($id)
    {
        $model = Kalender::findOne($id);
		if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
			return $this->redirect(['kln-view', 'id' => $model->kln_id]);
		}else{
			return $this->render('kln_view', ['model' => $model]);	
		}
    }

    public function actionKlnCreate()
    {
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
				
				$model2->kln_krs_lama=$Dkrs1->format("%R%a days");
				die($Dkrs1->format("%R%a days"));
				$model2->kln_masuk		=	$model->kln_masuk;

				$model2->kln_uts		=	$model->kln_uts;
				$Duts 	= date_create($model->kln_uts);
				$Duts1 	= date_create($model->kln_uts_lama);
				$model2->kln_uts_lama=$Duts1->format("%R%a days");

				$model2->kln_uas		=	$model->kln_uas;
				$Duas 	= date_create($model->kln_uas);
				$Duas1 	= date_create($model->kln_uas_lama);
				$model2->kln_uas_lama=$Duts1->format("%R%a days");

				$model2->kln_sesi		=	$model->kln_sesi;
				$model2->kr_kode		=	$model->kr_kode;
				if($model2->save(false)){$ok++;}else{
					die(print_r($model2->getErrors()));
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
    public function actionKlnUpdate($id)
    {
        $model=Kalender::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['kln-view', 'id' => $model->kln_id]);
        } else {
            return $this->render('kln_update', [
                'model' => $model,
            ]);
        }
    }

    public function actionKlnDelete($id)
    {
        $model=Kalender::findOne($id);
		$model->RStat=1;
		$model->save();
        return $this->redirect(['kln']);
    }
	/* End Kalender */

	/* Dosen */
    public function actionDsn()
    {
        $searchModel = new DosenSearch;
        $dataProvider = $searchModel->cari(Yii::$app->request->getQueryParams());
        return $this->render('dsn_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionDsnWaliList()
    {
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
    public function actionJdw()
    {
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams());

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
        $model2 =  Jadwal::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
			if(	$model2->bn_id==$model->bn_id && 
				$model2->jdwl_hari==$model->jdwl_hari && 
				$model2->jdwl_masuk==$model->jdwl_masuk && 
				$model2->jdwl_keluar==$model->jdwl_keluar && 
				$model2->rg_kode==$model->rg_kode){
				$model->save(false);
				//die($model->jdwl_uts);	
			}else{
				$save=$model->save();
			}
			if($save)return $this->redirect(['jdw-view', 'id' => $model->jdwl_id]);
		}else {
        return $this->render('jdw_view', ['model' => $model]);
		}
    }

    public function actionJdwCreate()
    {
        $model = new Jadwal;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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

        if ($model->load(Yii::$app->request->post())) {
			
			if(	$model2->bn_id===$model->bn_id && 
				$model2->jdwl_hari===$model->jdwl_hari && 
				$model2->jdwl_masuk===$model->jdwl_masuk && 
				$model2->jdwl_keluar===$model->jdwl_keluar && 
				$model2->rg_kode===$model->rg_kode){
					$save=$model->save(false);
			}else{
				$save=$model->save(false);
			}
			
			if($save)return $this->redirect(['jdw-view', 'id' => $model->jdwl_id]);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionJdwDelete($id)
    {
        $model=$this->findModel($id);
		$model->RStat=1;
		$model->save();
        return $this->redirect(['index']);
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

    public function actionAjrJdwUp($id)
    {
		$model = Jadwal::findOne($id);
		
		return $this->render('ajr_jdw_create',[
			'model'=>$model
		]);
    }

    public function actionAjrDelete($id)
    {
        
		$model=BobotNilai::findOne($id);
		$model->RStat='1';
		$model->save();
        return $this->redirect('ajr_index');
    }
    private function loadSession($Params)
    {
        $model = Absensi::findOne(['krs_id'=>$Params['krs_id'], 'jdwl_id_' => $Params['id'],'jdwl_sesi' => (int)$Params['sesi'] ] );
        if(empty($model)){
            $model = new Absensi;
            $model->krs_id = $Params['krs_id'];
            $model->jdwal_tgl = date('Y-m-d');
            $model->jdwl_sesi = (int)$Params['sesi'];
            $model->jdwl_stat ='0';
            $model->jdwl_id_ =$Params['id'];
            return $model;
        }
        return $model;
    }

	public function actionAttendance($id,$matakuliah=null,$token=null){

        $matkull = Matkul::findOne(['mtk_kode'=>$matakuliah]);
        $model   = JadwalDosen::findOne(['jdwl_id'=>$id]);
        $Params  = array();
        $columns = array();
        if ($token){
			//Perform Action Save Attendance Bray....
            $Params     = unserialize(base64_decode($token));
			//print_r($Params);
            $Absensi    = $this->loadSession($Params);       
            $stat       = $Absensi->jdwl_stat;
            $Absensi->jdwl_stat = ($stat =='1') ? '0':'1';
            $Absensi->save(false);
			\app\models\Funct::LOGS("Mengubah Data Kehadiran Mahasiswa ($Absensi->id)",new Absensi,$Absensi->id,'u',false);
            
        }
        $columns = [
                     ['label' => 'No.','attribute' => 'id'],
                     ['label' => 'NIM','attribute' => 'mhs_nim'],
                     ['label' => 'Mahasiswa','attribute' => 'Nama'],
                   ];
        $jum = $matkull->mtk_sesi;
        for ($idx=1; $idx <= (int)$jum; $idx++) { 
            $Sesion = ((int)$idx <=9) ? '0' . (int)$idx : (int)$idx;
            $columns[] =  [     
                            'width' => '5%',
                            'attribute' =>$Sesion,
                            'format' => 'raw',
                            'value' => function($model) use($Sesion,&$matakuliah){
                                        $S = "Sesi$Sesion";
                                        return JadwalDosen::formatAttendance($model,$model["$S"],(int)$Sesion,$matakuliah);
                            },
                        ];
        }
        if ($model) {     
        $db = Yii::$app->db1;
        $keuangan = Funct::getDsnAttribute('dbname', $db->dsn);       
        $query ="SELECT * FROM(SELECT DISTINCT DENSE_RANK() OVER (PARTITION BY jdwl_id ORDER BY mhs_nim ASC) as id, T.*,p.Nama FROM (
                SELECT krs.mhs_nim, krs.jdwl_id,
				 krs.krs_id, isnull([1],0)[Sesi01],
                isnull([2],0)[Sesi02],isnull([3],0)[Sesi03],isnull([4],0)[Sesi04],
                isnull([5],0)[Sesi05],isnull([6],0)[Sesi06],isnull([7],0)[Sesi07],
                isnull([8],0)[Sesi08],isnull([9],0)[Sesi09],isnull([10],0)[Sesi10],
                isnull([11],0)[Sesi11],isnull([12],0)[Sesi12],isnull([13],0)[Sesi13],
                isnull([14],0)[Sesi14],isnull([15],0)[Sesi15],isnull([16],0)[Sesi16]
                FROM (
                    SELECT k.jdwl_id,  a.krs_id,cast(isnull(jdwl_stat,0) as smallint) jdwl_stat, jdwl_sesi AS Sesi
                    FROM tbl_absensi a,tbl_krs k 
					where a.krs_id=k.krs_id
					and 
					k.jdwl_id = $id
                    ) AS src
                    pivot (
                    max(jdwl_stat) FOR Sesi IN ([1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],[12],[13],[14],[15],[16])
                ) AS pvt
                LEFT join tbl_krs krs on krs.krs_id = pvt.krs_id
                LEFT join tbl_absensi ab on ab.krs_id = pvt.krs_id 
                WHERE krs.krs_stat =1
                ) T  
                LEFT JOIN $keuangan.dbo.student st ON st.nim COLLATE Latin1_General_CI_AS = T.mhs_nim
                LEFT JOIN $keuangan.dbo.people p ON p.No_Registrasi = st.no_registrasi) B
               ";
        
        $count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($query) T;")->queryScalar();
        $dataProvider = new SqlDataProvider([
                'sql' => $query,
                'totalCount' => (int)$count,
                'sort' => [
                        'attributes' => [
                            'id' => [
                                'asc' => ['id' => SORT_ASC],
                                'default' => SORT_ASC,
                                'label' => 'No',
                            ],
                        ],
                ],
                'pagination' => [
                    'pageSize' => 0,
                ],
            ]);

        if (Yii::$app->getRequest()->isAjax) {
                json_encode(['status' => TRUE]);
        }
        return $this->render('attendance', [        
            'dataProvider'  => $dataProvider,
            'columns'       => $columns,
        ]);
         
         }
    }

    
    public function actionAbsensi($id,$matakuliah=null,$token=null){
		$this->redirect(['site/index']);

        $matkull = Matkul::findOne(['mtk_kode'=>$matakuliah]);
        $model   = JadwalDosen::findOne(['jdwl_id'=>$id]);

		$qDosen="
			SELECT 
				kl.kr_kode, 
				concat(jr.jr_jenjang,' ',jr.jr_nama) jurusan,
				pr.pr_nama program,	ds.ds_nm dosen,
				concat(mk.mtk_kode,': ',mk.mtk_nama,'(',jd.jdwl_kls,')') matkul,
				concat(dbo.cekHari(jd.jdwl_hari),', ',jd.jdwl_masuk,'-',jd.jdwl_keluar)jadwal
			from tbl_jadwal jd
			INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and ISNULL(bn.RStat,0)=0)
			INNER JOIN tbl_dosen ds on(ds.ds_id=bn.ds_nidn and ISNULL(ds.RStat,0)=0)
			INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and ISNULL(mk.RStat,0)=0)
			INNER JOIN tbl_kalender kl on(kl.kln_id=bn.kln_id and ISNULL(kl.RStat,0)=0)
			INNER JOIN tbl_jurusan jr on(jr.jr_id=kl.jr_id)
			INNER JOIN tbl_program pr on(pr.pr_kode=kl.pr_kode)
			where jd.jdwl_id=$id
		";
		$qDosen=Yii::$app->db->createCommand($qDosen)->queryOne();
		$table='
	
		<table style="font-size:14px;font-weight:bold">
			<tr><th colspan="7"><h4> TAHUN AKADEMIK: '.$qDosen[kr_kode].'</h4></th></tr>
			<tr>
				<td>DOSEN</td>
				<td>:</td>
				<td>'.$qDosen['dosen'].'</td>
				<td> </td>
				<td>MATAKULIAH</td>
				<td>:</td>
				<td>'.$qDosen['matkul'].'</td>
			</tr>
			<tr>
				<td>JURUSAN</td>
				<td>:</td>
				<td>'.$qDosen['jurusan'].'</td>
				<td></td>
				<td>JADWAL</td>
				<td>:</td>
				<td>'.$qDosen['jadwal'].'</td>
			</tr>
		</table>

		<br />
		';
        //echo $model->ds_nm;
		//print_r($model);
        $jum = 14;//$matkull->mtk_sesi;
		//echo"<!--".$model->jdwl_id.". -->";
        $table .= "<table class='table table-bordered table-hover'>
                    <thead>
                    <tr>
                    <th rowspan='2' style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>No</th>
                    <th rowspan='2' style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>NIM</th>
                    <th rowspan='2'style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>NAMA</th>
                    <th colspan=$jum style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>SESI</th>
                    </tr><tr style='align-content: center; text-align: center; font-weight: bold; background-color: rgb(51, 122, 183);'>";

        
        for ($idx=1; $idx <= (int)$jum; $idx++) { 
            $Sesion = ((int)$idx <=9) ? '0' . (int)$idx : (int)$idx;
            $S[] = $Sesion;
            $table .= "<td  style='background:".(Funct::StatAbsDsn($id,$Sesion)?'green':'red')."'>$Sesion</td>";
        }
        
        $table .= "</tr></thead>";

        if ($model) {     

        $db = Yii::$app->db1;
        $keuangan = Funct::getDsnAttribute('dbname', $db->dsn);   
		if(!$keuangan){$keuangan = Funct::getDsnAttribute('Database', $db->dsn);}

		// Query anyar
        $query1 ="SELECT * FROM(SELECT DISTINCT DENSE_RANK() OVER (PARTITION BY jdwl_id ORDER BY mhs_nim ASC) as id, T.*,p.Nama FROM (
                SELECT krs.mhs_nim, krs.jdwl_id,
                 krs.krs_id, isnull([1],0)[S01],
                isnull([2],0)[S02],isnull([3],0)[S03],isnull([4],0)[S04],
                isnull([5],0)[S05],isnull([6],0)[S06],isnull([7],0)[S07],
                isnull([8],0)[S08],isnull([9],0)[S09],isnull([10],0)[S10],
                isnull([11],0)[S11],isnull([12],0)[S12],isnull([13],0)[S13],
                isnull([14],0)[S14],isnull([15],0)[S15],isnull([16],0)[S16]
                FROM (
						SELECT distinct a.jdwl_id, a.krs_id,
							iif(max(ds_stat)!=0 AND isnull(a.mhs_stat,0)!=0,1,0) jdwl_stat,
							--isnull(a.mhs_stat,0) jdwl_stat, 
							a.sesi
						FROM m_transaksi_finger a
						INNER JOIN tbl_krs k on(k.krs_id=a.krs_id AND ISNULL(k.RStat,0)=0 AND k.jdwl_id=$id)
						INNER JOIN tbl_jadwal j on(j.jdwl_id=k.jdwl_id AND ISNULL(j.RStat,0)=0)
						INNER JOIN tbl_bobot_nilai bn on(bn.id=j.bn_id and ISNULL(bn.RStat,0)=0)
						where a.krs_id=k.krs_id
						and a.jdwl_id=$id							
						and isnull(a.RStat,0)=0
						GROUP BY a.jdwl_id,a.mhs_stat,a.krs_id,a.sesi
					
				) AS src
                    pivot (
                    max(jdwl_stat) FOR Sesi IN ([1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],[12],[13],[14],[15],[16])
                ) AS pvt
                LEFT join tbl_krs krs on krs.krs_id = pvt.krs_id
                LEFT join m_transaksi_finger ab on ab.krs_id = pvt.krs_id 
                WHERE krs.krs_stat ='1'
				and isnull(krs.RStat,0)=0 
				
                ) T  
                LEFT JOIN $keuangan.dbo.student st ON st.nim COLLATE Latin1_General_CI_AS = T.mhs_nim
                LEFT JOIN $keuangan.dbo.people p ON p.No_Registrasi = st.no_registrasi) B
                ORDER BY B.mhs_nim ASC
               ";
		
		echo "<!-- $query1 -->";
        $data = Yii::$app->db->createCommand($query1)->queryAll();
		$no=1;
        foreach ($data as $key) {
            $table .= "<tr>
                        <td style='font-weight: bold; font-size: 14px;'>".$no++."</td>
                        <td style='font-weight: bold; font-size: 14px;'>$key[mhs_nim]</td>
                        <td style='font-weight: bold; font-size: 14px;'>$key[Nama]</td>";
                        foreach ($S as $d => $val) {
                            $k = 'S'.$val;
							if(!isset($Sum[$k])){$Sum[$k]=0;}
							
                            $attribute = 'data-nim="'.$key['mhs_nim'].'" data-jdwl_id="'.$id.'" data-sesi="'.(int)$val.'" data-krs_id="'.$key['krs_id'].'"';
							$t="";
							if ($key[$k]==0){
								
								$t=	"A";
							}else{$t='M';$Sum[$k]++;}

                            if ($key[$k]==0) {
								$value ='<a href="javascript:;" class="do_attendance btn glyphicon glyphicon-remove-circle" name ="ctrl_attendance" id="'.$k.'" ' .$attribute. ' style="color: red;"></a>';
								//$value ='<i class="do_attendance btn glyphicon glyphicon-remove-circle" style="color: red;"></i>';
							}else{
								$value = '<a href="javascript:;" class="do_attendance btn glyphicon glyphicon-ok-circle" name ="ctrl_attendance" id="'.$k.'" ' .$attribute. '" style="color: green;"></a>';
								//$value = '<i class="do_attendance btn glyphicon glyphicon-ok-circle"  style="color: green;"></i>';
                            }

							/*$value = '<a href="javascript:;" class="do_attendance" name ="ctrl_attendance" id="'.$k.'" ' .$attribute. '" style="color: green;">
							'.Funct::IconAbs($t).'
							</a>';*/

            $table  .=  "<td style='text-align: center;'>
                            $value
                        </td>";                            
                        }

            $table  .="</tr>";
        }

		$table.='<tr>
			<th colspan="3">TOTAL</th><th>'.implode('</th><th>',$Sum).'</th>
		
		</tr>';
        $table .= "</table>";
        return $this->render('absensi_v1', [   
            'table' => $table,     
        ]);

	     /*
        return $this->render('absensi', [   
            'table' => $table,     
        ]);
       */  
        }
    }

    public function actionSaveAbsensiV1(){
		
		
		$usr="-";
		if(Yii::$app->user->identity->username){
			$usr=Yii::$app->user->identity->username;	
		}
        if (Yii::$app->getRequest()->isAjax) {

		$Params = $_POST;
		$model = Rekap::findOne(['krs_id'    => $Params['krs_id'], 
								'jdwl_id'  => $Params['jdwl_id'],
								'sesi' => (int)$Params['sesi'] ] );
   
		if(empty($model)){
			$model = new Rekap;
			$model->krs_id = $Params['krs_id'];
			$model->sesi = (int)$Params['sesi'];
			$model->mhs_stat ='0';
			$model->jdwl_id =$Params['jdwl_id'];
			echo json_encode(['data' => ($model),'status' => 'NEW']);
		}

            $stat  = $model->mhs_stat;
            $model->mhs_stat 	= ($stat =='1') ? '0':'1';
            $model->ds_stat 	= ($stat =='1') ? '0':'1';
            $model->ket_sys 	.= "|[$usr@".date('Ymd')."]RekapManual";
			
            if ($model->save(false)) {
                if ($model->mhs_stat==1) {
                    echo json_encode(['message'=>'','class' => 'do_attendance btn glyphicon glyphicon-ok-circle','color' => 'green']);
                }else{
                    echo json_encode(['message'=>'','class' => 'do_attendance btn glyphicon glyphicon-remove-circle','color' => 'red']);
                }
                \app\models\Funct::LOGS("Mengubah Data Kehadiran Mahasiswa ($model->id)",new Rekap,$model->id,'u',false);
            }else{
                echo json_encode(['message'=>'Terjadi Kesalahan, tidak dapat menyimpan. Hubungi IT Support.']);
            }

        }
    }


    public function actionSaveAbsensi(){
        if (Yii::$app->getRequest()->isAjax) {

            $Params = $_POST;

            $model = Absensi::findOne(['krs_id'     => $Params['krs_id'], 
                                        'jdwl_id_'  => $Params['jdwl_id'],
                                        'jdwl_sesi' => (int)$Params['sesi'] ] );
	   
            if(empty($model)){
                $model = new Absensi;
                $model->krs_id = $Params['krs_id'];
                $model->jdwal_tgl = date('Y-m-d');
                $model->jdwl_sesi = (int)$Params['sesi'];
                $model->jdwl_stat ='0';
                $model->jdwl_id_ =$Params['jdwl_id'];
                echo json_encode(['data' => ($model),'status' => 'NEW']);
            }

            $stat       = $model->jdwl_stat;
            $model->jdwl_stat = ($stat =='1') ? '0':'1';

            if ($model->save(false)) {
                if ($model->jdwl_stat==1) {
                    echo json_encode(['message'=>'','class' => 'do_attendance btn glyphicon glyphicon-ok-circle','color' => 'green']);
                }else{
                    echo json_encode(['message'=>'','class' => 'do_attendance btn glyphicon glyphicon-remove-circle','color' => 'red']);
                }

                \app\models\Funct::LOGS("Mengubah Data Kehadiran Mahasiswa ($model->id)",new Absensi,$model->id,'u',false);
            }else{
                echo json_encode(['message'=>'Terjadi Kesalahan, tidak dapat menyimpan. Hubungi IT Support.']);
            }

        }
    }

	// new cross jadwal
	public function actionAjrSplit($id){
		$ModJd 	= Jadwal::findOne($id);
		$ModBn 	= BobotNilai::findOne($ModJd->bn_id);
		$ModKrs	= new Krs();
		$KlnId	= $ModBn->kln_id;
		$MtkKode= $ModBn->mtk_kode;
        $searchModel 	= new JadwalSearch;
        $dataProvider 	= $searchModel->krs(Yii::$app->request->getQueryParams(),
			"mk.mtk_kode='$MtkKode' 
			and kl.kln_id='$KlnId'
			and jdwl_id!='$ModJd->jdwl_id'"
		);
		/*
        $dataProvider 	= $searchModel->krs(Yii::$app->request->getQueryParams(),
			"mk.mtk_kode='$MtkKode' 
			and jdwl_id!='$ModJd->jdwl_id'"
		);
		*/
		if(isset($_POST['qty']) && isset($_POST['pindah'])){
			
			$JadwalId=(int) key($_POST['pindah']);
			$qty 	 = (int) $_POST['qty'][$JadwalId];
			if(!empty($qty)){
				$q = "update tbl_krs set jdwl_id='$JadwalId' where jdwl_id='$id' and mhs_nim in(
						select top $qty mhs_nim 
						from tbl_krs 
						where jdwl_id='$id' 
						order by krs_Id desc 
				)";				
				yii::$app->db->createCommand($q)->execute();
				//\app\models\Funct::LOGS("Menambah Data Penjadwalan  ($model_jadwal->jdwl_id) ",$model_jadwal,$model_jadwal->jdwl_id,'c');
				$this->redirect(['ajr-split','id'=>$id]);
			}
		}
		
		if(isset($_POST['manual'])){
			$KrsJdw = new KrsSearch();
			$dataProvider 	= $KrsJdw->search(Yii::$app->request->getQueryParams(),['jdwl_id'=>$id]);
			
			$dataProvider->pagination->pageSize=false;
			return $this->render("ajr_splitmanual",[
				"model"=>$ModBn,
				"ModBn"=>$ModBn,
				"ModJd"=>$ModJd,
				"ModKrs"=>$ModKrs,
				"dataProvider"=>$dataProvider,
			]);
			//die('manual');	
		}
		
		return $this->render("ajr_split",[
			"model"=>$ModBn,
			"ModBn"=>$ModBn,
			"ModJd"=>$ModJd,
			"ModKrs"=>$ModKrs,
			"dataProvider"=>$dataProvider,
		]);
	}

	public function actionAjrSplitManual($id,$id1){
		$ModJd 	= Jadwal::findOne($id);
		$ModBn 	= BobotNilai::findOne($ModJd->bn_id);

		$ModJd_ 	= Jadwal::findOne($id1);
		$ModBn_ 	= BobotNilai::findOne($ModJd_->bn_id);


		$ModKrs	= new Krs();
		$KlnId	= $ModBn->kln_id;
		$MtkKode= $ModBn->mtk_kode;
		
		$KrsJdw = new KrsSearch();
		$dataProvider 	= $KrsJdw->search(Yii::$app->request->getQueryParams(),['jdwl_id'=>$id]);
		$dataProvider->pagination->pageSize=false;
		if(isset($_POST['selection'])){
			$insert =0;		
            //$v=0;	
			foreach($dataProvider->getModels() as $data){
				$cek=(string)array_search($data['krs_id'],$_POST['selection']);
				if($cek!=''){
					$krs    = Krs::findOne($data['krs_id']);
					if($krs){
						$krs->jdwl_id = $id1;
						$krs->save();
						$insert++;
						//echo $data['krs_id']."<br />";
					}
				}
			}
			if($insert>0){
				return $this->redirect(['bisa/ajr-split-manual','id'=>$id,'id1'=>$id1]);
			}

			
		}


		return $this->render("ajr_splitmanual",[
			"model"=>$ModBn,
			"ModBn"=>$ModBn,
			"ModJd"=>$ModJd,
			
			"ModBn_"=>$ModBn_,
			"ModJd_"=>$ModJd_,
			
			"ModKrs"=>$ModKrs,
			"dataProvider"=>$dataProvider,
		]);
		
		//die('manual');	
	}

	//cross schedule
    public function actionAjrCross($id,$jid){
        $krs		= Krs::find()->where(["jdwl_id" => $id])->all();
        $limit_krs 	= count($krs);
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
                return $this->render('ajr_jdw_cross', [
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


    public function actionAjrJdw($id){
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),['bn_id'=>$id]);
        $ModBn = BobotNilai::findOne($id);
        $model = new BobotNilai;
        $model2 = new Jadwal;
        return $this->render('ajr_jdw', [
            'dataProvider' => $dataProvider,
            'model'=>$model,
            'model2'=>$model2,
            'ModBn'=>$ModBn,
            'searchModel' => $searchModel,
        ]);
        
    }

	public function actionAjrJdwAdd($id){
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),['bn_id'=>$id]);
        $model = new BobotNilai;
        return $this->render('ajrJdwForm', [
            'dataProvider' => $dataProvider,
            'model'=>$model,
            'searchModel' => $searchModel,
        ]);
		
	}

	public function UpAllNil($id){
		$bobot = \app\models\BobotNilai::findOne($id);
		if($bobot){
			$sql="
			update tbl_krs set 
			krs_tot=(
				select tot
				from(
					select k.krs_id id,(
						iif(krs_tgs1=0,0,iif(nb_tgs1=0 or nb_tgs1 is null,0,krs_tgs1 * nb_tgs1 / 100))
						+iif(krs_tgs2=0,0,iif(nb_tgs2=0 or nb_tgs1 is null,0,krs_tgs2 * nb_tgs2 / 100))
						+iif(krs_tgs3=0,0,iif(nb_tgs3=0 or nb_tgs1 is null,0,krs_tgs3 * nb_tgs3 / 100))
						+iif(krs_tambahan=0,0,iif(nb_tambahan=0 or nb_tambahan is null,0,krs_tambahan * nb_tambahan/ 100))
						+iif(krs_quis=0,0,iif(nb_quis=0 or nb_quis is null,0,krs_quis * nb_quis / 100))
						+iif(krs_uts=0,0,iif(nb_uts=0 or nb_uts is null,0,krs_uts * nb_uts / 100))
						+iif(krs_uas=0,0,iif(nb_uas=0 or nb_uas is null,0,krs_uas * nb_uas / 100))
					) as tot, B,C,D,E
					from tbl_krs k, tbl_jadwal j, tbl_bobot_nilai b, tbl_kalender kl
					WHERE k.jdwl_id=j.jdwl_id and j.bn_id=b.id and kl.kln_id=b.kln_id
					and kl.kr_kode='11516'
					and B is not null
					and (krs_tgs1 is not null and krs_tgs1!=0)
					and (k.RStat is null OR k.RStat=0)
					and (j.RStat is null OR j.RStat=0)
					and (b.RStat is null OR b.RStat=0)
					and (krs_grade is not null)
				) t where krs_id=t.id
			),
			krs_grade=(
				select iif(tot <= E,'E',iif(tot<=D,'D',iif(tot<=C,'C',iif(tot<=B,'B','A')))) grade
				from(
					select k.krs_id id,(
						iif(krs_tgs1=0,0,iif(nb_tgs1=0 or nb_tgs1 is null,0,krs_tgs1 * nb_tgs1 / 100))
						+iif(krs_tgs2=0,0,iif(nb_tgs2=0 or nb_tgs1 is null,0,krs_tgs2 * nb_tgs2 / 100))
						+iif(krs_tgs3=0,0,iif(nb_tgs3=0 or nb_tgs1 is null,0,krs_tgs3 * nb_tgs3 / 100))
						+iif(krs_tambahan=0,0,iif(nb_tambahan=0 or nb_tambahan is null,0,krs_tambahan * nb_tambahan/ 100))
						+iif(krs_quis=0,0,iif(nb_quis=0 or nb_quis is null,0,krs_quis * nb_quis / 100))
						+iif(krs_uts=0,0,iif(nb_uts=0 or nb_uts is null,0,krs_uts * nb_uts / 100))
						+iif(krs_uas=0,0,iif(nb_uas=0 or nb_uas is null,0,krs_uas * nb_uas / 100))
					) as tot, B,C,D,E
					from tbl_krs k, tbl_jadwal j, tbl_bobot_nilai b, tbl_kalender kl
					WHERE k.jdwl_id=j.jdwl_id and j.bn_id=b.id and kl.kln_id=b.kln_id
					and kl.kr_kode='11516'
					and B is not null
					and (krs_tgs1 is not null and krs_tgs1!=0)
					and (k.RStat is null OR k.RStat=0)
					and (j.RStat is null OR j.RStat=0)
					and (b.RStat is null OR b.RStat=0)
					and (krs_grade is not null)
				) t WHERE krs_id=t.id
			)
			where (krs_tgs1 is not null and krs_tgs1!=0)
			and (krs_grade is NOT null)
			and (RStat is null OR RStat='0')
			and jdwl_id in(
				select  jdwl_id from tbl_jadwal WHERE bn_id in(
					select id from tbl_bobot_nilai bn 
					where (bn.RStat is null OR bn.RStat='0')
					and B is NOT NULL
					and kln_id in(
						select kln_id from tbl_kalender where kr_kode='11516'
					)
				)
			)
			and jdwl_id in(select jdwl_id from tbl_jadwal WHERE bn_id='$id')";
			
			$up = Yii::$app->db->createCommand($sql)->execute();
			if($up){
				return true;
			}else{return false;}
		}
			
	}

     public function actionAjrBobot($id)
    {
       
        if (!empty($_POST['action'])) {
            $action = @$_POST['action'];
            $id     = @$_POST['id'];
            $model  = BobotNilaiAkademik::findOne(['id'=> $id]);			
            if (empty($model)){return json_encode(['status'=> false]);}
            switch ($action) {
                case 'edit':
                    $model->nb_tgs1     =   $_POST['nb_tgs1'];
                    $model->nb_tgs2     =   $_POST['nb_tgs2'];
                    $model->nb_tgs3     =   $_POST['nb_tgs3'];
                    $model->nb_quis     =   $_POST['nb_quis'];
                    $model->nb_tambahan =   $_POST['nb_tambahan'];
                    $model->nb_uts      =   $_POST['nb_uts'];
                    $model->nb_uas      =   $_POST['nb_uas'];
                    $model->B           =   $_POST['B'];
                    $model->C           =   $_POST['C'];
                    $model->D           =   $_POST['D'];
                    $model->E           =   $_POST['E'];
                    $model->save(false);
					if(!BisaController::UpAllNil($model->id)){echo"error";}
					\app\models\Funct::LOGS("Mengubah Data Bobot Nilai($id) ",new BobotNilai,$id,'u');
                    return json_encode(['status'=> true]);
                    break;
                case 'default':
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
                    $model->save();
                    return json_encode(['status'=> true]);
                    break;
                default:
                    return json_encode(['status'=> false]);
                    break;
            }

        }

        $searchModel = new BobotNilaiAkademik;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),$id);
        return $this->render('ajr_bobot', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
			'ID'=>$id
        ]);
    }

    public function actionAtt(){
        echo json_encode(['status' => TRUE]);
    }

    public function actionAjrNilai($id)
    {

        $sub = self::sub();
        $acc=false;
        try {
            $QHeader = "SELECT
                     kl.jr_id,
                    ds.ds_nm, mk.mtk_nama,mk.mtk_sks,mk.mtk_kode, isnull(bn.nb_tgs1,0) nb_tgs1, isnull(bn.nb_tgs2,0) nb_tgs2,
                 	isnull(bn.nb_tgs3,0) nb_tgs3,isnull(bn.nb_quis,0) nb_quis, isnull(bn.nb_tambahan,0) nb_tambahan
                	,isnull(bn.nb_uts,0) nb_uts,isnull(bn.nb_uas,0) nb_uas, isnull(bn.B,0) B, isnull(bn.C,0) C, isnull(bn.D,0) D, 
                	isnull(bn.D,'0') D, isnull(bn.E,0) E
                      FROM tbl_bobot_nilai bn JOIN tbl_jadwal jd
                      on jd.bn_id = bn.id 
                      JOIN tbl_matkul mk on mk.mtk_kode = bn.mtk_kode
                      JOIN tbl_dosen ds on ds.ds_id= bn.ds_nidn
                      JOIN tbl_kalender kl on kl.kln_id= bn.kln_id
                      WHERE jdwl_id ='$id'
					  and(
						  	(bn.RStat is null or bn.RStat='0')
						and (jd.RStat is null or jd.RStat='0')
						and (mk.RStat is null or mk.RStat='0')
						and (ds.RStat is null or ds.RStat='0')
					  )
					  ;";
            $db = Yii::$app->db;
            $Header = $db->createCommand($QHeader)->queryOne();
            if($sub){
                foreach($sub['jurusan'] as $k=>$v){if($v==$Header['jr_id']){$acc=true;}}
                if($acc==false){ throw new NotFoundHttpException('The requested page does not exist.');}
            }
        $db = Yii::$app->db1;
        $keuangan = Funct::getDsnAttribute('dbname', $db->dsn);
		if(!$keuangan){$keuangan = Funct::getDsnAttribute('Database', $db->dsn);}
		
        $query ="SELECT jdwl_id,krs_id,mh.mhs_nim,p.Nama,
                    isnull(krs.krs_tgs1, '0') krs_tgs1,isnull(krs.krs_tgs2, '0') krs_tgs2,
                    isnull(krs.krs_tgs3, '0') krs_tgs3,isnull(krs.krs_quis, '0') krs_quis,
                    isnull(krs.krs_tambahan, '0') krs_tambahan,isnull(krs.krs_uts, '0') krs_uts,
                    isnull(krs.krs_uas, '0') krs_uas,
                    isnull(krs.krs_grade, '-') krs_grade,
                    krs_tot,
					cast( 
                    (
                        (
                            (isnull(krs.krs_tgs1, 0) * ".(float)$Header['nb_tgs1'].") / 100
                        ) + (
                            (isnull(krs.krs_tgs2, 0) * ".(float)$Header['nb_tgs2'].") / 100
                        ) + (
                            (isnull(krs.krs_tgs3, 0) * ".(float)$Header['nb_tgs3'].") / 100
                        ) + (
                            (isnull(krs.krs_quis, 0) * ".(float)$Header['nb_quis'].") / 100
                        ) + (
                            (isnull(krs.krs_tambahan, 0) * ".(float)$Header['nb_tambahan'].") / 100
                        ) + (
                            (isnull(krs.krs_uts, 0) * ".(float)$Header['nb_uts'].") / 100
                        ) + (
                            (isnull(krs.krs_uas, 0) * ".(float)$Header['nb_uas'].") / 100
                        )
                    ) as decimal(5,2) )total
                FROM tbl_krs krs
                LEFT JOIN tbl_mahasiswa mh ON (mh.mhs_nim = krs.mhs_nim and (krs.RStat is null or krs.RStat='0'))
                LEFT JOIN $keuangan.dbo.student st ON st.nim COLLATE Latin1_General_CI_AS= mh.mhs_nim
                LEFT JOIN $keuangan.dbo.people p ON p.No_Registrasi = st.no_registrasi
                WHERE jdwl_id = '$id' AND krs.krs_stat =1
				and (krs.RStat is null or krs.RStat='0')
				and jdwl_id in(
					select jdwl_id from tbl_jadwal where (RStat is null or RStat='0')
					and bn_id in(
						select id from tbl_bobot_nilai 
						where (RStat is null or RStat='0')
						and kln_id in(
							select kln_id from tbl_kalender where (RStat is null or RStat='0')
						)
					) 
				)
				";

        $count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($query) T;")->queryScalar();
        $dataProvider = new SqlDataProvider([
            'sql' => $query . " ORDER BY mhs_nim",
            'totalCount' => (int)$count,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        $searchModel = new KrsDosen();
        return $this->render('ajr_nilai', [
            'header'        => $Header,
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
			'ID'	=>$id
        ]);

        } catch (Exception $e) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
      

    }

    public function actionInputNilai()
    {
         if (!empty($_POST['action'])) {
            $action = @$_POST['action'];
            $id     = @$_POST['krs_id'];
            $model  = KrsDosen::findOne(['krs_id'=> $id]);
            if (empty($model)) {
                return json_encode(['status'=> false]);
            }
            switch ($action) {
                case 'edit':
                    $model->krs_tgs1     =   $_POST['krs_tgs1'];
                    $model->krs_tgs2     =   $_POST['krs_tgs2'];
                    $model->krs_tgs3     =   $_POST['krs_tgs3'];
                    $model->krs_quis     =   $_POST['krs_quis'];
                    $model->krs_tambahan =   $_POST['krs_tambahan'];
                    $model->krs_uts      =   $_POST['krs_uts'];
                    $model->krs_uas      =   $_POST['krs_uas'];
                    $model->save();
					Funct::TotNil($id);
					\app\models\Funct::LOGS("Mengubah Data Nilai Mahasiswa ($model->krs_id)",new KrsDosen,$model->krs_id,'u',false);
                    return json_encode(['status'=> true]);
                    break;
                default:
                    return json_encode(['status'=> false]);
                    break;
            }

        }


    }


	/*end ajar */

	/* Mahasiswa */
    public function actionMhs()
    {
        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('mhs_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionMhsPass($id='')
    {
        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),'mhs_nim is not null');
		if($id){
			if( Funct::ResetPass($id) ){
				return $this->redirect('mhs-pass');
			}
		}

        return $this->render('mhs_pass', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    public function actionMhsView($id)
    {
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
					'model' 	=> $model,
					'ThnAkdm'	=> $dataProvider,
					'ModKe'		=> $ModKe
				]
			);
		}
    }

	public function actionMhsKrsInput(){
	
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
				and (krs.RStat='0' or krs.RStat is null )
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
        $model=$this->findModel($id);
		$model->RStat=1;
		$model->save();
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
		$model->sys_=1;
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
	
	public function actionKrsT(){
		$db     = Yii::$app->db;
		$ID     = @$_GET['nim']; 
		
		$krs    =new Krs;
		$mhs    = Mahasiswa::findOne($ID);
		$jr     =Jurusan::findOne(@$mhs->jr_id);
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
		$sql="Select *,d.ds_nm dosen,dbo.subJdwl(j.jdwl_id) jadwal from 
				tbl_krs join tbl_jadwal j on(tbl_krs.jdwl_id=j.jdwl_id and isnull(j.RStat,0)=0 )
				left join tbl_bobot_nilai bn on(j.bn_id=bn.id and isnull(bn.RStat,0)=0 )
				left join tbl_kalender k on(k.kln_id=bn.kln_id and isnull(k.RStat,0)=0 )
				left join tbl_matkul m on(m.mtk_kode=bn.mtk_kode and isnull(m.RStat,0)=0 )
				left join tbl_dosen d on(d.ds_id=bn.ds_nidn and isnull(d.RStat,0)=0 ) 
				where tbl_krs.mhs_nim='".$ID."' and k.kr_kode='$kr' 
				and isnull(tbl_krs.RStat,0)=0 
				ORDER BY j.jdwl_hari, j.jdwl_masuk, j.jdwl_keluar, m.mtk_semester ASC
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
				tbl_krs join tbl_jadwal j on(tbl_krs.jdwl_id=j.jdwl_id and isnull(j.RStat,0)=0 )
				left join tbl_bobot_nilai bn on(j.bn_id=bn.id and isnull(bn.RStat,0)=0 )
				left join tbl_kalender k on(k.kln_id=bn.kln_id and isnull(k.RStat,0)=0 )
				left join tbl_matkul m on(m.mtk_kode=bn.mtk_kode and isnull(m.RStat,0)=0 )
				left join tbl_dosen d on(d.ds_nidn=bn.ds_nidn and isnull(d.RStat,0)=0 ) 
				where tbl_krs.mhs_nim='".$ID."' and k.kr_kode='$kr' and isnull(tbl_krs.RStat,0)=0";
			$sks = $db->createCommand($sks)
				->queryOne();
											
			}else{
	
				$sql="Select *,d.ds_nm dosen,dbo.subJdwl(j.jdwl_id) jadwal  from 
				tbl_krs join tbl_jadwal j on(tbl_krs.jdwl_id=j.jdwl_id and isnull(j.RStat,0)=0 )
				left join tbl_bobot_nilai bn on(j.bn_id=bn.id and isnull(bn.RStat,0)=0 )
				left join tbl_kalender k on(k.kln_id=bn.kln_id and isnull(k.RStat,0)=0 )
				left join tbl_matkul m on(m.mtk_kode=bn.mtk_kode and isnull(m.RStat,0)=0 )
				left join tbl_dosen d on(d.ds_nidn=bn.ds_nidn and isnull(d.RStat,0)=0 ) 
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
				tbl_krs join tbl_jadwal j on(tbl_krs.jdwl_id=j.jdwl_id and isnull(j.RStat,0)=0 )
				left join tbl_bobot_nilai bn on(j.bn_id=bn.id and isnull(bn.RStat,0)=0 )
				left join tbl_kalender k on(k.kln_id=bn.kln_id and isnull(k.RStat,0)=0 )
				left join tbl_matkul m on(m.mtk_kode=bn.mtk_kode and isnull(m.RStat,0)=0 )
				left join tbl_dosen d on(d.ds_nidn=bn.ds_nidn and isnull(d.RStat,0)=0 ) 
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
			'jr'=>$jr,
			'pr'=>$pr,
			'ds'=>$ds,
			'ID'=>$ID,
			'krs'=>$krs,
			'sks'=>$sks,
		);
		return $this->render('KRS',$data);	
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
					select sum(CAST(tbl_matkul.mtk_sks AS INT)) as sks_  
					from tbl_krs 
					join tbl_jadwal on (tbl_jadwal.jdwl_id=tbl_krs.jdwl_id)
					join tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
					join tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
					join tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
					where tbl_krs.mhs_nim='".$nim."' and tbl_krs.krs_stat='1' and tbl_kalender.kr_kode=$asli      
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
						pr.pr_kode='".$pr."' and k.kr_kode='".$kod."' and jr.jr_id='".$jr."' and bn.mtk_kode=mt.mtk_kode
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
					pr.pr_kode='".$pr."' and k.kr_kode='".$kod."' and jr.jr_id='".$jr."' and bn.mtk_kode=mt.mtk_kode
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
     public function actionSimpanKrs()
    {
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
                    tbl_jadwal on (tbl_jadwal.jdwl_id=tbl_krs.jdwl_id and (tbl_jadwal.RStat is null or tbl_jadwal.RStat='0') )
                    join
                    tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id  and (tbl_bobot_nilai.RStat is null or tbl_bobot_nilai.RStat='0') )
                    join
                    tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id  and (tbl_kalender.RStat is null or tbl_kalender.RStat='0') )
                    join
                    tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode  and (tbl_matkul.RStat is null or tbl_matkul.RStat='0') )
                where
                    tbl_krs.mhs_nim='$nim' and tbl_kalender.kln_id=$k
					and (tbl_krs.RStat is null or tbl_krs.RStat='0')
            ";
            $q=$con->createCommand($query)->queryOne();
            $tot = 0+$q['sks_'];
			//var_dump($query);die();
            $mhs=Mahasiswa::findOne($nim);
			$cJdw="";$sJdw=1;
             foreach($jd as $a){
				$model=new Krs;        
				$cek = Krs::find()->where(" mhs_nim='$nim' and jdwl_id='$a' and (RStat is null or RStat='0')")->count();

				if($cek==0){
					 $que ="select 
						dbo.avKrs('".$nim."','".$mtk[$a]."','".$kr[$a]."') avKrsMk
						,dbo.cekIgMk(bn.mtk_kode) Ig
						,dbo.avKrsTime_v1('".$nim."',j.jdwl_hari,j.jdwl_masuk,j.jdwl_keluar,'".$kr[$a]."') avKrsTime
					 from tbl_jadwal j,tbl_bobot_nilai bn where bn.id=j.jdwl_id and jdwl_id='$a'
					 ";
					 $cekJdw=Yii::$app->db->createCommand($que)->queryOne();
					 if( $cekJdw && $cekJdw['Ig']==0 ){
						if($cekJdw['avKrsMk']==0||$cekJdw['avKrsTime']==0){
							$sJdw=0;$cJdw.=" $mtk[$a], ";
						}
					 }
					 
					 $model->jdwl_id=$a;
					 $model->mhs_nim=$nim;
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
							\app\models\Funct::LOGS("Manambah Data Krs ",$model,$model->krs_id,'c',false);
						} 
					 }else{
						Yii::$app->getSession()->setFlash('error','Jumlah Sks Melebihi jumlah maksimum,mohon cek kembali matakuliah yang diambil');
						return Yii::$app->getResponse()->redirect(['/bisa/krs-t','Krs[kurikulum]'=>$k,'nim'=>$nim]);
					 }
					 $sJdw=1;
				 }
			}
			
			if($cJdw!=''){
				Yii::$app->getSession()->setFlash('error',"Bentrok Jam Perkuliah Dengan Kode MK. ".substr($cJdw,0,-1));
				return Yii::$app->getResponse()->redirect(['/bisa/krs-t','Krs[kurikulum]'=>$k,'nim'=>$nim]);
			}
            return Yii::$app->getResponse()->redirect(['/bisa/krs-t','Krs[kurikulum]'=>$k,'nim'=>$nim]);
        }else{
            return Yii::$app->getResponse()->redirect(['/bisa/tambah-krs','Krs[kurikulum]'=>$k,'nim'=>$nim]);
        }
    }
    
	public function actionAbsen(){
		
        $con=Yii::$app->db;
        //$sql="delete from tbl_krs where krs_id=$id";
        $sql="select krs_id, mhs_nim, jdwl_id from tbl_krs where krs_id not in(select krs_id from tbl_absensi)";
        $command=$con->createCommand($sql)->queryAll();
		$n=0;
		foreach($command as $data){
			for($a=1;$a <= 14 ;$a++){
				$sqlA = "insert into tbl_absensi(krs_id,jdwl_id_,jdwl_stat,jdwl_sesi,RStat) values ($data[krs_id],$data[jdwl_id],0,$a,0)";
				//$con->createCommand($sqlA)->execute()or die("err");
			}
			$n++;
		}
		echo $n;
	}

	public function actionDeleteKrs($id,$kurikulum='')
    {
		
        $con=Yii::$app->db;
        //$sql="delete from tbl_krs where krs_id=$id";
        $sql="update tbl_krs set RStat='1',krs_stat='0' where krs_id=$id";
        $command=$con->createCommand($sql)->execute();
		\app\models\Funct::LOGS("Menghapus Data Krs ($id) ",new Krs,$id,'d');
        return $this->redirect(array('bisa/krs-t','Krs[kurikulum]' => $kurikulum,'nim'=>$_GET['nim']));
    }	

    public function actionCetakKrs($kurikulum,$nim)
    {
        $db = Yii::$app->db;
        $ID = $nim;   
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

	public function actionNitip(){
		$sql="
			select * from tbl_krs where 
			jdwl_id in(
				select jdwl_id from tbl_jadwal
				where bn_id in(select id FROM tbl_bobot_nilai where B is not NULL)
			)
			and krs_uas is not null and krs_uas!=0
		";
		$sql = Yii::$app->db->createCommand($sql)->queryAll();
		$n=0;
		foreach($sql as $d){
			$n++;
			echo Funct::TotNil1($d['krs_id']);
		}
		echo $n;
	}
	
	public function actionRef(){
		/*
		$sql=" 
			insert into regmhs(nim,tahun,tanggal)
			select DISTINCT nim,tahun,GETDATE() from pembayarankrs 
			where tahun='21516' and concat(nim,tahun) not in(
				select DISTINCT concat(nim,tahun) FROM regmhs
			)		
		 ";	
		 $sql=Yii::$app->db1->createCommand($sql)->execute();
		 */
		 $a  ="SELECT count(DISTINCT nim) tot from regmhs where tahun='21516'";
		 $b  ="
		 		SELECT count(DISTINCT krs.mhs_nim) tot 
				FROM tbl_krs krs,tbl_jadwal jd, tbl_bobot_nilai bn,tbl_kalender kl,tbl_mahasiswa mh
				where jd.jdwl_id=krs.jdwl_id
				and	bn.id=jd.bn_id
				and	bn.kln_id=kl.kln_id
				and mh.mhs_nim = krs.mhs_nim
				and (mh.jr_id=kl.jr_id and mh.pr_kode=kl.pr_kode )
				and krs.kr_kode_='21516' and (
					krs.RStat is null or krs.RStat='0'
					or jd.RStat is null or jd.RStat='0'
					or bn.RStat is null or bn.RStat='0'
					or kl.RStat is null or kl.RStat='0'
					or mh.RStat is null or mh.RStat='0'
				)
				
		 ";
		 $a=Yii::$app->db1->createCommand($a)->queryOne();
		 $b=Yii::$app->db->createCommand($b)->queryOne();
		 return $this->render('refresh',[
		 	'data'=>$a['tot'],
			'data1'=>$b['tot'],
			
			]);
	}

	public function ref(){
		$sql=" 
			insert into regmhs(nim,tahun,tanggal)
			select DISTINCT nim,tahun,GETDATE() from pembayarankrs 
			where tahun='21516' and concat(nim,tahun) not in(
				select DISTINCT concat(nim,tahun) FROM regmhs
			)		
		 ";	
		 $sql=Yii::$app->db1->createCommand($sql)->execute();
		 $data='n';
		 if($sql){$data='y';}
		 return $this->render('refresh',$data);
		
	}

	public function actionB0w0Yu1(){
		$Q =" 
			select DISTINCT ds.ds_id,ds.ds_user,ds.ds_nm,ds.finger_id from tbl_bobot_nilai bn, tbl_kalender kl, tbl_dosen ds
			WHERE kl.kln_id=bn.kln_id
			and ds.ds_id=bn.ds_nidn
			and kr_kode='21516'
			and ds.finger_id is null
			and ds_user not in(
				select username from user_
				where tipe='3'
			)
		";
		
		if(isset($_POST['ok']) ){
			//return $this->redirect(['/bisa/b0w0-g0r3ng']);					
			$Fid=(int)$_POST['idp'];
			$id=(int)$_POST['id'];
			$cek=Dosen::find()->where(['finger_id'=>$Fid])->count();
			if(!$cek && $Fid!==0){
				//die('dsa');
				$dosen=Dosen::findOne($id);
				$dosen->finger_id=$Fid;
				if($dosen->save(false)){
					$GetKode	= Funct::acak(10);
					$PassBaru 	= md5($pass.$GetKode.$cmd['tipe']);
					$UserBaru = new \app\models\User();
					$UserBaru->username 	= $dosen->ds_user;
					$UserBaru->name 		= $dosen->ds_user;
					$UserBaru->password 	= $PassBaru;
					$UserBaru->pass_kode 	= $GetKode;
					$UserBaru->tipe 		= '3';
					$UserBaru->posisi 		= "Dosen";
					$UserBaru->username2 	= $dosen->ds_user."@usbypkp.ac.id";
					$UserBaru->stat 		='1';
					$UserBaru->status 		='10';
					$UserBaru->save(false);
					$ID =$UserBaru->id;
					$cmd = Yii::$app->db
					->createCommand("insert auth_assignment(item_name,[user_id],created_at)values('Dosen','$ID','".time()."') ")
					->execute();
					//return $this->redirect(['/bisa/b0w0-g0r3ng']);					
				}
			}
			return $this->redirect(['/bisa/b0w0-g0r3ng']);
		}
		$Q = Yii::$app->db->createCommand($Q)->queryAll();
		echo "<table>
		<thead>
		<tr>
			<th> </th>
			<th>Username</th>
			<th>Nama</th>
			<th>Finger Id</th>
		</tr>
		</thead>
		<tbody>
		";
		$n=0;
		foreach($Q as $r){
			$n++;
			echo"
				<tr>
					<td><b>$n</b></td>
					<td>$r[ds_user]</td>
					<td> $r[ds_nm]</td>
					<td>";
					$form=\kartik\widgets\ActiveForm::begin(
						[
							'options'=>[
								'onsubmit'=>"return confirm('Yakin Mang ??')"
							]
						]
					);
				echo
					'
						<input type="hidden" name="id" value="'.$r['ds_id'].'">
						<input type="text" name="idp" maxlength="5" size="5" required="required"> <input type="submit" name="ok" value"Save">
					';
					\kartik\widgets\ActiveForm::end();
					echo"</td>
				</tr>
			";
		}
		
		echo "</tbody></table>";	
	}

	public function actionContoh(){
		$sql="
			select 
			DISTINCT
			bn.id,jd.jdwl_id,bn.ds_nidn,ds_nm,bn.mtk_kode, jd.jdwl_hari,jd.jdwl_kls, jd.jdwl_masuk, jd.jdwl_keluar,
			SUBSTRING(convert(VARCHAR, cast(dateadd(minute,-25,jd.jdwl_keluar)as time(0) )),1,5) tengah
			
			from 
			tbl_bobot_nilai bn, tbl_kalender kl , tbl_jadwal jd
			
			, tbl_dosen ds, tbl_krs krs
			WHERE kl.kln_id=bn.kln_id
			and jd.bn_id=bn.id
			and ds.ds_id=bn.ds_nidn
			and krs.jdwl_id=jd.jdwl_id
			AND kr_kode='21516'
			and bn.ds_nidn='50940'
			and jdwl_hari='2'
			and (
						(kl.RStat is null or kl.RStat='0')
				and (bn.RStat is null or bn.RStat='0')
				and (ds.RStat is null or ds.RStat='0')
				and (jd.RStat is null or jd.RStat='0')
				and (krs.RStat is null or krs.RStat='0')
			)
			ORDER BY jd.jdwl_hari, jd.jdwl_masuk, bn.mtk_kode
		";	
		
		$sql=Yii::$app->db->createCommand($sql)->queryAll();
		$jdwl_masuk="";
		$mtk="";
		$jdwl_keluar="";
		$tng=0;
		$n=0;
		foreach($sql as $d){
			$n++;
			echo $n.' ';
			$msk=(int)'1'.implode("",explode(":",$d['jdwl_masuk']));
			$klr=(int)'1'.implode("",explode(":",$d['jdwl_keluar']));
			$tng1=(int)'1'.implode("",explode(":",$d['tengah']));
			
			
			if($klr <$tng){
				$tng=$tng1;
				$jdwl_keluar="";
				
			}else{
				echo "jdwl _id  : $d[jdwl_id] $d[mtk_kode]";
			}
			echo "<br />";
			//if($d['jdwl_masuk']==$jdwl_keluar){echo "jdwl _id  : maraton $d[jdwl_id] $d[mtk_kode] <br />";}
		}
	}

	public function actionAppAll(){
		$QueKrs="select krs_id from tbl_krs where krs_id not in(select krs_id from tbl_absensi)";
		$QueKrs=Yii::$app->db->createCommand($QueKrs)->queryAll();
		foreach($QueKrs as $d){
			echo $d['krs_id'].", ";
			//for($i=1;$i<=12;$i++){}
		}
		//echo "app";
		
	}

	public function actionTes(){
	$jadwal['Kehadiran']='berlangsung';
echo 	(true
	? 
	(strtoupper($jadwal["Kehadiran"])!='HADIR'?
	(strtoupper($jadwal["Kehadiran"])=='BERLANGSUNG'?'bg-info text-highlight':
			(strtoupper($jadwal["Kehadiran"])=='SELESAI'?'bg-warning text-highlight':'info')
		)
	:'bg-success text-highlight'
		
	):
	(strtoupper($jadwal["Kehadiran"])!='HADIR'?'info':
		strtoupper($jadwal["Kehadiran"])=='BERLANGSUNG'
		?'bg-info text-highlight':
		strtoupper($jadwal["Kehadiran"])=='SELESAI'?
		'bg-warning text-highlight':'bg-success text-highlight'
	)
	
	);
		
	}

	public function actionReKrs($kr){
		return _Inject::reKrsBentrok($kr);
	}

}
