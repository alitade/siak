<?php
namespace app\controllers;
use yii\web\Session;
use yii\data\ActiveDataProvider;
use Yii;

use app\models\Ruang;
use app\models\Akses;

use app\models\MAbsenDosen;

use kartik\mpdf\Pdf;


use app\models\Funct;

use app\models\Jadwal;
use app\models\JadwalSearch;
use app\models\KrsSearch;


use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use yii\db\Query;
use yii\data\ArrayDataProvider;

use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\db\Expression;

$connection = \Yii::$app->db;

class Perkuliahan extends Controller{
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

	public function sub(){return Akses::akses();}

    #2018-02-22
	public function persensiMahasiswa_finger($id){
        $id_dosen = Yii::$app->user->identity->id;
        $test= Yii::$app->authManager->checkAccess($id_dosen,'akses_dosen');
        $model = Jadwal::findOne($id);
        if ($test) {
            if ($model->bn->ds->ds_user!==Yii::$app->user->identity->username){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
//        die($test);


//        if($model->bn->ds->ds_user!==Yii::$app->user->identity->username){throw new NotFoundHttpException('The requested page does not exist.');}

        $Dosen = MAbsenDosen::find()->where(['jdwl_id'=>$id,'isnull(RStat,0)'=>0])->orderBy(["tgl_normal"=>SORT_ASC])->all();

        $Tot=[];
        $S=[];$S1=[];$DS=[];
        foreach($Dosen as $d){
            #$Tot[$d['sesi']]=0;
            $DS[$d['sesi']]=($d['ds_stat']==1?1:0);
            $bg="style='background:".($d['ds_stat']==1?"green":"red").";'";
            $S[$d['sesi']]="<th style='background:rgba(0,0,0,0.9)'>".Html::a("S.".$d['sesi'],['/perkuliahan/detail-sesi-kuliah','id'=>$d->id],['class'=>'btn badge','style'=>"background:".($d['ds_stat']==1?"green":"red")]).'</th>';
            $S1[$d['sesi']]="<td><span>".date("M d",strtotime($d['tgl_perkuliahan']))."</span>,<br>".($d[ds_masuk]?substr($d['ds_masuk'],0,5):"?")."-".($d[ds_keluar]?substr($d['ds_keluar'],0,5):"?").'</td>';
        }

        $n=0;
        $ds="";
        $ds1="";
        $ListSesi=Yii::$app->db->createCommand("SELECT * FROM dbo.periode_v5(DEFAULT,DEFAULT,DEFAULT,DEFAULT,$id)")->queryAll();
        $ig="";
        $LS=[];
        foreach ($ListSesi as $d) {
            if($d['t']=='TS'){$d['t']="UTS";}
            if($d['t']=='AS'){$d['t']="UAS";}

            if($ig!=$d['t']){
                $n++;
                $LS[$n]=$d['t'];
                $ig=$d['t'];
                if(isset($S[$d['t']])){
                    $ds.=$S[$d['t']];
                    $ds1.=$S1[$d['t']];
                }else{
                    $ds .= "<th style='background:red'>$d[t]</th>";
                    $ds1.="<td>----,<br>__:__-__:__</td>";
                }
            }
        }
        //$Dosen = MAbsenDosen::find()->where(['jdwl_id'=>$id,'isnull(RStat,0)'=>0])->all();
        $table='
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                <th colspan="'.$n.'">'.$model->bn->ds->ds_nm.'<br>
                    '.Funct::Hari()[$model->jdwl_hari].', '.$model->jdwl_masuk.' - '.$model->jdwl_keluar.' : '
                    .$model->bn->mtk_kode.' - '.$model->bn->mtk->mtk_nama.'( '.$model->jdwl_kls.' )
                </th></tr><tr>'.$ds.'</tr></thead><tr>'.$ds1.'</tr>  
            </table>
        ';
        $mahasiswa=Yii::$app->db->createCommand("exec absenperkuliahan $model->jdwl_id")->queryAll();
        $mhs="";
        $no=0;
        foreach($mahasiswa as $d){
            $no++;
            $mhs.="<tr><td>$no</td><td><b>$d[mhs_nim] |</b> $d[Nama]</td>";
            for($i=1;$i<=$n;$i++){
                ///$SESI=$S[]
                $vsesi =(strlen($LS[$i])==1 && is_numeric($LS[$i])?"S0".$LS[$i]:"S".$LS[$i]);
                $value = '';
                    if(isset($d[$vsesi])){
                        $hadir = explode('|',$d[$vsesi]);
                        //$Tot[$i]+=$hadir[0];
                        $attribute = ' data-nim="'.$d['mhs_nim'].'" data-jdwl_id="'.$id.'" data-sesi="'.$LS[$i].'" data-krs_id="'.$d['krs_id'].'"'." $DS[$i] ";
                        if($DS[$LS[$i]]==1){
                            if ($hadir[0]==1) {
                                $Tot[$i]++;
                                $value = '<a href="javascript:;" class="do_attendance btn badge" name ="ctrl_attendance" id="'.$vsesi.'" '.$attribute.' style="font-weight:normal;background:green;">'.$hadir[1].'</a>';
                            }else{
                                $value = '<a href="javascript:;" class="do_attendance btn badge" name ="ctrl_attendance" id="'.$vsesi.'" '.$attribute.' style="font-weight:normal;background:red;">'.$hadir[1].'</a>';
                            }
                        }else{$value='<span class="btn badge" style="font-weight:normal;background:red;">'.$hadir[1].'</span>';}
                    }
                    #$mhs.="<td>".($d[$vsesi]==1?1:0)." </td>";
                    $mhs.="<td> ".$value."</td>";
            }
            $mhs.="</tr>";
        }
        $mhsT="";
        for($i=1;$i<$n;$i++){
            #echo $i."|";
            $mhsT.="<th>$Tot[$i]</th>";}

        $a=ksort($Tot,1);
        $table.='
        <div class="col-sm-12" ><i><b> 1) Untuk melihat atau merubah keterangan kehadiran mahasiswa klik sesi di bawah</b></i></div>

           <table class="table table-bordered table-hover tb ">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">NIM | NAMA</th>
                    <th colspan="'.($n).'">1) Sesi</th>
                </tr>
                    <tr>'.$ds.'</tr>
                <tr>
                    <td colspan="'.($n+2).'" style="text-align:right;color:#000;background:#fff;">
                    <div class="badge" style="background:red">Tidak Hadir</div>
                    <div class="badge" style="background:green">Hadir</div>
                    <b>[ N:NORMAL | A:TIDAK HADIR | I:IZIN | S:SAKIT | D:DISPEN ]</b>
                    </td></tr>
            </thead>
            <tbody>'.$mhs.'</tbody>
            <tr><th colspan="2" style="text-align: right">Total</th>'.$mhsT.'</tr>
            </table>';

        return ['table'=>$table];
//        return $this->render('/perkuliahan/absen/finger_mahasiswa', [
//            'table'=>$table,
//        ]);

    }

    public function dPersensiBerjalan($id){
	    $model = \app\models\TAbsenDosen::findOne($id);
//        inner join $keuangan.dbo.student s on (s.nim COLLATE Latin1_General_CI_AS = t.mhs_nim)
//        inner join $keuangan.dbo.people p on ( p.No_Registrasi = s.no_registrasi)

        $db = Yii::$app->db1;
        $keuangan 	= Funct::getDsnAttribute('dbname', $db->dsn);
        if(!$keuangan){$keuangan 	= Funct::getDsnAttribute('Database', $db->dsn);}
	    $mhs   = \app\models\TAbsenMhs::find()
            ->select(['t_absen_mhs.*','p.Nama','ad.mtk_kode','ad.mtk_nama','ad.jdwl_kls'])
            ->innerJoin("t_absen_dosen ad","(ad.id=t_absen_mhs.id_absen_ds and isnull(ad.RStat,0)=0 and (ad.id=".($model->id?:0)." or ad.GKode_='$model->GKode_'))")
            ->innerJoin("$keuangan.dbo.student s","(s.nim COLLATE Latin1_General_CI_AS=t_absen_mhs.mhs_nim)")
            ->innerJoin("$keuangan.dbo.people p","(p.No_Registrasi = s.no_registrasi)")
            //->where(['ad'])
            ->orderBy(['ad.jdwl_id'=>SORT_ASC,'t_absen_mhs.mhs_nim'=>SORT_ASC])
            ->all();
        return ['model'=>$model,'mhs'=>$mhs,];

	    return @$this->render('/perkuliahan/absen/hari_iniV',[
            'model'=>$model,
            'mhs'=>$mhs,
        ]);

    }

    public function masuk($id){
        $cuid=Yii::$app->user->identity->id;
        $model  =\app\models\TAbsenDosen::findOne($id);
        $updateAll =\app\models\TAbsenDosen::updateAll([
            'ds_masuk'=>new Expression("isnull(ds_masuk,getdate())"),
            'input_tipe'=>new Expression("isnull(input_tipe,iif(ds_masuk is null,1,iif(ds_keluar is null,2,3)))"),
            'utgl'=>new Expression("getdate()"),
            'uuid'=>$cuid,
        ],['GKode_'=>$model->GKode_]
        );
        \app\models\Funct::LOGS('Update Masuk Dosen Hari ini',new \app\models\TAbsenDosen,$id,'r');
        return $this->redirect(['/perkuliahan/berjalan-v','id'=>$id]);
    }

    public function keluar($id,$m){
        $cuid=Yii::$app->user->identity->id;
        $model  =\app\models\TAbsenDosen::findOne($id);
        $updateAll =\app\models\TAbsenDosen::updateAll([
            'ds_keluar'=>new Expression("isnull(ds_keluar,getdate())"),
            'input_tipe'=>new Expression("isnull(input_tipe,iif(ds_masuk is null,1,iif(ds_keluar is null,2,3)))"),
            'utgl'=>new Expression("getdate()"),
            'uuid'=>$cuid,
            'ds_stat'=>1
        ],['GKode_'=>$model->GKode_]
        );
        \app\models\Funct::LOGS('Update Masuk Dosen Hari ini',new \app\models\TAbsenDosen,$id,'r');
        return $this->redirect(['/perkuliahan/berjalan-v','id'=>$id]);
    }

    public function pergantianPerkuliahan($id){
        $usrId=Yii::$app->user->identity->id;

        $model		= Jadwal::findOne($id);
        $ModJdwl	= Jadwal::find()
            ->innerJoin("tbl_bobot_nilai bn","( bn.id=tbl_jadwal.bn_id and isnull(bn.RStat,0)=0 and bn.ds_nidn='".$model->bn->ds_nidn."' and dbo.cekIgMk(bn.mtk_kode)=0)")
            ->where("isnull(tbl_jadwal.RStat,0)=0 and (jdwl_id=$model->jdwl_id or GKode='$model->GKode')")
            ->all();

        $user		= " select  fid from user_ where username='".$model->bn->ds->ds_user."' and fid is not null";
        $user=Yii::$app->db->createCommand($user)->QueryOne();

        $Detail="exec dbo.detailPerkuiahan $id";
        $Detail=Yii::$app->db->createCommand($Detail)->QueryAll();

        $id=abs($id);
        $sql = "
			select  t.*,t1.sesi,t1.tgl ftgl,ds_stat from dbo.sesijadwal($id) t
			left join (
				SELECT DISTINCT 
					cast(sesi as varchar(2))sesi,min(tgl) tgl ,max(isnull(ds_stat,0)) ds_stat
				FROM m_transaksi_finger 
				WHERE jdwl_id='$id'
				GROUP BY sesi
			) t1 on(t1.sesi=t.s)
			where isnull(ds_stat,0)!=1			
		";
        $sql=Yii::$app->db->createCommand($sql)->queryAll();

        $msg="";

        if($_POST['G']){

            $sesi	= $_POST['G']['sesi'];
            $tgl	= $_POST['G']['tgl'];
            $masuk	= $_POST['G']['masuk'];
            $keluar	= $_POST['G']['keluar'];

            $tglAwal = " select  tgl from dbo.sesijadwal($id) where s='$sesi'";
            $tglAwal = Yii::$app->db->createCommand($tglAwal)->QueryOne();

            $Qv = " select *from(
				select datediff(minute,'$masuk','$keluar')d, datediff(day,getdate(),'$tgl') t,
				datepart(dw,'$tgl') h ) t
				";
            $Qv=Yii::$app->db->createCommand($Qv)->QueryOne();
            if($Qv['h']==1){
                $msg="Perkuliahan Tidak Bisa Dilakukan Dihari minggu";
            }else{
                if($Qv['t'] > 0 ){
                    if($Qv['d'] < 0 ){
                        $msg="Jam Masuk Melebihi Jam Keluar";
                    }
                }else{
                    $msg="Salah Memilih Tanggal $tgl";
                    if($Qv['t']==0){$msg="Pergantian Jadwal Tidak Bisa Dilakukan Dihari ini";}
                }
            }

            $cekPergantian	=" select * from  dbo.bentrokpergantian($id,'$tgl','$masuk','$keluar','$sesi')";
            $cekPergantian =Yii::$app->db->createCommand($cekPergantian)->QueryAll();


            if($cekPergantian){
                $msg="Jadwal Bentrok";
            }

            if($msg==""){
                $q="
				INSERT INTO t_finger_pengganti(
					krs_id,krs_stat,ds_fid,mtk_kode,mtk_nama,jdwl_id
					,jdwl_hari,jdwl_masuk,jdwl_keluar,mhs_fid,tgl_ins
					,tgl,sesi
					--,ucreate
				)
				SELECT
					krs.krs_id,krs.krs_stat,$user[fid],mk.mtk_kode,mk.mtk_nama,
					jdw.jdwl_id,
					(DATEPART(dw,'$tgl')-1),
					'$masuk','$keluar',
					mhs.Fid,CAST('$tgl' as DATE)
					,'$tglAwal[tgl]'
					,'$sesi'
					--,'$usrId'
				from tbl_jadwal jdw
				INNER JOIN tbl_bobot_nilai bn on(bn.id=jdw.bn_id and(bn.RStat is NULL or bn.RStat='0'))
				INNER JOIN tbl_dosen ds on(ds.ds_id=bn.ds_nidn and ds.ds_id='".$model->bn->ds_nidn."')
				INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and(mk.RStat is NULL or mk.RStat='0'))
				INNER JOIN tbl_kalender kln on(
					kln.kln_id=bn.kln_id and(kln.RStat is NULL or kln.RStat='0')
					and(
						GETDATE()  
						BETWEEN kln_masuk 
						and DATEADD(day,kln.kln_uas_lama, kln.kln_uas)
				 )
				)
				INNER JOIN tbl_krs krs on(krs.jdwl_id=jdw.jdwl_id and(krs.RStat is NULL or krs.RStat='0'))
				INNER JOIN (
					SELECT m.mhs_nim, u.Fid from user_ u INNER JOIN tbl_mahasiswa m on(m.mhs_nim=u.username and u.tipe='5')
					WHERE (m.RStat is NULL or m.RStat='0')
				)mhs on(mhs.mhs_nim=krs.mhs_nim)
				WHERE NOT EXISTS(SELECT * FROM t_finger_pengganti tf WHERE tf.krs_id=krs.krs_id and tf.sesi='$sesi')
				AND dbo.cekIgMk(mk.mtk_kode)=0
				and jdw.jdwl_kls not in('j','R1','R2')
				and jdw.jdwl_hari = $model->jdwl_hari
				and (jdw.GKode='$model->GKode' or jdw.jdwl_id='$model->jdwl_id')			 			
				";

                $q=Yii::$app->db->createCommand($q)->execute();
                return $this->redirect(['dirit/pergantian','id'=>$id]);
            }
        }

        return $this->render('pergantian', [
            'sql' => $sql,
            'model' => $model,
            'ModJdwl' => $ModJdwl,
            'ModG' => $ModG,
            'Msg' => $msg,
            'Detail' => $Detail,
        ]);

    }

#==

#== method personal dosen ==#
    public function P_uMdosen($id){
        $id_dosen = Yii::$app->user->identity->id;
        $test= Yii::$app->authManager->checkAccess($id_dosen,'akses_dosen');
        $mJdwl=MAbsenDosen::findOne($id);
        if ($test) {
            $jadwal = Jadwal::findOne($mJdwl->jdwl_id);
            if ($jadwal->bn->ds->ds_user!==Yii::$app->user->identity->username){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
//        die($test);

        $db = Yii::$app->db1;
        $Uid=Yii::$app->user->identity->id;
        $keuangan 	= Funct::getDsnAttribute('dbname', $db->dsn);
        if(!$keuangan){$keuangan 	= Funct::getDsnAttribute('Database', $db->dsn);}

        if(isset($_POST['tf']) and isset($_POST['dsn_update'])){

            $stat=(int)$_POST['tf']['st'];
            $dsfid=(int)$_POST['tf']['ds'];
            $update ="
                UPDATE ad SET 
                    ad.ds_get_id      = ISNULL(t.ds_id,isnull(ad.ds_get_id,ad.ds_id)),
                    ad.ds_get_fid     = iif(t.ds_id is null,ad.ds_get_fid,t.fid),
                    ad.ds_stat='$stat',
					ad.utgl=getdate(),
					ad.uuid=$Uid					
                from m_absen_dosen ad
                LEFT JOIN (
                    SELECT '".$mJdwl->GKode_."' k, u.fid,d.ds_id 
                    FROM tbl_dosen d 
                    INNER JOIN user_ u on(u.username=d.ds_user and u.fid='$dsfid')
                ) t on(t.k=ad.GKode_)
                WHERE ad.sesi='$mJdwl->sesi' and ad.GKode_='".$mJdwl->GKode_."'" and d.username;

            Yii::$app->db->createCommand($update)->execute();
            return $this->redirect(['detail-perkuliahan','id'=>$id]);
        }

        if(isset($_POST['hadir'])){
            foreach($_POST['hadir']['abs'] as $k=>$v){
                $ket = Html::encode($_POST['hadir']['ket'][$k]);
                $up= " 
					update m_transaksi_finger set 
					mhs_stat='$v', ket='$ket'
					--,mhs_masuk=iif('$v'='1',jdwl_masuk,NULL),
					--,mhs_keluar=iif('$v'='1',jdwl_keluar,NULL)
				where jdwl_id in(
				select jdwl_id from tbl_jadwal where (jdwl_id ='$mJdwl->jdwl_id' or GKode='".$mJdwl->jdwl->GKode."')
				) and sesi='$s'
				and krs_id=$k";
                Yii::$app->db->createCommand($up)->execute();
            }
            return $this->redirect(['/perkuliahan/detail-perkuliahan','id' =>$id,'s'=>$s]);
        }

        #$mhs="exec absensesi '".($mJdwl->jdwl->GKode?:$mJdwl->jdwl_id)."','$s'"; #lemot bro
        $mhs="
            SELECT p.Nama,t.* from(
                SELECT DISTINCT 
                jd.jdwl_id,jd.jdwl_kls, a.id, a.mhs_nim,a.krs_id
                ,CAST(a.mhs_masuk as VARCHAR(5)) mhs_masuk,CAST(a.mhs_keluar as VARCHAR(5)) mhs_keluar
                ,iif(ad.ds_stat='1',mhs_stat,0) stat,isnull(datediff(minute,a.mhs_masuk,a.mhs_keluar),0) durasi_mhs,a.ket
                ,a.kode
                ,mk.mtk_kode,mk.mtk_nama
                from tbl_krs krs
                INNER JOIN tbl_jadwal jd on(jd.jdwl_id=krs.jdwl_id and isnull(jd.GKode,cast(jd.jdwl_id as VARCHAR(30)))='".($mJdwl->jdwl->GKode?:$mJdwl->jdwl_id)."' and isnull(jd.RStat,0)=0)
                -- join ke table absen jadwal yg asli
                INNER JOIN tbl_bobot_nilai bn on( bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
                INNER JOIN tbl_matkul mk on( mk.mtk_kode=bn.mtk_kode and isnull(mk.RStat,0)=0)
                INNER JOIN m_absen_mhs a on(a.krs_id=krs.krs_id and a.sesi='$mJdwl->sesi')
                INNER JOIN m_absen_dosen ad on(ad.jdwl_id=krs.jdwl_id and ad.sesi='$mJdwl->sesi')
                WHERE isnull(krs.RStat,0)=0 and krs.krs_stat=1
            ) t 
            inner join $keuangan.dbo.student s on (s.nim COLLATE Latin1_General_CI_AS = t.mhs_nim)
            inner join $keuangan.dbo.people p on ( p.No_Registrasi = s.no_registrasi)		
            ORDER BY t.jdwl_id,t.mhs_nim
        ";

        $mhs = Yii::$app->db->createCommand($mhs)->queryAll();

        return ['mJdwl' => $mJdwl,
            'mhs' => $mhs,
            'id' => $id,
            'u' => $u,
            'Sesi' => $mJdwl->sesi,];
//        return $this->render('/perkuliahan/absen/finger_sesi_dosen', [
//            'mJdwl' => $mJdwl,
//            'mhs' => $mhs,
//            'id' => $id,
//            'u' => $u,
//            'Sesi' => $mJdwl->sesi,
//
//        ]);

    }

#== ==#

    public function daftarPergantian(){
		$date=date('Y-m-d');		
        $searchModel = new \app\models\PerkuliahanSearch;
        $dataProvider = $searchModel->pergantian(Yii::$app->request->getQueryParams(),$date,"(
			select count(*) from tbl_krs 
			where jdwl_id=tbl_jadwal.jdwl_id
			and isnull(RStat ,0)=0 )>0");

        return $this->render('jdw_pergantian', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
		
	}

	public function masterAbsenDosen(){

        $searchModel = new \app\models\MAbsenDosenSearch;
        $dataProvider = $searchModel->master(Yii::$app->request->getQueryParams());

        return $this->render('/perkuliahan/absen/master_dosen', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

	public function masterperkuliahan(){
		
        $searchModel = new \app\models\MasterSearch;
		$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),'','1=0');
		if( isset($_GET['MasterSearch']['kr_kode']) && !empty($_GET['MasterSearch']['kr_kode'])){
			$krkd=(int)$_GET['MasterSearch']['kr_kode'];
			$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),$krkd);
		}
        
        return $this->render('master_perkuliahan', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
		
	}

    public function uMabsDsn($id,$u){
        $mJdwl=MAbsenDosen::findOne($id);
        $db = Yii::$app->db1;
        $Uid=Yii::$app->user->identity->id;
        $keuangan 	= Funct::getDsnAttribute('dbname', $db->dsn);
        if(!$keuangan){$keuangan 	= Funct::getDsnAttribute('Database', $db->dsn);}

        if(isset($_POST['tf']) and isset($_POST['dsn_update'])){

            $stat=(int)$_POST['tf']['st'];
            $dsfid=(int)$_POST['tf']['ds'];
            $update ="
                UPDATE ad SET 
                    ad.ds_get_id      = ISNULL(t.ds_id,isnull(ad.ds_get_id,ad.ds_id)),
                    ad.ds_get_fid     = iif(t.ds_id is null,ad.ds_get_fid,t.fid),
                    ad.ds_stat='$stat',
					ad.utgl=getdate(),
					ad.uuid=$Uid					
                from m_absen_dosen ad
                LEFT JOIN (
                    SELECT '".$mJdwl->GKode_."' k, u.fid,d.ds_id 
                    FROM tbl_dosen d 
                    INNER JOIN user_ u on(u.username=d.ds_user and u.fid='$dsfid')
                ) t on(t.k=ad.GKode_)
                WHERE ad.sesi='$mJdwl->sesi' and ad.GKode_='".$mJdwl->GKode_."'";

            Yii::$app->db->createCommand($update)->execute();
            return $this->redirect(['detail-perkuliahan','id'=>$id]);
        }

        if(isset($_POST['hadir'])){
            foreach($_POST['hadir']['abs'] as $k=>$v){
                $ket = Html::encode($_POST['hadir']['ket'][$k]);
                $up= " 
					update m_transaksi_finger set 
					mhs_stat='$v', ket='$ket'
					--,mhs_masuk=iif('$v'='1',jdwl_masuk,NULL),
					--,mhs_keluar=iif('$v'='1',jdwl_keluar,NULL)
				where jdwl_id in(
				select jdwl_id from tbl_jadwal where (jdwl_id ='$mJdwl->jdwl_id' or GKode='".$mJdwl->jdwl->GKode."')
				) and sesi='$s'
				and krs_id=$k";
                Yii::$app->db->createCommand($up)->execute();
            }
            return $this->redirect(['/perkuliahan/detail-perkuliahan','id' =>$id,'s'=>$s]);
        }

        #$mhs="exec absensesi '".($mJdwl->jdwl->GKode?:$mJdwl->jdwl_id)."','$s'"; #lemot bro
        $mhs="
            SELECT p.Nama,t.* from(
                SELECT DISTINCT 
                jd.jdwl_id,jd.jdwl_kls, a.id, a.mhs_nim,a.krs_id
                ,CAST(a.mhs_masuk as VARCHAR(5)) mhs_masuk,CAST(a.mhs_keluar as VARCHAR(5)) mhs_keluar
                ,iif(ad.ds_stat='1',mhs_stat,0) stat,isnull(datediff(minute,a.mhs_masuk,a.mhs_keluar),0) durasi_mhs,a.ket
                ,a.kode
                ,mk.mtk_kode,mk.mtk_nama
                from tbl_krs krs
                INNER JOIN tbl_jadwal jd on(jd.jdwl_id=krs.jdwl_id and isnull(jd.GKode,cast(jd.jdwl_id as VARCHAR(30)))='".($mJdwl->jdwl->GKode?:$mJdwl->jdwl_id)."' and isnull(jd.RStat,0)=0)
                -- join ke table absen jadwal yg asli
                INNER JOIN tbl_bobot_nilai bn on( bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
                INNER JOIN tbl_matkul mk on( mk.mtk_kode=bn.mtk_kode and isnull(mk.RStat,0)=0)
                INNER JOIN m_absen_mhs a on(a.krs_id=krs.krs_id and a.sesi='$mJdwl->sesi')
                INNER JOIN m_absen_dosen ad on(ad.jdwl_id=krs.jdwl_id and ad.sesi='$mJdwl->sesi')
                WHERE isnull(krs.RStat,0)=0 and krs.krs_stat=1
            ) t 
            inner join $keuangan.dbo.student s on (s.nim COLLATE Latin1_General_CI_AS = t.mhs_nim)
            inner join $keuangan.dbo.people p on ( p.No_Registrasi = s.no_registrasi)		
            ORDER BY t.jdwl_id,t.mhs_nim
        ";

        $mhs = Yii::$app->db->createCommand($mhs)->queryAll();

        return $this->render('/perkuliahan/absen/finger_sesi', [
            'mJdwl' => $mJdwl,
            'mhs' => $mhs,
            'id' => $id,
            'u' => $u,
            'Sesi' => $mJdwl->sesi,

        ]);
    }

    public function dmasterperkuliahan($id,$s,$u){
		$mJdwl=Jadwal::findOne($id);
 		$db = Yii::$app->db1;
		$Uid=Yii::$app->user->identity->id;
		$keuangan 	= Funct::getDsnAttribute('dbname', $db->dsn);		
		if(!$keuangan){$keuangan 	= Funct::getDsnAttribute('Database', $db->dsn);}
		
		if(isset($_POST['tf']) and isset($_POST['dsn_update'])){
			
			$stat=(int)$_POST['tf']['st'];
			$dsfid=(int)$_POST['tf']['ds'];
			$update =" 
				update m_transaksi_finger set 
					ds_get_fid=".($dsfid||$dsfid>0?$dsfid:'ds_fid').",
					ds_stat='$stat',
					mhs_stat='$stat',
					tgl=isnull(tgl,tgl_ins),
					tgl_u=getdate(),
					user_u=$Uid					
				where jdwl_id in(
				select jdwl_id from tbl_jadwal where (jdwl_id ='$mJdwl->jdwl_id' or GKode='$mJdwl->GKode')
				) and sesi='$s'";
			Yii::$app->db->createCommand($update)->execute();
			return $this->redirect(['detail-perkuliahan','id' =>$id,'s'=>$s]);
		}

		if(isset($_POST['hadir'])){
			foreach($_POST['hadir']['abs'] as $k=>$v){
				$ket = Html::encode($_POST['hadir']['ket'][$k]);
				$up= " 
					update m_transaksi_finger set 
					mhs_stat='$v', ket='$ket'
					--,mhs_masuk=iif('$v'='1',jdwl_masuk,NULL),
					--,mhs_keluar=iif('$v'='1',jdwl_keluar,NULL)
				where jdwl_id in(
				select jdwl_id from tbl_jadwal where (jdwl_id ='$mJdwl->jdwl_id' or GKode='$mJdwl->GKode')
				) and sesi='$s'
				and krs_id=$k";
				Yii::$app->db->createCommand($up)->execute();
			}
			return $this->redirect(['detail-perkuliahan','id' =>$id,'s'=>$s]);
		}
		
		$dosen	="
			select distinct
				tf.jdwl_hari,
				tf.tgl_ins,
				concat(cast(jdwl_masuk as varchar(5)),' - ',cast(jdwl_keluar as varchar(5))) jadwal,
				iif(isnull(ds_stat,0)='1','Hadir','Tidak Hadir') status,
				datediff(minute,jdwl_masuk,jdwl_keluar) durasi,
				t.ds_nm dosen,
				t1.ds_nm pengajar,
				cast(ds_masuk as varchar(5)) ds_masuk,
				cast(ds_keluar as varchar(5)) ds_keluar,
				datediff(minute,ds_masuk,ds_keluar) durasi_dosen
			from m_transaksi_finger tf
			inner join (
				select ds.ds_nm,u.Fid 
				from user_ u inner join	tbl_dosen ds on(ds.ds_user=u.username)
			) t on(t.Fid=tf.ds_fid)
			inner join (
				select  ds.ds_nm,u.Fid 
				from user_ u inner join	tbl_dosen ds on(ds.ds_user=u.username)
				WHERE Fid is not NULL
			) t1 on(t1.Fid=tf.ds_get_fid)
			where jdwl_id in(
			select jdwl_id from tbl_jadwal where (jdwl_id ='$mJdwl->jdwl_id' or GKode='$mJdwl->GKode')
			) and sesi='$m'";
			
		$mhs="
			select 
				tf.ket,
				tf.krs_id,
				concat(tf.mtk_kode,': ',tf.mtk_nama, '(',jd.jdwl_kls,')') matakuliah,
				mhs_nim ,tf.mhs_stat,
				t.Nama,
				iif( isnull(mhs_stat,0)='0' or mhs_stat='','Tidak Hadir','Hadir') status,
				cast(mhs_masuk as varchar(5)) mhs_masuk,
				cast(mhs_keluar as varchar(5)) mhs_keluar,
				datediff(minute,mhs_masuk,mhs_keluar) durasi_mhs
			from m_transaksi_finger tf
			inner join (
				select m.mhs_nim, p.Nama, u.Fid 
				from user_ u 
				inner join tbl_mahasiswa m on(m.mhs_nim=u.username)
				inner join $keuangan.dbo.student s on (s.nim COLLATE Latin1_General_CI_AS = m.mhs_nim)
				inner join $keuangan.dbo.people p on ( p.No_Registrasi = s.no_registrasi )		
			) t on(t.Fid=tf.mhs_fid)
			inner join tbl_jadwal jd on(
				jd.jdwl_id=tf.jdwl_id and isnull(jd.RStat,0)=0 
				and	(jd.jdwl_id ='$mJdwl->jdwl_id' or GKode='$mJdwl->GKode')
			)
			inner join tbl_bobot_nilai bn on(bn.id=jd.bn_id and isnull(bn.RStat,0)=0 and bn.ds_nidn='".$mJdwl->bn->ds_nidn."')
			where  tf.sesi='$s'
			and tf.krs_stat is not null
			order by tf.jdwl_id,tf.mtk_kode, t.mhs_nim
			";

		$dosen = Yii::$app->db->createCommand($dosen)->queryOne();
		$mhs = Yii::$app->db->createCommand($mhs)->queryAll();
		return $this->render('master_view', [

            'mJdwl' => $mJdwl,
			'dosen' => $dosen,
			'mhs' => $mhs,
			'id' => $id,
			'u' => $u,
			'Sesi' => $s,
			
        ]);
    }

	public function TanggalKuliah($kr='',$id=''){
		$kr=(int) $kr;
		if($id){$id=(int) $id;}
		
		$sql = "exec TglPerkuliahanAll '$kr'";
		$queFinger="SELECT DISTINCT tgl_ins from m_transaksi_finger tf INNER JOIN tbl_krs krs on(krs.krs_id=tf.krs_id and isnull(krs.RStat,0)=0 AND krs.kr_kode_='$kr')";
		$sql = Yii::$app->db->createCommand($sql)->queryAll();
		$queFinger= Yii::$app->db->createCommand($queFinger)->queryAll();
		$FT=[];$qMtf="";$getTgl="";
		foreach($queFinger as $d){$FT[$d['tgl_ins']]=1;}
		$tbl='<table class="table table-bordered"><thead><tr><th>No</th><th>Hari</th><th>Tanggal</th></tr></thead><tbody> ';
		foreach($sql as $d){
			$FT_=$FT[$d['Dates']];
			$TGL_=explode('-',$d['Dates']);
			$TGL_=implode('_',$TGL_);
			
			$tanggal=Funct::TANGGAL($d['Dates']);
			
			if($d['Dates']==date('Y-m-d')){
				break;
			}else{
				$SetMft=0;$qMtf="";
				if(!$FT_){
					$qMtf="SELECT OBJECT_ID('transaksi_finger.dbo.tf_$TGL_', 'U') id";
					$qMtf=Yii::$app->db->createCommand($qMtf)->queryOne();
					if($qMtf['id']){
						if($id==$d['id']){$getTgl=$d['Dates'];}
						$SetMft=1;
					}
				}
				
				$tbl.=
				'<tr style="'.($FT_?'background:green;font-weight:bold;color:#fff;':"").'" id="t'.$d['id'].'">
					<td width="1%">'.$d['id'].'</td>
					<td>'.Funct::getHari()[$d['h']].'</td>
					<td>'.($SetMft?Html::a('<i class="fa fa-upload"> </i> '.$tanggal,['/dirit/tanggal-kuliah','kr[kr]'=>$kr,'id'=>$d['id']],['class'=>'btn btn-primary']):"$tanggal").'</td>
				</tr>';			
			}
			
			//echo $d['id']." $d[dates]<br />";
		}
		if($getTgl){
			Yii::$app->db->createCommand("exec dbo.insMtransFinger_v3 '$getTgl'")->execute();
			$this->redirect(['dirit/tanggal-kuliah','kr[kr]'=>$kr,'#'=>'t'.$id]);
			
		}
		
		$tbl.='</tbody></table> ';
		
		return $this->render('/dirit/tanggal',[
			'tbl'=>$tbl
			
		]);
	
	}

	public function index(){
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),"(
			select count(*) from tbl_krs 
			where jdwl_id=tbl_jadwal.jdwl_id
			and isnull(RStat ,0)=0 )>0");
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
				'destination'=>'I',
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
	
    public function absenharianpdf($id,$jenis=1){
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
            $keuangan = Funct::getDsnAttribute('dbname', $db->dsn);
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
					'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'||'.date('r').' - Page {PAGENO}'],
					//'SetWatermakText' =>"asd",
					//'ShowWatermarkText'=>true,
				]
			]);
			
			return $pdf->render();        
    }

    public function absenhariandarurat($id,$jenis=1){
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
            $keuangan = Funct::getDsnAttribute('dbname', $db->dsn);
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
			
			$content = $this->renderPartial('cetak_absen',[
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
					'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'||'.date('r').' - Page {PAGENO}'],
					//'SetWatermakText' =>"asd",
					//'ShowWatermarkText'=>true,
				]
			]);
			
			return $pdf->render();        
    }

	public function cekpergantian($id){
        $model = Jadwal::findOne($id);
        $ModBn = \app\models\BobotNilai::findOne($model->bn_id);
		$Q="";

        if ($model->load(Yii::$app->request->post())) {
			$vMasuk	 = $model->jdwl_masuk;
			$vKeluar = $model->jdwl_keluar;
			$vHari 	 = $model->jdwl_hari;
			$vHari   = date('N',strtotime($model->jdwl_hari));
			
			$thn 	 = $ModBn->kln->kr_kode;
			
			if(strlen($vMasuk)==5){
				$vMasuk = $vHari.substr($vMasuk,0,2).substr($vMasuk,3,2);
				if(!is_numeric($vMasuk)){$vMasuk="";}
			}
			if(strlen($vKeluar)==5){
				$vKeluar = $vHari.substr($vKeluar,0,2).substr($vKeluar,3,2);
				if(!is_numeric($vKeluar)){$vKeluar="";}
			}
			if($vMasuk!=''&&$vKeluar!=''){
				$cekPergantian	=" select * from  dbo.bentrokpergantian($id,'$model->jdwl_hari','$model->jdwl_masuk','$model->jdwl_keluar',default)";
				$Q=Yii::$app->db->createCommand($cekPergantian)->QueryAll();
			}				
        }
		
		return $this->render('ajr_jdw', [
			'dataProvider' => $dataProvider,
			'dataProvider2' => $dataProvider2,
			'dataProvider3' => $dataProvider3,
			'model'=>$model,
			'ModBn'=>$ModBn,
			'Q' => $Q,
			'searchModel' => $searchModel,
			'id'=>$id,
		]);
		 
	}

    public function peserta($id){
        $model= Jadwal::findOne($id);
		$searchModel = new KrsSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),['jdwl_id'=>$id]);
        return $this->render('jdw_detail',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model' => $model,
        ]);
    }

    public function PesertaKuliah($id,$m){

		$MaxAbsen=30;	
        $model   = \app\models\JadwalDosen::findOne(['jdwl_id'=>$id]);
		$tbl='transaksi_finger';
		
        $AbsDos="
        select 
            max(sesi) sesi,
			max(ds_get_fid) fid,
			tgl_ins tgl_absen,(DATEPART(dw,tgl_ins)-1) jdwl_hari, max(ds_masuk) masuk,max(ds_keluar) keluar,
            iif(
                isnull(max(ds_get_fid),max(ds_fid))
                =max(ds_fid),1,0
            ) m,
			isnull(datediff(minute,max(jdwl_masuk),max(ds_masuk)),0) MaxAbsn,
			max(ds_stat) ds_stat
        	-- isnull(datediff(minute,max(ds_masuk),max(ds_keluar)),0) m		
        from $tbl where jdwl_id='".$model->jdwl_id."' and left(jdwl_masuk,2)='".$m."'
        GROUP by tgl_ins, jdwl_hari
        ";
		
		$AbsDos=Yii::$app->db->createCommand($AbsDos)->queryOne();
		//echo $model->bn->ds_nidn;
		$ketDsn="";
		if($AbsDos['MaxAbsn']>0){$ketDsn="Terlambat $AbsDos[MaxAbsn] Menit. ";}
		if($AbsDos['ds_stat']==='2'){$ketDsn="Belum Saatnya Pulang. ";}
		
        $table ='
		<table class="table table-bordered">
				<tr>
					<th rowspan="3"> (Pertemuan Ke. '.$AbsDos['sesi'].')<br />
						'.$model->bn->ds->ds_nm.' <br />'.$model->bn->mtk_kode.' - '.$model->bn->mtk->mtk_nama.'( '.$model->jdwl_kls.' )
					</th>
				</tr>
				<tr style="background:none">
					<th>Jadwal</th>
					<th>'.$model->jdwl_masuk.' - '.$model->jdwl_keluar.'</th>
				</tr>
				<tr>
					<th>Absen</th>
					<th>
					'.($AbsDos['masuk'] ? substr($AbsDos['masuk'],0,5):"??:??").' - '.($AbsDos['keluar']?substr($AbsDos['keluar'],0,5):"??:??")
					.'</th>
				</tr>'.($ketDsn!=''?'<tr><td colspan="3" align="right"><span style="color:red"><b>['.$ketDsn.']</b></span></td></tr>':'').'
			</table>';
			$table.="
			
		
			<table class='table table-bordered table-hover'>
				<thead>
				<tr>
				<th style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;' width='1%'>No</th>
				<th style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;' width='1%'>KODE</th>
				<th style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>(NPM) NAMA</th>
				<th style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>Masuk|Keluar</th>
				<th style='background:".(!$AbsDos['fid']||$AbsDos['MaxAbsn']>$MaxAbsen||!$AbsDos['masuk']?'red':'green')."'>Sesi ".$AbsDos['sesi']."</th>
				<th style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>Ket</th>
				</tr></thead>";

        if ($model) {     
        $db = Yii::$app->db1;
        $keuangan = Funct::getDsnAttribute('dbname', $db->dsn);       
		if(!$keuangan){$keuangan = Funct::getDsnAttribute('Database', $db->dsn);}
       $query1 ="
			SELECT a.id,k.mhs_nim,p.Nama, k.jdwl_id,k.krs_id,a.sesi,mhs_masuk masuk,mhs_keluar keluar,a.mhs_stat jdwl_stat,
				datediff(minute,dateadd(minute,15,ds_masuk),mhs_masuk) toleransi
				,a.ds_stat
				,bn.mtk_kode,j.jdwl_kls	
			FROM $tbl a
			INNER JOIN tbl_krs k on(k.krs_id=a.krs_id AND ISNULL(k.RStat,0)=0 AND k.krs_stat ='1')
			INNER JOIN tbl_jadwal j on(j.jdwl_id=k.jdwl_id AND ISNULL(j.RStat,0)=0)
			INNER JOIN tbl_bobot_nilai bn on(bn.id=j.bn_id and ISNULL(bn.RStat,0)=0)
			LEFT JOIN $keuangan.dbo.student st ON (st.nim COLLATE Latin1_General_CI_AS = k.mhs_nim)
			LEFT JOIN $keuangan.dbo.people p ON (p.No_Registrasi = st.no_registrasi)
			where a.jdwl_id in(
				select jdwl_id from tbl_jadwal where (jdwl_id ='$model->jdwl_id' or GKode='$model->GKode')
			)
			and left(a.jdwl_masuk,2)='".$m."'
			order by j.jdwl_id
		   ";
		   
        $data = Yii::$app->db->createCommand($query1)->queryAll();
        $no=1;
		$mTot=0;
		$mHdTot=0;
		$value='';
        foreach ($data as $key) {
			
            $table .= "<tr>
			<td style='font-weight: bold; font-size: 14px;'>".$no++."</td>
			<td><span class='label label-".($key['jdwl_id']==$id?"success":"primary")."' style='font-size:14px'>$key[mtk_kode] ($key[jdwl_kls]) </span></td>
			<td style='font-weight: bold; font-size: 14px;'>($key[mhs_nim]) $key[Nama]</td>";
			$attribute = '';
			if($AbsDos['fid']){
				$attribute = 'data-kode="'.$key['id'].'"';
			}
			
			$mStat = $key['jdwl_stat'];
			$masuk =  $key['masuk'];//($mStat==4?null:$key['masuk']);
			$keluar = $key['keluar'];//( $mStat &&($mStat==4)?null:$key['keluar']);
			//$mStat =($mStat==4?null:$mStat);
			$mKeluar=($keluar?substr($keluar,0,5):"--:--");
			$k="a$key[id]";

			$ket='';
			if($mStat==='4'){
				$ket='Finger Sebelum Dosen. ';/* if($key['masuk']){$ket='Perkuliahan Belum di Buka. ';if($key['keluar']){$ket='Perkuliahan Belum di Tutup. ';}}*/
			}
			
			if($mStat==='2'){
				$ket='Dosen Belum Menutup Perkuliahan. ';
			}
			
			if(!$masuk){
				$value='';
				$mMasuk = "<span style='font-size: 12px;'> - </span>";
			}else{
				if($mStat==='4' || $mStat==='2' ){
					$mMasuk = '<span class=" label label-danger" style="font-size:12px"><b style="color:#000">'.substr($masuk,0,5)." | $mKeluar</b></span>";
				}else{
					$mMasuk = '<span class=" label label-success"><b style="color:#000" id="'.$k.'">'.substr($masuk,0,5)." | $mKeluar</b></span>";
				}
				
				$mTot++;
				
				$toleran =$key['toleransi'];
				if($mStat==='0'){
					$mMasuk = '<a href="'.Url::to(['save-absensi-kuliah-v2','id'=>$key['id'],'s'=>'0','#'=>"$k"]).'" class=" btn btn-danger"><b style="color:#000" id="'.$k.'">'.substr($masuk,0,5)." | $mKeluar</b></a>";
				}
						
				if($key['ds_stat']==='1'){
					$value='';
					$value = '<a href="'.Url::to(['save-absensi-kuliah-v2','id'=>$key['id'],'s'=>'1','f'=>1]).'" class="glyphicon glyphicon-remove-circle" style="color:red;"></a>';
					$mMasuk = '<span class=" label label-danger" style="font-size:12px"><b style="color:#000">'.substr($masuk,0,5)." | $mKeluar</b></span>";
					if($mStat==='1'||$mStat==='2'){
						$mHdTot++;
						$mMasuk = '<span class=" label label-success" style="font-size:12px"><b style="color:#000">'.substr($masuk,0,5)." | $mKeluar</b></span>";
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
					}else{
						if($mStat==='0'){$ket="Kehadiran Dibatalkan.";}						
					}
				}
				if ($mStat!=='0') {$arrTot++;}				
				if($AbsDos['MaxAbsn']>$MaxAbsen){
					$mMasuk = '<span class=" label label-danger" style="font-size:12px"><b style="color:#000">'.substr($masuk,0,5)." | $mKeluar</b></span>";
				}
			}
			$table  .="<td style='text-align:center;'>$mMasuk</td>";
			$table  .="<td style='text-align:center;'>$value</td>";                            
			$table  .="<td style='text-align:center;'> ".($key['toleransi'] > 0?"Terlambat $toleran menit dari toleransi. ":"")." $ket </td>";                            
            $table  .="</tr>";
        }

        $table .= "
			<tr style='font-weight:bold;text-align:right;'><th colspan='2'>TOTAL</th><th>$mTot</th>
			<th>$mHdTot</th><th> </th>
			</tr>
		</table>";
		
        return $this->render('absensi_kuliah_v2', [   
            'table' => $table,     
        ]);
         
         }
    
    }

	public function pergantian($id){
		$usrId=Yii::$app->user->identity->id;
	
		$model		= Jadwal::findOne($id);
		$ModJdwl	= Jadwal::find()
		->innerJoin("tbl_bobot_nilai bn","( bn.id=tbl_jadwal.bn_id and isnull(bn.RStat,0)=0 and bn.ds_nidn='".$model->bn->ds_nidn."' and dbo.cekIgMk(bn.mtk_kode)=0)")
		->where("isnull(tbl_jadwal.RStat,0)=0 and (jdwl_id=$model->jdwl_id or GKode='$model->GKode')")
		->all();
		
		$user		= " select  fid from user_ where username='".$model->bn->ds->ds_user."' and fid is not null";
		$user=Yii::$app->db->createCommand($user)->QueryOne();

		$Detail="exec dbo.detailPerkuiahan $id";	
		$Detail=Yii::$app->db->createCommand($Detail)->QueryAll();

		$id=abs($id);
		$sql = "
			select  t.*,t1.sesi,t1.tgl ftgl,ds_stat from dbo.sesijadwal($id) t
			left join (
				SELECT DISTINCT 
					cast(sesi as varchar(2))sesi,min(tgl) tgl ,max(isnull(ds_stat,0)) ds_stat
				FROM m_transaksi_finger 
				WHERE jdwl_id='$id'
				GROUP BY sesi
			) t1 on(t1.sesi=t.s)
			where isnull(ds_stat,0)!=1			
		";
		$sql=Yii::$app->db->createCommand($sql)->queryAll();

		$msg="";
		
		if($_POST['G']){

			$sesi	= $_POST['G']['sesi'];
			$tgl	= $_POST['G']['tgl'];
			$masuk	= $_POST['G']['masuk'];
			$keluar	= $_POST['G']['keluar'];
			
			$tglAwal = " select  tgl from dbo.sesijadwal($id) where s='$sesi'";
			$tglAwal = Yii::$app->db->createCommand($tglAwal)->QueryOne();
			
			$Qv = " select *from(
				select datediff(minute,'$masuk','$keluar')d, datediff(day,getdate(),'$tgl') t,
				datepart(dw,'$tgl') h ) t
				";
			$Qv=Yii::$app->db->createCommand($Qv)->QueryOne();
			 if($Qv['h']==1){
			 	$msg="Perkuliahan Tidak Bisa Dilakukan Dihari minggu";	
			 }else{
			 	if($Qv['t'] > 0 ){
			 		if($Qv['d'] < 0 ){
			 			$msg="Jam Masuk Melebihi Jam Keluar";
			 		}
			 	}else{
			 		$msg="Salah Memilih Tanggal $tgl";
			 		if($Qv['t']==0){$msg="Pergantian Jadwal Tidak Bisa Dilakukan Dihari ini";}
			 	}
			 }

			$cekPergantian	=" select * from  dbo.bentrokpergantian($id,'$tgl','$masuk','$keluar','$sesi')";
			$cekPergantian =Yii::$app->db->createCommand($cekPergantian)->QueryAll();


			if($cekPergantian){
				$msg="Jadwal Bentrok";				
			}
			
			if($msg==""){
				$q="
				INSERT INTO t_finger_pengganti(
					krs_id,krs_stat,ds_fid,mtk_kode,mtk_nama,jdwl_id
					,jdwl_hari,jdwl_masuk,jdwl_keluar,mhs_fid,tgl_ins
					,tgl,sesi
					--,ucreate
				)
				SELECT
					krs.krs_id,krs.krs_stat,$user[fid],mk.mtk_kode,mk.mtk_nama,
					jdw.jdwl_id,
					(DATEPART(dw,'$tgl')-1),
					'$masuk','$keluar',
					mhs.Fid,CAST('$tgl' as DATE)
					,'$tglAwal[tgl]'
					,'$sesi'
					--,'$usrId'
				from tbl_jadwal jdw
				INNER JOIN tbl_bobot_nilai bn on(bn.id=jdw.bn_id and(bn.RStat is NULL or bn.RStat='0'))
				INNER JOIN tbl_dosen ds on(ds.ds_id=bn.ds_nidn and ds.ds_id='".$model->bn->ds_nidn."')
				INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and(mk.RStat is NULL or mk.RStat='0'))
				INNER JOIN tbl_kalender kln on(
					kln.kln_id=bn.kln_id and(kln.RStat is NULL or kln.RStat='0')
					and(
						GETDATE()  
						BETWEEN kln_masuk 
						and DATEADD(day,kln.kln_uas_lama, kln.kln_uas)
				 )
				)
				INNER JOIN tbl_krs krs on(krs.jdwl_id=jdw.jdwl_id and(krs.RStat is NULL or krs.RStat='0'))
				INNER JOIN (
					SELECT m.mhs_nim, u.Fid from user_ u INNER JOIN tbl_mahasiswa m on(m.mhs_nim=u.username and u.tipe='5')
					WHERE (m.RStat is NULL or m.RStat='0')
				)mhs on(mhs.mhs_nim=krs.mhs_nim)
				WHERE NOT EXISTS(SELECT * FROM t_finger_pengganti tf WHERE tf.krs_id=krs.krs_id and tf.sesi='$sesi')
				AND dbo.cekIgMk(mk.mtk_kode)=0
				and jdw.jdwl_kls not in('j','R1','R2')
				and jdw.jdwl_hari = $model->jdwl_hari
				and (jdw.GKode='$model->GKode' or jdw.jdwl_id='$model->jdwl_id')			 			
				";

				$q=Yii::$app->db->createCommand($q)->execute();
				return $this->redirect(['dirit/pergantian','id'=>$id]);					
			}
		}

        return $this->render('pergantian', [   
            'sql' => $sql,
            'model' => $model,
            'ModJdwl' => $ModJdwl,
            'ModG' => $ModG,
            'Msg' => $msg,
            'Detail' => $Detail,
        ]);

	}
	
	public function pergantiandel($id,$s,$d){
			$d=(int) $d;
			if($d==1){
				$jd = Jadwal::findOne($id);
				$Pergantian="
					delete from t_finger_pengganti
					where sesi='$s' and sesi is not null
					and jdwl_id in( select jdwl_id from tbl_jadwal where GKode='$jd->GKode')
				";
				//($Pergantian);
				$Pergantian=Yii::$app->db->createCommand($Pergantian)->execute();
				
			}
			return $this->redirect(['dirit/pergantian','id'=>$id]);
	}

	public function resendPergantian(){
		$q	=	"exec dbo.insFingerPengganti_v2";
		$q	=	Yii::$app->db->createCommand($q)->execute();
		return $this->redirect(['dirit/jadwal-pergantian']);
	}

    public function PersensiJadwalDosen(){

        $kon='';
        $krKode=0;
        $mode=0;
        $render='kehadiran';
        $JR='';
        if(isset($_GET['Thn'])){
            if(!empty($_GET['Thn']['thn'])){
                $krKode=(int)$_GET['Thn']['thn'];
                $kon=" ,'Where t.thn=".$krKode."'";
            }

            if(!empty($_GET['Thn']['jr'])){$JR=$_GET['Thn']['jr'];}
            $mode = (int)$_GET['Thn']['mode'];
        }


        if($mode==1){
            $render='kehadiran_waktu';
        }

        $Id		= Yii::$app->user->identity->username;
        $QueTahun="select * from tbl_kalender kln, tbl_kurikulum kr 
			where kln.kr_kode=kr.kr_kode
			and kln.kr_kode='".$krKode."'
			and (kln.RStat is null or kln.RStat='0')
		";

        $Qthn=Yii::$app->db->createCommand($QueTahun)->queryOne();
        $QueKuliah="EXEC pvt_hdrdsn '".$JR."','$krKode'";
        //echo $QueKuliah;
        if($krKode>0){$QueKuliah=Yii::$app->db->createCommand($QueKuliah)->queryAll(/*\PDO::FETCH_NUM*/);}
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),['jr.jr_id'=>$JR]);

        return $this->render($render, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'QueKuliah'=>$QueKuliah,
            'Qthn'=>$Qthn,
            'Gthn'=>$_GET['Thn'],
            //'HDR'=>$This->Hadir($id),
            'KrKd'=>$krKode,
            'subAkses'=>self::sub(),


        ]);
    }

    public function KehadiranDosen(){
		
		$kon='';
		$krKode=0;
		$mode=0;
		$render='kehadiran';
		$JR='';
		if(isset($_GET['Thn'])){
			if(!empty($_GET['Thn']['thn'])){
				$krKode=(int)$_GET['Thn']['thn'];
				$kon=" ,'Where t.thn=".$krKode."'";
			}

			if(!empty($_GET['Thn']['jr'])){$JR=$_GET['Thn']['jr'];}
			$mode = (int)$_GET['Thn']['mode'];
		}
		
		
		if($mode==1){
			$render='kehadiran_waktu';
		}

		$Id		= Yii::$app->user->identity->username;
		$QueTahun="select * from tbl_kalender kln, tbl_kurikulum kr 
			where kln.kr_kode=kr.kr_kode
			and kln.kr_kode='".$krKode."'
			and (kln.RStat is null or kln.RStat='0')
		";
		
		$Qthn=Yii::$app->db->createCommand($QueTahun)->queryOne();
		$QueKuliah="EXEC pvt_hdrdsn '".$JR."','$krKode'";
		//echo $QueKuliah;
		if($krKode>0){$QueKuliah=Yii::$app->db->createCommand($QueKuliah)->queryAll(/*\PDO::FETCH_NUM*/);}
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),['jr.jr_id'=>$JR]);

        return $this->render($render, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
			'QueKuliah'=>$QueKuliah,
			'Qthn'=>$Qthn,
			'Gthn'=>$_GET['Thn'],
			//'HDR'=>$This->Hadir($id),
			'KrKd'=>$krKode,
			'subAkses'=>self::sub(),
			
			
        ]);
    }

    public function cetakKehadiranDosen($kr,$t,$jr)
    {
		$kon='';
		$krKode=(int)$kr;
		if($kr){
			$krKode=(int)$kr;
			$kon=" ,'Where t.thn=".$krKode."'";
		}

		$Id		= Yii::$app->user->identity->username;
		$QueTahun="select * from tbl_kalender kln, tbl_kurikulum kr 
			where kln.kr_kode=kr.kr_kode
			and kln.kr_kode='".$krKode."'
			and (kln.RStat is null or kln.RStat='0')
		";
		
		$Qthn=Yii::$app->db->createCommand($QueTahun)->queryOne();
		
		$QueKuliah="EXEC pvt_hdrdsn '".$jr."','$krKode'";
		if($krKode>0){$QueKuliah=Yii::$app->db->createCommand($QueKuliah)->queryAll(/*\PDO::FETCH_NUM*/);}
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),['jr.jr_id'=>$jr]);


		$content = $this->renderPartial('kehadiran_pdf',[
		'dataProvider' => $dataProvider,
		'searchModel' => $searchModel,
		'QueKuliah'=>$QueKuliah,
		'Qthn'=>$Qthn,
		//'HDR'=>$This->Hadir($id),
		'KrKd'=>$krKode
		]);

		if($t==1){
			$content = $this->renderPartial('kehadiran_waktu_pdf',[
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
			'QueKuliah'=>$QueKuliah,
			'Qthn'=>$Qthn,
			//'HDR'=>$This->Hadir($id),
			'KrKd'=>$krKode
			]);
		}
		

		
		//die($content);
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
			'content' => $content,
			'format'=>Pdf::FORMAT_LETTER,
			'marginLeft'=>5,
			'marginRight'=>5,
			'marginTop'=>18,
			'marginHeader'=>1,
			'orientation'=>'L',
			//'destination'=>'I',
			'cssInline'=>'
			',
			'cssInline' => '
				td,th{
					padding:4px;
					font-size:8pt;
				}
			', 
			'options' => [
				'title' =>'Laporan Kehadiran Perkuliahan '.$kr,
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
					'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'||'.date('r').' - Page {PAGENO}'],
			]
		]);			
		return $pdf->render();

    }

	#==
	public function iMasuk($id,$m){
		$cuid=Yii::$app->user->identity->id;		
		$sql="
			update tf set tf.ds_masuk=isnull(tf1.ds_masuk,cast(getdate() as time(0))),tf.ds_get_fid=tf1.ds_fid,tf.cuid='$cuid',tf.ctgl=getdate()
			from transaksi_finger tf
			inner Join (
				SELECT 
					max(ds_fid) ds_fid,
					MAX(jdwl_masuk) jdwl_masuk, 
					MAX(jdwl_keluar) jdwl_keluar,
					max(ds_masuk) ds_masuk,
					max(ds_keluar) ds_keluar
				from transaksi_finger where jdwl_id=$id
		) tf1 on(tf1.ds_fid=tf.ds_fid and tf1.jdwl_masuk=tf.jdwl_masuk)
		WHERE isnull(tf.RStat,0)=0
		";
		$sql=Yii::$app->db->createCommand($sql)->execute();
		return $this->redirect(['/dirit/peserta-kuliah','id'=>$id,'m'=>$m]);
		
	}
	
	public function iKeluar($id,$m){
		$cuid=Yii::$app->user->identity->id;		
		$sql="
			update tf set tf.ds_masuk=isnull(tf1.ds_masuk,cast(getdate() as time(0))),tf.ds_get_fid=tf1.ds_fid,tf.uuid='$cuid',tf.utgl=getdate()
			from transaksi_finger tf
			inner Join (
				SELECT 
					max(ds_fid) ds_fid,
					MAX(jdwl_masuk) jdwl_masuk, 
					MAX(jdwl_keluar) jdwl_keluar,
					max(ds_masuk) ds_masuk,
					max(ds_keluar) ds_keluar
				from transaksi_finger where jdwl_id=$id
		) tf1 on(tf1.ds_fid=tf.ds_fid and tf1.jdwl_masuk=tf.jdwl_masuk)
		WHERE isnull(tf.RStat,0)=0
		";
		//$sql=Yii::$app->db->createCommand($sql)->execute();
		echo "<pre>";
		print_r($sql);
		echo "</pre>";
		//return $this->redirect(['/dirit/peserta-kuliah','id'=>$id,'m'=>$m]);
		
	}

	public function fixAbsen($id,$m){
		$cuid=Yii::$app->user->identity->id;		
		$sql="
			update tf set 
				tf.mhs_stat=iif(isnull(tf.mhs_stat,0)in(0,1),isnull(tf.mhs_stat,0),iif(isnull(DATEDIFF(MINUTE,tf.ds_keluar,tf.mhs_keluar),-1)<0,0,1))
				,tf.ds_stat=1
				,tf.uuid='$cuid',tf.utgl=getdate()
			from transaksi_finger tf
			inner Join (
				SELECT 
					max(ds_fid) ds_fid,
					MAX(jdwl_masuk) jdwl_masuk, 
					MAX(jdwl_keluar) jdwl_keluar,
					max(ds_masuk) ds_masuk,
					max(ds_keluar) ds_keluar,
					max(ds_stat) ds_stat,
					MIN(ds_stat) ds_stat1
					from transaksi_finger where jdwl_id=$id
			) tf1 on(tf1.ds_fid=tf.ds_fid and tf1.jdwl_masuk=tf.jdwl_masuk)
			WHERE isnull(tf.RStat,0)=0
		";
		
		$ins="
		insert into absen_awal(GKode,jdwl_masuk,jdwl_keluar,tgl,tipe,ds_fid,jdwl_id,cuid,ctgl)
		SELECT *,$cuid,getdate() FROM(
			SELECT DISTINCT
			jd.GKode,
			tf1.jdwl_masuk,tf1.jdwl_keluar,tf.tgl_ins,'2' tipe ,tf1.ds_fid,jd.jdwl_id
			from transaksi_finger tf
			INNER JOIN tbl_jadwal jd on(jd.jdwl_id=tf.jdwl_id)
			inner Join (
				SELECT 
					max(ds_fid) ds_fid,
					MAX(jdwl_masuk) jdwl_masuk, 
					MAX(jdwl_keluar) jdwl_keluar,
					max(ds_masuk) ds_masuk,
					max(ds_keluar) ds_keluar,
					max(ds_stat) ds_stat,
					MIN(ds_stat) ds_stat1
					from transaksi_finger where jdwl_id=17009
			) tf1 on(tf1.ds_fid=tf.ds_fid and tf1.jdwl_masuk=tf.jdwl_masuk)
			WHERE isnull(tf.RStat,0)=0
		)t	
		";
		#/*
		if(Yii::$app->db->createCommand($sql)->execute()){
			Yii::$app->db->createCommand($ins)->execute();
		}
		#*/
		/*
		echo "<pre>";
		print_r($ins);
		echo "</pre>";
		#*/return $this->redirect(['/dirit/peserta-kuliah','id'=>$id,'m'=>$m]);
		
	}

	public function accAbsen($id,$m){
		$cuid=Yii::$app->user->identity->id;		
		$sql="
		update tf set 
			tf.mhs_stat=iif(tf.mhs_masuk is null,null, iif(DATEDIFF(MINUTE, DATEADD(MINUTE, -10, tf.jdwl_masuk),tf.mhs_masuk)<0,4,NULL))
			,tf.ds_stat=NULL,tf.uuid='$cuid',tf.utgl=getdate()
		from transaksi_finger tf
		inner Join (
			SELECT 
				max(ds_fid) ds_fid,
				MAX(jdwl_masuk) jdwl_masuk, 
				MAX(jdwl_keluar) jdwl_keluar,
				max(ds_masuk) ds_masuk,
				max(ds_keluar) ds_keluar,
				max(ds_stat) ds_stat,
				MIN(ds_stat) ds_stat1
				from transaksi_finger where jdwl_id=$id
		) tf1 on(tf1.ds_fid=tf.ds_fid and tf1.jdwl_masuk=tf.jdwl_masuk)
		WHERE isnull(tf.RStat,0)=0
		";
		Yii::$app->db->createCommand($sql)->execute();
		return $this->redirect(['/dirit/peserta-kuliah','id'=>$id,'m'=>$m]);
		
	}
	
	
}
