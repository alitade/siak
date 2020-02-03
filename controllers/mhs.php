<?php

namespace app\controllers;
use Yii;
use app\models\Mahasiswa;
use app\models\MahasiswaSearch;


class mhs extends Controller{

	public function mhs(){

        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
		//\app\models\Funct::LOGS('Mengakses Halaman Mahasiswa');
		
		if($_GET['c']==1){
			$tahun="";$jurusan="";$program="";
			if($_GET['MahasiswaSearch']['jr_id']!=''){
				$jr=(int)$_GET['MahasiswaSearch']['jr_id'];
				$ModJr = \app\models\Jurusan::find()->where(['jr_id'=>$jr])->one();

			}
			if($_GET['MahasiswaSearch']['mhs_angkatan']!=''){
				$tahun= "-".((int) $_GET['MahasiswaSearch']['mhs_angkatan']);
			}

			if($_GET['MahasiswaSearch']['pr_kode']!=''){
				$pr=(int)$_GET['MahasiswaSearch']['pr_kode'];
				$ModPr = \app\models\Program::find()->where(['pr_kode'=>$pr])->one();
				$program="-".@$ModPr->pr_nama;
			}
			$Ket='Daftar Mahasiswa '.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'')." $program $tahun ";

	        $this->layout = 'blank';
			$content = $this->renderPartial('mhs_pdf',[
				'dataProvider' => $dataProvider,'Ket'=>$Ket
			]);
			
			$pdf = new Pdf([
				'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
				'content' => $content,
				'format'=>Pdf::FORMAT_LETTER,
				'marginLeft'=>5,
				'marginRight'=>5,
				'marginTop'=>20,
				'marginHeader'=>1,
				'orientation'=>'P',
				'destination'=>'I',
				'cssInline'=>'
					table{overlow:warp;font-size:12px;}
				',
				'filename'=>'Mahasiswa-'.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'All')." $program $tahun ".'-'.date('YmdHis').'.pdf',
				'options' => [
					'title' => 'Mahasiswa '.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'Semua Jurusan')." $program $tahun ",
					'subject' => 'Mahasiswa '.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'Semua Jurusan')." $program $tahun ",
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
					'SetFooter' => [ substr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],0,80).'.../.. '.date('r').' [Hal.{PAGENO}]'],
				]
			]);			
			return $pdf->render();
		}
		
        return $this->render('mhs_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
	
	
	}


}
