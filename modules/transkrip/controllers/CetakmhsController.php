<?php

namespace app\modules\transkrip\controllers;

use Yii;
use Yii\db\Query;

use app\modules\transkrip\models\Wisuda;
use app\modules\transkrip\models\Pejabat;

use app\modules\transkrip\models\Nilai;
use app\modules\transkrip\models\NilaiSearch;

use app\models\Mahasiswa;
use app\models\MahasiswaSearch;

use kartik\mpdf\Pdf;
use yii\helpers\Url;

use app\models\Krs;
use app\models\KrsSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * NilaiController implements the CRUD actions for Nilai model.
 */
class CetakmhsController extends ModController{



    public function actionTranskrip2($id){
        $Nilai = Nilai::find()->where(['npm'=>$id])->all();

        $ni =
            '

	<table width="100%" style="font-size:10px">
		<tr>
			<td rowspan="3" width="15%"><center><img src="'.Url::to("@web/ypkp.png").'" width="10%"></center></td>
			<td width="45%" valign="bottom">
				<h4><b>UNIVERSITAS SANGGA BUANA YPKP</b></h4>
			</td>
		</tr>
		<tr>
			<td>Jalan PHH Mustopa No. 68 - Bandung 40124</td>
		</tr>
		<tr>
			<td>Telp. 022-7202233 / Fax. 022-7201756</td>
			<td>Website : http://www.usbypkp.ac.id &nbsp; Email : admin@usbypkp.ac.id</td>
		</tr>
	</table>
<p style="border-style: solid; border-width: 3px; margin-bottom: 5px;"></p>
<p style="border-style: solid; border-width: 1px;"></p>

	<table width="100%">
		<tr>
			<td><u><center><b>TRANSKRIP NILAI AKADEMIK</b></center></u></td>
		</tr>
	</table>
<br />
	<table style="font-size:10px" width="100%">
		<tr>
			<td width="80px" style="padding-bottom: 5px;">Nama</td>
			<td style="padding-bottom: 5px;" width="2%">&nbsp;:&nbsp;</td>
			<td style="padding-bottom: 5px;">Nama</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td width="80px" style="padding-bottom: 5px;">NPM</td>
			<td style="padding-bottom: 5px;">&nbsp;:&nbsp;</td>
			<td style="padding-bottom: 5px;">A101415RT5010</td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td>&nbsp;:&nbsp;</td>
			<td>Alamat</td>

			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>

			<td>Program Studi</td>
			<td width="2%">&nbsp;:&nbsp;</td>
			<td>S1 ILMU KOMUNIKASI DAN ADMINISTRASI BISNIS</td>
		</tr>
	</table>
	
<br />

		<table width="100%" style="font-size:10px; border:1px solid black;border-collapse:collapse;">
			<tr style="border-style: solid; border-width: 1px">
				<th style="text-align: center; border:1px solid;">NO</th>
				<th style="text-align: center; border:1px solid;">Kode</th>
				<th style="text-align: center; border:1px solid;">Matakuliah</th>
				<th style="text-align: center; border:1px solid;">SKS</th>
				<th style="text-align: center; border:1px solid;">Nilai</th>

				<th colspan="1" rowspan="4"></th>
				
				<th style="text-align: center; border:1px solid;">NO</th>
				<th style="text-align: center; border:1px solid;">Kode</th>
				<th style="text-align: center; border:1px solid;">Matakuliah</th>
				<th style="text-align: center; border:1px solid;">SKS</th>
				<th style="text-align: center; border:1px solid;">Nilai</th>
			</tr>
			<tr>
				<th colspan="5" style="border:1px solid;"><center>SEMESTER 1</center></th>
				
				<th colspan="5" style="border:1px solid;"><center>SEMESTER 7</center></th>
			</tr>
			<tr>
				<td style="border:1px solid;"></td>
				<td style="border:1px solid;"></td>
				<td style="border:1px solid;"></td>
				<td style="border:1px solid;"></td>
				<td style="border:1px solid;"></td>
				
				<td style="border:1px solid;"></td>
				<td style="border:1px solid;"></td>
				<td style="border:1px solid;"></td>
				<td style="border:1px solid;"></td>
				<td style="border:1px solid;"></td>
			</tr>
			<tr>
				<th colspan="3" style="border:1px solid;">&nbsp;&nbsp;Jumlah SKS = </th>
				<th colspan="2" style="border:1px solid;">&nbsp;&nbsp;IP = </th>
						
				<th colspan="3" style="border:1px solid;">&nbsp;&nbsp;Jumlah SKS = </th>
				<th colspan="2" style="border:1px solid;">&nbsp;&nbsp;IP = </th>
		</table>
		
		<br />
		<table width="100%" frame="box" style="font-size:10px">
			<tr>
				<th colspan="3" style="padding-bottom: 5px;">&nbsp;&nbsp;Rangkuman Nilai :</th>
			</tr>
			<tr>
				<th width="30%" style="padding-bottom: 5px;">&nbsp;&nbsp;Total SKS</th>
				<th width="2%">:</th>
				<th>Tes</th>
			</tr>
			<tr>
				<th style="padding-bottom: 5px;">&nbsp;&nbsp;Total Matakuliah</th>
				<th>:</th>
				<th>Tes</th>
			</tr>
			<tr>
				<th style="padding-bottom: 5px;">&nbsp;&nbsp;IPK</th>
				<th>:</th>
				<th>Tes</th>
			</tr>
		</table>

	<p style="border-style: solid; border-width: 1px; margin-bottom: 5px;"></p>
	<p style="border-style: solid; border-width: 3px;"></p>


			';

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $ni,
            'marginLeft'=>'5px',
            'marginRight'=>'5px',
            'marginTop'=>'5px',
            'defaultFontSize'=>'10',
            'cssInline'=>'
				body{
					background-image: url(a.png);
				}
			',
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'methods' => [
                'SetHeader'=>[''],
                'SetFooter'=>[''],
            ]
        ]);

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');

        return $pdf->render();

    }



    public function actionCetak($id,$mode='1',$f='A4'){
        $model	= new Nilai;
        $add	= true;
        $Layout	= '/nilai/cetakmhs';
//        $Header	= '/nilai/cetak_nilai2';
        $Footer  = '&nbsp;';
        $PdfSize = Pdf::FORMAT_LEGAL;
        $MrgL='5mm';
        $MrgR='5mm';
        $MrgT='31mm';
        $MrgB='5mm';
        $FontSize='10px';
        $StyBor='';

        if($mode==2){
            $Layout	= '/nilai/transkrip_f';
            $Header	= '';
            $Footer = '';
            $PdfSize=[217,330];
            $MrgL='16mm';
            $MrgR='16mm';
            $MrgT='45mm';
            $MrgB='10mm';

        }

        if($mode==3){
            $Layout	= '/nilai/transkrip_f2';
            $Header	=false;#*/ '&nbsp;';
            $Footer =false;#*/ '&nbsp;';
            $PdfSize=[217,330];
            $MrgL='16mm';
            $MrgR='16mm';
            $MrgT='46mm';
            $MrgB='10mm';
            $FontSize='15pt';
            $StyBor='border:solid 1pt #000;';

        }
        $ModNil	= Nilai::find()->select(['npm','kode_mk','semester','nama_mk','huruf','sks','nilai'])->where("
			npm='$id' and(stat is null or stat='0')
		")->distinct(true)->orderBy([
            'semester'=>SORT_ASC,
            'kode_mk'=>SORT_ASC
        ])
            ->all();
        $ModMhs = \app\models\Mahasiswa::findOne($id);
        $MHS    = \app\models\Mahasiswa::MHS($id);
        $Head 		= Wisuda::find()->where(['npm'=>$id]);
        $Pejabat 	= Pejabat::find();
        $Rektor = $Pejabat->where([
            'jabatan'=>'Rektor',
            'status'=>'1'
        ])
            ->orderBy([
                'thn_jabatan'=>SORT_DESC
            ])
            ->one();
        //echo count($ModNil);
        //die();
        $Pejabat 	= $Pejabat->where([
            //'npm'=>$id,
            'kode_fakultas'=>$ModMhs->jr->fk_id,
            'status'=>'1'
        ])
            ->orderBy(['thn_jabatan'=>SORT_DESC])
            ->one();
        $NoTrans=[];
        $Ta=[];
        foreach($Head->all() as $d){
            $NoTrans[]=$d->kode_;
            $Ta[]=$d->skripsi_indo;
        }

        if(is_array($NoTrans)){$NoTrans=implode(";",$NoTrans);}
        if(is_array($Ta)){$Ta=implode(";",$Ta);}
        $ni = $this->renderPartial( $Layout,[
            'ModMhs'=>$ModMhs,
            'ModNil'=>$ModNil,
            'model'=>$model,
            'Pejabat'=>$Pejabat,
            'Rektor'=>$Rektor,
            'NoTrans'=>$NoTrans,
            'Ta'=>$Ta,
            'add'=>$add,
            'MHS'=>$MHS,
            'ModHead'=>$Head->one(),
            'c'=>count($ModNil),
        ]);



        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => $PdfSize,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $ni,
            'marginLeft'=>$MrgL,
            'marginRight'=>$MrgR,
            'marginTop'=>$MrgT,
            'marginBottom'=>$MrgB,
            //'defaultFontSize'=>'10',
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline'=>'
				 td,th {
					border-collapse:collapse;
				}				

				 .data td, .data th {
					border-collapse:collapse;
					padding-left:1mm;
					padding-right:1mm;
				}				

				.bd{
					border-collapse:collapse;
					border:solid 1px #000;
				}				
				.bdr{
					border-collapse:collapse;
					border-right:solid 1px #000;
				}				

				.ft{
					border-collapse:collapse;
					border-right:solid 1px #000;
					width:40mm;
					height:40mm;
					
				}				

				table .data{
					background:#000000;
					font-size:'.$FontSize.';
					border-collapse:collapse;
					'.$StyBor.'
				}
				
				.data tr{
					background:#ffffff;
				}				
			',
            'methods' => [
                'setHeader'=>["$Header"],
                'setFooter'=>["$Footer"],
            ],
            'options'=>[
                //'adjustFontDescLineheight'=>1,
                'shrink_tables_to_fit'=>0,
            ]

        ]);

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();

    }
    public function actionCetaks2($id){
        $model	= new Nilai;
        $add	= true;
        $Layout	= '/nilai/transkrip_s2';
        $PdfSize = Pdf::FORMAT_LEGAL;
        $Header	= '';
        $Footer = '';
        /*
        $PdfSize=[217,330];
        $MrgL='10mm';
        $MrgR='10mm';
        $MrgT='46mm';
        $MrgB='10mm';
        $FontSize='9pt';
        */
        $MrgL		= '16mm';
        $MrgR		= '16mm';
        $MrgT		= '46mm';
        $MrgB		= '10mm';
        $FontSize='9pt';

        $StyBor='border:solid 1pt #000;';
        $ModNil	= Nilai::find()->select(['npm','kode_mk','nama_mk','huruf','sks','nilai'])->where("
			npm='$id' and(stat is null or stat='0')
		")->distinct(true)->orderBy(['kode_mk'=>SORT_ASC])
            ->all();
        $ModMhs = \app\models\Mahasiswa::findOne($id);
        $Head 		= Wisuda::find()->where(['npm'=>$id]);
        $Pejabat 	= Pejabat::find();
        $Rektor = $Pejabat->where([
            'jabatan'=>'Rektor',
            'status'=>'1'
        ])
            ->orderBy([
                'thn_jabatan'=>SORT_DESC
            ])
            ->one();
        //echo count($ModNil);
        //die();
        $Pejabat 	= $Pejabat->where([
            //'npm'=>$id,
            'kode_jurusan'=>$ModMhs->jr_id,
            'status'=>'1'
        ])
            ->orderBy(['thn_jabatan'=>SORT_DESC])
            ->one();
        $NoTrans=[];
        $Ta=[];
        $Ta1=[];
        foreach($Head->all() as $d){
            $NoTrans[]=$d->kode_;
            $Ta[]=$d->skripsi_indo;
            $Ta1[]=$d->skripsi_end;
        }

        if(is_array($NoTrans)){$NoTrans=implode(";",$NoTrans);}
        if(is_array($Ta)){$Ta=implode(";",$Ta);}
        if(is_array($Ta1)){$Ta1=implode(";",$Ta1);}
        $ni = $this->renderPartial( $Layout,[
            'ModMhs'=>$ModMhs,
            'ModNil'=>$ModNil,
            'model'=>$model,
            'Pejabat'=>$Pejabat,
            'Rektor'=>$Rektor,
            'NoTrans'=>$NoTrans,
            'Ta'=>$Ta,
            'Ta1'=>$Ta1,
            'add'=>$add,
            'ModHead'=>$Head->one(),
            'c'=>count($ModNil),
        ]);


        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => $PdfSize,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $ni,
            'marginLeft'=>$MrgL,
            'marginRight'=>$MrgR,
            'marginTop'=>$MrgT,
            'marginBottom'=>$MrgB,
            //'defaultFontSize'=>'10',
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'defaultFont'=>'Times New Roman',
            'cssInline'=>"
				*{
					font-family:'Times New Roman';
				}
				 td,th {
					border-collapse:collapse;
					padding:0px;
				}				

				 #data td, #data th {
					border-collapse:collapse;
					padding:1mm;
				}				

				.bd{
					border-collapse:collapse;
					border:solid 1px #000;
				}				
				.bdr{
					border-collapse:collapse;
					border-right:solid 1px #000;
					padding:0px;
					FONT-SIZE:10pt;
				}				

				.ft{
					border-collapse:collapse;
					border-right:solid 1px #000;
					width:40mm;
					height:40mm;
					
				}				

				#data{
					background:#000000;
					font-size:'.$FontSize.';
					border-collapse:collapse;
					'.$StyBor.'
				}
				
				#data tr{
					background:#ffffff;
				}				
			",
            'methods' => [
                'setHeader'=>["$Header"],
                'setFooter'=>["$Footer"],
            ],
            'options'=>[
                //'adjustFontDescLineheight'=>1,
                'shrink_tables_to_fit'=>0,
            ]

        ]);

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        return $pdf->render();

    }


}
