<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\Mahasiswa;
use mPDF;
use yii\helpers\Html;
use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\helpers\Json;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['tv','logout'],
                'rules' => [
                    [
                        'actions' => ['tv','login'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

	public function actionTabsData() {
		$html = $this->renderPartial('error');
		return Json::encode($html);
	}

    public function actionIndex()
    {

		
        return $this->render('index');
    }

	public function actionPerkuliahan(){
		$Id		= Yii::$app->user->identity->username;
		$ModMhs	=\app\models\Mahasiswa::findOne($Id);
		$QueTahun="select * from tbl_kalender kln, tbl_kurikulum kr 
			where kln.kr_kode=kr.kr_kode
			and (GETDATE()
			BETWEEN kln_masuk and DATEADD(day,kln.kln_uas_lama, kln.kln_uas)
			and (kln.RStat is null or kln.RStat='0'))
			and kln.jr_id='".$ModMhs->jr_id."' and pr_kode='$ModMhs->pr_kode'
		";
		$QueKuliah="select *, concat(dbo.cekHari(hari),' ',masuk,'-',keluar) jadwal from dbo.Perkuliahan ('".Yii::$app->user->identity->Fid."','') order by hari asc";
		$Qthn=Yii::$app->db->createCommand($QueTahun)->queryOne();
		$QueKuliah=Yii::$app->db->createCommand($QueKuliah)->queryAll();
		$data ='<center><b>Perkuliahan Tahun Akademik Semester '."$Qthn[kr_nama] ($Qthn[kr_kode])".'</b> </center><br />';
		$data.='<table class="table table-striped table-hover">
			<thead>
				<tr>
				<th>Jadwal</th>
				<th>Matakuliah</th>
				<th>Dosen</th>
				<th>Kelas</th>
				<th> Lantai | Ruang</th>
				<th>Status</th>
				</tr>
			</thead>
			<tbody>
			';
		foreach($QueKuliah as $d){
			$data .='
				<tr>
					<td>'.$d['jadwal'].'</td>
					<td>'.$d['matakuliah'].'</td>
					<td>'.$d['dosen'].(trim($d['status'])=='Tetap'?'':' (Pengganti)').'</td>
					<td>'.$d['kelas'].'</td>
					<td>'.$d['lantai'].' | '.$d['ruang'].'</td>
					<td>'
					.(trim($d['status'])=='Opened'?
					'Di Mulai'.substr($d['MinMasuk'],1,2).":".substr($d['MinMasuk'],3,2):'-')
					.'</td>
				</tr>';
		}
		$data.="</tbody></table>";
		return Json::encode($data);
	}

	public function actionJadwalDosen(){
		$Id	= Yii::$app->user->identity->username;
		$Fid=Yii::$app->user->identity->Fid;
		
		$QueTahun="select * from tbl_kalender kln, tbl_kurikulum kr 
			where kln.kr_kode=kr.kr_kode
			and (GETDATE()
			BETWEEN kln_masuk and DATEADD(day,kln.kln_uas_lama, kln.kln_uas)
			and (kln.RStat is null or kln.RStat='0'))";
		$QueKuliah=" select *, concat(dbo.cekHari(hari),' ',masuk,'-',keluar) jadwal 
			from dbo.ViewJadwalDosen($Fid,GETDATE(),DEFAULT,DEFAULT,DEFAULT,DEFAULT)";
		$Qthn=Yii::$app->db->createCommand($QueTahun)->queryOne();
		$QueKuliah=Yii::$app->db->createCommand($QueKuliah)->queryAll();
		$data ='<center><b>Perkuliahan Tahun Akademik Semester '."$Qthn[kr_nama] ($Qthn[kr_kode])".'</b> </center><br />';
		$data.='<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>Jadwal</th>
					<th>Matakuliah</th>
					<th>Kelas</th>
					<th> Lantai | Ruang</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
			';
		foreach($QueKuliah as $d){
			$data .='
				<tr>
					<td>'.$d['jadwal'].'</td>
					<td>'.$d['matakuliah'].'</td>
					<td>'.$d['kelas'].'</td>
					<td>'.$d['lantai'].' | '.$d['ruang'].'</td>
					<td>'.$d['kehadiran'].'</td>
				</tr>';
		}
		$data.="</tbody></table>";
		return Json::encode($data);
	}

	public function actionHadirDosen(){
		$Id		= Yii::$app->user->identity->username;
		$QueTahun="select * from tbl_kalender kln, tbl_kurikulum kr 
			where kln.kr_kode=kr.kr_kode
			and (GETDATE()
			BETWEEN kln_masuk and DATEADD(day,kln.kln_uas_lama, kln.kln_uas)
			and (kln.RStat is null or kln.RStat='0'))
		";
		$QueKuliah="EXEC R_AbsDsn '".Yii::$app->user->identity->Fid."','',''";
		$Qthn=Yii::$app->db->createCommand($QueTahun)->queryOne();
		$QueKuliah=Yii::$app->db->createCommand($QueKuliah)->queryAll(\PDO::FETCH_NUM);
		$data ='<center><b>Kehadiran Perkuliahan Tahun Akademik Semester '."$Qthn[kr_nama] ($Qthn[kr_kode])".'</b> </center><br />';
		$n=0;
		$hd="";
		$bd='';
		foreach($QueKuliah as $d){
			$bd .='<tr>';			
			for($i=0;$i<count($d);$i++){
				
				$jadwal=$d[(count($d)-7)].", ".$d[(count($d)-6)]."-".$d[(count($d)-5)];
				if($i!=0 && $i!=1 && $i!=(count($d)-4)){
					if($n==0){
						// head table
						if($i==2){
							$hd .="
								<th rowspan='2'>Jadwal</th>
								<th rowspan='2'>Matakuliah</th>
								<th colspan='".(count($d)-3)."' align='center'>Sesi</th></tr><tr>
							";
						}else{
							if($i>=3 and $i<(count($d)-7)){
								$hd .="<th width='1%'>".($i-2)." </th>";
							}else{
								if($i==(count($d)-3)){$hd .='<th> Total</th>';}
								if($i==(count($d)-2)){$hd .='<th> Target</th>';}
								if($i==(count($d)-1)){$hd .='<th> %</th>';}
							}
						}
					}

					// Status absen
					if($i==2){
						$bd.="<td>$jadwal</td>";
						$bd.='<td>'.$d[$i].'</td>';
					}else{
						if($i>=3 and $i<(count($d)-7)){
							$bd.='
							<td width="1%">
								<i class="glyphicon glyphicon-'.($d[$i]==0?'remove':'ok').'-circle" style="color:'.($d[$i]==0?'red':'green').';"></i>
							</td>';
						}else{
							//$bd.='<td>'.$d[$i]	.'</td>';
							if($i==(count($d)-3)){$bd.='<td>'.number_format($d[$i],0).'</td>';}
							if($i==(count($d)-2)){$bd.='<td>'.number_format($d[$i],0).'</td>';}
							if($i==(count($d)-1)){$bd.='<td>'.number_format($d[$i],1).'%</td>';}
							
						}
					}
				}
			}
			$bd.='</tr>';
			$n++;
		}
		$data.='
		<div class="table-responsive">
		<table class="table table-bordered table-hover">
			<thead><tr>'.$hd.'</tr></thead>
			<tbody>'.$bd.'</tbody></table>
		</div>
		';
		return Json::encode($data);
	}

	public function actionHadirMhs2(){
		$Id		= Yii::$app->user->identity->username;
		$ModMhs	= \app\models\Mahasiswa::findOne($Id);

		$QueTahun="select * from tbl_kalender kln, tbl_kurikulum kr 
			where kln.kr_kode=kr.kr_kode
			and (GETDATE()
			BETWEEN kln_masuk and DATEADD(day,kln.kln_uas_lama, kln.kln_uas)
			and (kln.RStat is null or kln.RStat='0'))
			and kln.jr_id='".$ModMhs->jr_id."' and pr_kode='$ModMhs->pr_kode'
		";
		$QueKuliah  ="EXEC pvtKuliahMhs '".Yii::$app->user->identity->username."',NULL";
		$Qthn       =Yii::$app->db->createCommand($QueTahun)->queryOne();
		$QueKuliah=Yii::$app->db->createCommand($QueKuliah)->queryAll(/*\PDO::FETCH_NUM*/);
		$data ='<center><b>Kehadiran Perkuliahan Tahun Akademik Semester '."$Qthn[kr_nama] ($Qthn[kr_kode])".'</b> </center><br />';
		$n=0;
		$hd="";
		$bd='';
		#/*
        #$stat='s';
		foreach($QueKuliah as $d){
			$n++;
			$abs="";
            $hdrDsn=0;$hdrMhs=0;
            for($i=1;$i<=14;$i++){
                $stat=explode('|',$d[$i]);
                if($d[$i]!='0'){if($stat[0]==1){$hdrDsn+=1;if($stat[1]=='1'){$hdrMhs++;}}}
                $abs.="<td>".($d[$i]=='0'||$d[$i]=='' || $stat[0]==0 ?'X':'<span class="badge" style="background:'.($stat[1]=='1'?'green;':'red;').'" title="'.$stat[3].'">'.$stat[2]."</span>")."</td>";
            }
			$bd.="
            <tr>
                <th>".\app\models\Funct::HARI()[$d['jdwl_hari']].", $d[jdwl_masuk]-$d[jdwl_keluar] | $d[mtk_kode]: $d[mtk_nama] ($d[jdwl_kls])</th>
                <td>".ceil($hdrMhs*100/$hdrDsn)."%</td>$abs</tr>";
		}
		#*/


		$data.='
		<div class="table-responsive">
		<div><b>[ N:Normal (melakukan absen masuk dan keluar)  | A:Alpa | S:Sakit | I:Izin | D:Dispen | - :Perkuliahan Hari Ini]</b></div>
		<span class="badge" style="background: green"> Hadir </span> <span class="badge" style="background:red">Tidak Hadir </span>
		<table class="table table-bordered table-hover" border="1">
			<thead>
			    <tr>
			        <th rowspan="2">Matakuliah </th>
			        <th rowspan="2">%</th>
			        <th colspan="14">Sesi</th>
			    </tr>
			    <tr>
			        <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th>
			        <th>11</th>
			        <th>12</th>
			        <th>13</th>
			        <th>14</th>
                </t>
			</thead>
			<tbody>'.$bd.'</tbody></table>
		</div>
		';

        #return $data;
		#/*
        if($n<0){
			$data="<center><h4>Data Kahadiran Tidak Tersedia</h4></center>";
		}#*/
		return Json::encode($data);
	}

    public function actionHadirMhs1_180303(){
        $Id		= Yii::$app->user->identity;
        $ModMhs	= \app\models\Mahasiswa::findOne($Id->username);
        $QueTahun="
		select * from tbl_kalender kln, tbl_kurikulum kr 
			where kln.kr_kode=kr.kr_kode
			and (GETDATE()
			BETWEEN kln_masuk and DATEADD(day,kln.kln_uas_lama, kln.kln_uas)
			and (kln.RStat is null or kln.RStat='0'))
			and kln.jr_id='".$ModMhs->jr_id."' and pr_kode='$ModMhs->pr_kode'
		";
        $Qthn=Yii::$app->db->createCommand($QueTahun)->queryOne();

        $QueKuliah="EXEC dbo.AbsensiMhs ".$Id->Fid.",'".$Qthn['kr_kode']."'";
        #echo " $QueKuliah";
        $QueKuliah=Yii::$app->db->createCommand($QueKuliah)->queryAll(\PDO::FETCH_NUM);

        $data ='<center><b>Kehadiran Perkuliahan Tahun Akademik Semester '."$Qthn[kr_nama] ($Qthn[kr_kode])".'</b> </center><br />';
        $n=0;
        $hd="";
        $bd='';
        foreach($QueKuliah as $d){
            $bd .='<tr>';

            for($i=0;$i<count($d);$i++){
                $matakuliah = "$d[0]: $d[1]";
                $jadwal = \app\models\Funct::HARI()[$d[3]].", ".$d[4].'-'.$d[5];
                $persen	= round($d[8]);

                if($i>=0&&$i!=1){
                    if($n==0){
                        // head table
                        if($i==8){
                            $hd .="
								<th rowspan='2'>Matakuliah</th>
								<th rowspan='2'>Jadwal</th>
								<th rowspan='2' width='1%'>%</th>
								<th colspan='".(count($d)-9)."' align='center'>Sesi</th></tr><tr>
							";
                        }else{
                            if($i>=9 and $i<count($d)){$hd .="<th width='1%'>".($i-8)." </th>";}
                        }

                    }

                    // Status absen

                    if($i==8){
                        $bd.=
                            '<td>'.$matakuliah.'</td>
						<td>'.$jadwal.'</td>
						<td>'.$persen.'</td>
						';
                    }else{
                        if($i>=9 and $i<count($d)){
                            $bd.='
							<td width="1%">
								<i class="glyphicon glyphicon-'.($d[$i]==1?'ok':'remove').'-circle" style="color:'.($d[$i]==1?'green':'red').';"></i>
							</td>';
                        }
                    }
                }

            }
            $bd.='</tr>';
            $n++;
        }

        $data.='
		<div class="table-responsive">
		<table class="table table-bordered table-hover" border="1">
			<thead><tr>'.$hd.'</tr></thead>
			<tbody>'.$bd.'</tbody></table>
		</div>
		';

        if($n<=0){
            $data="<center><h4>Data Kahadiran Tidak Tersedia</h4></center>";
        }
        //echo $data;
        return Json::encode($data);
    }
    public function actionHadirMhs2_180303(){
        $Id		= Yii::$app->user->identity;
        $ModMhs	= \app\models\Mahasiswa::findOne($Id->username);
        $QueTahun="
		select * from tbl_kalender kln, tbl_kurikulum kr 
			where kln.kr_kode=kr.kr_kode
			and (GETDATE()
			BETWEEN kln_masuk and DATEADD(day,kln.kln_uas_lama, kln.kln_uas)
			and (kln.RStat is null or kln.RStat='0'))
			and kln.jr_id='".$ModMhs->jr_id."' and pr_kode='$ModMhs->pr_kode'
		";
        $Qthn=Yii::$app->db->createCommand($QueTahun)->queryOne();

        $QueKuliah="EXEC dbo.infohadirmhs ".$Id->username.",'".$Qthn['kr_kode']."'";
        #echo " $QueKuliah";
        $QueKuliah=Yii::$app->db->createCommand($QueKuliah)->queryAll(\PDO::FETCH_NUM);

        $data ='<center><b>Kehadiran Perkuliahan Tahun Akademik Semester '."$Qthn[kr_nama] ($Qthn[kr_kode])".'</b> </center><br />';
        $n=0;
        $hd="";
        $bd='';
        foreach($QueKuliah as $d){
            $bd .='<tr>';

            for($i=0;$i<count($d);$i++){
                $matakuliah = "$d[5]: $d[6]";
                $jadwal = \app\models\Funct::HARI()[$d[1]].", ".$d[2].'-'.$d[3];
                $persen	= round($d[0]);

                if($i>=0&&$i!=1){
                    if($n==0){
                        // head table
                        if($i==8){
                            $hd .="
								<th rowspan='2'>Matakuliah</th>
								<th rowspan='2' width='1%'>%</th>
								<th colspan='".(count($d)-9)."' align='center'>Sesi</th></tr><tr>
							";
                        }else{
                            if($i>=10 and $i<count($d)){$hd .="<th width='1%'>".($i-9)." </th>";}
                        }

                    }

                    // Status absen

                    if($i==8){
                        $bd.=
                        '<td>['.$jadwal.'] '.$matakuliah.'</td>
						<td>'.$persen.'</td>
						';
                    }else{
                        if($i>=10 and $i<count($d)){
                            $inf=explode('|',$d[$i]);
                            $bd.='
							<td width="1%">'
                            .($inf[2]?'<i class="glyphicon glyphicon-'.($inf[0]==1?'ok':'remove').'-circle" style="color:'.($inf[0]==1?'green':'red').';"></i>':"X")
                            .'</td>';
                        }
                    }
                }

            }
            $bd.='</tr>';
            $n++;
        }

        $data.='
		<div class="table-responsive">
		<table class="table table-bordered table-hover" border="1">
			<thead><tr>'.$hd.'</tr></thead>
			<tbody>'.$bd.'</tbody></table>
		</div>
		';

        if($n<=0){
            $data="<center><h4>Data Kahadiran Tidak Tersedia</h4></center>";
        }
        //echo $data;
        return Json::encode($data);
    }

    public function actionTv(){
        $rows = (new \yii\db\Query())
            ->select(['*'])
            ->from('tbl_berita_2')
            ->where(['status' => 'Publish'])
            ->all();

        $hari = date('N');
        $hari1 = date('N');

        if($hari=="7"){
            $hari="Minggu";
        }elseif ($hari=='1') {
            $hari="Senin";
        }elseif ($hari=="2") {
            $hari="Selasa";
        }elseif ($hari=="3") {
            $hari="Rabu";
        }elseif ($hari=="4") {
            $hari="Kamis";
        }elseif ($hari=="5") {
            $hari="Jumat";
        }elseif ($hari=="6") {
            $hari="Sabtu";
        }
        $TGL="'2016-09-29 18:00'";
        $TGL="";
        if($TGL==''){$TGL="getdate()";}

        $query = "
        SELECT max(jdwl_id)jdwl_id,Status,kr_kode
        ,concat(Matakuliah,' (',STUFF((SELECT distinct ','+ jdwl_kls FROM t_absen_dosen WHERE isnull(RStat,0)=0 and Gkode_=t.Gkode_ FOR XML PATH ('')),1,1,''),')')Matakuliah
        ,jam
        ,dosen
        ,Kehadiran
        ,ruangan
        FROM (
        SELECT 
            ad.jdwl_id 
            ,ad.GKode_
            ,iif(isnull(ad.ds_id1,ad.ds_id)=isnull(ad.ds_get_id,ad.ds_id),'TETAP','PENGGANTI') Status
            ,ad.kr_kode_ kr_kode
            ,concat(mk.mtk_kode,':',mk.mtk_nama) Matakuliah
            ,concat(CAST(ad.jdwl_masuk as VARCHAR(5)),'-',CAST(ad.jdwl_keluar as VARCHAR(5))) jam
            ,ds.ds_nm dosen
            ,CASE 
                    WHEN ad.ds_stat is null THEN 
                    iif(ad.ds_masuk is NULL,'-','HADIR')
                    WHEN ad.ds_stat=0 THEN '-'
                    WHEN ad.ds_stat=1 THEN 'SELESAI'
                    ELSE '-'
            END Kehadiran
            ,rg.rg_nama ruangan
            ,ad.jdwl_masuk, ad.jdwl_keluar
        FROM t_absen_dosen ad
        INNER JOIN tbl_jadwal jd on(jd.jdwl_id=ad.jdwl_id and isnull(jd.RStat,0)=0)
        INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
        INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and isnull(mk.RStat,0)=0)
        INNER JOIN tbl_dosen ds on(ds.ds_id=ad.ds_id and isnull(ds.RStat,0)=0)
        INNER JOIN tbl_ruang rg on(rg.rg_kode=jd.rg_kode)
        WHERE CAST(GETDATE() as TIME(0)) BETWEEN DATEADD(MINUTE,-50, ad.jdwl_masuk)	AND DATEADD(MINUTE,50, ad.jdwl_keluar)	
        -- ORDER BY ad.jdwl_masuk, ad.jdwl_keluar, ds.ds_nm
        ) t
        GROUP BY Matakuliah,GKODE_,Status,kr_kode,dosen,Kehadiran,ruangan,jam
        ORDER BY max(jdwl_masuk),max(jdwl_keluar)
		";

        // $query = "SELECT * FROM dosen_absen";


        $dataProvider = new SqlDataProvider([
            'sql' => $query,
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);

        return $this->renderPartial('tv',[
            'rows'=>$rows,
            'items'=>$dataProvider->getModels(),

            'hari'=>$hari,
        ]);


    }

//
    public function actionTv2(){  
		//echo md5('ypkp@#1234a7');die();
        $rows = (new \yii\db\Query())
            ->select(['*'])
            ->from('tbl_berita_2')
            ->where(['status' => 'Publish'])
            ->all();
                
        $hari = date('N');
        $hari1 = date('N');

          if($hari=="7"){
                $hari="Minggu";
            }elseif ($hari=='1') {
                $hari="Senin";
            }elseif ($hari=="2") {
                $hari="Selasa";
            }elseif ($hari=="3") {
                $hari="Rabu";
            }elseif ($hari=="4") {
                $hari="Kamis";
            }elseif ($hari=="5") {
                $hari="Jumat";
            }elseif ($hari=="6") {
                $hari="Sabtu";
            }

        $query = "SELECT
            kr_kode,b.*, r.rg_nama,r.rg_kode,
            m.mtk_nama,
            jr.jr_nama,
            d.ds_nm,
            jr.jr_jenjang,
            j.jdwl_masuk,
            j.jdwl_keluar,
            j.jdwl_hari,
		    j.jdwl_id,
		    j.jdwl_kls,
            status = IIF( 
				(
					SELECT count(*) FROM dosen_absen da 
					WHERE tgl_absen = CAST(getdate() AS DATE) 
					AND j.jdwl_id = da.jdwl_id 
				)  > 0
				, 'HADIR', 'BELUM HADIR'
			) 
        FROM
            tbl_bobot_nilai b
        LEFT JOIN tbl_jadwal j ON (j.bn_id = b.id)
        LEFT JOIN tbl_kalender k ON (
			k.kln_id = b.kln_id
			and (
				GETDATE() BETWEEN GETDATE()
				and DATEADD(day,k.kln_uas_lama, k.kln_uas)
			)
			and (b.RStat is null or b.RStat='0')		
		)
        LEFT JOIN tbl_matkul m ON (m.mtk_kode = b.mtk_kode)
        LEFT JOIN tbl_ruang r ON (r.rg_kode = j.rg_kode)
        LEFT JOIN tbl_jurusan jr ON (jr.jr_id = m.jr_id)
        LEFT JOIN tbl_dosen d ON (b.ds_nidn = d.ds_id)

        WHERE j.jdwl_hari = $hari1
		and j.jdwl_id 
		in(select jdwl_id from tbl_krs where (RStat is null or RStat='0'))
		and(
				(j.RStat is null or j.RStat='0')
			and (k.RStat is null or k.RStat='0')
			and (m.RStat is null or m.RStat='0')
			and (r.RStat is null or r.RStat='0')
			and (d.RStat is null or d.RStat='0')
			and (b.RStat is null or b.RStat='0')
		)
        ORDER BY
            j.jdwl_masuk ASC";
        
       // $query = "SELECT * FROM dosen_absen";
        
         
        $dataProvider = new SqlDataProvider([ 
            'sql' => $query,
             'pagination' => [
                    'pageSize' => 0,
                ],
        ]);
	
        return $this->renderPartial('tv3',[
            'rows'=>$rows,
            'items'=>$dataProvider->getModels(),

            'hari'=>$hari,
        ]);

    
    }


    public function actionInfoJadwal(){  
		//echo md5('ypkp@#1234a7');die();
        $rows = (new \yii\db\Query())
            ->select(['*'])
            ->from('tbl_berita_2')
            ->where(['status' => 'Publish'])
            ->all();
                
        $hari = date('N');
        $hari1 = date('N');

          if($hari=="7"){
                $hari="Minggu";
            }elseif ($hari=='1') {
                $hari="Senin";
            }elseif ($hari=="2") {
                $hari="Selasa";
            }elseif ($hari=="3") {
                $hari="Rabu";
            }elseif ($hari=="4") {
                $hari="Kamis";
            }elseif ($hari=="5") {
                $hari="Jumat";
            }elseif ($hari=="6") {
                $hari="Sabtu";
            }

        $query = "SELECT
            kr_kode,b.*, r.rg_nama,r.rg_kode,
            m.mtk_nama,
            jr.jr_nama,
            d.ds_nm,
            jr.jr_jenjang,
            j.jdwl_masuk,
            j.jdwl_keluar,
            j.jdwl_hari,
		    j.jdwl_id,
		    j.jdwl_kls,
            status = IIF( 
				(
					SELECT count(*) FROM dosen_absen da 
					WHERE tgl_absen = CAST(getdate() AS DATE) 
					AND j.jdwl_id = da.jdwl_id 
				)  > 0
				, 'HADIR', 'BELUM HADIR'
			) 
        FROM
            tbl_bobot_nilai b
        LEFT JOIN tbl_jadwal j ON (j.bn_id = b.id)
        LEFT JOIN tbl_kalender k ON (
			k.kln_id = b.kln_id
			and (
				GETDATE() BETWEEN GETDATE()
				and DATEADD(day,k.kln_uas_lama, k.kln_uas)
			)
			and (b.RStat is null or b.RStat='0')		
		)
        LEFT JOIN tbl_matkul m ON (m.mtk_kode = b.mtk_kode)
        LEFT JOIN tbl_ruang r ON (r.rg_kode = j.rg_kode)
        LEFT JOIN tbl_jurusan jr ON (jr.jr_id = m.jr_id)
        LEFT JOIN tbl_dosen d ON (b.ds_nidn = d.ds_id)

        WHERE j.jdwl_hari = $hari1
		and j.jdwl_id 
		in(select jdwl_id from tbl_krs where (RStat is null or RStat='0'))
		and(
				(j.RStat is null or j.RStat='0')
			and (k.RStat is null or k.RStat='0')
			and (m.RStat is null or m.RStat='0')
			and (r.RStat is null or r.RStat='0')
			and (d.RStat is null or d.RStat='0')
			and (b.RStat is null or b.RStat='0')
		)
        ORDER BY
            j.jdwl_masuk ASC";
        
       // $query = "SELECT * FROM dosen_absen";
        
         
        $dataProvider = new SqlDataProvider([ 
            'sql' => $query,
             'pagination' => [
                    'pageSize' => 0,
                ],
        ]);
	
        return $this->renderPartial('infojadwal',[
            'rows'=>$rows,
            'items'=>$dataProvider->getModels(),

            'hari'=>$hari,
        ]);

    
    }


//


    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
			\app\models\Funct::LOGS('Login');
            return $this->goBack();
        }
        $this->layout = 'login';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
		\app\models\Funct::LOGS('Logout');
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {     
        $this->layout = 'login';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
 
    }


    public function actionChangePassword(){
       
       //$model = User::getModel();
       $User  = Yii::$app->session['user'];
       $model = User::findOne(Yii::$app->user->id);
    
        if (!empty($_POST)) {

                $Pass1 =    $_POST['Reset']['password1'];
                $Pass2 =    $_POST['Reset']['password2'];
                $Pass3 =    $_POST['Reset']['password3'];

                if ($Pass1 != $Pass2) {                
                    Yii::$app->getSession()->setFlash('danger','Konfirmasi Password Baru tidak sesuai');                
	                return $this->render('ch_pw');
                }else{
                    if ($User->validatePassword($Pass3) == 1) {
                       if ($User->changePassword($model,$Pass1) == 1 ) {
                              Yii::$app->getSession()->setFlash('success','Kata Sandi telah berhasil di Update');
							\app\models\Funct::LOGS("Mengubah Password/Kata Sandi");
                            return $this->redirect('index');
                        } 
                    }else{
                          Yii::$app->getSession()->setFlash('danger','Password Lama Tidak Sesuai');
                        return $this->render('ch_pw');
                    }
                }
        }
       
       if ($model){
        return $this->render('ch_pw', [
            'model' => $model,
        ]);

       }else{
         //Yii::$app->user->logout();
         //return $this->goHome();
       }
    }


    public function actionCreateMPDF(){
        $mpdf=new mPDF();
        $mpdf->WriteHTML($this->renderPartial('mpdf'));
        $mpdf->Output();
        exit;
        //return $this->renderPartial('mpdf');
    }
    
    public function actionSamplePdf() {
        $mpdf = new mPDF;
        $mpdf->WriteHTML('Sample Text');
        $mpdf->Output();
        exit;
    }

    public function actionForceDownloadPdf(){
        $mpdf=new mPDF();
        $mpdf->WriteHTML($this->renderPartial('mpdf'));
        $mpdf->Output('MyPDF.pdf', 'D');
        exit;
    }

}
