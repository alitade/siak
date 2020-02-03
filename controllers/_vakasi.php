<?php
namespace app\controllers;
use yii\web\Session;
use yii\data\ActiveDataProvider;
use Yii;

use app\models\Akses;
use \mPdf;
use kartik\mpdf\Pdf;
use yii\helpers\Url;


use app\models\Jadwal;
use app\models\JadwalSearch;

#transakasi vakasi
use app\models\VakasiData;
use app\models\TransaksiVakasi;
use app\models\Transaksi;
use app\models\TransaksiDetail;
#end transaksi vakasi

use app\models\BobotNilai;
use app\models\BobotNilaiSearch;
use app\models\Funct;


use yii\filters\VerbFilter;
use yii\helpers\Json;

use yii\data\ArrayDataProvider;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use yii\db\Query;
use yii\db\Expression;

$connection = \Yii::$app->db;
/**/
use yii\app\controllers\AkademikGroupMk;
/**/

/**
 * BobotNilaiController implements the CRUD actions for BobotNilai model.
 */
class _vakasi extends Controller{

    public function sub(){return Akses::akses();}

    #VAKASI
    public function vakasi(){
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

    public function VakasiDetail($id,$pid=NULL){
        $subAkses=self::sub();
        $KetJr="";
        if($subAkses){$KetJr="'".implode("','",$subAkses['jurusan'])."'";}

        $B=false;
        $model_jadwal = new Jadwal;
        $model_jadwal->bn_id = $id;
        $ModBn = BobotNilai::findOne($id);
        $ModBn_= BobotNilai::find()
            //->select(['tbl_bobot_nilai.*'])
            ->innerJoin("tbl_kalender kln","(kln.kln_id=tbl_bobot_nilai.kln_id and kln.kr_kode='".$ModBn->kln->kr_kode."'  
		".(Akses::acc('/pengajar/vakasi-master')?"":($subAkses?"and kln.jr_id in($KetJr)":"")).")")
            ->where(['ds_nidn'=>$ModBn->ds_nidn,'isnull(tbl_bobot_nilai.RStat,0)'=>0])->all();

        $que="
			SELECT 
				vd.jdwl_id, 
				max(t.Gkode) Gkode,min(tr.status) status,
				max(tgs1) tgs1,max(uts) uts,
				max(tgs2) tgs2,max(uas) uas
			FROM (
				SELECT DISTINCT jd1.jdwl_id,jd.GKode from tbl_bobot_nilai bn
				INNER JOIN tbl_kalender kl on(
					kl.kln_id=bn.kln_id and isnull(kl.RStat,0)=0 and kl.kr_kode='".$ModBn->kln->kr_kode."'
					-- ".($KetJr?" and kl.jr_id in($KetJr)":"")."
				)
				INNER JOIN tbl_jadwal jd on(jd.bn_id=bn.id and isnull(jd.RStat,0)=0)
				INNER JOIN tbl_jadwal jd1 on(isnull(jd1.GKode,jd1.jdwl_id)=isnull(jd.GKode,jd.jdwl_id) and isnull(jd1.RStat,0)=0)
				INNER JOIN tbl_bobot_nilai bn1 on(bn1.ds_nidn=bn.ds_nidn and isnull(bn1.RStat,0)=0 AND bn1.id=jd1.bn_id)
				INNER JOIN tbl_kalender kl1 on(kl1.kln_id=bn1.kln_id and isnull(kl1.RStat,0)=0 AND kl1.kr_kode=kl.kr_kode)
				WHERE isnull(bn.RStat,0)=0
				AND bn.ds_nidn=".$ModBn->ds_nidn."
			) t
			INNER JOIN vakasi_data vd on(vd.jdwl_id=t.jdwl_id and isnull(vd.Rstat,0)=0)
			INNER JOIN transaksi tr on(tr.kode_transaksi=vd.kode and isnull(tr.Rstat,0)=0)
			GROUP BY vd.jdwl_id		
		
		";
        /*
        echo"<pre>";
        print_r($que);
        echo"</pre>";
        die();
        #*/
        $que=Yii::$app->db->createCommand($que)->queryAll();
        $kdVk=[];
        foreach($que as $d){
            if($d['tgs1']){$kdVk[$d['jdwl_id']]['tgs1']=1;}
            if($d['tgs2']){$kdVk[$d['jdwl_id']]['tgs2']=1;}
            if($d['uts']){$kdVk[$d['jdwl_id']]['uts']=1;}
            if($d['uas']){$kdVk[$d['jdwl_id']]['uas']=1;}
            $kdVk['D'][$d['Gkode']]=$d['status'];
        }
        #pencarian jadwal berdasarkan jurusan dan dosen

        return ['ModBn'=>$ModBn,
                'ModBn_'=>$ModBn_,
                'id'=>$id,
                'kdVk'=>$kdVk,];
//        return $this->render('/pengajar/vakasi_detail', [
//            'ModBn'=>$ModBn,
//            'ModBn_'=>$ModBn_,
//            'id'=>$id,
//            'kdVk'=>$kdVk,
//        ]);
    }

    public function VakasiDetailV1($id,$pid=NULL){
        $subAkses=self::sub();
        $KetJr="";
        if($subAkses){$KetJr="'".implode("','",$subAkses['jurusan'])."'";}

        $B=false;
        $model_jadwal = new Jadwal;
        $model_jadwal->bn_id = $id;
        $ModBn = BobotNilai::findOne($id);
        $ModBn_= BobotNilai::find()
            //->select(['tbl_bobot_nilai.*'])
            ->innerJoin("tbl_kalender kln","(kln.kln_id=tbl_bobot_nilai.kln_id and kln.kr_kode='".$ModBn->kln->kr_kode."'  
		".(Akses::acc('/pengajar/vakasi-master')?"":($subAkses?"and kln.jr_id in($KetJr)":"")).")")
            ->where(['ds_nidn'=>$ModBn->ds_nidn,'isnull(tbl_bobot_nilai.RStat,0)'=>0])->all();


        $qVakasi = Yii::$app->db->createCommand("exec vakasiDosen $ModBn->id ")->queryAll();

        $que="
			SELECT 
				vd.jdwl_id, 
				max(t.Gkode) Gkode,min(tr.status) status,
				max(tgs1) tgs1,max(uts) uts,
				max(tgs2) tgs2,max(uas) uas
			FROM (
				SELECT DISTINCT jd1.jdwl_id,jd.GKode from tbl_bobot_nilai bn
				INNER JOIN tbl_kalender kl on(
					kl.kln_id=bn.kln_id and isnull(kl.RStat,0)=0 and kl.kr_kode='".$ModBn->kln->kr_kode."'
					-- ".($KetJr?" and kl.jr_id in($KetJr)":"")."
				)
				INNER JOIN tbl_jadwal jd on(jd.bn_id=bn.id and isnull(jd.RStat,0)=0)
				INNER JOIN tbl_jadwal jd1 on(isnull(jd1.GKode,jd1.jdwl_id)=isnull(jd.GKode,jd.jdwl_id) and isnull(jd1.RStat,0)=0)
				INNER JOIN tbl_bobot_nilai bn1 on(bn1.ds_nidn=bn.ds_nidn and isnull(bn1.RStat,0)=0 AND bn1.id=jd1.bn_id)
				INNER JOIN tbl_kalender kl1 on(kl1.kln_id=bn1.kln_id and isnull(kl1.RStat,0)=0 AND kl1.kr_kode=kl.kr_kode)
				WHERE isnull(bn.RStat,0)=0
				AND bn.ds_nidn=".$ModBn->ds_nidn."
			) t
			INNER JOIN vakasi_data vd on(vd.jdwl_id=t.jdwl_id and isnull(vd.Rstat,0)=0)
			INNER JOIN transaksi tr on(tr.kode_transaksi=vd.kode and isnull(tr.Rstat,0)=0)
			GROUP BY vd.jdwl_id		
		
		";
        $que=Yii::$app->db->createCommand($que)->queryAll();
        $kdVk=[];
        foreach($que as $d){
            if($d['tgs1']){$kdVk[$d['jdwl_id']]['tgs1']=1;}
            if($d['tgs2']){$kdVk[$d['jdwl_id']]['tgs2']=1;}
            if($d['uts']){$kdVk[$d['jdwl_id']]['uts']=1;}
            if($d['uas']){$kdVk[$d['jdwl_id']]['uas']=1;}
            $kdVk['D'][$d['Gkode']]=$d['status'];
        }
        #pencarian jadwal berdasarkan jurusan dan dosen

        return $this->render('/pengajar/vakasi_detail_v1', [
            'ModBn'=>$ModBn,
            'ModBn_'=>$ModBn_,
            'qVakasi'=>$qVakasi,
            'id'=>$id,
            'kdVk'=>$kdVk,
        ]);
    }

    public function VakasiView($id){

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

    public function VakasiProc($kd){
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

    public function VakasiDelDraft($id){

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

    public function VakasiDelete($id){

        $model=Transaksi::findOne($id);
        $jdwlid="";
        foreach($model->dat as $d){$jdwlid=$d->jdwl_id;}
        $modJdw=Jadwal::findOne($jdwlid);
        if($model->cetak<=0||$model->status==0){
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

                TransaksiDetail::updateAll([
                    'duid'=>$cuid,
                    'dtgl'=>new  Expression("getdate()"),
                    'Rstat'=>'1'
                ],['kd_trans'=>$model->kode_transaksi]);
                return $this->redirect(["/pengajar/vakasi-view",'id'=>$modJdw->GKode]);
            }
        }
        return $this->redirect(["/pengajar/vakasi-view",'id'=>$modJdw->GKode]);
    }


    public function VakasiDel($id=""){
        unset($_SESSION['UTS']);
        unset($_SESSION['TUTS']);
        unset($_SESSION['UAS']);
        unset($_SESSION['TUAS']);
        unset($_SESSION['TANDA']);
        unset($_SESSION['PPH']);
        unset($_SESSION['kode']);
        if($id){return $this->redirect(['/pengajar/vakasi-detail','id'=>$id]);}


    }

    public function VakasiFaktur($id){
        self::VakasiDel();
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

    public function VakasiFakturView($id){

        $modVk 		= VakasiData::find()->where(['kode'=>$id])->All();
        $modTrans	= Transaksi::find()->where(['kode_transaksi'=>$id,'isnull(RStat,0)'=>'0'])->one();
        $data = self::faktur($id);
        if(!$modTrans->kode_transaksi){return $this->redirect(['/pengajar/vakasi']);}
        if(($modTrans->anv==1?1:0)==1){$data = self::fakturAnv($id);}
        $modJdw	= Jadwal::find()->innerJoin('vakasi_data vd',"(vd.jdwl_id=tbl_jadwal.jdwl_id and vd.kode='$id')")->all();

        $model	= Jadwal::findOne($idJdw);

        return ['data' => $data['data'],
                'tanda1' => $data['tanda1'],
                'tanda2' => $data['tanda2'],
                'model'=>$model,
                'modTrans'=>$modTrans,
                'modJdw'=>$modJdw,];
//        return $this->render('/pengajar/vakasi_faktur_view',[
//            'data' => $data['data'],
//            'tanda1' => $data['tanda1'],
//            'tanda2' => $data['tanda2'],
//            'model'=>$model,
//            'modTrans'=>$modTrans,
//            'modJdw'=>$modJdw,
//        ]);
    }

    public function VakasiAdd($kd){
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


    public function VakasiUpdate($id){
        //self::VakasiDel();
        #$modJdw	= Jadwal::find()->where(['GKode'=>$kd])->one();
        $model		= Transaksi::findOne($id);
        $mVakasi = new TransaksiVakasi;

        $sql="select * from vakasi_data where kode='$id'";
        $sql=Yii::$app->db->createCommand($sql)->queryAll();
        $id_=[];

        foreach($sql as $d){$id_[]=$d['jdwl_id'];}
        //die();
        $modelAll=Jadwal::find()->where(['jdwl_id'=>$id_])->all();

        $que="
		SELECT SUM(mhs) mhs, MAX(TGS1) TGS1, MAX(UTS) UTS, MAX(NUTS) NUTS, MAX(TGS2) TGS2, MAX(UAS) UAS, MAX(NUAS) NUAS,MAX(UTS1) UTS1,MAX(UAS1) UAS1
		, MAX(NUTS1) NUTS1, MAX(NUAS) NUAS1
			FROM(
			SELECT 
				vd.jdwl_id, 
				t.mhs,
				sum(iif(trd.kd_prod='TGS1',trd.qty,0))TGS1,
				sum(iif(trd.kd_prod='UTS',trd.qty,0))UTS,
				sum(iif(trd.kd_prod='UTS1',trd.qty,0)) UTS1,  
				sum(iif(trd.kd_prod='NUTS',trd.qty,0)) NUTS, 
				sum(iif(trd.kd_prod='NUTS1',trd.qty,0)) NUTS1, 
				sum(iif(trd.kd_prod='TGS2',trd.qty,0))TGS2,
				sum(iif(trd.kd_prod='UAS',trd.qty,0)) UAS,
				sum(iif(trd.kd_prod='UAS1',trd.qty,0)) UAS1,  
				sum(iif(trd.kd_prod='NUAS',trd.qty,0)) NUAS,
				sum(iif(trd.kd_prod='NUAS1',trd.qty,0)) NUAS1
			FROM (
                SELECT count(distinct krs.krs_id) mhs, jd1.jdwl_id,jd1.GKode 
                from tbl_bobot_nilai bn
                INNER JOIN tbl_jadwal jd1 on(jd1.bn_id=bn.id and isnull(jd1.RStat,0)=0 and jd1.jdwl_id in(".implode(',',$id_)."))
                INNER JOIN tbl_krs krs on(krs.jdwl_id=jd1.jdwl_id and isnull(krs.RStat,0)=0)
                WHERE isnull(bn.RStat,0)=0
                GROUP BY jd1.jdwl_id,jd1.GKode
			) t
			LEFT JOIN vakasi_data vd on(vd.jdwl_id=t.jdwl_id and isnull(vd.Rstat,0)=0)
			INNER JOIN transaksi tr on(tr.kode_transaksi=vd.kode and isnull(tr.Rstat,0)=0)
			LEFT JOIN transaksi_detail trd on(trd.kd_trans=tr.kode_transaksi and isnull(trd.Rstat,0)=0
				-- AND trd.kd_prod in('UTS','UAS','NUTS','NUAS','UTS1','UAS1')
			)
			GROUP BY vd.jdwl_id,t.mhs		
		) t
		";
        #subakses
        $subAkses=self::sub();

        #getUserId
        $cuid = Yii::$app->user->identity->id;#

        $que=Yii::$app->db->createCommand($que)->queryOne();
        $modelTransDet = TransaksiDetail::find()->where(['kd_trans'=>$model->kode_transaksi])->all();




        #print_r($modelTransDet);
        #die();
        #--------------------------
        return $this->render('/pengajar/vakasi_update',[
            'mVakasi'=>$mVakasi,
            'model'=>$model,
            'All'=>$modelAll,
            'modelTransDet'=>$modelTransDet,
            'que'=>$que,
            'subTot'=>$subTot,
            'pph'=>$pph,
            'subAkses'=>$subAkses['jurusan'],
        ]);
    }


    public function VakasiUpdate1($id){
        self::VakasiDel();
        $subAkses=self::sub();
        $modVk 		= VakasiData::find()->where(['kode'=>$id])->All();
        $modTrans	= Transaksi::findOne($id);
        $modJdw	    = Jadwal::find()->innerJoin('vakasi_data vd',"(vd.jdwl_id=tbl_jadwal.jdwl_id and vd.kode='$id')")->all();

        $sql="select * from vakasi_data where kode='$id'";
        $sql=Yii::$app->db->createCommand($sql)->queryAll();
        $id_=[];
        foreach($sql as $d){
            $id_[]=$d['jdwl_id'];
        }

        if($modTrans->cetak>=1){return $this->redirect(['/pengajar/vakasi-faktur-view','id'=>$id]);}
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
        if($modTrans){
            $mVakasi->TANDA['kajur']=$modTrans->mengetahui2;
            $mVakasi->TANDA['keuangan']=$modTrans->mengetahui1;
            $mVakasi->PPH=$modTrans->pph;
        }

        $que="
		SELECT SUM(mhs) mhs, MAX(TGS1) TGS1, MAX(UTS) UTS, MAX(NUTS) NUTS, MAX(TGS2) TGS2, MAX(UAS) UAS, MAX(NUAS) NUAS,MAX(UTS1) UTS1,MAX(UAS1) UAS1
			FROM(
			SELECT 
				vd.jdwl_id, 
				t.mhs,
				sum(iif(trd.kd_prod='TGS1',trd.qty,0))TGS1,
				sum(iif(trd.kd_prod='UTS',trd.qty,0))UTS,
				sum(iif(trd.kd_prod='UTS1',trd.qty,0)) UTS1,  
				sum(iif(trd.kd_prod='NUTS',trd.qty,0)) NUTS, 
				sum(iif(trd.kd_prod='TGS2',trd.qty,0))TGS2,
				sum(iif(trd.kd_prod='UAS',trd.qty,0)) UAS,
				sum(iif(trd.kd_prod='UAS1',trd.qty,0)) UAS1,  
				sum(iif(trd.kd_prod='NUAS',trd.qty,0)) NUAS
			FROM (
                SELECT count(distinct krs.krs_id) mhs, jd1.jdwl_id,jd1.GKode 
                from tbl_bobot_nilai bn
                INNER JOIN tbl_jadwal jd1 on(jd1.bn_id=bn.id and isnull(jd1.RStat,0)=0 and jd1.jdwl_id in(".implode(',',$id_)."))
                INNER JOIN tbl_krs krs on(krs.jdwl_id=jd1.jdwl_id and isnull(krs.RStat,0)=0)
                WHERE isnull(bn.RStat,0)=0
                GROUP BY jd1.jdwl_id,jd1.GKode
			) t
			LEFT JOIN vakasi_data vd on(vd.jdwl_id=t.jdwl_id and isnull(vd.Rstat,0)=0)
			INNER JOIN transaksi tr on(tr.kode_transaksi=vd.kode and isnull(tr.Rstat,0)=0)
			LEFT JOIN transaksi_detail trd on(trd.kd_trans=tr.kode_transaksi and isnull(trd.Rstat,0)=0
				-- AND trd.kd_prod in('UTS','UAS','NUTS','NUAS','UTS1','UAS1')
			)
			GROUP BY vd.jdwl_id,t.mhs		
		) t
		";

        #echo"<pre>";
        #print_r($que);
        #echo"</pre>";

        $que=Yii::$app->db->createCommand($que)->queryOne();

        //$subTot = 0;
        #data awal produk
        $modelDetTrans = TransaksiDetail::find()->where(['kd_trans'=>$modTrans->kode_transaksi])->all();
        foreach($modelDetTrans as $d){
            if($d->kd_prod=='NUTS'){
                $q=$d->qty;$h=$d->harga;$t=$q*$h;
                $_SESSION['UTS']['NUTS']=['q'=>$q,'h'=>$h,'t'=>$t];
            }

        }
        /*
        echo"<pre>";
        print_r($_SESSION);
        echo"</pre>";
        #*/




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

        return $this->render('/pengajar/vakasi_update1',[
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




    public function VakasiSave1($kd){

        $ModJdw = Jadwal::find()->where(['GKode'=>$kd,'jdwl_id'=>$_SESSION['kode']]);
        $modelAll=$ModJdw->all();

        $cuid = Yii::$app->user->identity->id;#
        $Nofaktur=date('Ymd')."-".time();
        $ins=0;
        $bnid="";
        foreach($modelAll as $rec){
            $ins++;
            $bnid = $rec->bn->ds_nidn;
            #$rec['jdwl_id']."<br />";
            $VakasiData =new VakasiData;
            $VakasiData->jdwl_id=$rec['jdwl_id'];
            $VakasiData->uts1=($_SESSION['UTS1']['q']?1:0);
            $VakasiData->uas1=($_SESSION['UAS1']['q']?1:0);
            $VakasiData->cuid=$cuid;
            $VakasiData->ctgl = new  Expression("getdate()");
            $VakasiData->kode=$Nofaktur;
            $VakasiData->save();

        }
        //$modTrans	= Transaksi::findOne($id);
        $idJdw="";
        $cuid = Yii::$app->user->identity->id;
        $ins="insert into transaksi_detail (kd_trans,kd_prod,qty,harga,cuid) values";
        if(isset($_SESSION['TANDA']) ){
            if(isset($_SESSION['UTS1'])){
                foreach($_SESSION['UTS1'] as $k=>$v){
                    $qty=$v['q'];$hrg=$v['h'];
                    $ins.="('$Nofaktur','$k','$qty','$hrg','$cuid'),";
                }
            }
            if(isset($_SESSION['UAS1'])){
                foreach($_SESSION['UAS1'] as $k=>$v){
                    $qty=$v['q'];$hrg=$v['h'];
                    $ins.="('$Nofaktur','$k','$qty','$hrg','$cuid'),";
                }
            }
            #data susulan
            //die($bnid);


            $sql=Yii::$app->db->createCommand(substr($ins,0,-1))->execute();

            if($sql){
                #header
                $modTrans= new Transaksi;
                $modTrans->kode_transaksi 	= $Nofaktur;
                $modTrans->ds_id   			= $bnid;
                $modTrans->pph				= ($_SESSION['PPH']?$_SESSION['PPH']:0);
                $modTrans->mengetahui1		= ($_SESSION['TANDA']['keuangan']?$_SESSION['TANDA']['keuangan']:'-');
                $modTrans->mengetahui2		= ($_SESSION['TANDA']['kajur']?$_SESSION['TANDA']['kajur']:'-');
                $modTrans->kat				= ($_SESSION['TANDA']['BAAK']?'1':'0');

                $modTrans->cuid				=$cuid;
                $modTrans->tgl				=new  Expression("getdate()");
                $modTrans->lock				='1';
                $modTrans->status			='1';
                $modTrans->kr_kode_ 		=$modJdw->bn->kln->kr_kode;
                $modTrans->save();

                #/*
                unset($_SESSION['UTS1']);
                unset($_SESSION['UAS1']);
                unset($_SESSION['TANDA']);
                unset($_SESSION['PPH']);
                unset($_SESSION['kode']);
                return $this->redirect(['/pengajar/vakasi-faktur-view','id'=>$modTrans->kode_transaksi]);
                #*/
            }
        }

    }


    public function VakasiSave($id){
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

    public function faktur($kd){
        $tgl=date('Y-m-d');
        #if($model->tgl_cetak){$tgl=$model->tgl_cetak;}
        $model=Transaksi::findOne($kd);
        $tipe="";
        $Ttgs1=0;$Ttgs2=0;$Tuts=0;$Tuas=0;
        foreach($model->dat as $d1){$Ttgs1+=$d1->tgs1;$Ttgs2+=$d1->tgs2;$Tuts+=$d1->uts;$Tuas+=$d1->uas;}
        $UTS1="";
        $UTS2="";
        foreach($model->det as $d1){
            if($d1->kd_prod=='UTS1'||$d1->kd_prod=='NUTS1'){$UTS1=' UTS SUSULAN,';}
            if($d1->kd_prod=='UAS1'||$d1->kd_prod=='NUAS1'){$UAS1=' UAS SUSULAN,';}
        }
        if($Ttgs1>0||$Tuts>0){$tipe.=" UTS,";}$tipe.=$UTS1;
        if($Ttgs2>0||$Tuas>0){$tipe.=" UAS,";}$tipe.=$UAS1;

        $modJdw	= Jadwal::find()
            ->innerJoin('vakasi_data vd',"(vd.jdwl_id=tbl_jadwal.jdwl_id and vd.kode='$kd')")->all();

        $MK="";$_MK=[];
        $JR="";$_JR=[];
        foreach($modJdw as $d){
            if(!isset($_MK[$d->jdwl_id])){
                $_MK[$d->jdwl_id]=1;
                $MK.=" ".$d->bn->mtk_kode.": ".$d->bn->mtk->mtk_nama."(".$d->jdwl_kls."),";
            }
            if(!isset($_JR[$d->bn->kln->jr->jr_id])){
                $_JR[$d->bn->kln->jr->jr_id]=1;
                $JR.=" ".$d->bn->kln->jr->jr_jenjang." ".$d->bn->kln->jr->jr_nama.",";
            }
        }

        $data='<table border="0" width="100%" style="font-size:11px;margin:5px;">
				<tr>
					<th align="center" colspan="3" style="text-transform:uppercase;text-align:center;" id="td">USB YPKP FAK. EKONOMI, TEKNIK, FIKA 
					<br /> Vakasi '.substr($tipe,0,-1).'
					<br /> Semester '.$model->kr->kr_nama.' </th>
				<tr>
				<tr style="vertical-align:top;" id="td">
					<td width="30px"> Dosen </td>
					<td width="2px">:</td>
					<td>'.$model->dsn->ds_nm.'</td>
				</tr>
				<tr style="vertical-align:top;" id="td">
					<td style="vertical-align:top;"> Matakuliah </td>
					<td style="vertical-align:top;">:</td>
					<td>'.substr($MK,0,-1).'</td>
				</tr>
				<tr id="td">
					<td style="vertical-align:top;"> Jurusan </td>
					<td style="vertical-align:top;">:</td>
					<td>'.substr($JR,0,-1).'</td>
				</tr>
			</table>';

        $detail=TransaksiDetail::find()->where(['kd_trans'=>$model->kode_transaksi])->all();
        $data.='<table width="100%" style="font-size:11px;margin:5px;" id="td">';
        $total=0;
        $idKat=[];
        foreach($detail as $d){
            $idKat[$d->produk->kategori]=1;
            $tot =$d->qty*$d->harga;
            $total+=$tot;
            $data.="<tr id='td'><td>&nbsp;".$d->produk->produk."&nbsp;</td>
				<td align='right'>&nbsp;".number_format($d->qty,0,'.',',')."&nbsp;</td>
				<td>&nbsp;x&nbsp;</td>
				<td align='right'>&nbsp;Rp".number_format($d->harga,0,'.',',')."&nbsp;</td>
				<td align='right'>&nbsp;Rp.".number_format($tot,0,'.',',')."&nbsp;</td>
				</tr>
				";
        }
        ;


        //var_dump(array_keys($idKat));
        $pph=$model->pph*$total/100;
        $GrandTot=$total-$pph;
        $data.='<tr id="td"><th colspan="4"  style="text-align:right">Sub Total</th><th align="right" style="border-top:solid 1px #000;text-align:right">Rp.'.number_format($total,0,'.',',').'</th></tr>';
        $data.='<tr id="td"><th colspan="4"  style="text-align:right">PPH ('.$model->pph.'%)</th><th style="text-align:right">Rp.'.number_format($pph,0,'.',',').'</th></tr>';
        $data.='<tr id="td"><th colspan="4"  style="text-align:right">Total</th><th align="right" style="border-top:solid 1px #000;text-align:right">Rp.'.number_format($GrandTot,0,'.',',').'</th></tr>';
        $data.="</table>";


        $tanda1='
				<table border="0" width="100%" style="font-size:11px;text-align:center;margin:5px;" class="td">
					<tr id="td"><td align="center" colspan="3"> Bandung,'.Funct::TANGGAL($tgl,2).' </td></tr>
					<tr id="td">
						<td width="48%"> Yang Menyerahkan Nilai,</td>
						<td rowspan="2"  width="5px"> </td>
						<td> Yang Menerima Nilai,</td>
					</tr>
					<tr valign="bottom" id="td">
						<td height="50px" style="vertical-align:bottom">'.$model->dsn->ds_nm.'</td>
						<td style="vertical-align:bottom">'.Yii::$app->user->identity->name.'</td>
					</tr>
				</table>
			';

        $tanda2='
				<table border="0" width="100%" style="font-size:11px;text-align:center;margin:5px;" class="td">
					<tr id="td"><td align="center" colspan="3"> Bandung,'.Funct::TANGGAL($tgl,2).' </td></tr>
					<tr id="td">
						<td width="48%"> Yang Menerima Vakasi,</td>
						<td rowspan="2" width="5px"> </td>
						<td> Yang Menyerahkan Vakasi,</td>
					</tr>
					<tr valign="bottom" id="td">
						<td style="vertical-align:bottom">'.$model->dsn->ds_nm.'</td>
						<td height="50px" style="vertical-align:bottom">____________________</td>
					</tr>
					<tr id="td"><td align="center" colspan="3"> <br />Mengetahui </td></tr>
					<tr id="td">
						<td>'.($model->kat==1?"Kepala Bagian IT. Akademik":" Ketua Jurusan").', </td>
						<td rowspan="2"> </td>
						<td> Kepala Bagian Keuangan,</td>
					</tr>
					<tr style="vertical-align:bottom" id="td">
						<td height="50px" style="vertical-align:bottom">'.$model->mengetahui2.' </td>
						<td style="vertical-align:bottom">'.$model->mengetahui1.' </td>
					</tr>
					
					
				</table>
			';
        return['data' => $data,'tanda1' => $tanda1,'tanda2' => $tanda2,'model'=>$model];

    }

    public function fakturAnv($kd){
        $tgl=date('Y-m-d');
        #if($model->tgl_cetak){$tgl=$model->tgl_cetak;}
        $model=Transaksi::findOne($kd);
        $tipe="";
        $Ttgs1=0;$Ttgs2=0;$Tuts=0;$Tuas=0;
        foreach($model->dat as $d1){$Ttgs1+=$d1->tgs1;$Ttgs2+=$d1->tgs2;$Tuts+=$d1->uts;$Tuas+=$d1->uas;}
        $UTS1="";
        $UTS2="";
        foreach($model->det as $d1){
            if($d1->kd_prod=='UTS1'||$d1->kd_prod=='NUTS1'){$UTS1=' UTS SUSULAN,';}
            if($d1->kd_prod=='UAS1'||$d1->kd_prod=='NUAS1'){$UAS1=' UAS SUSULAN,';}
        }
        if($Ttgs1>0||$Tuts>0){$tipe.=" UTS,";}$tipe.=$UTS1;
        if($Ttgs2>0||$Tuas>0){$tipe.=" UAS,";}$tipe.=$UAS1;

        $modJdw	= Jadwal::find()
            ->innerJoin('vakasi_data vd',"(vd.jdwl_id=tbl_jadwal.jdwl_id and vd.kode='$kd')")->all();

        $MK="";$_MK=[];
        $JR="";$_JR=[];
        foreach($modJdw as $d){
            if(!isset($_MK[$d->bn->mtk_kode])){
                $_MK[$d->bn->mtk_kode]=1;
                $MK.=" ".$d->bn->mtk_kode.": ".$d->bn->mtk->mtk_nama."(".$d->jdwl_kls."),";
            }
            if(!isset($_JR[$d->bn->kln->jr->jr_id])){
                $_JR[$d->bn->kln->jr->jr_id]=1;
                $JR.=" ".$d->bn->kln->jr->jr_jenjang." ".$d->bn->kln->jr->jr_nama.",";
            }
        }

        $data='<table border="0" width="100%" style="font-size:11px;margin:5px;">
				<tr>
					<th align="center" colspan="3" style="text-transform:uppercase;text-align:center;" id="td">USB YPKP FAK. EKONOMI, TEKNIK, FIKA 
					<br /> Vakasi Anvulen
					<br /> Semester '.$model->kr->kr_nama.'</th>
				<tr>
				<tr><th colspan="3" id="td"> Telah terima nilai dari:</th></tr>
				<tr style="vertical-align:top;" id="td">
					<td width="30px"> Dosen </td>
					<td width="2px">:</td>
					<td>'.$model->dsn->ds_nm.'</td>
				</tr>
				<tr style="vertical-align:top;" id="td">
					<td style="vertical-align:top;"> Matakuliah </td>
					<td style="vertical-align:top;">:</td>
					<td>'.substr($MK,0,-1).'</td>
				</tr>
				<tr id="td">
					<td style="vertical-align:top;"> Jurusan </td>
					<td style="vertical-align:top;">:</td>
					<td>'.substr($JR,0,-1).'</td>
				</tr>
			</table>';

        $tanda1='
				<table border="0" width="100%" style="font-size:11px;text-align:center;margin:5px;" class="td">
					<tr id="td"><td align="center" colspan="3"> Bandung,'.Funct::TANGGAL($tgl,2).' </td></tr>
					<tr id="td">
						<td width="48%"> Yang Menyerahkan Nilai,</td>
						<td rowspan="2"  width="5px"> </td>
						<td> Yang Menerima Nilai,</td>
					</tr>
					<tr valign="bottom" id="td">
						<td height="50px" style="vertical-align:bottom">'.$model->dsn->ds_nm.'</td>
						<td style="vertical-align:bottom">'.Yii::$app->user->identity->name.'</td>
					</tr>
				</table>
			';

        $tanda2='
				<table border="0" width="100%" style="font-size:11px;text-align:center;margin:5px;" class="td">
					<tr id="td"><td align="center" colspan="3"> Bandung,'.Funct::TANGGAL($tgl,2).' </td></tr>
					<tr id="td">
						<td width="48%"> Yang Menerima Vakasi,</td>
						<td rowspan="2" width="5px"> </td>
						<td> Yang Menyerahkan Vakasi,</td>
					</tr>
					<tr valign="bottom" id="td">
						<td height="50px" style="vertical-align:bottom">____________________</td>
						<td style="vertical-align:bottom">'.$model->dsn->ds_nm.'</td>
					</tr>
					<tr id="td"><td align="center" colspan="3"> <br />Mengetahui </td></tr>
					<tr id="td">
						<td>'.($model->kat==1?"Kepala Bagian IT. Akademik":" Ketua Jurusan").', </td>
						<td rowspan="2"> </td>
						<td> Kepala Bagian Keuangan,</td>
					</tr>
					<tr style="vertical-align:bottom" id="td">
						<td height="50px" style="vertical-align:bottom">'.$model->mengetahui2.' </td>
						<td style="vertical-align:bottom">'.$model->mengetahui1.' </td>
					</tr>
					
					
				</table>
			';
        return['data' => $data,'tanda1' => $tanda1,'tanda2' => $tanda2,'model'=>$model];

    }


    public function VakasiCetak($kd){
        $mod = Transaksi::findOne($kd);
        $mod->cetak='1';
        $mod->save();

        $this->layout = "blank";
        $data = self::faktur($kd);
        if(($mod->anv==1?1:0)==1){$data = self::fakturAnv($kd);}

        //return
        $content = $this->renderPartial('vakasi_cetak',[
            'data' => $data['data'],
            'tanda1' => $data['tanda1'],
            'tanda2' => $data['tanda2'],
            'model'=>$data['model'],
        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_BLANK , // leaner size using standard fonts
            'content' => $content,
            'format'=>Pdf::FORMAT_LETTER,
            'marginLeft'=>5,
            'marginRight'=>5,
            'marginTop'=>5,
            'marginHeader'=>false,
            'orientation'=>'L',
            'destination'=>'I',
            'filename'=>"$kd.pdf",
            'options' => [
                'title' => 'Vakasi '.$kd,
                'subject' => 'Vakasi '.$kd,
            ],
        ]);
        return $pdf->render();
    }

}
