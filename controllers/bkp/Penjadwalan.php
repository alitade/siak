<?php
namespace app\controllers;
use  yii\web\Session;
use yii\data\ActiveDataProvider;
use Yii;

use app\models\Ruang;

use kartik\mpdf\Pdf;
use yii\helpers\Url;


use app\models\Funct;

use app\models\Jadwal;
use app\models\JadwalSearch;
use app\models\KrsSearch;


use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

use yii\db\Query;
use yii\data\ArrayDataProvider;

$connection = \Yii::$app->db;


class Penjadwalan {
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
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

    public function PesertaKuliah($id){

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
        from $tbl where jdwl_id='".$model->jdwl_id."'
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
				<th style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>No</th>
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
			FROM $tbl a
			INNER JOIN tbl_krs k on(k.krs_id=a.krs_id AND ISNULL(k.RStat,0)=0 AND k.jdwl_id=$id and k.krs_stat ='1')
			INNER JOIN tbl_jadwal j on(j.jdwl_id=k.jdwl_id AND ISNULL(j.RStat,0)=0)
			INNER JOIN tbl_bobot_nilai bn on(bn.id=j.bn_id and ISNULL(bn.RStat,0)=0)
			LEFT JOIN $keuangan.dbo.student st ON (st.nim COLLATE Latin1_General_CI_AS = k.mhs_nim)
			LEFT JOIN $keuangan.dbo.people p ON (p.No_Registrasi = st.no_registrasi)
			where a.jdwl_id=$id				
		   ";


        $data = Yii::$app->db->createCommand($query1)->queryAll();
        $no=1;
		$mTot=0;
		$mHdTot=0;
		$value='';
        foreach ($data as $key) {
			
            $table .= "<tr>
			<td style='font-weight: bold; font-size: 14px;'>".$no++."</td>
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
		$model		= Jadwal::findOne($id);
		$ModJdwl	= Jadwal::find()->where(['GKode'=>$model->GKode])->all();
		$user		= " select  fid from user_ where username='".$model->bn->ds->ds_user."' and fid is not null";
		$user=Yii::$app->db->createCommand($user)->QueryOne();
		$ModG= 
		"
			select distinct
				tf.jdwl_hari,tf.tgl_ins, left(tf.jdwl_masuk,5) jdwl_masuk, left(tf.jdwl_keluar,5) jdwl_keluar 
				,concat(replace(tf.tgl_ins,'-',''),tf.jdwl_hari,tf.ds_fid,left(tf.jdwl_masuk,2)) kode,tf.sesi
			from t_finger_pengganti tf 
			inner join tbl_jadwal jdw on(jdw.jdwl_id=tf.jdwl_id)
			where jdw.GKode='$model->GKode'
			and tf.tgl_ins >= cast(getdate() as date)			
		";
		$ModG=Yii::$app->db->createCommand($ModG)->QueryAll();
			
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
				)
				SELECT
					krs.krs_id,krs.krs_stat,$user[fid],mk.mtk_kode,mk.mtk_nama,
					jdw.jdwl_id,
					(DATEPART(dw,'$tgl')-1),
					'$masuk','$keluar',
					
					mhs.Fid,CAST('$tgl' as DATE)
					,'$tglAwal[tgl]'
					,'$sesi'
				from tbl_jadwal jdw
				INNER JOIN tbl_bobot_nilai bn on(bn.id=jdw.bn_id and(bn.RStat is NULL or bn.RStat='0'))
				INNER JOIN tbl_dosen ds on(ds.ds_id=bn.ds_nidn)
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
				and (
					jdw.GKode='$model->GKode'
					or(jdwl_hari=$model->jdwl_hari and left(jdwl_masuk,2)=left('$model->jdwl_masuk',2)) 
				)			 			
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

}
