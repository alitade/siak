<?php
namespace app\controllers;
use Yii;
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
use yii\helpers\Url;


class TrxFingerController extends TransaksiFingerController {

 public function actionCetakForm($id)
    {

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
		$vieJadwal=Yii::$app->db->createCommand($vieJadwal)->queryAll();

 
		$header = '<table cellspacing="0" cellpaddin="0">
			        <tbody style="text-align: left;">
			        <tr><th style="width: 200px;"> Dosen </th><td> : </td><td>'.$model->bn->ds->ds_nm.'</td></tr>
			        <tr><th> Tanggal </th><td> : </td><td>'.date('d-m-Y').'</td></tr>
					<tr><th> Jadwal </th><td> : </td><td>'.Funct::HARI()[$model->jdwl_hari].', '.$model->jdwl_masuk."-".$model->jdwl_keluar.'</td></tr>	
					<tr style="height: 50px;"><th> Perubahan Jam </th><td> : </td><td>_______:_______ (jam : menit)</td></tr>
					<tr><th style="vertical-align: top;"> Alasan </th><td style="vertical-align: top;"> : </td><td
					style="border: 1px solid #999;border-collapse: collapse;height: 78px;width: 100%;"></td></tr>
					</tbody>
				   </table>';

	 

		$contentJadwal = '<hr style="margin-top:10px"><h4 style="margin-top:-15px"> Info Matakuliah</h4>
			<br>
            <table class="table table-bordered" style="font-size:8pt; margin-top:-15px;width: 100%;" cellspacing="0" border="1">
            <thead>
            <tr><th>Kode</th><th>Matakuliah</th><th>Kelas</th><th>Program</th><th>Jurusan</th></tr>
            </thead>
            <tbody >';

		if ($vieJadwal) {
			foreach ($vieJadwal as $d) {
				$contentJadwal .= "<tr>
					                    <td>$d[kode]</td>
					                    <td>$d[matakuliah]</td>
					                    <td>$d[kelas]</td>
					                    <td>$d[program]</td>
					                    <td>$d[jurusan]</td>
					               </tr>";
			}


		}

		$contentJadwal .= '</tbody>
				           </table>*Hanya berlaku untuk 1 hari<br>';

		$content = '
                <table class="table table-bordered">
                    <tr>
                        <td align="center"><img src="'. Url::to("@web/ypkp.png") .'" width="10%"></td>
                        <td align="center"><h4>Universitas Sangga Buana YPKP Bandung</h4><p><b>Direktorat Sistem Informasi & Multimedia</b></p></td>
                    </tr>
                </table><h5 style="text-align:center"><b>FORM PERUBAHAN JAM PULANG MENGAJAR</b></h5>';

		$content .= $header.$contentJadwal;

		$content .='<br><br><br><table>
					  <tr>
					    <th>____________________________</th>
					  </tr>
					  <tr>
					    <td>(Tanda Tangan Dosen)</td>
					  </tr>
					</table>';
	 	
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
            'marginLeft'=>10,
			'marginRight'=>5,
			'marginTop'=>5,
			'marginHeader'=>5,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
            'filename'=>'FORM PERUBAHAN JAM-'.$id.'-'.date('YmdHis').'.pdf',
            'options' => [
					'title' => 'FORM PERUBAHAN JAM '.$id,
					'subject' => 'FORM PERUBAHAN JAM '.$id,
					'watermarkText'=>'DIREKTORAT SISTEM INFORMASI & MULTIMEDIA',
					'watermarkTextAlpha'=>0.1,
					'showWatermarkText'=>true,
					//'debug'=>true,
				],
             // call mPDF methods on the fly
            'methods' => [ 
                //'SetHeader'=>['Form Perubahan Jam - Universitas Sangga Buana YPKP Bandung'], 
                'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'|Page {PAGENO}'.'|'.date("r")],
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


 public function actionForm($id,$t=1){

    	$model =  Jadwal::findOne($id);
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
		/*
		echo"<pre>";
		print_r($vieJadwal);
		echo"</pre>";
		die();
		#*/
		$vieJadwal=Yii::$app->db->createCommand($vieJadwal)->queryAll();

 
		$header = '<table cellspacing="0" cellpaddin="0">
			        <tbody style="text-align: left;">
			        <tr><th style="width: 200px;"> Dosen </th><td> : </td><td>'.$model->bn->ds->ds_nm.'</td></tr>
			        <tr><th> Tanggal </th><td> : </td><td>_____________ ,'.date('Y').'</td></tr>
					<tr><th> Jadwal </th><td> : </td><td>'.Funct::HARI()[$model->jdwl_hari].', '.$model->jdwl_masuk."-".$model->jdwl_keluar.'</td></tr>	
					<tr style="height: 50px;"><th> Perubahan Jam </th><td> : </td><td>_______:_______ (jam : menit)</td></tr>
					<tr><th style="vertical-align: top;"> Alasan </th><td style="vertical-align: top;"> : </td><td
					style="border: 1px solid #999;border-collapse: collapse;height: 78px;width: 100%;"></td></tr>
					</tbody>
				   </table>';

	 

		$contentJadwal = '<hr style="margin-top:10px"><h4 style="margin-top:-15px"> Info Matakuliah</h4>
			<br>
            <table class="table table-bordered" style="font-size:8pt; margin-top:-15px;width: 100%;" cellspacing="0" border="1">
            <thead>
            <tr><th>Kode</th><th>Matakuliah</th><th>Kelas</th><th>Program</th><th>Jurusan</th></tr>
            </thead>
            <tbody >';
		$thn='';
		if ($vieJadwal) {
			foreach ($vieJadwal as $d) {
				$thn=$d['tahun'];
				$contentJadwal .= "
				<tr>
					<td>$d[kode]</td>
					<td>$d[matakuliah]</td>
					<td>$d[kelas]</td>
					<td>$d[program]</td>
					<td>$d[jurusan]</td>
			   </tr>";
			}


		}

		$contentJadwal .= '</tbody>
				           </table>*Hanya berlaku untuk 1 hari<br>';

		$content = '
                <table class="table table-bordered">
                    <tr>
                        <td align="center"><img src="'. Url::to("@web/ypkp.png") .'" width="10%"></td>
                        <td align="center"><h4>Universitas Sangga Buana YPKP Bandung</h4><p><b>Direktorat Sistem Informasi & Multimedia</b></p></td>
                    </tr>
                </table><h5 style="text-align:center"><b>FORM PERUBAHAN JAM '.($t=='1'?'PULANG':'MASUK').' PERKULIAHAN TAHUN AKADEMIK '.$thn.'</b></h5>';

		$content .= $header.$contentJadwal;

		$content .='<br><br><br><table>
					  <tr>
					    <th>____________________________</th>
					  </tr>
					  <tr>
					    <td>(Tanda Tangan Dosen)</td>
					  </tr>
					</table>';
	 	
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
            'marginLeft'=>10,
			'marginRight'=>5,
			'marginTop'=>5,
			'marginHeader'=>5,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
            'filename'=>'FORM PERUBAHAN JAM-'.$id.'-'.date('YmdHis').'.pdf',
            'options' => [
					'title' => 'FORM PERUBAHAN JAM '.$id,
					'subject' => 'FORM PERUBAHAN JAM '.$id,
					'watermarkText'=>'DIREKTORAT SISTEM INFORMASI & MULTIMEDIA',
					'watermarkTextAlpha'=>0.1,
					'showWatermarkText'=>true,
					//'debug'=>true,
				],
             // call mPDF methods on the fly
            'methods' => [ 
                //'SetHeader'=>['Form Perubahan Jam - Universitas Sangga Buana YPKP Bandung'], 
                'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'|Page {PAGENO}'.'|'.date("r")],
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


 public function actionForm1($id){

    	$model =  Jadwal::findOne($id);
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
		INNER JOIN tbl_bobot_nilai bn on(bn.id=jdw.bn_id and(bn.RStat is NULL or bn.RStat='0'))
		INNER JOIN tbl_dosen ds on(ds.ds_id=bn.ds_nidn and(ds.RStat is NULL or ds.RStat='0'))
		INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and(mk.RStat is NULL or mk.RStat='0'))
		INNER JOIN tbl_kalender kln on(kln.kln_id=bn.kln_id and(kln.RStat is NULL or kln.RStat='0') and kr_kode='11617')
		INNER JOIN tbl_jurusan jr on(jr.jr_id=kln.jr_id)
		INNER JOIN tbl_program pr on(pr.pr_kode=kln.pr_kode )		
		WHERE (jdw.jdwl_id is NOT NULL)
		and (jdw.GKode='$model->GKode' or jdw.jdwl_id='$model->jdwl_id')
		";
		$vieJadwal=Yii::$app->db->createCommand($vieJadwal)->queryAll();

 
		$header = '<table cellspacing="0" cellpaddin="0">
			        <tbody style="text-align: left;">
			        <tr><th style="width: 200px;"> Dosen </th><td> : </td><td>'.$model->bn->ds->ds_nm.'</td></tr>
			        <tr><th> Tanggal </th><td> : </td><td>_____________ ,'.date('Y').'</td></tr>
					<tr><th> Jadwal </th><td> : </td><td>'.Funct::HARI()[$model->jdwl_hari].', '.$model->jdwl_masuk."-".$model->jdwl_keluar.'</td></tr>	
					<tr style="height: 50px;"><th> Perubahan Jam </th><td> : </td><td>_______:_______ (jam : menit)</td></tr>
					<tr><th style="vertical-align: top;"> Alasan </th><td style="vertical-align: top;"> : </td><td
					style="border: 1px solid #999;border-collapse: collapse;height: 78px;width: 100%;"></td></tr>
					</tbody>
				   </table>';

	 

		$contentJadwal = '<hr style="margin-top:10px"><h4 style="margin-top:-15px"> Info Matakuliah</h4>
			<br>
            <table class="table table-bordered" style="font-size:8pt; margin-top:-15px;width: 100%;" cellspacing="0" border="1">
            <thead>
            <tr><th>Kode</th><th>Matakuliah</th><th>Kelas</th><th>Program</th><th>Jurusan</th></tr>
            </thead>
            <tbody >';
		$thn='';
		if ($vieJadwal) {
			foreach ($vieJadwal as $d) {
				$thn=$d['tahun'];
				$contentJadwal .= "
				<tr>
					<td>$d[kode]</td>
					<td>$d[matakuliah]</td>
					<td>$d[kelas]</td>
					<td>$d[program]</td>
					<td>$d[jurusan]</td>
			   </tr>";
			}


		}

		$contentJadwal .= '</tbody>
				           </table>*Hanya berlaku untuk 1 hari<br>';

		$content = '
                <table class="table table-bordered">
                    <tr>
                        <td align="center"><img src="'. Url::to("@web/ypkp.png") .'" width="10%"></td>
                        <td align="center"><h4>Universitas Sangga Buana YPKP Bandung</h4><p><b>Direktorat Sistem Informasi & Multimedia</b></p></td>
                    </tr>
                </table><h5 style="text-align:center"><b>FORM PERUBAHAN JAM '.($t=='1'?'PULANG':'MASUK').' PERKULIAHAN TAHUN AKADEMIK '.$thn.'</b></h5>';

		$content .= $header.$contentJadwal;

		$content .='<br><br><br><table>
					  <tr>
					    <th>____________________________</th>
					  </tr>
					  <tr>
					    <td>(Tanda Tangan Dosen)</td>
					  </tr>
					</table>';
	 	
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
            'marginLeft'=>10,
			'marginRight'=>5,
			'marginTop'=>5,
			'marginHeader'=>5,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
            'filename'=>'FORM PERUBAHAN JAM-'.$id.'-'.date('YmdHis').'.pdf',
            'options' => [
					'title' => 'FORM PERUBAHAN JAM '.$id,
					'subject' => 'FORM PERUBAHAN JAM '.$id,
					'watermarkText'=>'DIREKTORAT SISTEM INFORMASI & MULTIMEDIA',
					'watermarkTextAlpha'=>0.1,
					'showWatermarkText'=>true,
					//'debug'=>true,
				],
             // call mPDF methods on the fly
            'methods' => [ 
                //'SetHeader'=>['Form Perubahan Jam - Universitas Sangga Buana YPKP Bandung'], 
                'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'|Page {PAGENO}'.'|'.date("r")],
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



 public function actionFormPergantian($id,$k=''){
		$tr	=	\app\models\Rekap::find()->where(["concat(replace(tgl_ins,'-',''),jdwl_hari,jdwl_id)"=>$k])->one();    	
		$model 	=  Jadwal::findOne($id);
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
		INNER JOIN tbl_bobot_nilai bn on(bn.id=jdw.bn_id and(bn.RStat is NULL or bn.RStat='0'))
		INNER JOIN tbl_dosen ds on(ds.ds_id=bn.ds_nidn and(ds.RStat is NULL or ds.RStat='0'))
		INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and(mk.RStat is NULL or mk.RStat='0'))
		INNER JOIN tbl_kalender kln on(kln.kln_id=bn.kln_id and(kln.RStat is NULL or kln.RStat='0') and kr_kode='11617')
		INNER JOIN tbl_jurusan jr on(jr.jr_id=kln.jr_id)
		INNER JOIN tbl_program pr on(pr.pr_kode=kln.pr_kode)		
		WHERE (jdw.jdwl_id is NOT NULL)
		and (jdw.GKode='$model->GKode' or jdw.jdwl_id='$model->jdwl_id')
		";
		$vieJadwal=Yii::$app->db->createCommand($vieJadwal)->queryAll();

 
		$header = '<table cellspacing="0" cellpaddin="0">
			        <tbody style="text-align: left;">
			        <tr><th> Tanggal Permintaan</th><td> : </td><td>_____________</td></tr>
			        <tr><th style="width: 150px;"> Dosen </th><td> : </td><td>'.$model->bn->ds->ds_nm.'</td></tr>
			        <tr><th style="width: 150px;"> No. Tlp. </th><td> : </td><td>________________</td></tr>
					<tr>'
					.($k==""?
						'<th> Jadwal Awal </th><td> : </td><td>'.Funct::HARI()[$model->jdwl_hari].', __________________ ('.$model->jdwl_masuk."-".$model->jdwl_keluar.')<br />
						<i style="font-size:12">(tanggal (jam masuk - jam keluar))</i>
						</td>'
						:'<th> Jadwal Awal</th><td> : </td><td>'.Funct::HARI()[$model->jdwl_hari].', '.Funct::TANGGAL($tr['tgl_ins']).' ('.$model->jdwl_masuk."-".$model->jdwl_keluar.')</td>					
					')
					.'</tr>
					<tr>
			        <tr><th style="width: 150px;"> Sesi </th><td> : </td><td>____</td></tr>
					<tr style="height: 50px;">
						<th> Perubahan Jadwal </th><td> : </td>
						<td>________________ (______:______ - ______:______)<br />
						<i style="font-size:12">(tanggal (jam masuk - jam keluar))</i></td>
					</tr>
					<tr><th style="vertical-align: top;"> Alasan </th><td style="vertical-align: top;"> : </td><td
					style="border: 1px solid #999;border-collapse: collapse;height: 78px;width: 100%;"></td></tr>
					</tbody>
				   </table><i style="font-size:12px;font-weight:bold">* Hanya berlaku untuk 1 hari</i>
				   ';

	 

		$contentJadwal = '<hr style="margin-top:10px"><h4 style="margin-top:-15px"> Info Matakuliah</h4>
			<br>
            <table class="table table-bordered" style="font-size:8pt; margin-top:-15px;width: 100%;" cellspacing="0" border="1">
            <thead>
            <tr><th>Kode</th><th>Matakuliah</th><th>Kelas</th><th>Program</th><th>Jurusan</th></tr>
            </thead>
            <tbody >';
		$thn='';
		if ($vieJadwal) {
			foreach ($vieJadwal as $d) {
				$thn=$d['tahun'];
				$contentJadwal .= "
				<tr>
					<td>$d[kode]</td>
					<td>$d[matakuliah]</td>
					<td>$d[kelas]</td>
					<td>$d[program]</td>
					<td>$d[jurusan]</td>
			   </tr>";
			}
		}

		$contentJadwal .= '</tbody></table><br>';
		$content = '
                <table class="table table-bordered">
                    <tr>
                        <td align="center"><img src="'. Url::to("@web/ypkp.png") .'" width="10%"></td>
                        <td align="center"><h4>Universitas Sangga Buana YPKP Bandung</h4><p><b>Direktorat Sistem Informasi & Multimedia</b></p></td>
                    </tr>
                </table><h5 style="text-align:center;font-size:12px"><b><u>FORM PERGANTIAN JADWAL PERKULIAHAN TAHUN AKADEMIK '.$thn.'</u></b></h5>
				<br /><br />
				<!-- FRM1-<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>-'.date('m').'-'.date('Y').' -->
				';

		$content .= $header.$contentJadwal;

		$content .='<br><br><br><table>
					  <tr>
					    <th>______________________________</th>
					  </tr>
					  <tr>
					    <td>(Tanda Tangan & Nama Jelas Dosen)</td>
					  </tr>
					</table>';
	 	//return;
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
            'marginLeft'=>10,
			'marginRight'=>5,
			'marginTop'=>5,
			'marginHeader'=>5,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
			'cssInline'=>'table tr td,table tr th{padding:2px} ',
            'filename'=>'FORM PERGANTIAN JADWAL -'.$id.'-'.date('YmdHis').'.pdf',
            'options' => [
					'title' => 'FORM PERGANTIAN JADWAL '.$id,
					'subject' => 'FORM PERGANTIAN JADWAL '.$id,
					'watermarkText'=>'DIREKTORAT SISTEM INFORMASI & MULTIMEDIA',
					'watermarkTextAlpha'=>0.1,
					'showWatermarkText'=>true,
					//'debug'=>true,
				],
             // call mPDF methods on the fly
            'methods' => [ 
                //'SetHeader'=>['Form Perubahan Jam - Universitas Sangga Buana YPKP Bandung'], 
                'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'|Page {PAGENO}'.'|'.date("r")],
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


}
	