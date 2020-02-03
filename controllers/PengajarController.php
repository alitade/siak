<?php
namespace app\controllers;
use  yii\web\Session;
use yii\data\ActiveDataProvider;
use Yii;

use app\models\Akses;
use \mPdf;
use kartik\mpdf\Pdf;
use yii\helpers\Url;


use app\models\Jadwal;
use app\models\JadwalSearch;
use app\models\Kalender;

#transakasi vakasi
use app\models\VakasiData;
use app\models\TransaksiVakasi;
use app\models\Transaksi;
use app\models\TransaksiDetail;
#end transaksi vakasi

use app\models\BobotNilai;
use app\models\BobotNilaiSearch;
use app\models\KrsDosen;
use app\models\Vakasi;
use app\models\Funct;


use yii\filters\VerbFilter;
use yii\helpers\Json;

use yii\data\ArrayDataProvider;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use yii\db\Query;
use yii\db\Expression;
use yii\data\SqlDataProvider;

$connection = \Yii::$app->db;
/**/
use yii\app\controllers\AkademikGroupMk;
/**/

/**
 * BobotNilaiController implements the CRUD actions for BobotNilai model.
 */
class PengajarController extends Controller{
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
    public function actionVksbaa(){return true;}
    public function actionVakasiUpdate($id){
        return _vakasi::vakasiUpdate($id);
    }
    public function actionVakasiUpdate1($id){
        return _vakasi::vakasiUpdate1($id);
    }
    public function actionVakasiRevisi(){return true;}

    public function actionIndex(){
        if(!Akses::acc('/pengajar/index')){throw new ForbiddenHttpException("Forbidden access");}
        $searchModel = new BobotNilaiSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,(self::sub()?['jr_id'=>self::sub()['jurusan']]:""));
        return $this->render('/pengajar/ajr_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'subAkses'=>self::sub()
        ]);
    }

    public function actionJdwGroup($id){
        $modJdw	= Jadwal::findOne($id);
        $ModBn = $this->findModel($modJdw->bn_id);

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
        $model = $this->findModel($id);
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

    public function actionDelGp($id){
        $model=Jadwal::updateAll(['RStat'=>'1'],"isnull(jdwl_parent,jdwl_id)=:id",[':id'=>$id]);
        $model=Jadwal::findOne($id);
        return $this->redirect(['view','id' => $model->bn_id]);
    }

    public function actionDelCh($id){
        $JR=

        $model=Jadwal::findOne($id);


        echo $model->bn->kln->jr_id;
        Funct::v(self::sub());
        $q = "exec HapusJadwal $model->jdwl_id";
        $q =Yii::$app->db->createCommand($q)->execute();
        $id= $model->bn_id;
        return $this->redirect(['ajr-view','id' =>$id]);
    }

    public function actionView($id,$pid=NULL){
        $B=false;
        $model_jadwal = new Jadwal;
        $model_jadwal->bn_id = $id;
        $ModBn = BobotNilai::findOne($id);
        $ViewForm="../pengajar/schedule__form";
        $subAkses=self::sub();

        #Penambahan SubJawal
        if(isset($pid)){
            $ViewForm="../pengajar/schedule__form_child";
            if(is_numeric($pid)){
                $modJdwlp = Jadwal::findOne($pid);
                if(isset($_POST['subjdwl'])){
                    if ($model_jadwal->load(Yii::$app->request->post())) {
                        $vMasuk	= $model_jadwal->jdwl_masuk;
                        $vKeluar= $model_jadwal->jdwl_keluar;
                        $vHari 	= $model_jadwal->jdwl_hari;
                        $qBentrok="select * from dbo.JadwalDosenBn($id) where h=$vHari and (
							(CAST(DATEADD(MINUTE,1,m) as time(0)) BETWEEN CAST(DATEADD(MINUTE,0,'$vMasuk') as time(0)) AND CAST(DATEADD(MINUTE,0, '$vKeluar') as time(0))) 
							or
							(CAST(DATEADD(MINUTE,-1,k) as time(0)) BETWEEN CAST(DATEADD(MINUTE,0,'$vMasuk') as time(0)) AND CAST(DATEADD(MINUTE,0, '$vKeluar') as time(0)))
						)
						";
                        $countBentrok=Yii::$app->db->createCommand($qBentrok)->queryAll();
                        if($countBentrok){
                            Yii::$app->getSession()->setFlash('error',
                                "Jadwal ".Funct::HARI()[$vHari].", $vMasuk-$vKeluar Bentrok"
                            );
                            return $this->redirect(['/pengajar/view', 'id' => $id,'pid'=>$pid]);
                        }else{
                            $qParent = "
							select distinct jd.jdwl_id id,jd.jdwl_kls kls,jd.rg_kode from dbo.JadwalDosenBn($id) t
							INNER JOIN tbl_jadwal jd on(jd.jdwl_id=t.id AND jd.GKode=t.gkode )
							where jd.gkode='$modJdwlp->GKode'";
                            $qParent=Yii::$app->db->createCommand($qParent)->queryAll();
                            $idParent=NULL;
                            foreach($qParent as $d){
                                $modJdw=new Jadwal;
                                $modJdw->bn_id 		= $id;
                                $modJdw->semester 	='1';
                                $modJdw->jdwl_hari 	= $vHari;
                                $modJdw->jdwl_masuk	= $vMasuk;
                                $modJdw->jdwl_keluar= $vKeluar;
                                $modJdw->jdwl_kls 	= $d['kls'];
                                $modJdw->rg_kode 	= $model_jadwal->rg_kode;
                                $modJdw->GKode 		= $modJdwlp->GKode;
                                $modJdw->jdwl_parent= $d['id'];
                                $modJdw->save();
                                //print_r($modJdw->getErrors());
                                //die();
                                if(!$idParent){$idParent=$modJdw->jdwl_id;}
                            }
                            if($idParent){return $this->redirect(['/pengajar/view', 'id' => $id]);}
                        }
                    }



                }
            }else{return $this->redirect(['/pengajar/view','id'=>$id]);}
        }
        #akhir penambah SubJadwal

        $model_jadwal->semester = '1';
        $model = new BobotNilai;


        $qDetJdwl="
			SELECT 
				isnull(jdw.jdwl_parent,jdw.jdwl_id) id,
				jdw.jdwl_id,isnull(jdw.GKode,jdw.jdwl_id) GKode,
				kln.kln_id,jr.jr_id,pr.pr_kode,bn.id bn_id,ds.ds_id,
				mtk.mtk_kode,pr.pr_nama
				
				,dbo.subJdwl(jdw.jdwl_id) jadwal
				,jr.jr_jenjang,jr.jr_nama
				,mtk.mtk_nama,mtk.mtk_sks
				
				,ds.ds_nm
				,jdw.jdwl_hari,jdw.jdwl_kls,jdw.rg_kode
				,isnull(t.totMhs,0) totMhs
				,isnull(t.app,0) app
				,dbo.TotMhsPerJdwl(jdw.jdwl_id) s
				
			from tbl_bobot_nilai bn
			INNER JOIN tbl_dosen ds on(ds.ds_id=bn.ds_nidn and (ds.RStat is null or ds.RStat='0') and ds.ds_id='".$ModBn->ds_nidn."')
			INNER JOIN tbl_kalender kln on(kln.kln_id=bn.kln_id  and (kln.RStat is null or kln.RStat='0') and kln.kr_kode='".$ModBn->kln->kr_kode."')
			INNER JOIN tbl_jurusan jr on(jr.jr_id=kln.jr_id)
			INNER JOIN tbl_program pr on(pr.pr_kode=kln.pr_kode)
			INNER JOIN tbl_jadwal jdw on(jdw.bn_id=bn.id and (jdw.RStat is null or jdw.RStat='0') and isnull(jdw.jdwl_parent,jdw.jdwl_id)=jdw.jdwl_id)
			INNER JOIN tbl_matkul mtk on(mtk.mtk_kode=bn.mtk_kode and (mtk.RStat is null or mtk.RStat='0'))
			LEFT JOIN (
				SELECT krs.jdwl_id,sum(iif(isnull(krs_stat,0)=1,1,0)) app, count(krs.jdwl_id) totMhs 
				FROM tbl_krs krs 
				INNER JOIN tbl_mahasiswa mhs on(mhs.mhs_nim=krs.mhs_nim and isnull(mhs.RStat,0)=0)
				WHERE isnull(krs.RStat,0)=0 
				-- and krs.krs_stat='1'
				AND krs.kr_kode_='".$ModBn->kln->kr_kode."'
				group by krs.jdwl_id
			) t on(t.jdwl_id=jdw.jdwl_id)
			order by jdw.GKode,jdw.jdwl_hari,jdw.jdwl_masuk,jr.jr_id
		";
        /*
        echo"<pre>";
        print_r($qDetJdwl);
        echo"</pre>";
        #die();
        #*/
        $qDetJdwl=Yii::$app->db->createCommand($qDetJdwl)->queryAll();
        #Req Data
        if ($model_jadwal->load(Yii::$app->request->post())) {
            if(isset($_POST['gabung'])){
                #Pengabungan Jadwal
                $idGab=$_POST['gab'];
                $modGab=Jadwal::FindOne($idGab);
                #-- insert into tbl_jadwal(bn_id,semester,jdwl_hari,jdwl_masuk,jdwl_kelaur,jdwl_kls,rg_kode,GKode,jdwl)
                $qGab="select *,dbo.subJdwl($idGab) jadwal from dbo.JadwalDosenBn($modGab->bn_id) where id=$idGab order by p desc";
                $qGab=Yii::$app->db->createCommand($qGab)->queryAll();
                $idParent=NULL;
                foreach($qGab as $d){
                    $modJdw=new Jadwal;
                    $modJdw->bn_id 		= $id;
                    $modJdw->semester 	='1';
                    $modJdw->jdwl_hari 	= $d['h'];
                    $modJdw->jdwl_masuk	= substr($d['m'],0,5);
                    $modJdw->jdwl_keluar= substr($d['k'],0,5);
                    $modJdw->jdwl_kls 	= $model_jadwal->jdwl_kls;
                    $modJdw->rg_kode 	= $modGab->rg_kode;
                    $modJdw->GKode 		= $modGab->GKode;
                    $modJdw->jdwl_parent= $idParent;
                    $modJdw->save();
                    if(!$idParent){$idParent=$modJdw->jdwl_id;}
                }
                if($idParent){return $this->redirect(['/pengajar/view','id' => $id]);}
                #Akhir PEngabungan Jadwal
            }

            $vMasuk	= $model_jadwal->jdwl_masuk;
            $vKeluar= $model_jadwal->jdwl_keluar;
            $vHari 	= $model_jadwal->jdwl_hari;

            $vCount=0;

            if($vMasuk!=''&&$vKeluar!=''){

                $qBentrok="select *,dbo.subJdwl($id) jadwal from dbo.JadwalDosenBn($id) where h=$vHari and 
				(
					(CAST(DATEADD(MINUTE,1,'$vMasuk') as time(0))  BETWEEN CAST(DATEADD(MINUTE,0,m) as time(0)) 
						AND CAST(DATEADD(MINUTE,0, k) as time(0))) 
					or
					(CAST(DATEADD(MINUTE,-1,'$vKeluar') as time(0))  BETWEEN CAST(DATEADD(MINUTE,0, m) as time(0)) 
					AND CAST(DATEADD(MINUTE,0, k) as time(0)))
				)
				";
                $countBentrok=Yii::$app->db->createCommand($qBentrok)->queryAll();
                if($countBentrok){
                    $qBentrok="select distinct t1.*,dbo.subJdwl(t.id) jadwal 
					from dbo.JadwalDosenBn($id) t
					INNER JOIN dbo.JadwalDosenBn($id) t1 on (t1.jdw=t.id)
					where t.h=$vHari and 
					(
						(CAST(DATEADD(MINUTE,1,'$vMasuk') as time(0))  BETWEEN CAST(DATEADD(MINUTE,0,t.m) as time(0)) 
							AND CAST(DATEADD(MINUTE,0, t.k) as time(0))) 
						or
						(CAST(DATEADD(MINUTE,-1,'$vKeluar') as time(0))  BETWEEN CAST(DATEADD(MINUTE,0, t.m) as time(0)) 
						AND CAST(DATEADD(MINUTE,0, t.k) as time(0)))
					)
					";
                    //$qBentrok=$qBentrok." and t1.sks='".$ModBn->mtk->mtk_sks."' and t1.p='1'";
                    /*echo"<pre>";
                        print_r($qBentrok);
                    echo"</pre>";
                    */
                    //echo $qBentrok;
                    $qBentrok=Yii::$app->db->createCommand($qBentrok)->queryAll();
                    if($qBentrok){
                        Yii::$app->getSession()->setFlash('error',"Jadwal ".$ModBn->kln->jr->jr_jenjang." ".$ModBn->kln->jr->jr_nama
                            ." ".$ModBn->kln->pr->pr_nama." ".Funct::HARI()[$vHari].", $vMasuk-$vKeluar Bentrok ");
                    }

                    foreach($countBentrok as $d){
                        if($d['bn']==$id){
                            Yii::$app->getSession()->setFlash('error',"Jadwal ".Funct::HARI()[$vHari].", $vMasuk-$vKeluar Program ".$d['program']." Sudah Ada");
                            $qBentrok=false;
                        }
                    }

                    $B=true;
                    return $this->render('/pengajar/ajr_jdw', [
                        'dataProvider' => $dataProvider,
                        'dataProvider2' => $dataProvider2,
                        'dataProvider3' => $dataProvider3,
                        'qDetJdwl'=>$qDetJdwl,
                        'ViewForm'=>$ViewForm,
                        'model'=>$model,
                        'pid'=>$pid,
                        'subAkses'=>$subAkses,
                        'qBentrok'=>$qBentrok,
                        'B'=>$B,
                        'ModBn'=>$ModBn,
                        'model2' => $model_jadwal,
                        'searchModel' => $searchModel,
                        'id'=>$id,
                    ]);

                    //return $this->redirect(['ajr-view', 'id' => $id,'pid'=>$pid]);
                }else{
                    $model_jadwal->GKode=$ModBn->kln->kr_kode.".".$ModBn->ds_nidn.".".date("dHis");
                    if($model_jadwal->save()){
                        return $this->redirect(['/pengajar/view', 'id' => $id]);
                    }
                }


            }

        }

        return $this->render('/pengajar/ajr_jdw', [
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,
            'dataProvider3' => $dataProvider3,
            'qDetJdwl'=>$qDetJdwl,
            'qDetJdwl_'=>$qDetJdwl_,
            'ViewForm'=>$ViewForm,
            'subAkses'=>$subAkses,
            'model'=>$model,
            'ModBn'=>$ModBn,
            'model2' => $model_jadwal,
            'searchModel' => $searchModel,
            'id'=>$id,
        ]);
    }

    //cross schedule
    public function actionCross($id,$jid){
        $krs = Krs::find()->where(["jdwl_id" => $id])->all();
        $limit_krs = count($krs);
        $model_jadwal = new Jadwal;
        $model_jadwal->bn_id = $jid;
        $model_jadwal->semester = '1';
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),['bn_id'=>$jid]);
        $ModBn = $this->findModel($jid);
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

    public function actionUpdate($id){
        return \app\controllers\_PenjadwalanA::updatePengajar($id);
    }

    public function actionCreate(){
        $model = new BobotNilai;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \app\models\Funct::LOGS("Menambah Data Pengajar($model->id)",$model,$model->id,'c');
            return $this->redirect(['/pengajar/view', 'id' => $model->id]);
        }
        return $this->render('/pengajar/ajr_create', [
            'model' => $model,
            'subAkses'=>self::sub(),
        ]);

    }

    public function actionDelete($id){
        $model=$this->findModel($id);
        $model->RStat=1;
        if($model->save(false)){
            \app\models\Funct::LOGS("Menghapus Data Pengajar ($id) ",new BobotNilai,$id,'d');
        }
        return $this->redirect(Yii::$app->request->referrer?: Yii::$app->homeUrl);
    }


    protected function findModel($id){
        if (($model = BobotNilai::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    #VAKASI
    public function actionVakasi(){
        //if(!Akses::acc('/pengajar/index')){throw new ForbiddenHttpException("Forbidden access");}
        $searchModel = new BobotNilaiSearch;
        $dataProvider = $searchModel->vakasi(Yii::$app->request->queryParams,(self::sub()?['jr_id'=>self::sub()['jurusan']]:""));
        if(Akses::acc('/pengajar/vakasi-master')){$dataProvider = $searchModel->vakasi(Yii::$app->request->queryParams);}
        #var_dump($dataProvider);
        return $this->render('/pengajar/vakasi', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    public function actionVakasiMaster(){return true;}
    public function DataVakasi($kr,$id){
        $subAkses=self::sub();
        $KetJr="";
        if($subAkses){$KetJr="'".implode("','",$subAkses['jurusan'])."'";}

        $que="
			SELECT 
				vd.jdwl_id, 
				max(tgs1) tgs1 ,max(uts) uts,
				max(tgs2) tgs2,max(uas) uas
			FROM (
			SELECT DISTINCT jd1.jdwl_id,jd.GKode from tbl_bobot_nilai bn
			INNER JOIN tbl_kalender kl on(
				kl.kln_id=bn.kln_id and isnull(kl.RStat,0)=0 and kl.kr_kode='".$kr."'
				".($KetJr?" and kl.jr_id in($KetJr)":"")."
			)
			INNER JOIN tbl_jadwal jd on(jd.bn_id=bn.id and isnull(jd.RStat,0)=0)
			INNER JOIN tbl_jadwal jd1 on(isnull(jd1.GKode,jd1.jdwl_id)=isnull(jd.GKode,jd.jdwl_id) and isnull(jd1.RStat,0)=0)
			INNER JOIN tbl_bobot_nilai bn1 on(bn1.ds_nidn=bn.ds_nidn and isnull(bn1.RStat,0)=0 AND bn1.id=jd1.bn_id)
			INNER JOIN tbl_kalender kl1 on(kl1.kln_id=bn1.kln_id and isnull(kl1.RStat,0)=0 AND kl1.kr_kode=kl.kr_kode)
			WHERE isnull(bn.RStat,0)=0
			AND bn.ds_nidn=".$id."
			) t
			INNER JOIN vakasi_data vd on(vd.jdwl_id=t.jdwl_id)
			GROUP BY vd.jdwl_id		
		
		";
        $que=Yii::$app->db->createCommand($que)->queryAll();
        $kdVk=[];
        foreach($que as $d){
            if($d['tgs1']){	$kdVk[$d['jdwl_id']]['tgs1']=1;}
            if($d['tgs2']){	$kdVk[$d['jdwl_id']]['tgs2']=1;}
            if($d['uts']){	$kdVk[$d['jdwl_id']]['uts']=1;}
            if($d['uas']){	$kdVk[$d['jdwl_id']]['uas']=1;}
        }

        return $kdVk;
    }

    public function actionVakasiDetail($id,$pid=NULL){
        return $this->render('/pengajar/vakasi_detail', _vakasi::VakasiDetail($id,$pid=NULL));
    }
    public function actionVakasiDetailV1($id,$pid=NULL){return _vakasi::VakasiDetailV1($id,$pid=NULL);}

    public function actionVakasiView($id){
        $modJdw=Jadwal::find()->where(['GKode'=>$id])->one();
        #subakses
        $subAkses=self::sub();

        #getUserId
        $cuid = Yii::$app->user->identity->id;#

        #GetDbName
        $usb_ = Yii::$app->db;
        $usb_ = Funct::getDsnAttribute('dbname', $usb_->dsn);#

        $que="
			select 
				jd.jdwl_id,
				sum(iif(isnull(krs_tgs1,0)>1,1,0)) tgs1, 
				sum(iif(isnull(krs_tgs2,0)>1,1,0)) tgs2, 
				sum(iif(isnull(krs_tgs3,0)>1,1,0)) tgs3, 
				sum(iif(isnull(krs_quis,0)>1,1,0)) quiz, 
				sum(iif(isnull(krs_uts,0)>1,1,0)) uts, 
				sum(iif(isnull(krs_uas,0)>1,1,0)) uas,				
				count(krs.jdwl_id) totMhs			
			from $usb_.dbo.tbl_krs krs
			inner join $usb_.dbo.tbl_jadwal jd on(
				jd.jdwl_id=krs.jdwl_id and isnull(jd.GKode,jd.jdwl_id)='$id'
				and isnull(jd.RStat,0)=0
				"//.($_id?" and jd.jdwl_id in($_id)":" and 1=0")
            ."
			)
			where isnull(krs.RStat,0)=0
			and isnull(krs.krs_stat,0)=1
			group by jd.jdwl_id
		";
        $que=Yii::$app->db->createCommand($que)->queryAll();
        /* ------------------------
            model untuk vakasi
            VakasiData

        */

        //print_r(self::DataVakasi());
        $ModJdw = Jadwal::find()->where(['GKode'=>$id]);
        $model=$ModJdw->one();
        $modelAll=$ModJdw->all();

        $_id=[];
        foreach($modelAll as $d){
            $_id[]=$d->jdwl_id;
        }

        //print_r($_id);
        $TRANSAKSI = Transaksi::find()
            ->select('transaksi.*')
            ->innerJoin('vakasi_data vd',"(vd.kode=transaksi.kode_transaksi and isnull(vd.Rstat,0)=0)")
            ->leftJoin('transaksi_detail td',"(td.kd_trans=transaksi.kode_transaksi and isnull(td.Rstat,0)=0)")
            ->where(['isnull(transaksi.Rstat,0)'=>0,'vd.jdwl_id'=>$_id])
            ->all();


        $qDetJdwl="
			select 
				sum(iif(isnull(krs_stat,0)=1,1,0)) app, 
				sum(iif(isnull(krs_tgs1,0)>1,1,0)) tgs1, 
				sum(iif(isnull(krs_tgs2,0)>1,1,0)) tgs2, 
				sum(iif(isnull(krs_tgs3,0)>1,1,0)) tgs3, 
				sum(iif(isnull(krs_quis,0)>1,1,0)) quiz, 
				sum(iif(isnull(krs_uts,0)>1,1,0)) uts, 
				sum(iif(isnull(krs_uas,0)>1,1,0)) uas,				
				count(krs.jdwl_id) totMhs			
			from tbl_krs krs
			inner join tbl_jadwal jd on(
				jd.jdwl_id=krs.jdwl_id and isnull(jd.GKode,jd.jdwl_id)='$id'
				and isnull(jd.RStat,0)=0
				"//.($_id?" and jd.jdwl_id in($_id)":" and 1=0")
            ."
			)
			where isnull(krs.RStat,0)=0
		";

        //return "sss";
        $sql=Yii::$app->db->createCommand($qDetJdwl)->queryAll();


        return $this->render('/pengajar/vakasi_view',[
            'mVakasi'=>$mVakasi,
            'model'=>$model,
            'All'=>$modelAll,
            'TRANSAKSI'=>$TRANSAKSI,
            'sql'=>$sql,
            'subTot'=>$subTot,
            'pph'=>$pph,
            'subAkses'=>$subAkses['jurusan'],
        ]);
    }
    public function actionVakasiProc($kd){
        if(isset($_POST['s'])){
            unset($_SESSION['kode']);
            if($_POST['id']){$_SESSION['kode']=$_POST['id'];}
            return $this->redirect(['/pengajar/vakasi-add','kd'=>$kd]);
        }


        $subAkses=self::sub();
        $KetJr="";
        if($subAkses){$KetJr="'".implode("','",$subAkses['jurusan'])."'";}

        $modJdw=Jadwal::find()->where(['GKode'=>$kd])->one();
        $que="
		SELECT 
		t1.jdwl_id 
		,iif(isnull(t.tgs1,0)=0,t1.tgs1,0) tgs1
		,iif(isnull(t.uts,0)=0,t1.uts,0) uts
		,iif(isnull(t.tgs2,0)=0,t1.tgs2,0) tgs2
		,iif(isnull(t.uas,0)=0,t1.uas,0) uas
		,iif(isnull(t.tgs1,0)=0,t1.tgs1,0)+ iif(isnull(t.uts,0)=0,t1.uts,0)+iif(isnull(t.tgs2,0)=0,t1.tgs2,0)+iif(isnull(t.uas,0)=0,t1.uas,0) tot
		FROM( 
			SELECT 
				vd.jdwl_id, 
				max(tgs1) tgs1 ,max(uts) uts,
				max(tgs2) tgs2,max(uas) uas
			FROM (
			SELECT DISTINCT jd1.jdwl_id,jd.GKode from tbl_bobot_nilai bn
			INNER JOIN tbl_kalender kl on(
				kl.kln_id=bn.kln_id and isnull(kl.RStat,0)=0 and kl.kr_kode='".$modJdw->bn->kln->kr_kode."'
				-- ".($KetJr?" and kl.jr_id in($KetJr)":"")."
			)
			INNER JOIN tbl_jadwal jd on(jd.bn_id=bn.id and isnull(jd.RStat,0)=0 and jd.GKode='$kd')
			INNER JOIN tbl_jadwal jd1 on(isnull(jd1.GKode,jd1.jdwl_id)=isnull(jd.GKode,jd.jdwl_id) and isnull(jd1.RStat,0)=0)
			INNER JOIN tbl_bobot_nilai bn1 on(bn1.ds_nidn=bn.ds_nidn and isnull(bn1.RStat,0)=0 AND bn1.id=jd1.bn_id)
			INNER JOIN tbl_kalender kl1 on(kl1.kln_id=bn1.kln_id and isnull(kl1.RStat,0)=0 AND kl1.kr_kode=kl.kr_kode)
			WHERE isnull(bn.RStat,0)=0
			AND bn.ds_nidn=".$modJdw->bn->ds_nidn."
			) t
			INNER JOIN vakasi_data vd on(vd.jdwl_id=t.jdwl_id and isnull(vd.Rstat,0)=0)
			GROUP BY vd.jdwl_id		
		) t
		right join (
			select 
				jd.jdwl_id,
				sum(iif(isnull(krs_tgs1,0)>1,1,0)) tgs1, 
				sum(iif(isnull(krs_tgs2,0)>1,1,0)) tgs2, 
				sum(iif(isnull(krs_tgs3,0)>1,1,0)) tgs3, 
				sum(iif(isnull(krs_quis,0)>1,1,0)) quiz, 
				sum(iif(isnull(krs_uts,0)>1,1,0)) uts, 
				sum(iif(isnull(krs_uas,0)>1,1,0)) uas,				
				count(krs.jdwl_id) totMhs			
			from $usb_.dbo.tbl_krs krs
			inner join $usb_.dbo.tbl_jadwal jd on(
				jd.jdwl_id=krs.jdwl_id and isnull(jd.GKode,jd.jdwl_id)='$kd'
				and isnull(jd.RStat,0)=0
			)
			where isnull(krs.RStat,0)=0
			and isnull(krs.krs_stat,0)=1
			group by jd.jdwl_id
		) t1 on(t1.jdwl_id=t.jdwl_id)
		";

        $Nofaktur=date('Ymd')."-".time();

        /*
        echo"<pre>";
        print_r($que);
        echo"</pre>";
        die();
        #*/
        //die($Nofaktur);
        $que=Yii::$app->db->createCommand($que)->queryAll();
        $cuid = Yii::$app->user->identity->id;#

        $ins=0;
        foreach($que as $rec){
            if($rec['tot']>0){
                $ins++;
                #$rec['jdwl_id']."<br />";
                $VakasiData =new VakasiData;
                $VakasiData->jdwl_id=$rec['jdwl_id'];
                $VakasiData->tgs1=$rec['tgs1'];
                $VakasiData->tgs2=$rec['tgs2'];
                $VakasiData->uts=$rec['uts'];
                $VakasiData->uas=$rec['uas'];
                $VakasiData->cuid=$cuid;
                $VakasiData->ctgl = new  Expression("getdate()");
                $VakasiData->kode=$Nofaktur;
                $VakasiData->save();

            }
        }
        #masukan data faktur
        if($ins >0){
            $modTransaksi = new Transaksi;
            $modTransaksi->kode_transaksi = $Nofaktur;
            $modTransaksi->ds_id   	= $modJdw->bn->ds_nidn;
            $modTransaksi->pph		= 0;
            #$modTransaksi->mengetahui1		= ($_SESSION['TANDA']['keuangan']?$_SESSION['TANDA']['keuangan']:'-');
            #$modTransaksi->mengetahui2		= ($_SESSION['TANDA']['kajur']?$_SESSION['TANDA']['kajur']:'-');
            $modTransaksi->cuid				=$cuid;
            $modTransaksi->tgl				=new  Expression("getdate()");
            $modTransaksi->status			='0';
            $modTransaksi->kr_kode_ 		=$modJdw->bn->kln->kr_kode;
            $modTransaksi->anv				='0';
            $modTransaksi->jdwl_hari_=$modJdw->jdwl_hari;
            $modTransaksi->jdwl_masuk_=$modJdw->jdwl_masuk;
            $modTransaksi->jdwl_keluar_=$modJdw->jdwl_keluar;
            if(isset($_POST['av'])){$modTransaksi->anv='1';}
            $modTransaksi->save();
        }else{return $this->redirect(['/pengajar/vakasi-view','id'=>$kd]);}

        if (Funct::acc('/pengajar/vksbaa-faktur')){return $this->redirect(['/pengajar/vksbaa-faktur','id'=>$Nofaktur]);}
        return $this->redirect(['/pengajar/vakasi-faktur','id'=>$Nofaktur]);

    }


    public function actionVakasiFaktur($id){
        self::actionVakasiDel();
        $subAkses=self::sub();
        $modVk 		= VakasiData::find()->where(['kode'=>$id])->All();
        $modTrans	= Transaksi::find($id)->where(['kode_transaksi'=>$id,'isnull(RStat,0)'=>'0'])->one();
        if(!$modTrans->kode_transaksi){return $this->redirect(['/pengajar/vakasi']);}
        if($modTrans->status=='1'){return $this->redirect(['/pengajar/vakasi-faktur-view','id'=>$id]);}
        $modJdw	= Jadwal::find()->innerJoin('vakasi_data vd',"(vd.jdwl_id=tbl_jadwal.jdwl_id and vd.kode='$id')")->all();
        $TGS1=0;$TGS2=0;$UTS=0;$UAS=0;
        $p=[];
        foreach($modVk as $d){
            $TGS1+=$d->tgs1;
            $TGS2+=$d->tgs2;
            $UTS+=$d->uts;
            $UAS+=$d->uas;
            $idJdw=$d->jdwl_id;
        }


        $model	= Jadwal::findOne($idJdw);

        $mVakasi = new TransaksiVakasi;
        //if(!$modTrans->kode_transaksi){return $this->redirect(['/pengajar/vakasi-view','id'=>]);}


        $que="
		SELECT SUM(mhs) mhs, MAX(UTS) UTS, MAX(NUTS) NUTS, MAX(UAS) UAS, MAX(NUAS) NUAS,MAX(UTS1) UTS1,MAX(UAS1) UAS1
			FROM(
			SELECT 
				vd.jdwl_id, 
				t.mhs,
				sum(iif(trd.kd_prod='UTS',trd.qty,0))UTS,
				sum(iif(trd.kd_prod='UTS1',trd.qty,0)) UTS1,  
				sum(iif(trd.kd_prod='NUTS',trd.qty,0)) NUTS, 
				sum(iif(trd.kd_prod='UAS',trd.qty,0)) UAS,
				sum(iif(trd.kd_prod='UAS1',trd.qty,0)) UAS1,  
				sum(iif(trd.kd_prod='NUAS',trd.qty,0)) NUAS
			FROM (
				SELECT count(distinct krs.krs_id) mhs, jd1.jdwl_id,jd.GKode from tbl_bobot_nilai bn
				INNER JOIN tbl_kalender kl on(
					kl.kln_id=bn.kln_id and isnull(kl.RStat,0)=0 and kl.kr_kode='".$model->bn->kln->kr_kode."'
					".($KetJr?" and kl.jr_id in($KetJr)":"")."
				)
				INNER JOIN tbl_jadwal jd on(jd.bn_id=bn.id and isnull(jd.RStat,0)=0 and jd.GKode='$model->GKode')
				INNER JOIN tbl_jadwal jd1 on(isnull(jd1.GKode,jd1.jdwl_id)=isnull(jd.GKode,jd.jdwl_id) and isnull(jd1.RStat,0)=0)
				INNER JOIN tbl_bobot_nilai bn1 on(bn1.ds_nidn=bn.ds_nidn and isnull(bn1.RStat,0)=0 AND bn1.id=jd1.bn_id)
				INNER JOIN tbl_kalender kl1 on(kl1.kln_id=bn1.kln_id and isnull(kl1.RStat,0)=0 AND kl1.kr_kode=kl.kr_kode)
				INNER JOIN tbl_krs krs on(krs.jdwl_id=jd1.jdwl_id and isnull(krs.RStat,0)=0)
				WHERE isnull(bn.RStat,0)=0 AND bn.ds_nidn=".($model->bn->ds_nidn?$model->bn->ds_nidn:'NULL')."
				GROUP BY jd1.jdwl_id,jd.GKode
			) t
			LEFT JOIN vakasi_data vd on(vd.jdwl_id=t.jdwl_id and isnull(vd.Rstat,0)=0)
			INNER JOIN transaksi tr on(tr.kode_transaksi=vd.kode and isnull(tr.Rstat,0)=0)
			LEFT JOIN transaksi_detail trd on(trd.kd_trans=tr.kode_transaksi and isnull(trd.Rstat,0)=0
				AND trd.kd_prod in('UTS','UAS','NUTS','NUAS','UTS1','UAS1')
			)
			GROUP BY vd.jdwl_id,t.mhs		
		) t
		";



        /*
        echo "<pre>";
        print_r($que);
        echo "</pre>";
        die();
        #*/

        $que=Yii::$app->db->createCommand($que)->queryOne();


        //$subTot = 0;
        #data awal produk
        if($TGS1>0){
            $q=$TGS1;$h=Funct::produk('TGS1')->hrg->harga;$t=$q*$h;
            if(!isset($p['UTS']['TGS1'])){
                $p['UTS']['TGS1']=['q'=>$q,'h'=>$h,'t'=>$t];
            }else{$p['UTS']['TGS1']=['q'=>$q,'h'=>$h,'t'=>$t];}
            $subTot+=$t;$q=0;$h=0;$t=0;
        }
        if($UTS>0){
            $q=$UTS;$h=Funct::produk('UTS')->hrg->harga;$t=$q*$h;
            if(!isset($p['UTS']['UTS'])){
                $p['UTS']['UTS']=['q'=>$q,'h'=>$h,'t'=>$t];
            }else{$p['UTS']['UTS']=['q'=>$q,'h'=>$h,'t'=>$t];}
            $subTot+=$t;$q=0;$h=0;$t=0;
        }

        if($TGS2>0){
            $q=$TGS2;$h=Funct::produk('TGS2')->hrg->harga;$t=$q*$h;
            if(!isset($p['UAS']['TGS2'])){
                $p['UAS']['TGS2']=['q'=>$q,'h'=>$h,'t'=>$t];
            }else{$p['UAS']['TGS2']=['q'=>$q,'h'=>$h,'t'=>$t];}
            $subTot+=$t;
            $q=0;$h=0;$t=0;
        }

        $totUas=0;
        if($UAS>0){
            $q=$UAS;$h=Funct::produk('UAS')->hrg->harga;$t=$q*$h;
            if(!isset($p['UAS']['UAS'])){
                $p['UAS']['UAS']=['q'=>$q,'h'=>$h,'t'=>$t];
            }else{$p['UAS']['UAS']=['q'=>$q,'h'=>$h,'t'=>$t];}
            $totUas=$t;
            $subTot+=$t;//$q=0;$h=0;$t=0;

        }

        if ($mVakasi->load(Yii::$app->request->post())) {
            #head
            if($mVakasi->TANDA){
                if(isset($_SESSION['TANDA'])){
                    $_SESSION['TANDA']=$mVakasi->TANDA;
                }else{$_SESSION['TANDA']=$mVakasi->TANDA;}
            }
            if($mVakasi->PPH){
                if(isset($_SESSION['PPH'])){
                    $_SESSION['PPH']=$mVakasi->PPH;
                }else{$_SESSION['PPH']=$mVakasi->PPH;}
            }
            #end head

            #data awal
            $q=0;$h=0;$t=0;


            #UTS
            if($mVakasi->PR['UTS']){
                $q=1;$h=Funct::produk('AWS1T')->hrg->harga;
                if($mVakasi->PR['UTS']==2){
                    $h=Funct::produk('AWS2T')->hrg->harga;
                    $t=$q*$h;
                    if(!isset($_SESSION['UTS']['AWS2T'])){
                        $_SESSION['UTS']['AWS2T']=['q'=>$q,'h'=>$h,'t'=>$t];
                    }else{$_SESSION['UTS']['AWS2T']=['q'=>$q,'h'=>$h,'t'=>$t];}
                    unset($_SESSION['UTS']['AWS1T']);

                }else{
                    $t=$q*$h;
                    if(!isset($_SESSION['UTS']['AWS1T'])){
                        $_SESSION['UTS']['AWS1T']=['q'=>$q,'h'=>$h,'t'=>$t];
                    }else{$_SESSION['UTS']['AWS1T']=['q'=>$q,'h'=>$h,'t'=>$t];}
                    unset($_SESSION['UTS']['AWS2T']);
                }

                $subTot+=$t;
            }$q=0;$h=0;$t=0;

            if($mVakasi->NUTS){
                $q=$mVakasi->NUTS;$h=Funct::produk('NUTS')->hrg->harga;$t=$q*$h;
                if(!isset($_SESSION['UTS']['NUTS'])){
                    $_SESSION['UTS']['NUTS']=['q'=>$q,'h'=>$h,'t'=>$t];
                }else{
                    $_SESSION['UTS']['NUTS']=['q'=>$q,'h'=>$h,'t'=>$t];
                }
                $subTot+=$t;
            }$q=0;$h=0;$t=0;

            if($mVakasi->TB['UTS']){
                $q=$mVakasi->QTY['UTS'];$h=Funct::produk($mVakasi->TB['UTS'])->hrg->harga;$t=$q*$h;
                if(!isset($_SESSION['TUTS'][$mVakasi->TB['UTS']])){
                    $_SESSION['TUTS'][$mVakasi->TB['UTS']]=$mVakasi->QTY['UTS'];
                }else{
                    $_SESSION['TUTS'][$mVakasi->TB['UTS']]=$mVakasi->QTY['UTS'];
                }
                $subTot+=$t;
            }$q=0;$h=0;$t=0;

            /* UTS SUSULAN */

            if($mVakasi->NILAI[SUTS]){
                $q=$mVakasi->NILAI[SUTS];$h=Funct::produk('UTS1')->hrg->harga;$t=$q*$h;
                if(!isset($_SESSION['UTS1']['UTS1'])){
                    $_SESSION['UTS1']['UTS1']=['q'=>$q,'h'=>$h,'t'=>$t];
                }else{$_SESSION['UTS1']['UTS1']=['q'=>$q,'h'=>$h,'t'=>$t];	}
                $subTot+=$t;
            }$q=0;$h=0;$t=0;

            if($mVakasi->SOAL[SUTS]){
                $q=$mVakasi->SOAL[SUTS];$h=Funct::produk('NUTS1')->hrg->harga;$t=$q*$h;
                if(!isset($_SESSION['UTS1']['NUTS1'])){
                    $_SESSION['UTS1']['NUTS1']=['q'=>$q,'h'=>$h,'t'=>$t];
                }else{
                    $_SESSION['UTS1']['NUTS1']=['q'=>$q,'h'=>$h,'t'=>$t];
                }
                $subTot+=$t;
            }$q=0;$h=0;$t=0;
            /*END UTS SUSULAN*/

            if($mVakasi->NUTS){
                $q=$mVakasi->NUTS;$h=Funct::produk('NUTS')->hrg->harga;$t=$q*$h;
                if(!isset($_SESSION['UTS']['NUTS'])){
                    $_SESSION['UTS']['NUTS']=['q'=>$q,'h'=>$h,'t'=>$t];
                }else{$_SESSION['UTS']['NUTS']=['q'=>$q,'h'=>$h,'t'=>$t];}
                $subTot+=$t;
            }$q=0;$h=0;$t=0;

            #END UTS


            #UAS
            if($mVakasi->QTY[BUAS] and isset($p['UAS']['UAS']) and $UAS>0){
                $q=$mVakasi->QTY[BUAS];
                $h= $totUas*$mVakasi->QTY[BUAS]/100 ;
                $t=$h;
                if(!isset($_SESSION['UAS']['B'])){
                    $_SESSION['UAS']['B']=$t;
                }else{$_SESSION['UAS']['B']=$t;}
                $subTot+=$t;
            }$q=0;$h=0;$t=0;


            if($mVakasi->PR['UAS']){
                $q=1;$h=Funct::produk('AWS1')->hrg->harga;
                if($mVakasi->PR['UAS']==2){
                    $h=Funct::produk('AWS2')->hrg->harga;
                    $t=$q*$h;
                    if(!isset($_SESSION['UAS']['AWS2'])){
                        $_SESSION['UAS']['AWS2']=['q'=>$q,'h'=>$h,'t'=>$t];
                    }else{$_SESSION['UAS']['AWS2']=['q'=>$q,'h'=>$h,'t'=>$t];}
                }else{
                    $t=$q*$h;
                    if(!isset($_SESSION['UAS']['AWS1'])){
                        $_SESSION['UAS']['AWS1']=['q'=>$q,'h'=>$h,'t'=>$t];
                    }else{$_SESSION['UAS']['AWS1']=['q'=>$q,'h'=>$h,'t'=>$t];}

                }
                $subTot+=$t;
            }$q=0;$h=0;$t=0;

            if($mVakasi->NUAS){
                $q=$mVakasi->NUAS;$h=Funct::produk('NUAS')->hrg->harga;$t=$q*$h;
                if(!isset($_SESSION['UAS']['NUAS'])){
                    $_SESSION['UAS']['NUAS']=['q'=>$q,'h'=>$h,'t'=>$t];
                }else{
                    $_SESSION['UAS']['NUAS']=['q'=>$q,'h'=>$h,'t'=>$t];
                }
                $subTot+=$t;
            }$q=0;$h=0;$t=0;

            if($mVakasi->TB['UAS']){
                $q=$mVakasi->QTY['UAS'];$h=Funct::produk($mVakasi->TB['UAS'])->hrg->harga;$t=$q*$h;
                if(!isset($_SESSION['TUAS'][$mVakasi->TB['UAS']])){
                    $_SESSION['TUAS'][$mVakasi->TB['UAS']]=$mVakasi->QTY['UAS'];
                }else{
                    $_SESSION['TUAS'][$mVakasi->TB['UAS']]=$mVakasi->QTY['UAS'];
                }
                $subTot+=$t;
            }$q=0;$h=0;$t=0;
            /* UTS SUSULAN */
            if($mVakasi->NILAI[SUAS]){
                $q=$mVakasi->NILAI[SUAS];$h=Funct::produk('UAS1')->hrg->harga;$t=$q*$h;
                if(!isset($_SESSION['UAS1']['UAS1'])){
                    $_SESSION['UAS1']['UAS1']=['q'=>$q,'h'=>$h,'t'=>$t];
                }else{$_SESSION['UAS1']['UAS1']=['q'=>$q,'h'=>$h,'t'=>$t];	}
                $subTot+=$t;
            }$q=0;$h=0;$t=0;

            if($mVakasi->SOAL[SUAS]){
                $q=$mVakasi->SOAL[SUAS];$h=Funct::produk('NUAS1')->hrg->harga;$t=$q*$h;
                if(!isset($_SESSION['UAS1']['NUAS1'])){
                    $_SESSION['UAS1']['NUAS1']=['q'=>$q,'h'=>$h,'t'=>$t];
                }else{
                    $_SESSION['UAS1']['NUAS1']=['q'=>$q,'h'=>$h,'t'=>$t];
                }
                $subTot+=$t;
            }$q=0;$h=0;$t=0;
            /*END UTS SUSULAN*/


            #end UAS
            $pph =($mVakasi->PPH?$mVakasi->PPH * $subTot /100:0);
        }

        return $this->render('/pengajar/vakasi_faktur',[
            'model'=>$model,
            'modTrans'=>$modTrans,
            'nofaktur'=>$id,
            'que'=>$que,
            'modJdw'=>$modJdw,
            'mVakasi'=>$mVakasi,
            'subTot'=>$subTot,
            'pph'=>$pph,
            'subAkses'=>$subAkses['jurusan'],
            'p'=>$p
        ]);
    }
    public function actionVakasiFakturView($id){
        return $this->render('/pengajar/vakasi_faktur_view', _vakasi::VakasiFakturView($id));
    }
    public function actionVakasiDelDraft($id){
        $model=Transaksi::findOne($id);
        $cuid = Yii::$app->user->identity->id;

        $model->duid=$cuid;
        $model->dtgl=new  Expression("getdate()");
        $model->Rstat='1';

        if($model->save()){
            VakasiData::updateAll([
                'duid'=>$cuid,
                'dtgl'=>new  Expression("getdate()"),
                'Rstat'=>'1'
            ],['kode'=>$model->kode_transaksi]);
            return $this->redirect(["/pengajar/vakasi"]);
        }
    }
    public function actionVakasiSave($id){
        $modVk 		= VakasiData::find()->where(['kode'=>$id])->All();
        $modTrans	= Transaksi::findOne($id);
        $idJdw="";
        $TGS1=0;$TGS2=0;$UTS=0;$UAS=0;
        $cuid = Yii::$app->user->identity->id;
        $bnid="";
        $ins="insert into transaksi_detail (kd_trans,kd_prod,qty,harga,cuid) values";
        foreach($modVk as $d){$TGS1+=$d->tgs1;$TGS2+=$d->tgs2;$UTS+=$d->uts;$UAS+=$d->uas;$idJdw=$d->jdwl_id;}
        $modJdw	= Jadwal::findOne($idJdw);
        if(isset($_SESSION['TANDA'])  && (isset($_SESSION['PPH'])||$modTrans->anv==1) ){
            if($TGS1>0){$ins.="('$id','TGS1','$TGS1','".Funct::produk('TGS1')->hrg->harga."','$cuid'),";}
            if($UTS>0){$ins.="('$id','UTS','$UTS','".Funct::produk('UTS')->hrg->harga."','$cuid'),";}
            if(isset($_SESSION['UTS'])){foreach($_SESSION['UTS'] as $k=>$v){$qty=$v['q'];$hrg=$v['h'];$ins.="('$id','$k','$qty','$hrg','$cuid'),";}}
            if(isset($_SESSION['UTS1'])){
                foreach($_SESSION['UTS1'] as $k=>$v){
                    $qty=$v['q'];$hrg=$v['h'];
                    $ins.="('$id','$k','$qty','$hrg','$cuid'),";
                }
            }


            if($TGS2>0){$ins.="('$id','TGS2','$TGS2','".Funct::produk('TGS2')->hrg->harga."','$cuid'),";}
            if($UAS>0){$ins.="('$id','UAS','$UAS','".Funct::produk('UAS')->hrg->harga."','$cuid'),";}
            if(isset($_SESSION['UAS'])){
                foreach($_SESSION['UAS'] as $k=>$v){
                    $qty=$v['q'];$hrg=$v['h'];
                    if($k=='B'){$k='BUAS';$qty= 1;$hrg= $v;}
                    $ins.="('$id','$k','$qty','$hrg','$cuid'),";
                }
            }
            if(isset($_SESSION['UAS1'])){
                foreach($_SESSION['UAS1'] as $k=>$v){
                    $qty=$v['q'];$hrg=$v['h'];
                    $ins.="('$id','$k','$qty','$hrg','$cuid'),";
                }
            }
            #data susulan
            if(isset($_SESSION['TUTS'])){
                foreach($_SESSION['TUTS'] as $k=>$v){$qty=$v;$hrg=Funct::produk($k)->hrg->harga;$ins.="('$id','$k','$qty','$hrg','$cuid'),";}
            }
            if(isset($_SESSION['TUAS'])){
                foreach($_SESSION['TUAS'] as $k=>$v){$qty=$v;$hrg=Funct::produk($k)->hrg->harga;$ins.="('$id','$k','$qty','$hrg','$cuid'),";}
            }

            #die($ins);
            $sql=Yii::$app->db->createCommand(substr($ins,0,-1))->execute();

            if($sql){
                #header
                $modTrans->pph				= ($_SESSION['PPH']?$_SESSION['PPH']:0);
                $modTrans->mengetahui1		= ($_SESSION['TANDA']['keuangan']?$_SESSION['TANDA']['keuangan']:'-');
                $modTrans->mengetahui2		= ($_SESSION['TANDA']['kajur']?$_SESSION['TANDA']['kajur']:'-');
                $modTrans->kat				= ($_SESSION['TANDA']['BAAK']?'1':'0');

                $modTrans->uuid				= $cuid;
                //$modTrans->bn_id			= $modJdw->bn->ds_nidn;
                $modTrans->status			=1;
                $modTrans->lock				='1';
                $modTrans->utgl				=new  Expression("getdate()");
                $modTrans->save();
                #/*
                unset($_SESSION['UTS']);
                unset($_SESSION['UTS1']);
                unset($_SESSION['TUTS']);
                unset($_SESSION['UAS']);
                unset($_SESSION['TUAS']);
                unset($_SESSION['UAS1']);
                unset($_SESSION['TANDA']);
                unset($_SESSION['PPH']);
                return $this->redirect(['/pengajar/vakasi-faktur-view','id'=>$modTrans->kode_transaksi]);
                #*/
            }
        }
    }
    public function actionVakasiSave1($id){return _vakasi::VakasiSave1($id);}
    public function actionVakasiDel($id=""){
        unset($_SESSION['UTS']);
        unset($_SESSION['TUTS']);
        unset($_SESSION['UAS']);
        unset($_SESSION['TUAS']);
        unset($_SESSION['TANDA']);
        unset($_SESSION['PPH']);
        unset($_SESSION['kode']);
        if($id){return $this->redirect(['/pengajar/vakasi-detail','id'=>$id]);}


    }

    public function actionVakasiDelete($id){return _vakasi::VakasiDelete($id);}
    public function actionVakasiCetak($kd){return _vakasi::VakasiCetak($kd);}
    public function actionVakasiAdd($kd){
        //self::VakasiDel();
        $modJdw=Jadwal::find()->where(['GKode'=>$kd])->one();
        $que="
		SELECT SUM(mhs) mhs, MAX(UTS) UTS, MAX(NUTS) NUTS, MAX(UAS) UAS, MAX(NUAS) NUAS,MAX(UTS1) UTS1,MAX(UAS1) UAS1
			FROM(
			SELECT 
				vd.jdwl_id, 
				t.mhs,
				sum(iif(trd.kd_prod='UTS',trd.qty,0))UTS,
				sum(iif(trd.kd_prod='UTS1',trd.qty,0)) UTS1,  
				sum(iif(trd.kd_prod='NUTS',trd.qty,0)) NUTS, 
				sum(iif(trd.kd_prod='UAS',trd.qty,0)) UAS,
				sum(iif(trd.kd_prod='UAS1',trd.qty,0)) UAS1,  
				sum(iif(trd.kd_prod='NUAS',trd.qty,0)) NUAS
			FROM (
				SELECT count(distinct krs.krs_id) mhs, jd1.jdwl_id,jd.GKode from tbl_bobot_nilai bn
				INNER JOIN tbl_kalender kl on(
					kl.kln_id=bn.kln_id and isnull(kl.RStat,0)=0 and kl.kr_kode='".$modJdw->bn->kln->kr_kode."'
					".($KetJr?" and kl.jr_id in($KetJr)":"")."
				)
				INNER JOIN tbl_jadwal jd on(jd.bn_id=bn.id and isnull(jd.RStat,0)=0 and jd.GKode='$kd')
				INNER JOIN tbl_jadwal jd1 on(isnull(jd1.GKode,jd1.jdwl_id)=isnull(jd.GKode,jd.jdwl_id) and isnull(jd1.RStat,0)=0)
				INNER JOIN tbl_bobot_nilai bn1 on(bn1.ds_nidn=bn.ds_nidn and isnull(bn1.RStat,0)=0 AND bn1.id=jd1.bn_id)
				INNER JOIN tbl_kalender kl1 on(kl1.kln_id=bn1.kln_id and isnull(kl1.RStat,0)=0 AND kl1.kr_kode=kl.kr_kode)
				INNER JOIN tbl_krs krs on(krs.jdwl_id=jd1.jdwl_id and isnull(krs.RStat,0)=0)
				WHERE isnull(bn.RStat,0)=0 AND bn.ds_nidn=".$modJdw->bn->ds_nidn."
				GROUP BY jd1.jdwl_id,jd.GKode
			) t
			INNER JOIN vakasi_data vd on(vd.jdwl_id=t.jdwl_id and isnull(vd.Rstat,0)=0)
			INNER JOIN transaksi tr on(tr.kode_transaksi=vd.kode and isnull(tr.Rstat,0)=0)
			INNER JOIN transaksi_detail trd on(trd.kd_trans=tr.kode_transaksi and isnull(trd.Rstat,0)=0
				AND trd.kd_prod in('UTS','UAS','NUTS','NUAS','UTS1','UAS1')
			)
			GROUP BY vd.jdwl_id,t.mhs		
		) t
		";
        /*
        echo"<pre>";
        print_r($que);
        echo"</pre>";
        #*/
        #subakses
        $subAkses=self::sub();

        #getUserId
        $cuid = Yii::$app->user->identity->id;#

        $que=Yii::$app->db->createCommand($que)->queryOne();
        /* ------------------------
            model untuk vakasi
            VakasiData

        */

        //print_r(self::DataVakasi());


        $ModJdw = Jadwal::find()->where(['GKode'=>$kd,'jdwl_id'=>$_SESSION['kode']]);
        $model=$ModJdw->one();
        $modelAll=$ModJdw->all();
        //return "sss";
        $mVakasi = new TransaksiVakasi;
        $subTot = 0;
        if ($mVakasi->load(Yii::$app->request->post())) {
            #head

            if($mVakasi->TANDA){
                if(isset($_SESSION['TANDA'])){
                    $_SESSION['TANDA']=$mVakasi->TANDA;
                }else{$_SESSION['TANDA']=$mVakasi->TANDA;}
            }
            if($mVakasi->PPH){
                if(isset($_SESSION['PPH'])){
                    $_SESSION['PPH']=$mVakasi->PPH;
                }else{$_SESSION['PPH']=$mVakasi->PPH;}
            }
            #end head

            #data awal
            $q=0;$h=0;$t=0;
            #UTS

            /* UTS SUSULAN */
            if($mVakasi->NILAI['SUTS']){
                $q=$mVakasi->NILAI[SUTS];
                $h=Funct::produk('UTS1')->hrg->harga;$t=$q*$h;
                if(!isset($_SESSION['UTS1']['UTS1'])){
                    $_SESSION['UTS1']['UTS1']=['q'=>$q,'h'=>$h,'t'=>$t];
                }else{$_SESSION['UTS1']['UTS1']=['q'=>$q,'h'=>$h,'t'=>$t];	}
                $subTot+=$t;
            }$q=0;$h=0;$t=0;

            if($mVakasi->SOAL[SUTS]){
                $q=$mVakasi->SOAL[SUTS];$h=Funct::produk('NUTS1')->hrg->harga;$t=$q*$h;
                if(!isset($_SESSION['UTS1']['NUTS1'])){
                    $_SESSION['UTS1']['NUTS1']=['q'=>$q,'h'=>$h,'t'=>$t];
                }else{
                    $_SESSION['UTS1']['NUTS1']=['q'=>$q,'h'=>$h,'t'=>$t];
                }
                $subTot+=$t;
            }$q=0;$h=0;$t=0;
            /*END UTS SUSULAN*/



            /* UAS SUSULAN */
            if($mVakasi->NILAI[SUAS]){
                $q=$mVakasi->NILAI[SUAS];$h=Funct::produk('UAS1')->hrg->harga;$t=$q*$h;
                if(!isset($_SESSION['UAS1']['UAS1'])){
                    $_SESSION['UAS1']['UAS1']=['q'=>$q,'h'=>$h,'t'=>$t];
                }else{$_SESSION['UAS1']['UAS1']=['q'=>$q,'h'=>$h,'t'=>$t];	}
                $subTot+=$t;
            }$q=0;$h=0;$t=0;

            if($mVakasi->SOAL[SUAS]){
                $q=$mVakasi->SOAL[SUAS];$h=Funct::produk('NUAS1')->hrg->harga;$t=$q*$h;
                if(!isset($_SESSION['UAS1']['NUAS1'])){
                    $_SESSION['UAS1']['NUAS1']=['q'=>$q,'h'=>$h,'t'=>$t];
                }else{
                    $_SESSION['UAS1']['NUAS1']=['q'=>$q,'h'=>$h,'t'=>$t];
                }
                $subTot+=$t;
            }$q=0;$h=0;$t=0;
            /*END UTS SUSULAN*/

            #end UAS
            $pph =($mVakasi->PPH?$mVakasi->PPH * $subTot /100:0);
        }


        #--------------------------

        return $this->render('/pengajar/vakasi_add',[
            'mVakasi'=>$mVakasi,
            'model'=>$model,
            'All'=>$modelAll,
            'que'=>$que,
            'subTot'=>$subTot,
            'pph'=>$pph,
            'subAkses'=>$subAkses['jurusan'],
        ]);
    }
    #end Vakasi

    public function actionJurusan() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = Kalender::find()->select(' kr_kode, jr_id ')
                ->distinct(true)
                ->andWhere(['kr_kode'=>$id,])
                ->andWhere(self::sub()?['jr_id'=>self::sub()['jurusan']]:"")
                ->asArray()->all();
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


    #
    public function actionJadwalCreate(){return true;}
    public function actionJadwalSubCreate(){return true;}

    public  function actionNilait($id){


        $subAkses=self::sub();
        $ModVakasi	 = Vakasi::find()->where(['jdwl_id'=>$id])->orderBy(['id'=>SORT_DESC])->all();

        try {

            $QHeader = "SELECT ds.ds_nm, mk.mtk_nama,mk.mtk_sks,mk.mtk_kode, isnull(bn.nb_tgs1,0) nb_tgs1, isnull(bn.nb_tgs2,0) nb_tgs2,
                    isnull(bn.nb_tgs3,0) nb_tgs3,isnull(bn.nb_quis,0) nb_quis, isnull(bn.nb_tambahan,0) nb_tambahan
                    ,isnull(bn.nb_uts,0) nb_uts,isnull(bn.nb_uas,0) nb_uas, isnull(bn.B,0) B, isnull(bn.C,0) C, isnull(bn.D,0) D, 
                    isnull(bn.D,'0') D, isnull(bn.E,0) E
					,jd.bn_id,
					jd.jdwl_id, jd.Lock
					,mk.jr_id
                      FROM tbl_bobot_nilai bn JOIN tbl_jadwal jd
                      on jd.bn_id = bn.id 
                      JOIN tbl_matkul mk on mk.mtk_kode = bn.mtk_kode
                      JOIN tbl_dosen ds on ds.ds_id= bn.ds_nidn
                      WHERE jdwl_id ='$id';";
            $db = Yii::$app->db;
            $Header = $db->createCommand($QHeader)->queryOne();
            $db = Yii::$app->db1;
            $keuangan = Funct::getDsnAttribute('Database', $db->dsn);
            if(!keuangan){$keuangan = Funct::getDsnAttribute('Database', $db->dsn);}

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
            /*
            echo"<pre>";
            print_r($subAkses);
            print_r($Header);
            echo"</pre>";
            #die();
            #*/
            #Jika user tidak memiliki akses ke jurusan lain
            if($subAkses){
                if(!isset(array_flip($subAkses['jurusan'])[$Header['jr_id']])){
                    throw new ForbiddenHttpException("Forbidden access");
                }
            }


            $searchModel = new KrsDosen();
            return $this->render('/pengajar/nilait_v2', [
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

}
