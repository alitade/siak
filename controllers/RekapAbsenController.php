<?php

namespace app\controllers;

use Yii;
use app\models\Rekap;
use app\models\RekapSearch;

use app\models\AbsenAwal;

use app\models\TransaksiFinger;
use app\models\TransaksiFingerSearch;

use app\models\Jadwal;
use app\models\JadwalSearch;
use app\models\Funct;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \mPdf;
use kartik\mpdf\Pdf;
use kartik\export\ExportMenu;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;


/**

 * RekapController implements the CRUD actions for Rekap model.
 */
class RekapAbsenController extends Controller
{
    /**
     * @inheritdoc
     */
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

    /**
     * Lists all Rekap models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RekapSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPerkuliahan()
    {
        $searchModel = new RekapSearch();
        $dataProvider = $searchModel->perkuliahan(Yii::$app->request->queryParams);

        return $this->render('perkuliahan',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

	// khusus untuk data per hari ini
    public function actionKuliah(){
        $searchModel = new TransaksiFingerSearch();
        $dataProvider = $searchModel->perkuliahan(Yii::$app->request->queryParams);
        return $this->render('kuliah',[
            'searchModel' 	=> $searchModel,
            'dataProvider'  => $dataProvider,
        ]);
    }



    public function actionMasukAwal($id){
        $model =  Jadwal::findOne($id);
        $model2 =  Jadwal::findOne($id);
		$vieJadwal = "
		SELECT 
			mk.mtk_kode kode,
			mk.mtk_nama matakuliah,
			concat(mk.mtk_kode,': ',mk.mtk_nama) matkul,
			concat(jr.jr_jenjang,'',jr.jr_nama) jurusan,
			pr.pr_nama program,jdwl_kls kelas	
		from tbl_jadwal jdw
		INNER JOIN tbl_bobot_nilai bn on(bn.id=jdw.bn_id and(bn.RStat is NULL or bn.RStat='0'))
		INNER JOIN tbl_dosen ds on(ds.ds_id=bn.ds_nidn and(ds.RStat is NULL or ds.RStat='0'))
		INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and(mk.RStat is NULL or mk.RStat='0'))
		INNER JOIN tbl_kalender kln on(kln.kln_id=bn.kln_id and(kln.RStat is NULL or kln.RStat='0') and kr_kode='11617')
		INNER JOIN tbl_jurusan jr on(jr.jr_id=kln.jr_id)
		INNER JOIN tbl_program pr on(pr.pr_kode=kln.pr_kode )		
		WHERE (jdw.jdwl_id is NOT NULL)
		and (jdw.GKode='$model->GKode' or jdw.jdwl_id='$model->jdwl_id')
		";
		
		
		$viewAbsen = "
		select left(ds_masuk,5) ds_masuk, left(ds_keluar,5) ds_keluar, 
		DATEDIFF(MINUTE,'".date('H:i')."',jdwl_keluar) durasi 
		from transaksi_finger where jdwl_id='$model->jdwl_id' and tgl='".date('Y-m-d')."'";
		$viewAbsen=Yii::$app->db->createCommand($viewAbsen)->queryOne();
		$vieJadwal=Yii::$app->db->createCommand($vieJadwal)->queryAll();
        
		if(isset($_POST['awl'])){
			$jam=Html::encode($_POST['awl']['masuk']);
			$thn=date('Y-m-d');
			$ins="
				insert into absen_awal(GKode,jdwl_masuk,jdwl_keluar,tgl,tipe,ds_fid,jdwl_id)
				select distinct 
					'".$model->GKode."','$jam',tf.jdwl_keluar,'$thn','1',tf.ds_fid,tf.jdwl_id
				from transaksi_finger tf
				INNER JOIN tbl_jadwal jd on(jd.jdwl_id=tf.jdwl_id and jd.GKode='".$model->GKode."' )
				AND NOT EXISTS(SELECT * FROM absen_awal WHERE GKode='".$model->GKode."'  AND tgl='$thn') 		
			";

			//
			$update="
				update tf set tf.jdwl_masuk='$jam'
				from transaksi_finger tf
				inner join tbl_jadwal jd on(jd.jdwl_id=tf.jdwl_id and jd.GKode='".$model->GKode."' )
				AND NOT EXISTS(SELECT * FROM absen_awal WHERE GKode='".$model->GKode."'  AND  tgl='$thn')
			";
			
			$update=Yii::$app->db->createCommand($update)->execute();
			$ins=Yii::$app->db->createCommand($ins)->execute();
			return $this->redirect(['perkuliahan', 'id' => $id]);
		}

		return $this->render('masuk_awal', [
			'model' => $model,
			'vieJadwal'=>$vieJadwal,
			'viewAbsen'=>$viewAbsen,				
		]);

    }

    public function actionPulangAwal($id){

        $thn=date('Y-m-d');
        $model  =  Jadwal::findOne($id);

        echo $model->bn->ds->u->Fid;
        $ModAwal = AbsenAwal::find()->where("jdwl_id='$model->jdwl_id' and isnull(RStat,0)=0 AND  tgl='$thn'")->one();

        $model2 =  Jadwal::findOne($id);
		$vieJadwal = "
		SELECT 
			mk.mtk_kode kode,
			mk.mtk_nama matakuliah,
			concat(mk.mtk_kode,': ',mk.mtk_nama) matkul,
			concat(jr.jr_jenjang,'',jr.jr_nama) jurusan,
			pr.pr_nama program,jdwl_kls kelas,
			kln.kr_kode tahun	
			
		from tbl_jadwal jdw
		INNER JOIN tbl_bobot_nilai bn on(bn.id=jdw.bn_id and  isnull(bn.RStat,0)=0)
		INNER JOIN tbl_dosen ds on(ds.ds_id=bn.ds_nidn and isnull(ds.RStat,0)=0 )
		INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and isnull(mk.RStat,0)=0)
		INNER JOIN tbl_kalender kln on(kln.kln_id=bn.kln_id and isnull(kln.RStat,0)=0)
		INNER JOIN tbl_jurusan jr on(jr.jr_id=kln.jr_id)
		INNER JOIN tbl_program pr on(pr.pr_kode=kln.pr_kode )		
		WHERE (jdw.GKode='$model->GKode' or jdw.jdwl_id='$model->jdwl_id')
		";
		
		
		$viewAbsen = "
		select left(ds_masuk,5) ds_masuk, left(ds_keluar,5) ds_keluar, 
		DATEDIFF(MINUTE,'".date('H:i')."',jdwl_keluar) durasi 
		from transaksi_finger where jdwl_id='$model->jdwl_id' and tgl='".date('Y-m-d')."'";
		$viewAbsen=Yii::$app->db->createCommand($viewAbsen)->queryOne();
		$vieJadwal=Yii::$app->db->createCommand($vieJadwal)->queryAll();
        
		if(isset($_POST['awl'])){
			$jam=Html::encode($_POST['awl']['keluar']);

			$update="
				update tf set tf.jdwl_keluar='$jam'
				from transaksi_finger tf
				inner join tbl_jadwal jd on(jd.jdwl_id=tf.jdwl_id and jd.GKode='".$model->GKode."' )
				AND NOT EXISTS(SELECT * FROM absen_awal WHERE GKode='".$model->GKode."'  AND  tgl='$thn')
			";

			$ins="
				insert into absen_awal(GKode,jdwl_masuk,jdwl_keluar,tgl,tipe,ds_fid,jdwl_id)
				select distinct 
					'".$model->GKode."',tf.jdwl_masuk,'$jam','$thn','2',tf.ds_fid,tf.jdwl_id
				from transaksi_finger tf
				INNER JOIN tbl_jadwal jd on(jd.jdwl_id=tf.jdwl_id and jd.GKode='".$model->GKode."' )
				AND NOT EXISTS(SELECT * FROM absen_awal WHERE GKode='".$model->GKode."'  AND tgl='$thn') 		
			";

			//
			#/*
			echo"<pre>";
			print_r($update);
			print_r($ins);
			echo"</pre>";
			die();
			#*/
			
			$update=Yii::$app->db->createCommand($update)->execute();
			$ins=Yii::$app->db->createCommand($ins)->execute();
			return $this->redirect(['perkuliahan', 'id' => $id]);
		}

		return $this->render('pulang_awal', [
            'model' => $model,
            'modAwal' => $ModAwal,
			'vieJadwal'=>$vieJadwal,
			'viewAbsen'=>$viewAbsen,				
		]);

    }


	//---------------------------------------------- end 
	
    /**
     * Displays a single Rekap model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id,$u=0){
		
 		$db = Yii::$app->db1;
		$keuangan 	= Funct::getDsnAttribute('dbname', $db->dsn);
		if(!$keuangan){$keuangan 	= Funct::getDsnAttribute('Database', $db->dsn);}
        $model	=	Rekap::find()->where(["concat(replace(tgl_ins,'-',''),jdwl_hari,jdwl_id)"=>$id])->one();
		/*
		ds_masuk=isnull(ds_masuk,jdwl_masuk),ds_keluar=isnull(ds_keluar,jdwl_keluar),ds_get_fid=ds_fid,ds_stat='1',
		mhs_masuk=isnull(mhs_masuk,jdwl_masuk),mhs_keluar=isnull(mhs_keluar,jdwl_keluar),mhs_stat='1'
		*/
		$update =" 
			update m_transaksi_finger set 
				ds_get_fid=ds_fid,ds_stat='1',mhs_stat='1',tgl=isnull(tgl,tgl_ins)
			where concat(replace(tgl_ins,'-',''),jdwl_hari,jdwl_id)='$id' and  ds_get_fid is null
		";
		if((int) $_GET['a']===1){
			Yii::$app->db->createCommand($update)->execute();
			return $this->redirect(['view','id' =>$id]);
		}
		
		if(isset($_POST['hadir'])){
			foreach($_POST['hadir']['abs'] as $k=>$v){
				$ket = Html::encode($_POST['hadir']['ket'][$k]);
				$up= " 
					update m_transaksi_finger set 
					mhs_stat='$v', ket='$ket'
					--,mhs_masuk=iif('$v'='1',jdwl_masuk,NULL),
					--,mhs_keluar=iif('$v'='1',jdwl_keluar,NULL)
					where concat(replace(tgl_ins,'-',''),jdwl_hari,jdwl_id)='$id' and krs_id=$k	
				";Yii::$app->db->createCommand($up)->execute();
			}
			return $this->redirect(['view','id' =>$id]);
		}
		
		$dosen	="
			select 
				concat(tf.mtk_kode,': ',tf.mtk_nama) matakuliah,
				tf.jdwl_hari,
				tf.tgl_ins,
				concat(cast(jdwl_masuk as varchar(5)),' - ',cast(jdwl_keluar as varchar(5))) jadwal,
				iif( isnull(ds_get_fid,0)='0','Tidak Hadir','Hadir') status,
				--iif( isnull(ds_stat,0)='0','Tidak Hadir','Hadir') status,
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
			LEFT join (
				select  ds.ds_nm,u.Fid 
				from user_ u inner join	tbl_dosen ds on(ds.ds_user=u.username)
				WHERE Fid is not NULL
			) t1 on(t1.Fid=tf.ds_get_fid)
			where concat(replace(tf.tgl_ins,'-',''),tf.jdwl_hari,tf.jdwl_id)='$id'";
			
		$mhs="
			select 
				tf.ket,
				tf.krs_id,
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
			where concat(replace(tf.tgl_ins,'-',''),tf.jdwl_hari,tf.jdwl_id)='$id'
			and tf.krs_stat is not null
			";
			
		$dosen = Yii::$app->db->createCommand($dosen)->queryOne();
		$mhs = Yii::$app->db->createCommand($mhs)->queryAll();
		//print_r($dosen);
		//$model 	= $this->findModel($id);
		return $this->render('view', [
            'model' => $model,
			'dosen' => $dosen,
			'mhs' => $mhs,
			'id' => $id,
			'u' => $u,
			
        ]);
    }


    public function actionCreate()
    {
        $model = new Rekap();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Rekap model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Rekap model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Rekap model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rekap the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rekap::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



	public function actionRekapAbsenPeriode(){

		$table='';
		if(isset($_POST['rekap'])){
			$jr="'".implode("','",$_POST['rekap']['jr'])."'";
			$pr="'".implode("','",$_POST['rekap']['pr'])."'";
			
			$kln		= (int)$_POST['rekap']['kalender'];
			if(!empty($_POST['rekap']['awal_']) && !empty($_POST['rekap']['akhir_'])){
				if(empty($_POST['rekap']['awal']) && empty($_POST['rekap']['akhir']) ){
					$tgl1	= Html::encode($_POST['rekap']['awal_']);
					$tgl2	= Html::encode($_POST['rekap']['akhir_']);				
				}
			}else{
				$tgl1		= Html::encode($_POST['rekap']['awal']);
				$tgl2		= Html::encode($_POST['rekap']['akhir']);
			}

			$kon='';
			$tgl[0] =$tgl1;
			$tgl[1] =$tgl2;
			$ModKln = \app\models\Kurikulum::findOne($kln);
			$ModKln->kr_nama;
			$title="Laporan Kehadiran Perkuliahan Dosen ".$ModKln->kr_nama." Tanggal "
			.\app\models\Funct::TANGGAL($tgl1)." / ".\app\models\Funct::TANGGAL($tgl2);
			
			if(!empty($tgl1) && !empty($tgl2)){
				$kon="and tgl1 between '$tgl1' and '$tgl2' ";
			}
			$Head 		= "select tgl1,tgl2,concat(tgl1,' / ',tgl2) h from dbo.periode1('$kln',default) where 1=1 $kon";
			$Content 	= "EXEC rekapAbsenDminguan '$kln',$jr,$pr,'$tgl1','$tgl2'";
			//$Content 	= "EXEC rekapAbsenDminguan ";
			$thead="<tr>";
			$theadT='';$theadM='';$theadD='';$tlabel=[];
			$T=0;$M=1;$D=0;
			$cT=13;$cM=13;$cD=13;
			$vT=0;$vM='';$vD='';
			$loop=0;
			$arr=[];
			$tbl=[];
			$mgu='';
			foreach(Yii::$app->db->createCommand($Head)->queryAll() as $d){
				$loop++;
				if(date('d',strtotime($d['tgl1']))!=$vD){$D++;$vD=date('d',strtotime($d['tgl1']));$theadD.='<th>'.$vD.'</th>';}
				if(date('d',strtotime($d['tgl2']))!=$vD){$D++;$vD=date('d',strtotime($d['tgl2']));;$theadD.='<th>'.$vD.'</th>';}
				$thead.="<th>".str_replace('/','<br />',$d['h'])."</th>";
				$tlabel[]=$d['h'];
				$Tahun1=date('Y',strtotime($d['tgl1']));$Bulan1=date('m',strtotime($d['tgl1']));$Hari1=date('d',strtotime($d['tgl1']));
				$Tahun2=date('Y',strtotime($d['tgl2']));$Bulan2=date('m',strtotime($d['tgl2']));$Hari2=date('d',strtotime($d['tgl2']));
				
				//$arr[$Tahun1][$Bulan1][]=$Hari1;
				//$arr[$Tahun1][$Bulan2][]=$Hari2;
	
				if(!isset($arr[$Tahun1])){
					$arr[$Tahun1]['n']=1;
					$tbl['thn'][$Tahun1]='<th colspan="'.$arr[$Tahun1]['n'].'">'.$Tahun1.'</th>';
					$tbl['bln'][$Bulan1]='<th colspan="'.$arr[$Tahun1][$Bulan1]['n'].'">'.$Bulan1.'</th>';
				}else{
					$arr[$Tahun1]['n']+=1;
					if(!isset($arr[$Tahun1][$Bulan1])){
						$arr[$Tahun1][$Bulan1]['n']=1;
						$tbl['bln'][$Bulan1]='<th colspan="'.$arr[$Tahun1][$Bulan1]['n'].'">'.$Bulan1.'</th>';
					}else{
						$arr[$Tahun1][$Bulan1]['n']+=1;
						$tbl['bln'][$Bulan1]='<th colspan="'.$arr[$Tahun1][$Bulan1]['n'].'">'.$Bulan1.'</th>';
					}
					$tbl['thn'][$Tahun1]='<th colspan="'.$arr[$Tahun1]['n'].'">'.$Tahun1.'</th>';
				}
				
				if(!isset($arr[$Tahun2])){
					$arr[$Tahun2]['n']=1;
					$tbl['thn'][$Tahun2]='<th colspan="'.$arr[$Tahun2]['n'].'">'.$Tahun2.'</th>';
					$tbl['bln'][$Bulan2]='<th colspan="'.$arr[$Tahun2][$Bulan2]['n'].'">'.$Bulan2.'</th>';
				}else{
					$arr[$Tahun2]['n']+=1;
					if(!isset($arr[$Tahun2][$Bulan2])){
						$arr[$Tahun2][$Bulan2]['n']=1;
						$tbl['bln'][$Bulan2]='<th colspan="'.$arr[$Tahun2][$Bulan2]['n'].'">'.$Bulan2.'</th>';
					}else{
						$arr[$Tahun2][$Bulan2]['n']+=1;
						$tbl['bln'][$Bulan2]='<th colspan="'.$arr[$Tahun2][$Bulan2]['n'].'">'.$Bulan2.'</th>';
					}
					$tbl['thn'][$Tahun2]='<th colspan="'.$arr[$Tahun2]['n'].'">'.$Tahun2.'</th>';
				}
				$mgu.='<th colspan="2">'.$loop.'</th>';
			}
			$theadT='
				<tr>
				<th rowspan="4">Jurusan</th>
				<th rowspan="4">Program</th>
				<th rowspan="4"	width="100px">Dosen</th>
				<th rowspan="4" width="100px">Matakuliah</th>
				<th rowspan="4" width="100px">Hari</th>
				<th rowspan="4" width="100px">Masuk</th>
				<th width="10px">Thn.</th>'.implode('',$tbl['thn'])
				.'</tr>';
			$theadM='<tr><th width="10px">Bln.</th>'.implode('',$tbl['bln']).'</tr>';;
			$theadD='<tr><th>Tgl.</th>'.$theadD.'</tr>';
			$mgu="<tr><th>Mgu.</th>$mgu</tr>";
			//$tlabel=[];
			$thead.="</tr>";
	
	
			$tbody="<tbody>";
			$subTitle="";
			foreach(Yii::$app->db->createCommand($Content)->queryAll() as $d){
				$subTitle[$d['Jurusan'].'-'.$d['program']]=$d['Jurusan'].'-'.$d['program'];
				$tbody.="<tr><td>".$d['Jurusan']."</td>
						<td>".$d['program']."</td>
						<td>".$d['DOSEN']."</td>
						<td>".$d['matakuliah']."</td>
						<td>".Funct::HARI()[$d['jdwl_hari']]."</td>
						<td colspan='2'>".$d['jdwl_masuk']."</td>";
				for($i=0;$i<count($tlabel);$i++){$tbody.="<td colspan='2'>".$d[$tlabel[$i]]."</td>";}
				$tbody.="</tr>";
			}
			$tbody.="</tbody>";
			$table ='<table class="table table-bordered" border="1"><thead>
			<tr><th colspan="'.(8+($loop*2)).'"><center>'.$title.(is_array($subTitle)? /*"<br />".implode(',',$subTitle)*/"":"").'</center> </th> </tr>
			'.$theadT.$theadM.$theadD.$mgu.'</thead>'.$tbody.'</table>';
			
			
			
			if(isset($_POST['ex'])){
				//print_r($_POST);
				//echo $table;
				//die();
				$filename="rekap-absen-dosen-".date('YmdHis').".xls";
				header("Content-type: application/vnd-ms-excel");
				header("Content-Disposition: attachment; filename=$filename");
				echo $table;
				return $this->redirect(['rekap-absen-periode']);					
			}


		}


		return $this->render('periode',[
			'table'=>$table,
			'tgl'=>$tgl,
			'title'=>$title,
			'vpr'=>$pr,
			'vjr'=>$jr,
		]);
	}


    public function actionAwal($t=1) {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
			$t=($t==1?'tgl1':'tgl2');
			$sql= "select $t from dbo.periode1('$id',default)" ;
			$list=Yii::$app->db->createCommand($sql)->queryAll();
			$n=0;
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $v) {
					$n++;
					$nama=$v[$t]." (Sesi $n) ";
                    $out[] = ['id'=>$v[$t], 'name' =>$nama];
                    if ($i == 0) {$selected = $v[$t];}
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }


	public function actionCetakAbsenDosen($id){
		echo "cetak absen";
		
	}

	public function actionCreatePergantian($id,$k,$k1=''){
		$id=(int)$id;
		$k=$k;
		$Jdwl			= Jadwal::findOne($id);
		$ModJdwl 		= Jadwal::find()->where("(
			GKode ='$Jdwl->GKode'			
		)")->all();
		
		if($k1!=''){
			$k1=$k1;
			$Pergantian="
				delete from t_finger_pengganti
				where concat(replace(tgl_ins,'-',''),jdwl_hari,ds_fid,left(jdwl_masuk,2))='$k1'
			";
			$Pergantian=Yii::$app->db->createCommand($Pergantian)->execute();
			return $this->redirect(['rekap-absen/create-pergantian','id'=>$id,'k'=>$k]);					
				
		}
		
		$Perkuliahan	= Rekap::find()
		->select([ 
			'jdwl_masuk'=>"left(jdwl_masuk,5)",
			'jdwl_keluar'=>"left(jdwl_keluar,5)",
			'jdwl_hari'=>"jdwl_hari",
			'tgl_ins'=>"tgl_ins",
			'dosen'=>"ds.ds_nm",
			'ds_fid'=>"ds_fid",
			//'hari'=>"dbo.cekHari(jdwl_hari)",
		])
		->innerJoin("user_ u","(u.Fid=m_transaksi_finger.ds_fid and u.tipe='3' and u.Fid is not null)")
		->innerJoin("tbl_dosen ds","(ds.ds_user=u.username)")
		->where("concat(replace(tgl_ins,'-',''),jdwl_hari,jdwl_id,left(jdwl_masuk,2))='$k'")
		->One();
		
		//print_r($Perkuliahan);
		
		$kode= str_replace('-','',$Perkuliahan->tgl_ins).$Jdwl->jdwl_hari.$Jdwl->jdwl_id.substr($Jdwl->jdwl_masuk,0,2);
		
		$Pergantian="
			select 
				tf.jdwl_hari,tf.tgl_ins, left(tf.jdwl_masuk,5) jdwl_masuk, left(tf.jdwl_keluar,5) jdwl_keluar 
				,concat(replace(tf.tgl_ins,'-',''),tf.jdwl_hari,tf.ds_fid,left(tf.jdwl_masuk,2)) kode
			from t_finger_pengganti tf 
			inner join tbl_jadwal jdw on(jdw.jdwl_id=tf.jdwl_id)
			where (
				jdw.GKode='$Jdwl->GKode'
				or(tf.jdwl_hari=$Jdwl->jdwl_hari and left(tf.jdwl_masuk,2)=left('$Jdwl->jdwl_masuk',2)) 
			)and tf.tgl='$Perkuliahan->tgl_ins'
		";
		$Pergantian=Yii::$app->db->createCommand($Pergantian)->QueryOne();
			
		$msg="";
		
		if(isset($_POST['G'])){
			$tgl	= $_POST['G']['tgl'];
			$masuk	= $_POST['G']['masuk'];
			$keluar	= $_POST['G']['keluar'];
			
			$Qv = "
				select datediff(minute,'$masuk','$keluar')d, datediff(day,getdate(),'$tgl') t,
				datepart(dw,'$tgl') h
				";
			$Qv=Yii::$app->db->createCommand($Qv)->QueryOne();
			// if($Qv['h']==1){
			// 	$msg="Perkuliahan Tidak Bisa Dilakukan Dihari minggu";	
			// }else{
			// 	if($Qv['t'] > 0 ){
			// 		if($Qv['d'] < 0 ){
			// 			$msg="Jam Masuk Melebihi Jam Keluar";
			// 		}else{if($Qv['d'] < 50 ){$msg="Durasi Perkuliahan Kurang dari 1 SKS";}}
			// 	}else{
			// 		$msg="Salah Memilih Tanggal $tgl";
			// 		if($Qv['t']==0){$msg="Pergantian Jadwal Tidak Bisa Dilakukan Dihari ini";}
			// 	}
			// }
			
			if($msg==""){
				$q="
				INSERT INTO t_finger_pengganti(
					krs_id,krs_stat,ds_fid,mtk_kode,mtk_nama,jdwl_id
					,jdwl_hari,jdwl_masuk,jdwl_keluar,mhs_fid,tgl_ins
					,tgl,sesi
				)
				SELECT
					krs.krs_id,krs.krs_stat,ds.Fid,mk.mtk_kode,mk.mtk_nama,
					jdw.jdwl_id,
					(DATEPART(dw,'$tgl')-1),
					'$masuk','$keluar',
					
					mhs.Fid,CAST('$tgl' as DATE)
					,'$Perkuliahan->tgl_ins'
					,(SELECT iif(t in('t','a'),0,t) t FROM dbo.periode_v1(DEFAULT,kln.kr_kode,DEFAULT,CAST(GETDATE() as DATE)) )
				from tbl_jadwal jdw
				INNER JOIN tbl_bobot_nilai bn on(bn.id=jdw.bn_id and(bn.RStat is NULL or bn.RStat='0'))
				INNER JOIN (
					SELECT ds.ds_id, Fid from user_ u INNER JOIN tbl_dosen ds on(ds.ds_user=u.username and u.tipe='3')
					WHERE (ds.RStat is NULL or ds.RStat='0')
					and u.Fid=$Perkuliahan->ds_fid
				)ds on(ds.ds_id=bn.ds_nidn)
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
				WHERE NOT EXISTS(SELECT * FROM t_finger_pengganti tf WHERE tf.krs_id=krs.krs_id and tf.tgl_ins=	'$tgl')
				AND dbo.cekIgMk(mk.mtk_kode)=0
				and jdw.jdwl_kls not in('j','R1','R2')
				and jdw.jdwl_hari = $Jdwl->jdwl_hari
				and (
					jdw.GKode='$Jdwl->GKode'
					or(jdwl_hari=$Jdwl->jdwl_hari and left(jdwl_masuk,2)=left('$Jdwl->jdwl_masuk',2)) 
				)			 			
				";
				/*
				echo"<pre>";
				print_r($q);
				echo"</pre>";
				die();
				*/
				$q=Yii::$app->db->createCommand($q)->execute();
				return $this->redirect(['rekap-absen/create-pergantian','id'=>$id,'k'=>$k]);					
			}
		}
		
		return $this->render('create_pergantian',[
			'perkuliahan'=>$Perkuliahan,
			'Pergantian'=>$Pergantian,
			'ModJdwl'=>$ModJdwl,
			'id'=>$id,
			'msg'=>$msg
		]);
		
		
	}


	public function actionAbsenKhusus($n,$s=1){
		
        $db 	= Yii::$app->db;
        $sql 	= "SELECT dbo.cekHari(idH)Hari, * from dbo.absenKhususDs('$n') order by idH,masuk";
        $krs 	= $db->createCommand($sql)->queryAll();    		
		$ds		=\app\models\Dosen::find()->where("ds_user='$n'")->one();
		
        //print_r($krs);
        $data = [
            'krs'=>$krs,
			'ds'=>$ds,
			's'=>$s
        ];
		
	        $this->layout = 'blank';
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
				'filename'=>'absen-'.$ds->ds_nm.'-'.date('YmdHis').'.pdf',
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


}
