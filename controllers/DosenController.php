<?php

namespace app\controllers;

use Yii;
use app\models\Funct;
use app\models\BobotNilaiSearch;
use app\models\BobotNilai;
use app\models\BobotNilaiDosen;
use app\models\Bobot;
use app\models\JadwalDosen;
use app\models\KrsDosen;
use app\models\Dosen;
use app\models\DosenSearch;
use app\models\MahasiswaSearch;
use app\models\Program;
use app\models\Matkul;
use app\models\Kurikulum;
use app\models\Jurusan;

use app\models\Absensi;
use app\models\Rekap;
use app\models\TransaksiFinger;


use app\models\Jadwal;
use app\models\Vakasi;
use app\models\Krs;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Exception;
use yii\db\Expression;

use \mPdf;
use kartik\mpdf\Pdf;
use app\models\TAbsenDosen;
/**
 * DosenController implements the CRUD actions for Dosen model.
 */
class DosenController extends Controller
{

    public function cekId(){
        $Dos =Yii::$app->user->identity->username; 
        $Mod =Dosen::findOne(['ds_user'=>$Dos]);
        if($Mod){return $Mod;}
        return false;
    }

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

    public function actionIndex()
    {
        throw new NotFoundHttpException('The requested page does not exist.');
        $searchModel = new DosenSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id)
    {
        throw new NotFoundHttpException('The requested page does not exist.');
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->ds_id]);
        } else {
        return $this->render('view', ['model' => $model]);
        }
    }

    public function actionCreate()
    {
        throw new NotFoundHttpException('The requested page does not exist.');
        $model = new Dosen;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ds_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        throw new NotFoundHttpException('The requested page does not exist.');
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ds_id]);
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

	public function UpAllNil($id){
		$bobot = \app\models\BobotNilai::findOne($id);
		if($bobot){
			$sql="
				update krs set krs.krs_tot=t.Total, krs.krs_grade=iif(t.Total>B,'A',iif(t.Total>C,'B',iif(t.Total>D,'C',iif(t.Total>E,'D','E'))))
				from (
					select
					jd.bn_id,jd.jdwl_id,krs.krs_id,
					bn.nb_tgs1,	krs_tgs1,	krs_tgs2,	krs_tgs3,	krs_tambahan,	krs_quis,	krs_uts,	krs_uas,	krs_tot,
					iif(krs_tgs1>0,krs_tgs1,0)* iif(nb_tgs1>0,nb_tgs1/convert(float,100),0)+
					iif(krs_tgs2>0 ,krs_tgs2,0)* iif(nb_tgs1>0,nb_tgs2/convert(float,100),0)+
					iif(krs_tgs3>0 ,krs_tgs3,0)* iif(nb_tgs1>0,nb_tgs3/convert(float,100),0)+
					iif(krs_tambahan >0 ,krs_tambahan,0)* iif(nb_tambahan>0,nb_tambahan/convert(float,100),0)+
					iif(krs_quis >0 ,krs_quis,0)* iif(nb_quis>0,nb_quis/convert(float,100),0)+
					iif(krs_uts >0 ,krs_uts,0)* iif(nb_uts>0,nb_uts/convert(float,100),0)+
					iif(krs_uas >0 ,krs_uas,0)* iif(nb_uas>0,nb_uas/convert(float,100),0) Total
					,krs_grade
					from tbl_jadwal jd, tbl_krs krs, tbl_bobot_nilai bn,tbl_kalender kln
					WHERE 	
					bn.id='$id'
					and (
						bn.nb_tgs1 >0 or bn.nb_tgs2 >0 or bn.nb_tgs3 >0 or bn.nb_tambahan >0 or
						bn.nb_quis >0 or bn.nb_uts >0 or bn.nb_uas >0 or bn.B >0 or
						bn.C >0 or bn.D >0 or bn.E >0
					)
					and kln.kln_id=bn.kln_id
					and jd.jdwl_id=krs.jdwl_id
					and bn.id = jd.bn_id	
				) t, tbl_bobot_nilai bn, tbl_krs krs
				WHERE t.bn_id=bn.id AND krs.krs_id=t.krs_id						
			";
			
			$up = Yii::$app->db->createCommand($sql)->execute();
			if($up){
				return true;
			}else{return false;}
		}
			
	}

	public function actionBobot()
    {
       
        if (!empty($_POST['action'])) {
            $action = @$_POST['action'];
            $id     = @$_POST['id'];
            $model  = BobotNilaiDosen::findOne(['id'=> $id]);
			$q="select max(isnull(Lock,0)) lock from tbl_jadwal where bn_id=$id";
			$q=Yii::$app->db->createCommand($q)->queryOne();
			
            if(empty($model)) {return json_encode(['status'=> false]);}
			if($q['lock']>=62){return json_encode(['status'=> false]);}

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
					
					if(!DosenController::UpAllNil($model->id)){echo"error";}
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
                    $model->save(false);
					\app\models\Funct::LOGS("Mengubah Data Bobot Nilai($id) ",new BobotNilai,$id,'u');
                    return json_encode(['status'=> true]);
                    break;
                default:
                    return json_encode(['status'=> false]);
                    break;
            }

        }


        $searchModel = new BobotNilaiDosen;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

      
        return $this->render('mtk', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionBobotPenilaian(){
       
        if (!empty($_POST['action'])) {
            $action = @$_POST['action'];
            $id     = @$_POST['id'];
			$uid	= Yii::$app->user->identity->id;
            $model  = BobotNilaiDosen::findOne(['id'=> $id]);
			$ModAll = BobotNilaiDosen::find()
			->innerJoin('tbl_jadwal jd',"(jd.bn_id=tbl_bobot_nilai.id and isnull(tbl_bobot_nilai.RStat,0)=0)")
			->innerJoin('tbl_jadwal jd1',"(isnull(jd1.GKode,jd1.jdwl_id)=jd.GKode and isnull(jd1.RStat,0)=0)")
			->innerJoin('tbl_bobot_nilai bn',"(jd1.bn_id=bn.id and isnull(bn.RStat,0)=0) and bn.id=$id")
			->all();
			#print_r($ModAll);
			//$id="";
			$LOCK=0;
			$BnId=[];
			foreach($ModAll as $d){
				foreach($d->jdw as $d1){if($d1->Lock=='64'){$LOCK=1;}}
				$BnId[]=$d->id;
			}
			
			//var_dump($BnId);
			//die();
			
			$q="select max(isnull(Lock,0)) lock from tbl_jadwal where bn_id=$id";
			$qBN="
				select bn1.id
				from tbl_jadwal jd
				inner JOIN tbl_jadwal jd1 on(ISNULL(jd1.GKode, jd1.jdwl_id)=jd.GKode AND isnull(jd1.RStat,0)=0)
				INNER JOIN tbl_bobot_nilai bn1 on(bn1.id=jd1.bn_id and isnull(bn1.RStat,0)=0)
				INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id AND bn.id=15678)
			";
			//$qBN=Yii::$app->db->createCommand($qBN)->queryAll();
			
			
			$q=Yii::$app->db->createCommand($q)->queryOne();

            if(empty($model)){return json_encode(['status'=> false]);}
			if($LOCK==1){return json_encode(['status'=> false]);}

            switch ($action) {
                case 'edit':				
					$upAll=BobotNilaiDosen::updateAll([
						'nb_tgs1'=> $_POST['nb_tgs1'],
						'nb_tgs2'=> $_POST['nb_tgs2'],
						'nb_tgs3'=> $_POST['nb_tgs3'],
						'nb_quis'=> $_POST['nb_quis'],
						'nb_tambahan'=> $_POST['nb_tambahan'],
						'nb_uts'=> $_POST['nb_uts'],
						'nb_uas'=> $_POST['nb_uas'],
						'B'=> $_POST['B'],
						'C'=> $_POST['C'],
						'D'=> $_POST['D'],
						'E'=> $_POST['E'],
						'cuid'=>new  Expression("isnull(cuid,$uid)"),
						'ctgl'=>new  Expression("isnull(ctgl,getdate())"),
						'uuid'=>$uid,
						'utgl'=>new  Expression("getdate()"),
					],['id'=>$BnId]);
					
					if($upAll){
						foreach($BnId as $k=>$v){
							echo "$v<br />";
							if(!self::UpAllNil($v)){echo"error";}
							\app\models\Funct::LOGS("Mengubah Data Bobot Nilai($id) ",new BobotNilaiDosen,$v,'u');					
						}
					}

					return json_encode(['status'=> true]);
                    break;
                case 'default':
					$upAll=BobotNilaiDosen::updateAll([
						'nb_tgs1'=> 10,
						'nb_tgs2'=> 10,
						'nb_tgs3'=> 0,
						'nb_quis'=> 0,
						'nb_tambahan'=> 0,
						'nb_uts'=> 40,
						'nb_uas'=> 40,
						'B'=> 80.99,
						'C'=> 70.99,
						'D'=> 59.99,
						'E'=> 34.99,
						'cuid'=>new  Expression("isnull(cuid,$uid)"),
						'ctgl'=>new  Expression("isnull(ctgl,getdate())"),
						'uuid'=>$uid,
						'utgl'=>new  Expression("getdate()"),
					],['id'=>$BnId]);

					if($upAll){
						foreach($BnId as $k=>$v){
							echo "$v<br />";
							if(!self::UpAllNil($v)){echo"error";}
							\app\models\Funct::LOGS("Mengubah Data Bobot Nilai($id) ",new BobotNilaiDosen,$v,'u');					
						}
					}
                    return json_encode(['status'=> true]);
                    break;
                default:
                    return json_encode(['status'=> false]);
                    break;
            }

        }


        $searchModel = new BobotNilaiDosen;
		$param=Yii::$app->request->getQueryParams();
        $dataProvider = $searchModel->search($param);
      
        return $this->render('mtk', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
			'KR'=>$param['BobotNilaiDosen']['kr_kode']
        ]);
    }

    public function actionJdwlV2(){

        $Params = @Yii::$app->request->getQueryParams();
        if (!empty(@Yii::$app->request->getQueryParams())) {
        $kr_kode = $Params['Kurikulum']['kr_kode'];
        $pr_kode = $Params['Program']['pr_kode']; 
        $jr_id   = $Params['Jurusan']['jr_id'];
        $ds_nidn = Yii::$app->user->identity->username;

        $par     = (empty($pr_kode)) ? '' : " AND pr.pr_kode =$pr_kode ";

        $query = "SELECT jdwl_id, kr_kode Tahun, jr.jr_nama Jurusan,pr.pr_nama Program, mk.mtk_kode Kode,mtk_nama Matakuliah
                ,mk.mtk_sks SKS, jdwl_kls Kelas,CONCAT(h.nm,', ',jdwl_masuk,'-',jdwl_keluar) Jam, ISNULL(T.Total,0) Approved,
                ISNULL(T.Totmin,0) Pending,ISNULL(T.Reject,0) Reject 
				FROM tbl_jadwal jd
                JOIN tbl_bobot_nilai bn on (bn.id=jd.bn_id)
                JOIN tbl_matkul mk on mk.mtk_kode = bn.mtk_kode
                JOIN tbl_kalender kl on kl.kln_id = bn.kln_id
                JOIN tbl_program pr on pr.pr_kode = kl.pr_kode
                JOIN tbl_jurusan jr on jr.jr_id = kl.jr_id
                JOIN tbl_dosen ds on (ds.ds_id = bn.ds_nidn and ds.ds_user ='$ds_nidn')
                JOIN hari h on h.id = jdwl_hari
                LEFT JOIN (SELECT jdwl_id jdwal, sum(iif(krs_stat = 0,1,0)) Totmin,sum(iif(krs_stat = 2,1,0)) Reject, sum(iif(krs_stat=1,1,0)) Total FROM tbl_krs GROUP BY jdwl_id) T 
                ON T.jdwal = jdwl_id
                WHERE kr_kode =$kr_kode $par AND jr.jr_id=$jr_id
				AND (
	 				   (bn.RStat is null or bn.RStat='0')
	 				or (jd.RStat is null or jd.RStat='0')
	 				or (kl.RStat is null or kl.RStat='0')
				)
				";

        $count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($query) T")->queryScalar();

        $dataProvider = new SqlDataProvider([
            'sql' => $query,
             
            'totalCount' => (int)$count,
            /*'sort' => [
                'attributes' => [
                    'age',
                    'name' => [
                        'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
                        'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => 'Name',
                    ],
                ],
            ],*/
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        $searchModel = new JadwalDosen();
        $Kurikulum = Kurikulum::findOne(['kr_kode' => $kr_kode]);
        $Jurusan = Jurusan::findOne(['jr_id' => $jr_id]);
        $Program = Program::findOne(['pr_kode' => $pr_kode]);    
        return $this->render('jdwl', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'Program'   => $Program,
            'Kurikulum'   => $Kurikulum,
            'Jurusan'   => $Jurusan,
        ]);
        }else  {
        $searchModel = new JadwalDosen();
        //$searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('jdwl', [
            'dataProvider' => NULL,
            'searchModel' => $searchModel,
            'Program'   => new Program,
            'Kurikulum'   => new Kurikulum,
            'Jurusan'   => new Jurusan,
        ]);
        }      
    }

    public function actionPerkuliahan($t=''){
        $ds_nidn = Yii::$app->user->identity->username;
        $dsid    = Dosen::findOne(['ds_user'=>$ds_nidn]);
        if($dsid){$dsid=$dsid->ds_id;}else{$dsid=0;}
        $query = TAbsenDosen::find()
            ->select([
                'id','mtk_kode','mtk_nama','jdwl_hari','jdwl_masuk','jdwl_keluar','ds_id','totMhs','jdwl_kls','jdwl_id',
                'totHdr'=>"(select sum(iif(mhs_stat=1,1,0)) from t_absen_mhs where id_absen_ds=t_absen_dosen.id)"
            ])
            #->where("ds_id=914 or ds_id1=914");
            ->where("ds_id=$dsid or ds_id1=$dsid");
        $dataProvider = new ActiveDataProvider(['query' => $query,]);
        return $this->render('perkuliahan2',['dataProvider' => $dataProvider,]);
    }


    public function actionPerkuliahanV180225($t=''){
			$ds_nidn = Yii::$app->user->identity->username;
			$tbl='transaksi_finger';
			if($t==1){$tbl='transaksi_finger.dbo.tf_2016_12_29';}
            $query = "
			select * from(
			
			SELECT distinct jd.jdwl_id, kl.kr_kode Tahun, Jurusan =jr.jr_jenjang+' '+jr.jr_nama,pr.pr_nama Program, mk.mtk_kode Kode,mk.mtk_nama Matakuliah
			,mk.mtk_sks SKS, jdwl_kls Kelas,CONCAT(h.nm,', ',jd.jdwl_masuk,'-',jd.jdwl_keluar) Jam, ISNULL(T.Total,0) Total,
			ISNULL(T.Hadir,0) Hadir,
			jd.Lock
			FROM tbl_jadwal jd
			INNER JOIN tbl_bobot_nilai bn on (bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
			INNER JOIN tbl_matkul mk on (mk.mtk_kode = bn.mtk_kode and isnull(mk.RStat,0)=0)
			INNER JOIN tbl_kalender kl on (kl.kln_id = bn.kln_id and isnull(kl.RStat,0)=0 )
			INNER JOIN tbl_program pr on pr.pr_kode = kl.pr_kode
			INNER JOIN tbl_jurusan jr on jr.jr_id = kl.jr_id
			INNER JOIN tbl_dosen ds on (ds.ds_id = bn.ds_nidn and isnull(ds.RStat,0)=0 )
			INNER JOIN hari h on h.id = jd.jdwl_hari
			INNER JOIN (
				SELECT jdwl_id jdwal, count(krs_id) total,
				sum(iif(ISNULL(tf.mhs_stat,0)=0,0,iif(tf.mhs_stat=1,1,0) )) Hadir
				FROM $tbl tf 
				INNER JOIN user_ u on(
					isnull(tf.ds_get_fid,tf.ds_fid)=u.fid 
					and u.tipe=3  and u.fid is not null 
					and u.username='$ds_nidn'
				)
				GROUP BY jdwl_id
			) T ON (T.jdwal = jd.jdwl_id)
			WHERE isnull(jd.RStat,0)=0 
			)T

			";
		/*
		echo"<pre>";
		print_r($query);			
		echo"</pre>";
		die();
		#*/

		$count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($query) T")->queryScalar();
		$dataProvider = new SqlDataProvider([
			'sql' => $query." order By Jam",    
			'totalCount' => (int)$count,
			'pagination' => false,
		]);
		$searchModel = new JadwalDosen();
		return $this->render('perkuliahan', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);


    }

	/*dummy*/
    public function actionPerkuliahanV2(){
			$ds_nidn = Yii::$app->user->identity->username;
            $query = "
			select * from(
			
			SELECT distinct jd.jdwl_id, kl.kr_kode Tahun, Jurusan =jr.jr_jenjang+' '+jr.jr_nama,pr.pr_nama Program, mk.mtk_kode Kode,mk.mtk_nama Matakuliah
			,mk.mtk_sks SKS, jdwl_kls Kelas,CONCAT(h.nm,', ',jd.jdwl_masuk,'-',jd.jdwl_keluar) Jam, ISNULL(T.Total,0) Total,
			ISNULL(T.Hadir,0) Hadir,
			jd.Lock
			FROM tbl_jadwal jd
			INNER JOIN tbl_bobot_nilai bn on (bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
			INNER JOIN tbl_matkul mk on (mk.mtk_kode = bn.mtk_kode and isnull(mk.RStat,0)=0)
			INNER JOIN tbl_kalender kl on (kl.kln_id = bn.kln_id and isnull(kl.RStat,0)=0 )
			INNER JOIN tbl_program pr on pr.pr_kode = kl.pr_kode
			INNER JOIN tbl_jurusan jr on jr.jr_id = kl.jr_id
			INNER JOIN tbl_dosen ds on (ds.ds_id = bn.ds_nidn and isnull(ds.RStat,0)=0 )
			INNER JOIN hari h on h.id = jd.jdwl_hari
			INNER JOIN (
				SELECT jdwl_id jdwal, count(krs_id) total,
				sum(iif(ISNULL(tf.mhs_stat,0)=0,0,iif(tf.mhs_stat=1,1,0) )) Hadir
				FROM transaksi_finger tf 
				INNER JOIN user_ u on(
					isnull(tf.ds_get_fid,tf.ds_fid)=u.fid 
					and u.tipe=3  and u.fid is not null 
					and u.username='$ds_nidn'
				)
				GROUP BY jdwl_id
			) T ON (T.jdwal = jd.jdwl_id)
			WHERE isnull(jd.RStat,0)=0 
			)T

			";
		/*
		echo"<pre>";
		print_r($query);			
		echo"</pre>";
		die();
		#*/

		$count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($query) T")->queryScalar();
		$dataProvider = new SqlDataProvider([
			'sql' => $query." order By Jam",    
			'totalCount' => (int)$count,
			'pagination' => false,
		]);

		$searchModel = new JadwalDosen();
		return $this->render('perkuliahan1', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);


    }

    public function actionAbsensiKuliahV2($id){
        $model = \app\models\TAbsenDosen::findOne($id);

        $db = Yii::$app->db1;
        $keuangan 	= Funct::getDsnAttribute('Database', $db->dsn);
        if(!$keuangan){$keuangan 	= Funct::getDsnAttribute('Database', $db->dsn);}
        $mhs   = \app\models\TAbsenMhs::find()
            ->select(['t_absen_mhs.*','p.Nama','ad.mtk_kode','ad.mtk_nama','ad.jdwl_kls'])
            ->innerJoin("t_absen_dosen ad","(ad.id=t_absen_mhs.id_absen_ds and isnull(ad.RStat,0)=0 and (ad.id=$model->id or ad.GKode_='$model->GKode_'))")
            ->innerJoin("$keuangan.dbo.student s","(s.nim COLLATE Latin1_General_CI_AS=t_absen_mhs.mhs_nim)")
            ->innerJoin("$keuangan.dbo.people p","(p.No_Registrasi = s.no_registrasi)")
            //->where(['ad'])
            ->orderBy(['ad.jdwl_id'=>SORT_ASC,'t_absen_mhs.mhs_nim'=>SORT_ASC])
            ->all();
        return $this->render('hari_iniV',[
            'model'=>$model,
            'mhs'=>$mhs,
        ]);


    }

    public function actionSaveBerjalanV(){
        $usr="0";
        if(Yii::$app->user->identity->username){$usr=Yii::$app->user->identity->id;}
        if (Yii::$app->getRequest()->isAjax) {
            $Params = $_POST;
            $model = \app\models\TAbsenMhs::findOne(['id'=> $Params['id'],'mhs_nim' => $Params['nim']]);
            $stat  = $model->mhs_stat;
            $model->mhs_stat    = ($stat =='1') ? '0':'1';
            $model->uuid        = $usr;
            $model->ket         = ($stat=='1') ? 'Kehadiran Dibatalkan Oleh Dosen':'Kehadiran Diizinkan Oleh Dosen';
            $model->utgl        = new  Expression("getdate()");

            if ($model->save(false)) {
                if ($model->mhs_stat==1) {
                    echo json_encode(['message'=>'','class' => 'do_abs btn fa fa-check-circle',"color"=>"green",'ket'=>$model->ket]);
                }else{echo json_encode(['message'=>'','class' => 'do_abs btn fa fa-times-circle',"color"=>"red",'ket'=>$model->ket]);}
                \app\models\Funct::LOGS("Mengubah Data Kehadiran  Mahasiswa ($model->id)",new \app\models\TAbsenMhs,$model->id,'u',false);
            }else{echo json_encode(['message'=>'Terjadi Kesalahan, tidak dapat menyimpan. Hubungi IT Support.']);}
        }
        #*/
    }

    public function actionSaveBerjalanVt(){
        #/*
        $usr="0";
        if(Yii::$app->user->identity->username){$usr=Yii::$app->user->identity->id;}
        if (Yii::$app->getRequest()->isAjax) {
            $Params = $_POST;
            $model  = \app\models\TAbsenMhs::findOne(['id'=>$Params['id'],'mhs_nim' => $Params['nim']]);
            $stat   = $model->mhs_stat;
            $model->mhs_stat    = $stat==""?($model->mhs_masuk==""?NULL:0):NULL; #new  Expression("iif(mhs_stat is null,0,NULL)");
            $model->ket         = $stat==""?($model->mhs_masuk==""?'Diizinkan Masuk Oleh Dosen':'Dibatalkan Masuk Oleh Dosen'):'Diizinkan Masuk Oleh Dosen'; #new  Expression("iif(mhs_stat is null,'Dibatalkan Masuk Berdasarkan Memo Dosen','Diizinkan Masuk Berdasarkan Memo Dosen')");
            $model->mhs_masuk   = new  Expression("isnull(mhs_masuk,cast(getdate() as time(0)))");
            $model->uuid        = $usr;
            $model->utgl        = new  Expression("getdate()");

            if ($model->save(false)) {
                $model = \app\models\TAbsenMhs::findOne($Params['id']);
                $time=substr($model->mhs_masuk,0,5);
                if ($model->mhs_stat==='0') {
                    echo json_encode(['message'=>'','class' => 'do_time btn badge',"background"=>"red","v"=>"$time",'ket'=>$model->ket]);
                }else{echo json_encode(['message'=>'','class' => 'do_time btn badge',"background"=>"green","v"=>"$time",'ket'=>$model->ket]);}
                \app\models\Funct::LOGS("Mengubah Data Kehadiran Masuk Mahasiswa ($model->id)",new \app\models\TAbsenMhs,$model->id,'u',false);
            }else{echo json_encode(['message'=>'Terjadi Kesalahan, tidak dapat menyimpan. Hubungi IT Support.']);}
        }
        #*/
    }


    public function actionAbsensiKuliahV2_180225($id,$matakuliah=null,$token=null,$t=''){

		$MaxAbsen=30;
        $matkull = Matkul::findOne(['mtk_kode'=>$matakuliah]);
        $model   = JadwalDosen::findOne(['jdwl_id'=>$id]);
        $jum = $matkull->mtk_sesi;

		$tbl='transaksi_finger';
		if($t==1){$tbl='transaksi_finger.dbo.tf_2016_12_29';}

        $AbsDos="
        select
		max(sesi) sesi,
		max(ds_get_fid) fid,
		tgl_ins tgl_absen,(DATEPART(dw,tgl_ins)-1) jdwl_hari, max(ds_masuk) masuk,max(ds_keluar) keluar,
		cast(getdate() as time(0) ) jserver,
        iif(max(ds_keluar) is null,
            iif(cast(getdate() as time(0)) BETWEEN CAST(max(jdwl_masuk) as time(0)) AND CAST(max(jdwl_keluar) as time(0)),1,0),
            0
        ) vMasuk,

		max(jdwl_masuk) jdwl_masuk,
		iif(
			isnull(max(ds_get_fid),max(ds_fid))
			=max(ds_fid),1,0
		) m,
		isnull(datediff(minute,max(jdwl_masuk),max(ds_masuk)),0) MaxAbsn,
		max(ds_stat) ds_stat
		-- isnull(datediff(minute,max(ds_masuk),max(ds_keluar)),0) m
        from $tbl where jdwl_id='".$model->jdwl_id."'
        GROUP by tgl_ins, jdwl_hari
        ";

        /*
        echo "<pre>";
        print_r($AbsDos);
        echo "</pre>";
        #die();
        #*/

		$AbsDos=Yii::$app->db->createCommand($AbsDos)->queryOne();
		//echo $model->bn->ds_nidn;
		$ketDsn="";
		if($AbsDos['MaxAbsn']>0){$ketDsn=" Terlambat $AbsDos[MaxAbsn] Menit,";}
		if($AbsDos['ds_stat']==='2'){$ketDsn.=" Belum Saatnya Pulang,";}

        $table ='
			<table class="table table-bordered">
				<tr>
					<th rowspan="3"> (Pertemuan Ke. '.$AbsDos['sesi'].')<br />
						'.$model->bn->ds->ds_nm.' <br />'.$model->bn->mtk_kode.' - '.$model->bn->mtk->mtk_nama.'( '.$model->jdwl_kls.' )
					</th>
				</tr>
				<tr style="background:none">
					<th>Jadwal</th>
					<th>Absen</th>
					<th> Jam Server </th>
					<!-- th>'.$model->jdwl_masuk.' - '.$model->jdwl_keluar.'</th -->
				</tr>
				<tr>
    				<th>'.$model->jdwl_masuk.' - '.$model->jdwl_keluar.'</th>
					<th>
					'.($AbsDos['masuk'] ? substr($AbsDos['masuk'],0,5):"??:??").' - '.($AbsDos['keluar']?substr($AbsDos['keluar'],0,5):"??:??")
					.'</th>
    				<th>'.$AbsDos['jserver'].'</th>
				</tr>'.
				(!$ketDsn?"":
					'
					<tr style="font-weight:bold;color:#fff;'.($AbsDos['ds_stat']==='1'?'background:green;':'background:red;').'">
						<td>'.($ketDsn!=''?'[ '.substr($ketDsn,0,-1).' ]':'').'</td>
						<td> </td>
						<td>'.($AbsDos['ds_stat']==='0'?'Tidak':($AbsDos['ds_stat']==='1'?'Selesai':'Sedang Berlangsung')).'</td>
					</tr>				
					'
				).'
			</table>';

			$table.="
			<div>
			Untuk mengizinkan mahasiswa yang terlambat, klik tombol jam masuk/keluar mahasiwa, sehingga mahasiswa bisa melakukan finger keluar. Tombol ini tidak akan berfungsi jika perkuliahan sudah selesai.<br />
			Jika perkuliahan sudah selesai, akan muncul icon dikolom sesi yang berfungsi untuk mengubah kehadiran mahasiswa
			</div>
			<table class='table table-bordered table-hover'>
				<thead>
				<tr>
				<th style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>No</th>
				<!-- th style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;' width='1%'>KODE</th -->
				<th style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>(NPM) NAMA</th>
				<th style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>Masuk|Keluar</th>
				<th style='background:".(!$AbsDos['ds_stat']==='1' ? 'green':'rgb(51, 122, 183) none repeat scroll 0% 0%;')."'>Sesi ".$AbsDos['sesi']."</th>
				<th style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>Ket</th>
				</tr></thead>";

        if ($model) {     
        $db = Yii::$app->db1;
        $keuangan = Funct::getDsnAttribute('Database', $db->dsn);
		if(!$keuangan){$keuangan = Funct::getDsnAttribute('Database', $db->dsn);}
			/*SELECT a.id,k.mhs_nim,p.Nama, k.jdwl_id,k.krs_id,a.sesi,mhs_masuk masuk,mhs_keluar keluar,a.mhs_stat jdwl_stat,
				datediff(minute,dateadd(minute,15,ds_masuk),mhs_masuk) toleransi
				,a.ds_stat
				,datediff(minute,a.ds_masuk,a.mhs_masuk) rmsk
				,datediff(minute,a.ds_masuk,a.mhs_masuk) rklr
			*/
        
		$query1 ="
			SELECT a.id,k.mhs_nim,p.Nama, k.jdwl_id,k.krs_id,a.sesi,mhs_masuk masuk,mhs_keluar keluar,a.mhs_stat jdwl_stat,
				datediff(minute,dateadd(minute,15,ds_masuk),mhs_masuk) toleransi
				,a.ds_stat
				,bn.mtk_kode,j.jdwl_kls,a.mtk_nama	
				,datediff(minute,a.ds_masuk,a.mhs_masuk) rklr
				,DATEDIFF(minute, DATEADD(MINUTE, -10, a.jdwl_masuk), a.mhs_masuk) mjd
			FROM $tbl a
			INNER JOIN tbl_krs k on(k.krs_id=a.krs_id AND ISNULL(k.RStat,0)=0 AND k.krs_stat ='1')
			INNER JOIN tbl_jadwal j on(j.jdwl_id=k.jdwl_id AND ISNULL(j.RStat,0)=0)
			INNER JOIN tbl_bobot_nilai bn on(bn.id=j.bn_id and ISNULL(bn.RStat,0)=0)
			LEFT JOIN $keuangan.dbo.student st ON (st.nim COLLATE Latin1_General_CI_AS = k.mhs_nim)
			LEFT JOIN $keuangan.dbo.people p ON (p.No_Registrasi = st.no_registrasi)
			where a.ds_get_fid = $AbsDos[fid] and left(a.jdwl_masuk,2)='".substr($AbsDos['jdwl_masuk'],0,2)."'
			order by j.jdwl_id
		   ";

			/*
			echo "<pre>";
			print_r($query1);
			echo "</pre>";
			die();
			#*/
        $data = Yii::$app->db->createCommand($query1)->queryAll();
        $no=1;
		$mTot=0;
		$mHdTot=0;
		$value='';
		$KdMk="";
        foreach ($data as $key) {
            if($KdMk!=$key['jdwl_id']){
                $KdMk=$key['jdwl_id'];
                $table.='<tr><th colspan="6">'."$key[mtk_kode]: $key[mtk_nama] ($key[jdwl_kls])".'</th></tr>';
            }
			$ket='';

            #<td><span class='label label-".($key['jdwl_id']==$id?"success":"primary")."' style='font-size:14px'>$key[mtk_kode] ($key[jdwl_kls]) </span></td>
            $table .= "<tr>
			<td style='font-weight: bold; font-size: 14px;'>".$no++."</td>			
			<td style='font-weight: bold; font-size: 14px;'>($key[mhs_nim]) $key[Nama] $key[rmhs]</td>";
			$attribute = '';
			if($AbsDos['fid']){
				$attribute = 'data-kode="'.$key['id'].'"';
			}
			
			$mStat 	= $key['jdwl_stat'];
			$masuk 	= $key['masuk'];
			$keluar = $key['keluar'];
			//$mStat =($mStat==4?null:$mStat);
            $mKeluar=($keluar?substr($keluar,0,5):"--:--");
            $mMasuk=($masuk?substr($masuk,0,5):"--:--");
			$k="a$key[id]";

			$InfoMasuk="";
			if($AbsDos['masuk']){
                $InfoMasuk='<a href="'.Url::to(['save-absensi-kuliah-v2','id'=>$key['id'],'s'=>'0','#'=>"$k"]).'" class=" btn btn-danger"><b style="color:#000" id="'.$k.'">'.$mMasuk." | $mKeluar</b></a>";
				if($key['mjd']<0){$ket='Finger masuk sebelum jam masuk yang sudah ditentukan';}
				if($AbsDos['keluar'] && $key['rklr']<0 && $key['keluar']){$ket='Finger Pulang Sebelum Dosen. ';}
								
				if($mStat==='2'){$ket='Dosen Belum Menutup Perkuliahan. ';}			

				if((!$key['keluar'] && $mStat==='4' ) || !$key['masuk']){
					
					$value='';

					#$mMasuk = "<span style='font-size: 12px;'> xx:xx </span>";
					if($mStat==4 && !$key['keluar']){
						//$value = '<a href="'.Url::to(['save-absensi-kuliah-v2','id'=>$key['id'],'s'=>'1','f'=>1]).'" class="glyphicon glyphicon-remove-circle" style="color:red;"></a>';
						#$ket="<b>Finger masuk sebelum jam masuk yang sudah ditentukan</b>";
						$InfoMasuk = '<a href="'.Url::to(['save-absensi-kuliah-v2','id'=>$key['id'],'s'=>'0','#'=>"$k"]).'" class=" btn btn-danger"><b style="color:#000" id="'.$k.'">'.$mMasuk." | $mKeluar</b></a>";
						if($key['ds_stat']==='1'){
							$value = '<a href="'.Url::to(['save-absensi-kuliah-v2','id'=>$key['id'],'s'=>'1','f'=>1,'#'=>"$k"]).'" class="glyphicon glyphicon-remove-circle" style="color:red;"  id="'.$k.'"></a>';
							$InfoMasuk = '<span class=" label label-danger" style="font-size:12px"><b style="color:#000">'.$mMasuk." | $mKeluar</b></span>";
						}
					}
					//$mMasuk = "<span style='font-size: 12px;'> ".substr($masuk,0,5)." </span>";
					
				}else{
					$mTot++;
					$InfoMasuk= '<a href="'.Url::to(['save-absensi-kuliah-v2','id'=>$key['id'],'s'=>'1','#'=>"$k"]).'" class=" btn btn-success"><b style="color:#000" id="'.$k.'">'.$mMasuk." | $mKeluar</b></a>";
					$toleran =$key['toleransi'];
					if($mStat==='0'){
						$InfoMasuk= '<a href="'.Url::to(['save-absensi-kuliah-v2','id'=>$key['id'],'s'=>'0','#'=>"$k"]).'" class=" btn btn-danger"><b style="color:#000" id="'.$k.'">'.substr($masuk,0,5)." | $mKeluar</b></a>";
					}
							
					if($key['ds_stat']==='1'){
						$value='';
						$value = '<a href="'.Url::to(['save-absensi-kuliah-v2','id'=>$key['id'],'s'=>'1','f'=>1]).'" class="glyphicon glyphicon-remove-circle" style="color:red;"></a>';
						$InfoMasuk= '<span class=" label label-danger" style="font-size:12px"><b style="color:#000">'.$mMasuk." | $mKeluar</b></span>";
						if($mStat==='1'||$mStat==='2'){
							$mHdTot++;
							$InfoMasuk = '<span class=" label label-success" style="font-size:12px"><b style="color:#000">'.substr($masuk,0,5)." | $mKeluar</b></span>";
							$value='<span class="label label-success" style="font-size:12px;"><b>Selesai</b></span>';
							$value = '<a href="'.Url::to(['save-absensi-kuliah-v2','id'=>$key['id'],'s'=>'0','f'=>1]).'" class="glyphicon glyphicon-ok-circle" style="color:green;"></a>';
							//$value = '<i class="btn glyphicon glyphicon-ok-circle" style="color:green;" ></i>';
						}
						
						if($mStat==='0'){
							$value='<span class="label label-danger" style="font-size:12px;"><b>Selesai</b></span>';
							$value = '<a href="'.Url::to(['save-absensi-kuliah-v2','id'=>$key['id'],'s'=>'1','f'=>1]).'" class="glyphicon glyphicon-remove-circle" style="color:red;"></a>';
							//$value = '<i class="btn glyphicon glyphicon-remove-circle" style="color: red;"></i>';
						}					
						
						if(!$keluar){$ket='Tidak Absen Keluar';}
						if($key['toleransi']>0){
							if($mStat==='1'){$ket="Di ijinkan Hadir.";}						
						}else{if($mStat==='0'){$ket="Kehadiran Dibatalkan.";}}
					}
					
					if($key['ds_stat']==='0'){
						$InfoMasuk= '<span class=" label label-danger" style="font-size:12px"><b style="color:#000">'."$mMasuk | $mKeluar</b></span>";
					}
	
					if ($mStat!=='0'){$arrTot++;}							
				}

			}

			if($AbsDos['vMasuk']==0){
                $InfoMasuk='<b style="color:#000" id="'.$k.'">'."$mMasuk | $mKeluar</b>";
            }
		
			$table  .="<td style='text-align:center;'>$InfoMasuk</td>";
			$table  .="<td style='text-align:center;'>$value</td>";                            
			$table  .="<td style='text-align:center;'>".($key['toleransi'] > 0?"Terlambat $toleran menit dari toleransi. ":"")." <b>$ket</b> </td>";
            $table  .="</tr>";
        }

        $table .= "
			<tr style='font-weight:bold;text-align:right;'><th colspan='2'>TOTAL MASUK</th><th>$mTot</th>
			<th>$mHdTot</th><th> </th>
			</tr>
		</table>";
     	//die();
        return $this->render('absensi_kuliah_v2', [   
            'table' => $table,     
        ]);
         
         }
    }

    public function actionSaveAbsensiKuliahV2($id,$s,$t='',$f=''){

		$tbl='transaksi_finger';
		#/*
		if($t==1){ // data dummy
			$tbl='transaksi_finger.dbo.tf_2016_12_29';
		}
		#*/
		$Fid= Yii::$app->user->identity->Fid;
		$sql="select * from $tbl where id='$id' and ds_get_fid='$Fid'";
		$sql=Yii::$app->db->createCommand($sql)->queryOne();
		$up="";
		
		if($f==1){
			if($s==1){$up="update $tbl set mhs_stat='1' where id='$id' and ds_get_fid='$Fid'";}
			if($s==0){$up="update $tbl set mhs_stat='0' where id='$id' and ds_get_fid='$Fid'";}			
		}else{
		    #Dosen Sudah Absen Masuk & belum a
			if($s==1){$up="update $tbl set mhs_stat='0' where id='$id' and ds_get_fid='$Fid'";}
			if($s==0){$up="update $tbl set mhs_stat=NULL ,mhs_masuk=isnull(mhs_masuk,cast(getdate() as time(0))) where id='$id' and ds_get_fid='$Fid'";}
		}
		
		
		if($sql && $up!=''){
			
			$up=$up;#." and tgl_ins='".date('Y-m-d')."'";
			$up=Yii::$app->db->createCommand($up)->execute();
		}	

		return $this->redirect([
			'absensi-kuliah-v2','id'=>$sql['jdwl_id'],'#'=>"a".$sql['id']
		]);	

    }	
    /*end dummy*/    
	
	public function actionBobotNilai($k){
		$model=Bobot::findOne($k);
		$uid=Yii::$app->user->identity->id;
		
		if(!$model->GKode){
			$model=new Bobot;
			$model->GKode = $k;
			$model->cuid  = $uid;
			$model->ctgl = new Expression("getdate()");
			$model->save();
			return $this->redirect(['/dosen/bobot-nilai','k'=>$k]);
		}else{
			$query="";
			$modJadwal=Jadwal::find()->where("GKode='$k' and isnull(RStat,0)=0 and jdwl_parent is null")->all();



			if(isset($_POST['DF'])){
				$model->nb_tgs1=10;$model->nb_tgs2=10;$model->nb_uts=40;$model->nb_uas=40;
				$model->B=80.99;$model->C=70.99;$model->D=59.99;$model->E=34.99;
				$model->uuid  = $uid;
				$model->utgl = new Expression("getdate()");
				$model->save();
				$ket_="<span style='color:#000;font-size:14px;'><b>Bobot nilai berhasil di ubah
				<ul>
					<li>Persentase: Tugas 1=".$model->nb_tgs1."%, Tugas 2=".$model->nb_tgs2."%, Tugas 3=".$model->nb_tgs3.
					"%, Absensi=".$model->nb_tambahan."%, Quis=".$model->nb_quis."%, UTS=".$model->nb_uts."%, UAS=".$model->nb_uas."%, </li>
					<li>Range Nilai: B=".$model->B.", C=".$model->C.", D=".$model->D.", E=".$model->E."</li>
				</ul> 
				Klik ".Html::a("<i class='fa fa-upload'></i> Input Nilai ",'#',['class'=>'btn btn-primary btn-xs'])
				." untuk melanjutkan ke proses penilian mahasiswa</b></span>";
				Yii::$app->getSession()->setFlash('success',"$ket_");
				return $this->redirect(['/dosen/bobot-nilai','k' => $model->GKode]);
			}

			if ($model->load(Yii::$app->request->post())) {
				$tot = $model->nb_tgs1+$model->nb_tgs2+$model->nb_tgs3+$model->nb_tambahan+$model->nb_quis+$model->nb_uts+$model->nb_uas;
				
				$ket="";
				if($tot > 100 ){$ket="Persentase melebihi 100%";}
				if($tot < 100 ){$ket="Persentase kurang dari 100%";}
				if($ket){Yii::$app->getSession()->setFlash('error',"$ket");}
				if($tot==100){
					$ket_="<span style='color:#000;font-size:14px;'><b>Bobot nilai berhasil di ubah
					<ul>
						<li>Persentase: Tugas 1=".$model->nb_tgs1."%, Tugas 2=".$model->nb_tgs2."%, Tugas 3=".$model->nb_tgs3.
						"%, Absensi=".$model->nb_tambahan."%, Quis=".$model->nb_quis."%, UTS=".$model->nb_uts."%, UAS=".$model->nb_uas."%, </li>
						<li>Range Nilai: B=".$model->B.", C=".$model->C.", D=".$model->D.", E=".$model->E."</li>
					</ul> 
					Klik ".Html::a("<i class='fa fa-upload'></i> Input Nilai ",'#',['class'=>'btn btn-primary btn-xs'])
					." untuk melanjutkan ke proses penilian mahasiswa</b></span>";
					Yii::$app->getSession()->setFlash('success',"$ket_");
					$model->save();
					return $this->redirect(['/dosen/bobot-nilai', 'k' => $model->GKode]);
					
				}
				
				#return $this->redirect(['kr-view', 'id' => $model->kr_kode]);
			} 

			
		return $this->render('/dosen/bobot_nilai',[
				'modJadwal'=>$modJadwal,
				'model'=>$model,
			]);
		}
		
		
		return $this->redirect(['/site/index']);
		
		
			
		
	}
	
    public function actionJdwl(){
        $Params = @Yii::$app->request->getQueryParams();
        if (!empty(@Yii::$app->request->getQueryParams())) {
            $kr_kode = $Params['Kurikulum']['kr_kode'];
            $pr_kode = $Params['Program']['pr_kode']; 
            $jr_id   = $Params['Jurusan']['jr_id'];
            $ds_nidn = Yii::$app->user->identity->username;
    
            //$par     = (empty($pr_kode)) ? '' : " AND pr.pr_kode =$pr_kode ";

            $query = "
				SELECT jdwl_id, kr_kode Tahun, Jurusan =jr.jr_jenjang+' '+jr.jr_nama,pr.pr_nama Program, mk.mtk_kode Kode,mtk_nama Matakuliah
                ,mk.mtk_sks SKS, jdwl_kls Kelas,CONCAT(h.nm,', ',jdwl_masuk,'-',jdwl_keluar) Jam, ISNULL(T.Total,0) Approved
				,jd.jdwl_hari,jd.jdwl_masuk,h.nm,
				
                ISNULL(T.Totmin,0) Pending,ISNULL(T.Reject,0) Reject ,
                jd.Lock
				,dbo.subJdwl(jd.jdwl_id) jadwal
				,bn.nb_tgs1,bn.nb_tgs2,bn.nb_tgs3,bn.nb_tambahan,bn.nb_quis,bn.nb_uts,bn.nb_uas
				,bn.B,bn.C,bn.D,bn.E
				,jd.GKode
                FROM tbl_jadwal jd
                JOIN tbl_bobot_nilai bn on (bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
                JOIN tbl_matkul mk on (mk.mtk_kode = bn.mtk_kode and isnull(mk.RStat,0)=0)
                JOIN tbl_kalender kl on (kl.kln_id = bn.kln_id and isnull(kl.RStat,0)=0 )
                JOIN tbl_program pr on pr.pr_kode = kl.pr_kode
                JOIN tbl_jurusan jr on jr.jr_id = kl.jr_id
                JOIN tbl_dosen ds on (ds.ds_id = bn.ds_nidn and ds.ds_user ='$ds_nidn' and isnull(ds.RStat,0)=0 )
                JOIN hari h on h.id = jdwl_hari
                LEFT JOIN (
					SELECT jdwl_id jdwal, sum(iif(krs_stat = 0,1,0)
					) Totmin,
                	sum(iif(krs_stat = 2,1,0)) Reject,
                	sum(iif(krs_stat=1,1,0)) Total
					FROM tbl_krs where isnull(RStat,0)=0 
					GROUP BY jdwl_id
				) T 
                ON T.jdwal = jdwl_id
                WHERE kr_kode =$kr_kode
				 and isnull(jd.RStat,0)=0 
				 and isnull(jd.jdwl_parent,jd.jdwl_id)=jd.jdwl_id
				 
                ";

            $count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($query) T")->queryScalar();
            $dataProvider = new SqlDataProvider([
                #'sql' => $query." order by jr.jr_id,pr.pr_kode,bn.mtk_kode ",
                 'sql' => $query." order by jd.jdwl_hari ASC,jd.jdwl_masuk ASC",
                'totalCount' => (int)$count,
                'pagination' => [
                    'pageSize' =>false,
                ],
            ]);
            $searchModel = new JadwalDosen();
            $Kurikulum = Kurikulum::findOne(['kr_kode' => $kr_kode]);
            $Jurusan = Jurusan::findOne(['jr_id' => $jr_id]);
            $Program = Program::findOne(['pr_kode' => $pr_kode]);    
            return $this->render('jdwl_v2', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'Program'   => $Program,
                'Kurikulum'   => $Kurikulum,
                'Jurusan'   => $Jurusan,
            ]);
        }else  {
            $searchModel = new JadwalDosen();
            return $this->render('jdwl_v2', [
                'dataProvider' => NULL,
                'searchModel' => $searchModel,
                'Program'   => new Program,
                'Kurikulum'   => new Kurikulum,
                'Jurusan'   => new Jurusan,
            ]);
        }      
    }

    public function actionBobot1($id){

//        $id_dosen = Yii::$app->user->identity->id;
//        $test= Yii::$app->authManager->checkAccess($id_dosen,'akses_dosen');
//        $Jadwal = Jadwal::findOne($id);
//        if ($test) {
//            if ($Jadwal->bn->ds->ds_user!==Yii::$app->user->identity->username){
//                throw new NotFoundHttpException('The requested page does not exist.');
//            }
//        }
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
                return $this->redirect(['/dosen/nilait','id'=>$id]);
            }else{
                if ($model->load(Yii::$app->request->post())){
                    $model->save(false);
                    return $this->redirect(['/dosen/nilait','id'=>$id]);
                }
            }
        }
        return $this->render('/jadwal/_bobot',[
            'model'=>$model,
            'jadwal'=>$Jadwal,
        ]);

    }

    public function actionJadwal(){

        $Params = @Yii::$app->request->getQueryParams();
        if (!empty(@Yii::$app->request->getQueryParams())) {
            $kr_kode = $Params['Kurikulum']['kr_kode'];
            $pr_kode = $Params['Program']['pr_kode']; 
            $jr_id   = $Params['Jurusan']['jr_id'];
            $ds_nidn = Yii::$app->user->identity->username;
    
            //$par     = (empty($pr_kode)) ? '' : " AND pr.pr_kode =$pr_kode ";
    
            $query = "
				SELECT jdwl_id, kr_kode Tahun, Jurusan =jr.jr_jenjang+' '+jr.jr_nama,pr.pr_nama Program, mk.mtk_kode Kode,mtk_nama Matakuliah
                ,mk.mtk_sks SKS, jdwl_kls Kelas,CONCAT(h.nm,', ',jdwl_masuk,'-',jdwl_keluar) Jam, ISNULL(T.Total,0) Approved
				,jd.jdwl_hari,jd.jdwl_masuk,h.nm,
				
                ISNULL(T.Totmin,0) Pending,ISNULL(T.Reject,0) Reject ,
                jd.Lock
				,dbo.subJdwl(jd.jdwl_id) jadwal
				,bt.nb_tgs1,bt.nb_tgs2,bt.nb_tgs3,bt.nb_tambahan,bt.nb_quis,bt.nb_uts,bt.nb_uas
				,bt.B,bt.C,bt.D,bt.E
				,jd.GKode
				,bt.GKode BKode
                FROM tbl_jadwal jd
                JOIN tbl_bobot_nilai bn on (bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
                JOIN tbl_matkul mk on (mk.mtk_kode = bn.mtk_kode and isnull(mk.RStat,0)=0)
                JOIN tbl_kalender kl on (kl.kln_id = bn.kln_id and isnull(kl.RStat,0)=0 )
                JOIN tbl_program pr on pr.pr_kode = kl.pr_kode
                JOIN tbl_jurusan jr on jr.jr_id = kl.jr_id
                JOIN tbl_dosen ds on (ds.ds_id = bn.ds_nidn and ds.ds_user ='$ds_nidn' and isnull(ds.RStat,0)=0 )
                JOIN hari h on h.id = jdwl_hari
				LEFT JOIN bobotnilai bt on(bt.GKode=jd.Gkode)
                LEFT JOIN (
					SELECT jdwl_id jdwal, sum(iif(krs_stat = 0,1,0)
					) Totmin,
                	sum(iif(krs_stat = 2,1,0)) Reject,
                	sum(iif(krs_stat=1,1,0)) Total
					FROM tbl_krs where isnull(RStat,0)=0 
					GROUP BY jdwl_id
				) T 
                ON T.jdwal = jdwl_id
                WHERE kr_kode =$kr_kode
				 and isnull(jd.RStat,0)=0 
				 and isnull(jd.jdwl_parent,jd.jdwl_id)=jd.jdwl_id
				 
                ";

            $count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($query) T")->queryScalar();
            $dataProvider = new SqlDataProvider([
                #'sql' => $query." order by jr.jr_id,pr.pr_kode,bn.mtk_kode ",
                 'sql' => $query." order by jd.jdwl_hari ASC,jd.jdwl_masuk ASC",
                'totalCount' => (int)$count,
                'pagination' => [
                    'pageSize' =>false,
                ],
            ]);
            $searchModel = new JadwalDosen();
            $Kurikulum = Kurikulum::findOne(['kr_kode' => $kr_kode]);
            $Jurusan = Jurusan::findOne(['jr_id' => $jr_id]);
            $Program = Program::findOne(['pr_kode' => $pr_kode]);    
            return $this->render('jadwal', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'Program'   => $Program,
                'Kurikulum'   => $Kurikulum,
                'Jurusan'   => $Jurusan,
            ]);
        }else  {
            $searchModel = new JadwalDosen();
            return $this->render('jdwl_v2', [
                'dataProvider' => NULL,
                'searchModel' => $searchModel,
                'Program'   => new Program,
                'Kurikulum'   => new Kurikulum,
                'Jurusan'   => new Jurusan,
            ]);
        }      
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
        if ($token) {//Perform Action Save Attendance Bray....
            $Params     = unserialize(base64_decode($token));
            $Absensi    = $this->loadSession($Params);           
            $stat       = $Absensi->jdwl_stat;
            $Absensi->jdwl_stat = ($stat =='1') ? '0':'1';
            if($Absensi->save(false)){
				\app\models\Funct::LOGS("Mengubah Data Kehadiran Mahasiswa ($Absensi->id)",new Absensi,$Absensi->id,'u',false);
			}
			
			
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
        $keuangan = Funct::getDsnAttribute('Database', $db->dsn);
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
                echo json_encode(['status' => TRUE]);
        }
        return $this->render('attendance', [        
            'dataProvider'  => $dataProvider,
            'columns'       => $columns,
        ]);
         
         }
    }

    public function actionAbsensiKuliah($id,$matakuliah=null,$token=null){

        $matkull = Matkul::findOne(['mtk_kode'=>$matakuliah]);
        $model   = JadwalDosen::findOne(['jdwl_id'=>$id]);
        $jum = $matkull->mtk_sesi;

        $AbsDos="
        select 
            max(sesi) sesi,
			max(ds_get_fid) fid,
			tgl_ins tgl_absen,(DATEPART(dw,tgl_ins)-1) jdwl_hari, max(ds_masuk) masuk,max(ds_keluar) keluar,
            iif(
                isnull(max(ds_get_fid),max(ds_fid))
                =max(ds_fid),1,0
            ) m,
            isnull(datediff(minute,max(ds_masuk),max(ds_keluar)),0) m
        from transaksi_finger where jdwl_id='".$model->jdwl_id."'
        GROUP by tgl_ins, jdwl_hari
        ";

		$AbsDos=Yii::$app->db->createCommand($AbsDos)->queryOne();
		//echo $model->bn->ds_nidn;
		$a=$AbsDos;
        $table ='
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th rowspan="3">
						'.$model->bn->ds->ds_nm.' <br />
						Pertemuan Ke. '.$d['sesi'].", ".Funct::Hari()[$model->jdwl_hari].', '.$model->jdwl_masuk.' - '.$model->jdwl_keluar.' : '
						.$model->bn->mtk_kode.' - '.$model->bn->mtk->mtk_nama.'( '.$model->jdwl_kls.' )
					</th>
				</tr>
				<tr>
					<th>Masuk : '.($AbsDos['masuk']?substr($AbsDos['masuk'],0,5):"??:??").'</th>
				</tr>
				<tr>
					<th>Keluar : '.($AbsDos['keluar']?substr($AbsDos['keluar'],0,5):"??:??").'</th>
				</tr>
			</thead>';
			$table.="
			<tr>
			</tbody>
			</table>
		
			<table class='table table-bordered table-hover'>
				<thead>
				<tr>
				<th rowspan='2' style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>No</th>
				<th rowspan='2' style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>(NPM) NAMA</th>
				<th rowspan='2' style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>Masuk</th>
				<th rowspan='2' style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>Keluar</th>
				<th style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>SESI</th>
				</tr><tr style='align-content: center; text-align: center; font-weight: bold; background-color: rgb(51, 122, 183);'>
				";        
			$table .= "<td style='background:".($AbsDos['fid']?'green':'red')."'>".$AbsDos['sesi']."</td>";        
        	$table .= "</tr></thead>";

        if ($model) {     
        $db = Yii::$app->db1;
        $keuangan = Funct::getDsnAttribute('Database', $db->dsn);
		if(!$keuangan){$keuangan = Funct::getDsnAttribute('Database', $db->dsn);}
		
        $query1 ="
			SELECT a.id,k.mhs_nim,p.Nama, k.jdwl_id,k.krs_id,a.sesi,mhs_masuk masuk,mhs_keluar keluar,a.mhs_stat jdwl_stat,
				datediff(minute,ds_masuk,mhs_masuk) toleransi
			FROM transaksi_finger a			
			INNER JOIN tbl_krs k on(k.krs_id=a.krs_id AND ISNULL(k.RStat,0)=0 AND k.jdwl_id=$id and k.krs_stat ='1')
			INNER JOIN tbl_jadwal j on(j.jdwl_id=k.jdwl_id AND ISNULL(j.RStat,0)=0)
			INNER JOIN tbl_bobot_nilai bn on(bn.id=j.bn_id and ISNULL(bn.RStat,0)=0)
			LEFT JOIN $keuangan.dbo.student st ON (st.nim COLLATE Latin1_General_CI_AS = k.mhs_nim)
			LEFT JOIN $keuangan.dbo.people p ON (p.No_Registrasi = st.no_registrasi)
			where a.ds_get_fid = $AbsDos[fid] and left(a.jdwl_masuk,2)='".substr($AbsDos['jdwl_masuk'],0,2)."'
		   ";
		echo"<pre>";
		print_r($q);
		echo"</pre>";

        $data = Yii::$app->db->createCommand($query1)->queryAll();
        $no=1;
        foreach ($data as $key) {
            $table .= "<tr>
                        <td style='font-weight: bold; font-size: 14px;'>".$no++."</td>
                        <td style='font-weight: bold; font-size: 14px;'>($key[mhs_nim]) $key[Nama]</td>";
						$k = 'S'.$key['sesi'];
						if(!isset($arrTot[$k])){$arrTot[$k]=0;}            

						$attribute = '';
						if($AbsDos['fid']){
							$attribute = 'data-kode="'.$key['id'].'"';
						}
						
						if ($key['jdwl_stat']==0) {
							$value = '<a href="javascript:;" class="do_attendance btn glyphicon glyphicon-remove-circle" name ="ctrl_attendance" id="'.$k.'" ' .$attribute. ' style="color: red;"></a>';
							//$value = '<i class="do_attendance btn glyphicon glyphicon-remove-circle" name ="ctrl_attendance"  style="color: red;"></i>';
						}else{
							$arrTot[$k]++;
							$value = '<a href="javascript:;" class="do_attendance btn glyphicon glyphicon-ok-circle" name ="ctrl_attendance" id="'.$k.'" ' .$attribute. '" style="color: green;"></a>';
							//$value = '<i class="do_attendance btn glyphicon glyphicon-ok-circle"  name ="ctrl_attendance" style="color: green;"></i>';
						}
					$table  .=  "<td style='text-align: center;'><!-- $key[toleransi] -->".($key['masuk']?substr($key['masuk'],0,5):"??:??")."</td>";                            
					$table  .=  "<td style='text-align: center;'><!-- $key[jdwl_stat] -->".($key['keluar']?substr($key['keluar'],0,5):"??:??")."</td>";                            
					$table  .=  "<td style='text-align: center;'>$value</td>";                            

            $table  .="</tr>";
        }

        $table .= "
			<tr style='font-weight:bold;text-align:right;'><th colspan='2'>TOTAL</th><th>".implode("</th><th>",$arrTot)."</th></tr>
		</table>";
     	//die();
        return $this->render('absensi_kuliah', [   
            'table' => $table,     
        ]);
         
         }
    }

    public function actionSaveAbsensiKuliah(){
        if (Yii::$app->getRequest()->isAjax) {

			$Params = $_POST;
			//die($Params['kode']);
			$model = TransaksiFinger::findOne(['id'=>$Params['kode']]);
            $stat  = $model->mhs_stat;
            $model->mhs_stat = ($stat =='1') ? '0':'1';
            //$model->mhs_masuk = ($model->mhs_masuk) ? $model->mhs_masuk:date('H:i');
            $model->Sys_ .= "2";			
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

    public function actionAbsensi($id,$matakuliah=null,$token=null){


        return $this->render('/perkuliahan/absen/finger_mahasiswa', Perkuliahan::persensiMahasiswa_finger($id)) ;
    }

    /**/
    public function actionDokAbsensi($id,$matakuliah=null,$token=null){
        $this->layout="none";
        $matkull = Matkul::findOne(['mtk_kode'=>$matakuliah]);
        $model   = JadwalDosen::findOne(['jdwl_id'=>$id]);

        $jum = $matkull->mtk_sesi;
        $AbsDos="
        select 
            max(sesi) sesi,
			tgl tgl_absen,(DATEPART(dw,tgl)-1) jdwl_hari, ds_masuk masuk,max(ds_keluar) keluar,
            iif(
                isnull(max(ds_get_fid),max(ds_fid))
                =max(ds_fid),1,0
            ) m,
            isnull(datediff(minute,max(ds_masuk),max(ds_keluar)),0) m
			,iif(max(isnull(ds_stat,0))=1,1,0) ds_stat
        from m_transaksi_finger where jdwl_id='".$id."'
		and (SELECT t FROM dbo.periode_v4(DEFAULT,tgl,(DATEPART(dw,tgl)-1),DEFAULT,jdwl_id )) not in('ts','as')
        GROUP by tgl, ds_masuk
        ";
        $AbsDos=Yii::$app->db->createCommand($AbsDos)->queryAll();
        //echo $model->bn->ds_nidn;
        $row=14;
        $blue=[];
        $Vsesi=[];$table_='';
        foreach($AbsDos as $d){
            $blue[$d['sesi']]='';
            if(empty($d['masuk'])){$blue[$d['sesi']]='blue;';}
            if($row>=1){
                $Vsesi[$d['sesi']]=$d['ds_stat'];
                $table_.="<td >".date("M d",strtotime($d[tgl_absen])).",<br>".($d[masuk]?substr($d['masuk'],0,5):"?")." - ".($d[keluar]?substr($d['keluar'],0,5):"?")."</td>";
            }
            $row--;
        }
        for($a=0;$a<$row;$a++){$table_.="<td> ? ? - ?</td>";}
        $jum=14;
        $table ='
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th colspan="14">
						'.$model->bn->ds->ds_nm.' <br />
						'.Funct::Hari()[$model->jdwl_hari].', '.$model->jdwl_masuk.' - '.$model->jdwl_keluar.' : '
            .$model->bn->mtk_kode.' - '.$model->bn->mtk->mtk_nama.'( '.$model->jdwl_kls.' )
					</th>
				</tr>
				<tr>';
        for($i=1;$i<=14;$i++){$table.="<th  style='background:".($Vsesi[$i]==1?'green':'red')."'>Sesi $i</th>";}
        $table.='</tr>
			</thead>';

        $table.="<tr>".$table_;

        $table.="
			<tr>
			</table>
				<table class='table table-bordered table-hover tb'>
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
            $table .= "<td style='background:".(Funct::StatAbsDsn($id,$Sesion)?'green':'red')."'>$Sesion</td>";
        }

        $table .= "</tr></thead><tbody style='background:#fff'>";

        if ($model) {
            $db = Yii::$app->db1;
            $keuangan = Funct::getDsnAttribute('Database', $db->dsn);
            if(!$keuangan){$keuangan = Funct::getDsnAttribute('Database', $db->dsn);}

            /*
                        isnull([1],0)[S01],
                        isnull([2],0)[S02],isnull([3],0)[S03],isnull([4],0)[S04],
                        isnull([5],0)[S05],isnull([6],0)[S06],isnull([7],0)[S07],
                        isnull([8],0)[S08],isnull([9],0)[S09],isnull([10],0)[S10],
                        isnull([11],0)[S11],isnull([12],0)[S12],isnull([13],0)[S13],
                        isnull([14],0)[S14],isnull([15],0)[S15],isnull([16],0)[S16]


            */
            $query1 ="SELECT * FROM(SELECT DISTINCT DENSE_RANK() OVER (PARTITION BY jdwl_id ORDER BY mhs_nim ASC) as id, T.*,p.Nama FROM (
			SELECT krs.mhs_nim, krs.jdwl_id,
			krs.krs_id,[1][S01],[2][S02],[3][S03],[4][S04],[5][S05],[6][S06],[7][S07],[8][S08],[9][S09],[10][S10],[11][S11],[12][S12],[13][S13],[14][S14],[15][S15],[16][S16]
			FROM(
				SELECT distinct a.jdwl_id, a.krs_id,
					iif(max(ds_stat)!=0 AND isnull(a.mhs_stat,0)!=0,1,0) jdwl_stat,
					a.sesi
				FROM m_transaksi_finger a
				INNER JOIN tbl_krs k on(k.krs_id=a.krs_id AND ISNULL(k.RStat,0)=0 AND k.jdwl_id=$id)
				INNER JOIN tbl_jadwal j on(j.jdwl_id=k.jdwl_id AND ISNULL(j.RStat,0)=0)
				INNER JOIN tbl_bobot_nilai bn on(bn.id=j.bn_id and ISNULL(bn.RStat,0)=0)
				where a.krs_id=k.krs_id
				and a.jdwl_id=$id
				and a.sesi not in('a','t','15','16')
				and isnull(a.RStat,0)=0
				GROUP BY a.jdwl_id,a.mhs_stat,a.krs_id,a.sesi
			) AS src
				pivot (
				max(jdwl_stat) FOR Sesi IN ([1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],[12],[13],[14],[15],[16])
			) AS pvt
			INNER join tbl_krs krs on krs.krs_id = pvt.krs_id
			INNER join m_transaksi_finger ab on ab.krs_id = pvt.krs_id 
			WHERE krs.krs_stat ='1'
			and isnull(krs.RStat,0)=0 
			and sesi not in('a','t','15','16')
			) T  
			INNER JOIN $keuangan.dbo.student st ON st.nim COLLATE Latin1_General_CI_AS = T.mhs_nim
			INNER JOIN $keuangan.dbo.people p ON p.No_Registrasi = st.no_registrasi) B
			ORDER BY B.mhs_nim ASC
		   ";

            /*
            echo"<pre>";
            print_r($query1);
            echo"</pre>";
            #*/
            $data = Yii::$app->db->createCommand($query1)->queryAll();
            $no=1;
            #/*
            foreach ($data as $key) {
                $table .= "<tr>
                        <td style='font-weight: bold; font-size: 14px;'>".$no++."</td>
                        <td style='font-weight: bold; font-size: 14px;'>$key[mhs_nim]</td>
                        <td >$key[Nama]</td>";
                foreach ($S as $d => $val) {
                    $k = 'S'.$val;
                    if(!isset($arrTot[$k])){$arrTot[$k]=0;}

                    $attribute = '';
                    $value='';
                    if($Vsesi[(int)$val]==1){
                        $attribute = 'data-nim="'.$key['mhs_nim'].'" data-jdwl_id="'.$id.'" data-sesi="'.(int)$val.'" data-krs_id="'.$key['krs_id'].'"';
                        if ($key[$k]==0) {
                            $value = '<a href="javascript:;" class="do_attendance glyphicon glyphicon-remove-circle" name ="ctrl_attendance" id="'.$k.'" ' .$attribute. ' style="margin:-8px;padding:10px;color:red;"></a>';
                            //$value = '<i class="do_attendance btn glyphicon glyphicon-remove-circle" name ="ctrl_attendance"  style="color: red;"></i>';
                        }else{
                            $arrTot[$k]++;
                            $value = '<a href="javascript:;" class="do_attendance glyphicon glyphicon-ok-circle" name ="ctrl_attendance" id="'.$k.'" ' .$attribute. '" style="margin:-8px;padding:10px;color:green;"></a>';
                            //$value = '<i class="do_attendance btn glyphicon glyphicon-ok-circle"  name ="ctrl_attendance" style="color: green;"></i>';
                        }
                    }else{$value = '<i class="glyphicon glyphicon-remove-circle" style="color:red;"></i>';}
                    #($key[$k])
                    #$value=$val." ".$value;

                    $table  .=  "<td style='text-align:center;background:#fff'>".(isset($key[$k])?$value:'<i class="glyphicon glyphicon-remove-circle" style="margin:-8px;padding:10px;color:#fff;"></i>')."</td>";
                }

                $table  .="</tr>";
            }
            #*/
            /*
            $table .= "
                <tr style='font-weight:bold;text-align:right;'><th colspan='3'>TOTAL</th><th>".implode("</th><th>",$arrTot)."</th></tr>
            ";
             */
            $table.="</tbody></table>";
            return $this->render('absensi', [
                'table' => $table,
            ]);

        }
    }

    /**/
	/*
	# absensi versi fingerprint
	*/
    public function actionAbsensiV2($id,$matakuliah=null,$token=null){

        $matkull = Matkul::findOne(['mtk_kode'=>$matakuliah]);
        $model   = JadwalDosen::findOne(['jdwl_id'=>$id]);
        	echo "<pre>";
			print_r($model);
        	echo "</pre>";
        $jum = $matkull->mtk_sesi;
		$AbsDos="
		select distinct 
			jdwl_id,ds_fid,ds_get_fid,left(ds_masuk,5) masuk,left(ds_keluar,5) keluar,jdwl_hari
		from m_transaksi_finger where jdwl_id='".$model->jdwl_id."'";
		$AbsDos=Yii::$app->db->createCommand($AbsDos)->queryAll();
		//echo $model->bn->ds_nidn;
        $table ='
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th colspan="14">
						'.$model->bn->ds->ds_nm.' <br />
						'.Funct::Hari()[$model->jdwl_hari].', '.$model->jdwl_masuk.' - '.$model->jdwl_keluar.' : '
						.$model->bn->mtk_kode.' - '.$model->bn->mtk->mtk_nama.'( '.$model->jdwl_kls.' )
					</th>
				</tr>
				<tr>
					<th>Sesi 1</th>
					<th>Sesi 2</th>
					<th>Sesi 3</th>
					<th>Sesi 4</th>
					<th>Sesi 5</th>
					<th>Sesi 6</th>
					<th>Sesi 7</th>
					<th>Sesi 8</th>
					<th>Sesi 9</th>
					<th>Sesi 10</th>
					<th>Sesi 11</th>
					<th>Sesi 12</th>
					<th>Sesi 13</th>
					<th>Sesi 14</th>
				</tr>
			</thead>';
			$row=14;
			$table.="<tbody><tr>";
			foreach($AbsDos as $d){
				$table.="<td>".date("M d",strtotime($d[tgl_absen])).", ".($d[masuk]?$d[masuk]:"?")." - ".($d[keluar]?$d[keluar]:"?")."</td>";
				$row--;
			}
			for($a=0;$a<$row;$a++){$table.="<td> ? ? - ?</td>";}
			$table.="
			<tr>
			</tbody>
			</table>
		
				<table class='table table-bordered table-hover'>
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
            $table .= "<td>$Sesion</td>";
        }
        
        $table .= "</tr></thead>";

        if ($model) {     
        $db = Yii::$app->db1;
        $keuangan = Funct::getDsnAttribute('Database', $db->dsn);
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


        #$data = Yii::$app->db->createCommand($query1)->queryAll();
        $no=1;
		/*
        foreach ($data as $key) {
            
            $table .= "<tr>
                        <td style='font-weight: bold; font-size: 14px;'>".$no++."</td>
                        <td style='font-weight: bold; font-size: 14px;'>$key[mhs_nim]</td>
                        <td >$key[Nama]</td>";
                        foreach ($S as $d => $val) {
                            $k = 'S'.$val;

                            $attribute = 'data-nim="'.$key['mhs_nim'].'" data-jdwl_id="'.$id.'" data-sesi="'.(int)$val.'" data-krs_id="'.$key['krs_id'].'"';
                            
                            if ($key[$k]==0) {
                                   // $value = '<a href="javascript:;" class="do_attendance btn glyphicon glyphicon-remove-circle" name ="ctrl_attendance" id="'.$k.'" ' .$attribute. ' style="color: red;"></a>';
									$value = '<i class="do_attendance btn glyphicon glyphicon-remove-circle" name ="ctrl_attendance"  style="color: red;"></i>';
                                }else{
                                    //$value = '<a href="javascript:;" class="do_attendance btn glyphicon glyphicon-ok-circle"     name ="ctrl_attendance" id="'.$k.'" ' .$attribute. '" style="color: green;"></a>';
                                    $value = '<i class="do_attendance btn glyphicon glyphicon-ok-circle"  name ="ctrl_attendance" style="color: green;"></i>';
                            }

            $table  .=  "<td style='text-align: center;'>
                            $value
                        </td>";                            
                        }

            $table  .="</tr>";
        }
		#*/
        $table .= "</table>";
     
        return $this->render('absensi', [   
            'table' => $table,     
        ]);
         
         }
    }
	/* akhir absensi versi finger */


    public function actionSaveAbsensi(){
        $usr="-";
        if(Yii::$app->user->identity->username){
            $usr=Yii::$app->user->identity->id;
        }
        if (Yii::$app->getRequest()->isAjax) {
            $Params = $_POST;
            $model = \app\models\MAbsenMhs::findOne(['krs_id'=> $Params['krs_id'],'sesi' => $Params['sesi']]);
            $stat  = $model->mhs_stat;
            $model->mhs_stat    = ($stat =='1') ? '0':'1';
            $model->ket         = ($stat =='1') ? 'Kehadiran Dibatalkan Dosen':'Kehadiran Diizinkan Dosen';;
            $model->uuid        = $usr;
            $model->utgl = new  Expression("getdate()");

            if ($model->save(false)) {
                if ($model->mhs_stat==1) {
                    echo json_encode(['message'=>'','class' => 'do_attendance btn badge',"background"=>"green",'nilai'=>'1']);
                }else{echo json_encode(['message'=>'','class' => 'do_attendance btn badge',"background"=>"red",'nilai'=>'0']);}
                \app\models\Funct::LOGS("Mengubah Data Kehadiran Mahasiswa ($model->id)",new \app\models\MAbsenMhs,$model->id,'u',false);
            }else{echo json_encode(['message'=>'Terjadi Kesalahan, tidak dapat menyimpan. Hubungi IT Support.']);}
        }

    }

    public function actionAtt(){
        echo json_encode(['status' => TRUE]);
    }
    public function actionNilai($id)
    {
        $id_dosen = Yii::$app->user->identity->id;
        $test= Yii::$app->authManager->checkAccess($id_dosen,'akses_dosen');
        $mJdwl=Jadwal::findOne($id);
        if ($test) {
            $jadwal = Jadwal::findOne($mJdwl->jdwl_id);
            if ($jadwal->bn->ds->ds_user!==Yii::$app->user->identity->username){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
//        die($test);


        try {
        
        $QHeader = "SELECT ds.ds_nm, mk.mtk_nama,mk.mtk_sks,mk.mtk_kode, isnull(bn.nb_tgs1,0) nb_tgs1, isnull(bn.nb_tgs2,0) nb_tgs2,
                 	isnull(bn.nb_tgs3,0) nb_tgs3,isnull(bn.nb_quis,0) nb_quis, isnull(bn.nb_tambahan,0) nb_tambahan
                	,isnull(bn.nb_uts,0) nb_uts,isnull(bn.nb_uas,0) nb_uas, isnull(bn.B,0) B, isnull(bn.C,0) C, isnull(bn.D,0) D, 
                	isnull(bn.D,'0') D, isnull(bn.E,0) E
                      FROM tbl_bobot_nilai bn JOIN tbl_jadwal jd
                      on jd.bn_id = bn.id 
                      JOIN tbl_matkul mk on mk.mtk_kode = bn.mtk_kode
                      JOIN tbl_dosen ds on ds.ds_id= bn.ds_nidn 
                      WHERE jdwl_id ='$id';";
        $db = Yii::$app->db;
        $Header = $db->createCommand($QHeader)->queryOne(); 	
        $db = Yii::$app->db1;
        $keuangan = Funct::getDsnAttribute('Database', $db->dsn);
        $query ="SELECT krs_id,mh.mhs_nim,p.Nama,
                    isnull(krs.krs_tgs1, '0') krs_tgs1,isnull(krs.krs_tgs2, '0') krs_tgs2,
                    isnull(krs.krs_tgs3, '0') krs_tgs3,isnull(krs.krs_quis, '0') krs_quis,
                    isnull(krs.krs_tambahan, '0') krs_tambahan,isnull(krs.krs_uts, '0') krs_uts,
                    isnull(krs.krs_uas, '0') krs_uas,
                    isnull(krs.krs_grade, '-') krs_grade,
                    krs_tot,
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
                    ) total
                FROM tbl_krs krs
                LEFT JOIN tbl_mahasiswa mh ON mh.mhs_nim = krs.mhs_nim
                LEFT JOIN $keuangan.dbo.student st ON st.nim COLLATE Latin1_General_CI_AS= mh.mhs_nim
                LEFT JOIN $keuangan.dbo.people p ON p.No_Registrasi = st.no_registrasi
                WHERE jdwl_id = '$id' AND krs.krs_stat =1 ";

        $count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($query) T;")->queryScalar();
        $dataProvider = new SqlDataProvider([
            'sql' => $query . " ORDER BY mhs_nim",
            'totalCount' => (int)$count,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        $searchModel = new KrsDosen();
        return $this->render('nilai', [
            'header'        => $Header,
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
        ]);

        } catch (Exception $e) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
      

    }
	
	/* action nilai lama */
    /*
	public function actionNilait($id){         
        try {
        
        $QHeader = "SELECT 
					jd.jdwl_kls,
					ds.ds_nm, mk.mtk_nama,mk.mtk_sks,mk.mtk_kode, isnull(bn.nb_tgs1,0) nb_tgs1, isnull(bn.nb_tgs2,0) nb_tgs2,
                    isnull(bn.nb_tgs3,0) nb_tgs3,isnull(bn.nb_quis,0) nb_quis, isnull(bn.nb_tambahan,0) nb_tambahan
                    ,isnull(bn.nb_uts,0) nb_uts,isnull(bn.nb_uas,0) nb_uas, isnull(bn.B,0) B, isnull(bn.C,0) C, isnull(bn.D,0) D, 
                    isnull(bn.D,'0') D, isnull(bn.E,0) E
                      FROM tbl_bobot_nilai bn 
					  JOIN tbl_jadwal jd on jd.bn_id = bn.id 
                      JOIN tbl_matkul mk on mk.mtk_kode = bn.mtk_kode
                      JOIN tbl_dosen ds on ds.ds_id= bn.ds_nidn
                      WHERE jdwl_id ='$id';";
        $db = Yii::$app->db;
        $Header = $db->createCommand($QHeader)->queryOne();     
        $db = Yii::$app->db1;
        $keuangan = Funct::getDsnAttribute('dbname', $db->dsn);
        $query ="SELECT krs.jdwl_id,krs_id,mh.mhs_nim,p.Nama,
                    isnull(krs.krs_tgs1, '0') krs_tgs1,isnull(krs.krs_tgs2, '0') krs_tgs2,
                    isnull(krs.krs_tgs3, '0') krs_tgs3,isnull(krs.krs_quis, '0') krs_quis,
                    isnull(krs.krs_tambahan, '0') krs_tambahan,isnull(krs.krs_uts, '0') krs_uts,
                    isnull(krs.krs_uas, '0') krs_uas,
                    isnull(krs.krs_grade, '-') krs_grade,
                    krs_tot,
                    (
                        (
                            (isnull(cast(krs.krs_tgs1 as DECIMAL(5,2)), 0) * ".(float)$Header['nb_tgs1'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_tgs2 as DECIMAL(5,2)), 0) * ".(float)$Header['nb_tgs2'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_tgs3 as DECIMAL(5,2)), 0) * ".(float)$Header['nb_tgs3'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_quis as DECIMAL(5,2)), 0) * ".(float)$Header['nb_quis'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_tambahan as DECIMAL(5,2)), 0) * ".(float)$Header['nb_tambahan'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_uts as DECIMAL(5,2)), 0) * ".(float)$Header['nb_uts'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_uas as DECIMAL(5,2)), 0) * ".(float)$Header['nb_uas'].") / 100
                        )
                    ) total
                FROM tbl_krs krs
                LEFT JOIN tbl_mahasiswa mh ON mh.mhs_nim = krs.mhs_nim
                LEFT JOIN $keuangan.dbo.student st ON st.nim COLLATE Latin1_General_CI_AS= mh.mhs_nim
                LEFT JOIN $keuangan.dbo.people p ON p.No_Registrasi = st.no_registrasi
                WHERE jdwl_id = '$id' AND krs.krs_stat =1 ";

        $count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($query) T;")->queryScalar();
        $dataProvider = new SqlDataProvider([
            'sql' => $query." order by mhs_nim asc" ,
            'totalCount' => (int)$count,
            'pagination' => [
                'pageSize' => 20,
            ],
			
			
        ]);


         $dataProvider->setSort([
        'attributes' => [

             'krs_tgs1' => [
                'asc' => ['krs_tgs1' => SORT_ASC],
                'desc' => ['krs_tgs1' => SORT_DESC],
                'default' => SORT_ASC
            ],

             'krs_tgs2' => [
                'asc' => ['krs_tgs2' => SORT_ASC],
                'desc' => ['krs_tgs2' => SORT_DESC],
                'default' => SORT_ASC
            ],
             'krs_tot' => [
                'asc' => ['krs_tot' => SORT_ASC],
                'desc' => ['krs_tot' => SORT_DESC],
                'default' => SORT_ASC
            ],
             
        ]
        ]);

        $searchModel = new KrsDosen();
        return $this->render('nilait', [
            'header'        => $Header,
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
			'BFinal'=>Funct::BFinal($id),
        ]);

        } catch (Exception $e) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
      

    }
	*//**/

	public function actionInputNilaiV1($k){

         
		$ModVakasi	 = Vakasi::find()->where(['jdwl_id'=>$id])->orderBy(['id'=>SORT_DESC])->all();

        try {
        
        $QHeader = "SELECT ds.ds_nm, mk.mtk_nama,mk.mtk_sks,mk.mtk_kode, isnull(bt.nb_tgs1,0) nb_tgs1, isnull(bt.nb_tgs2,0) nb_tgs2,
                    isnull(bt.nb_tgs3,0) nb_tgs3,isnull(bt.nb_quis,0) nb_quis, isnull(bt.nb_tambahan,0) nb_tambahan
                    ,isnull(bt.nb_uts,0) nb_uts,isnull(bt.nb_uas,0) nb_uas, isnull(bt.B,0) B, isnull(bt.C,0) C, isnull(bt.D,0) D, 
                    isnull(bt.D,'0') D, isnull(bt.E,0) E
					,jd.bn_id,
					jd.jdwl_id, jd.Lock
					FROM tbl_bobot_nilai bn JOIN tbl_jadwal jd on (jd.bn_id = bn.id )
					INNER JOIN tbl_matkul mk on mk.mtk_kode = bn.mtk_kode
					INNER JOIN tbl_dosen ds on ds.ds_id= bn.ds_nidn
					INNER join bobotnilai bt on(bt.GKode=jd.GKode)
					WHERE jd.GKode='$k' and isnull(bn.RStat,0)=0
					order by isnull(bn.nb_tgs1,0)";
        $db = Yii::$app->db;
		#echo"<pre>";
		#print_r($QHeader);
		#echo"</pre>";		
        $Header = $db->createCommand($QHeader)->queryOne();     
        $db = Yii::$app->db1;
        $keuangan = Funct::getDsnAttribute('Database', $db->dsn);
        $query ="SELECT jd.jdwl_id,krs_id,mh.mhs_nim,p.Nama,
                    isnull(krs.krs_tgs1, '0') krs_tgs1,isnull(krs.krs_tgs2, '0') krs_tgs2,
                    isnull(krs.krs_tgs3, '0') krs_tgs3,isnull(krs.krs_quis, '0') krs_quis,
                    isnull(krs.krs_tambahan, '0') krs_tambahan,isnull(krs.krs_uts, '0') krs_uts,
                    isnull(krs.krs_uas, '0') krs_uas,
                    isnull(krs.krs_grade, '-') krs_grade,
                    krs_tot,
                    (
                        (
                            (isnull(cast(krs.krs_tgs1 as DECIMAL(5,2)), 0) * ".(float)$Header['nb_tgs1'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_tgs2 as DECIMAL(5,2)), 0) * ".(float)$Header['nb_tgs2'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_tgs3 as DECIMAL(5,2)), 0) * ".(float)$Header['nb_tgs3'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_quis as DECIMAL(5,2)), 0) * ".(float)$Header['nb_quis'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_tambahan as DECIMAL(5,2)), 0) * ".(float)$Header['nb_tambahan'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_uts as DECIMAL(5,2)), 0) * ".(float)$Header['nb_uts'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_uas as DECIMAL(5,2)), 0) * ".(float)$Header['nb_uas'].") / 100
                        )
                    ) total,
					concat(jr.jr_jenjang,' ',jr.jr_nama) jurusan
					,mk.mtk_kode,mk.mtk_nama,jd.jdwl_kls
                FROM tbl_krs krs
				INNER JOIN tbl_jadwal jd on(jd.jdwl_id=krs.jdwl_id and isnull(jd.RStat,0)=0)
				INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
				INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and isnull(mk.RStat,0)=0)
				INNER JOIN tbl_kalender kl on(kl.kln_id=bn.kln_id and isnull(kl.RStat,0)=0)
				INNER JOIN tbl_jurusan jr on(jr.jr_id=kl.jr_id)
                INNER JOIN tbl_mahasiswa mh ON mh.mhs_nim = krs.mhs_nim
                INNER JOIN $keuangan.dbo.student st ON st.nim COLLATE Latin1_General_CI_AS= mh.mhs_nim
                INNER JOIN $keuangan.dbo.people p ON p.No_Registrasi = st.no_registrasi
                WHERE jd.GKode= '$k' AND krs.krs_stat =1 ";

		/*echo"<pre>";
		print_r($query);
		echo"</pre>";
		die();
		#*/
        $count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($query) T;")->queryScalar();
        $dataProvider = new SqlDataProvider([
            'sql' => $query." order by jd.jdwl_id,mhs_nim asc" ,
            'totalCount' => (int)$count,
            'pagination' => [
                'pageSize' =>false,
            ],
			
			
        ]);


         $dataProvider->setSort([
        'attributes' => [

             'krs_tgs1' => [
                'asc' => ['krs_tgs1' => SORT_ASC],
                'desc' => ['krs_tgs1' => SORT_DESC],
                'default' => SORT_ASC
            ],

             'krs_tgs2' => [
                'asc' => ['krs_tgs2' => SORT_ASC],
                'desc' => ['krs_tgs2' => SORT_DESC],
                'default' => SORT_ASC
            ],
             'krs_tot' => [
                'asc' => ['krs_tot' => SORT_ASC],
                'desc' => ['krs_tot' => SORT_DESC],
                'default' => SORT_ASC
            ],
             
        ]
        ]);

        $searchModel = new KrsDosen();
        return $this->render('/dosen/input_nilai', [
            'header'        => $Header,
            'ModVakasi'     => $ModVakasi,
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
			'jdwl_id'=>$id,
        ]);

        } catch (Exception $e) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }


	}

	public function actionNilait($id){

        $id_dosen = Yii::$app->user->identity->id;
        $test= Yii::$app->authManager->checkAccess($id_dosen,'akses_dosen');
        $mJdwl=Jadwal::findOne($id);
        if ($test) {
            $jadwal = Jadwal::findOne($mJdwl->jdwl_id);
            if ($jadwal->bn->ds->ds_user!==Yii::$app->user->identity->username){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }


        $ModVakasi	 = Vakasi::find()->where(['jdwl_id'=>$id])->orderBy(['id'=>SORT_DESC])->all();

        try {

            $QHeader = "SELECT ds.ds_nm, mk.mtk_nama,mk.mtk_sks,mk.mtk_kode, isnull(bn.nb_tgs1,0) nb_tgs1, isnull(bn.nb_tgs2,0) nb_tgs2,
                    isnull(bn.nb_tgs3,0) nb_tgs3,isnull(bn.nb_quis,0) nb_quis, isnull(bn.nb_tambahan,0) nb_tambahan
                    ,isnull(bn.nb_uts,0) nb_uts,isnull(bn.nb_uas,0) nb_uas, isnull(bn.B,0) B, isnull(bn.C,0) C, isnull(bn.D,0) D, 
                    isnull(bn.D,'0') D, isnull(bn.E,0) E
					,jd.bn_id,
					jd.jdwl_id, jd.Lock
                      FROM tbl_bobot_nilai bn JOIN tbl_jadwal jd
                      on jd.bn_id = bn.id 
                      JOIN tbl_matkul mk on mk.mtk_kode = bn.mtk_kode
                      JOIN tbl_dosen ds on ds.ds_id= bn.ds_nidn
                      WHERE jdwl_id ='$id';";
            $db = Yii::$app->db;
            $Header = $db->createCommand($QHeader)->queryOne();
            $db = Yii::$app->db1;
            $keuangan = Funct::getDsnAttribute('Database', $db->dsn);
            $query ="SELECT jdwl_id,krs_id,mh.mhs_nim,p.Nama,
                    isnull(krs.krs_tgs1, '0') krs_tgs1,isnull(krs.krs_tgs2, '0') krs_tgs2,
                    isnull(krs.krs_tgs3, '0') krs_tgs3,isnull(krs.krs_quis, '0') krs_quis,
                    isnull(krs.krs_tambahan, '0') krs_tambahan,isnull(krs.krs_uts, '0') krs_uts,
                    isnull(krs.krs_uas, '0') krs_uas,
                    isnull(krs.krs_grade, '-') krs_grade,
                    krs_tot,
                    (
                        (
                            (isnull(cast(krs.krs_tgs1 as DECIMAL(5,2)), 0) * ".(float)$Header['nb_tgs1'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_tgs2 as DECIMAL(5,2)), 0) * ".(float)$Header['nb_tgs2'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_tgs3 as DECIMAL(5,2)), 0) * ".(float)$Header['nb_tgs3'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_quis as DECIMAL(5,2)), 0) * ".(float)$Header['nb_quis'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_tambahan as DECIMAL(5,2)), 0) * ".(float)$Header['nb_tambahan'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_uts as DECIMAL(5,2)), 0) * ".(float)$Header['nb_uts'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_uas as DECIMAL(5,2)), 0) * ".(float)$Header['nb_uas'].") / 100
                        )
                    ) total
                FROM tbl_krs krs
                LEFT JOIN tbl_mahasiswa mh ON mh.mhs_nim = krs.mhs_nim
                LEFT JOIN $keuangan.dbo.student st ON st.nim COLLATE Latin1_General_CI_AS= mh.mhs_nim
                LEFT JOIN $keuangan.dbo.people p ON p.No_Registrasi = st.no_registrasi
                WHERE jdwl_id = '$id' AND krs.krs_stat =1 ";

            $count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($query) T;")->queryScalar();
            $dataProvider = new SqlDataProvider([
                'sql' => $query." order by mhs_nim asc" ,
                'totalCount' => (int)$count,
                'pagination' => [
                    'pageSize' => 20,
                ],


            ]);


            $dataProvider->setSort([
                'attributes' => [

                    'krs_tgs1' => [
                        'asc' => ['krs_tgs1' => SORT_ASC],
                        'desc' => ['krs_tgs1' => SORT_DESC],
                        'default' => SORT_ASC
                    ],

                    'krs_tgs2' => [
                        'asc' => ['krs_tgs2' => SORT_ASC],
                        'desc' => ['krs_tgs2' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'krs_tot' => [
                        'asc' => ['krs_tot' => SORT_ASC],
                        'desc' => ['krs_tot' => SORT_DESC],
                        'default' => SORT_ASC
                    ],

                ]
            ]);

            $searchModel = new KrsDosen();
            return $this->render('nilait_v2', [
                'header'        => $Header,
                'ModVakasi'     => $ModVakasi,
                'dataProvider'  => $dataProvider,
                'searchModel'   => $searchModel,
                'jdwl_id'=>$id,
            ]);

        } catch (Exception $e) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }


    }

    public function actionInputNilai(){
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

    public function actionKrs(){
        $model = Dosen::findOne(['ds_user' =>Yii::$app->user->identity->username]);
        $id = $model->ds_id;
        $model = Dosen::findOne($id);
        $Kurikulum = Kurikulum::findOne(['kr_kode' => @$_POST['Kurikulum']['kr_kode'] ]);

        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams()," ds_wali='$model->ds_id'");
        
        return $this->render('dsn_wali', [
            'model' => $model,
            'Kurikulum' =>  (empty($Kurikulum)) ? new Kurikulum() : $Kurikulum,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]); 
    }

    public function actionKrsMhs(){
        echo "Hallo Para Subcriber....";
    }

    public function actionKrsApprove(){
        $data = $_POST['mhs_krs'];

        if (!empty($data)){
            foreach ($data as $key => $value) {       
             
             if(!empty($value['data'])){
             
                    foreach ($value['data'] as $k => $r) {
                        $model = KrsDosen::findOne(['krs_id' => $r]);
                        
                        if($model){

                            $model->krs_stat = '1';
                            $model->save();

                        }
                    
                    }
             
             }

            }    

        }
        
        echo json_encode(['status' => true]);
    }
      
    /**
     * Finds the Dosen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dosen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
	public function actionNilaiPdf($id){

        $QHeader = "SELECT ds.ds_nm, mk.mtk_nama,mk.mtk_sks,mk.mtk_kode, isnull(bn.nb_tgs1,0) nb_tgs1, isnull(bn.nb_tgs2,0) nb_tgs2,
                    isnull(bn.nb_tgs3,0) nb_tgs3,isnull(bn.nb_quis,0) nb_quis, isnull(bn.nb_tambahan,0) nb_tambahan
                    ,isnull(bn.nb_uts,0) nb_uts,isnull(bn.nb_uas,0) nb_uas, isnull(bn.B,0) B, isnull(bn.C,0) C, isnull(bn.D,0) D, 
                    isnull(bn.D,'0') D, isnull(bn.E,0) E,jd.jdwl_id, jd.Lock,jd.jdwl_kls,dbo.cekHari(jd.jdwl_hari) hari,jd.jdwl_masuk,jd.jdwl_keluar,
					kr.kr_nama,jr.jr_jenjang,jr.jr_nama
					FROM tbl_bobot_nilai bn JOIN tbl_jadwal jd on (jd.bn_id = bn.id and isnull(jd.RStat,0)=0)
					JOIN tbl_matkul mk on (mk.mtk_kode = bn.mtk_kode and isnull(mk.RStat,0)=0)
					JOIN tbl_dosen ds on (ds.ds_id= bn.ds_nidn and isnull(ds.RStat,0)=0)
					inner join tbl_kalender kl on(kl.kln_id=bn.kln_id and isnull(kl.RStat,0)=0)
					inner join tbl_jurusan jr on(jr.jr_id=kl.jr_id)
					inner join tbl_kurikulum kr on(kr.kr_kode=kl.kr_kode)
					
					  
                      WHERE jdwl_id ='$id';";
        $db = Yii::$app->db;
        $Header = $db->createCommand($QHeader)->queryOne();     
        $db = Yii::$app->db1;
        $keuangan = Funct::getDsnAttribute('Database', $db->dsn);
        $query ="SELECT jdwl_id,krs_id,mh.mhs_nim,p.Nama,
                    isnull(krs.krs_tgs1, '0') krs_tgs1,isnull(krs.krs_tgs2, '0') krs_tgs2,
                    isnull(krs.krs_tgs3, '0') krs_tgs3,isnull(krs.krs_quis, '0') krs_quis,
                    isnull(krs.krs_tambahan, '0') krs_tambahan,isnull(krs.krs_uts, '0') krs_uts,
                    isnull(krs.krs_uas, '0') krs_uas,
                    isnull(krs.krs_grade, '-') krs_grade,
                    krs_tot,
                    (
                        (
                            (isnull(cast(krs.krs_tgs1 as DECIMAL(5,2)), 0) * ".(float)$Header['nb_tgs1'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_tgs2 as DECIMAL(5,2)), 0) * ".(float)$Header['nb_tgs2'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_tgs3 as DECIMAL(5,2)), 0) * ".(float)$Header['nb_tgs3'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_quis as DECIMAL(5,2)), 0) * ".(float)$Header['nb_quis'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_tambahan as DECIMAL(5,2)), 0) * ".(float)$Header['nb_tambahan'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_uts as DECIMAL(5,2)), 0) * ".(float)$Header['nb_uts'].") / 100
                        ) + (
                            (isnull(cast(krs.krs_uas as DECIMAL(5,2)), 0) * ".(float)$Header['nb_uas'].") / 100
                        )
                    ) total
                FROM tbl_krs krs
                LEFT JOIN tbl_mahasiswa mh ON mh.mhs_nim = krs.mhs_nim
                LEFT JOIN $keuangan.dbo.student st ON st.nim COLLATE Latin1_General_CI_AS= mh.mhs_nim
                LEFT JOIN $keuangan.dbo.people p ON p.No_Registrasi = st.no_registrasi
                WHERE jdwl_id = '$id' AND krs.krs_stat =1 ";

        $count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($query) T;")->queryScalar();
		$sql=Yii::$app->db->createCommand($query." order by mhs_nim asc")->queryAll();

	        $this->layout = 'blank';
			$data=[
				'sql'=>$sql,
				'header'=>$Header
			];
			#return 
			$content = $this->renderPartial('nilaiPdf',$data);

			$pdf = new Pdf([
				'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
				'content' => $content,
				'format'=>'A4',
				'marginLeft'=>5,
				'marginRight'=>5,
				'marginTop'=>20,
				'marginHeader'=>5,
				#'orientation'=>($s==4?'P':'L'),
				'destination'=>'I',
				'cssInline'=>'
					table{overlow:warp;font-size:12px;}
					body{font-size: 12px;}
				',
				'filename'=>'KPU-'.$ID.'-'.date('YmdHis').'.pdf',
				'options' => [
					'title' => 'KPU '.$ID,
					'subject' => 'KPU '.$ID,
//					'watermarkText'=>'DIREKTORAT SISTEM INFORMASI & MULTIMEDIA',
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

    public function actionMhs(){
        $usr    = Yii::$app->user->identity->username;
        $dosen  = Dosen::findOne(['ds_user'=>$usr]);
        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,['ds_wali'=>$dosen->ds_id,#"iif(hd.npm is not null,1,0)"=>0
        ]);
        #echo $dosen->ds_id;
        return $this->render('mhs_index', [
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
        ]);

    }


    protected function findModel($id)
    {
        if (($model = Dosen::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
