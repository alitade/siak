<?php

namespace app\modules\transkrips2\controllers;

use Yii;
use Yii\db\Query;

use app\modules\transkrip\models\Nilai;
use app\modules\transkrip\models\NilaiSearch;

use app\models\Mahasiswa;
use app\models\MahasiswaSearch;

use app\models\Jadwal;
use app\models\JadwalSearch;

use kartik\mpdf\Pdf;
use yii\helpers\Url;

use app\models\Krs;
use app\models\KrsSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

use yii\helpers\Html;

/**
 * NilaiController implements the CRUD actions for Nilai model.
 */
class NilaiController extends ModController{
	

    public function actionIndex(){
        $searchModel = new NilaiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

	public function actionCetakAll(){
		$chk=$_POST['chk'];
		if(is_array($chk)){
			//$chk="'".implode("','",$chk)."'";
			$TABLE="";
			$ModMhs = \app\models\Mahasiswa::find()->where(" mhs_nim=:n and jr_id in(select jr_id from tbl_jurusan where jr_jenjang='s2')",[':n'=>$id])->one();
			$n=0;
			foreach($ModMhs as $data){
				$n++;
				$ModNil	= Nilai::find()->select(['npm','kode_mk','semester','nama_mk','huruf','sks','nilai'])->where("
					npm='$data[mhs_nim]' and(stat is null or stat='0')
				")->distinct(true)->orderBy(['semester'=>SORT_ASC,'kode_mk'=>SORT_ASC])->all();
				//foreach($ModNil as $data1){
					
				$no1=0;$Smster=1;$Kod=[];$TotSks=0;$SumSks=0;$Row1="";$Row="";$no=0;
				$Row2="";
				$pembagi=0;
				$c=count($ModNil);
				if($c>0){
					$c+=3;
					if($ModMhs->jr->jr_jenjang=='S1'){$c+=8;}
					else if($ModMhs->jr->jr_jenjang=='D3'){$c+=6;}
					$pembagi=$c/2;
					if($c%2!=0){$pembagi=($c+1)/2;}
				}
				
				$Sks=0;
				$Nil=0;
				// var_dump($ModNil);
				foreach($ModNil as $data1){
					$Sum = $data1['sks']*(int)$data1['nilai'];
					$SumSks+=$Sum;	
				
					$TotSks+=$data1['sks'];
					
					$Kod[$data1['kode_mk'].$data1['huruf']]='';
					$no1++;
					$no++;
				
					$a=filter_var($data1['kode_mk'], FILTER_SANITIZE_NUMBER_INT);
					$SmsterSub=substr($a,0,1);
					if($Smster!=$SmsterSub){
						$Smster=$SmsterSub;
						if($no<=$pembagi){
							$Row.='
							<tr>
								<td valign="top" colspan="3"><b>&nbsp;Jumlah&nbsp;SKS&nbsp;=&nbsp;'.$Sks.'</b></td>
								<td valign="top" colspan="2"><b><center>IP&nbsp;=&nbsp;'.number_format(($SumNil/$Sks),2).'</center></b></td>
							</tr>		
							<tr>
								<th colspan="5" align="center">&nbsp;SEMESTER&nbsp;'.$Smster.'</th>
							</tr>';
						}else{
							$Row2.='
							<tr>
								<td valign="top" colspan="3"><b>&nbsp;Jumlah&nbsp;SKS&nbsp;=&nbsp;'.$Sks.'</b></td>
								<td valign="top" colspan="2"><b><center>IP&nbsp;=&nbsp;'.number_format(($SumNil/$Sks),2).'</center></b></td>
							</tr>		
							<tr>
								<th colspan="5" align="center">&nbsp;SEMESTER&nbsp;'.$Smster.'</th>
							</tr>';
						}
						$no++;
						$Sks=0;
						$SumNil=0;
					}
					$Nil = $data1['sks']*(int)$data1['nilai'];
					$SumNil+=$Sum;	
					$Sks+=$data1['sks'];
					if($no<=$pembagi){
						$Row.="
						<tr align='left' valign='top'> 
							<td valign='top'><center>$no1</center></td>
							<td valign='top'> $data1[kode_mk]".($data1['kat']==1?'*':"")." </td>
							<td valign='top'> ".Html::decode($data1['nama_mk'])."</td>
							<td valign='top'><center>$data1[sks]</center></td>
							<td valign='top'><center>$data1[huruf]</center></td>
						</tr>";
					}else{
						$Row2.="
						<tr align='left'> 
							<td valign='top'><center>$no1</center></td>
							<td valign='top'> $data1[kode_mk]".($data1['kat']==1?'*':"")." </td>
							<td valign='top'> ".Html::decode($data1['nama_mk'])."</td>
							<td valign='top'><center>$data1[sks]</center></td>
							<td valign='top'><center>$data1[huruf]</center></td>
						</tr>
						";
					}
				}
				$DataSks='
				<tr>
					<td valign="top" colspan="3"><b> &nbsp;Jumlah SKS = '.$Sks.'</b></td>
					<td valign="top" colspan="2"><b><center>IP = '.number_format(($SumNil/$Sks),2).'</center></b></td>
				</tr>
				';
				$Row1.=$DataSks;
				$Row2.=$DataSks;

				/// table inti
				$TABLE.=($n>1?'<pagebreak>':'').'
				<table width="100%">
					<tr><td><center><div style="border-bottom:solid 1pt #000;font-weight:bold">TRANSKRIP NILAI AKADEMIK</div></center></td></tr>
				</table>
				<table width="100%" style="font-size:11px;" cellspacing="0">
					<tr>
					<td width="49%" valign="top" >
						<table>
							<tr><td width="1px">Nama</td><td>:</td><td>'.$data->mhs->people->Nama.'</td></tr>
							<tr><td>Alamat</td><td>:</td><td>'.$data->mhs->people->alamat.'</td></tr>
						</table>
					</td>
					<td>&nbsp;</td>
					<td width="49%" valign="top" align="right">
						<table style="text-align:left">
							<tr><td width="1px">NPM</td><td width="1px">:</td><td>'.$data->mhs_nim.'</td></tr>
							<tr><td>Program&nbsp;Studi</td><td width="1px">:</td><td>'.\app\models\Funct::JURUSAN()[$data->jr_id].'</td>
							</tr>
						</table>
					</td>
					</tr>
				</table><br />
				<table width="100%" cellspacing="0" >
					<tr>
					<td width="49%" valign="top">
						<table width="100%" border="1" class="data"  cellspacing="0">
							<tr><th>NO</th><th>Kode</th><th>Matakuliah</th><th>SKS</th><th>Nilai</th></tr>
							<tr><th colspan="5" align="center"> SEMESTER 1</th></tr>
							<tr>'.$Row.'</tr>	
						</table>
					</td>
					<td>&nbsp;</td>
					<td width="49%" valign="top">
						<table  width="100%" border="1" class="data" cellspacing="0">
							<tr><th>NO</th><th>Kode</th><th>Matakuliah</th><th>SKS</th><th>Nilai</th></tr>
							<tr>'.$Row2.'</tr>	
						</table>
						<table border="0" style="border:solid 1px #000;border-collapse:collapse;margin-top:5px" width="100%" class="data" >
							<tr><th colspan="3" style="border-bottom:solid 1px #000;">Rangkuman&nbsp;Nilai</th></tr>	
							<tr><th width="1px">Total&nbsp;SKS</th><th width="1px">:</th><th>'.$TotSks.' SKS</th></tr>
							<tr><th>Total&nbsp;Matakuliah</th><th>:</th><th>'.count($ModNil).'</th></tr>
							<tr><th>IPK</th><th>:</th><th>'.number_format(($SumSks/$TotSks),2).'</th></tr>
						</table>
					</td>
					</tr>
				</table>';
				unset($Kod);
				$SumNil=0;
				/// table inti

				
			}
			
			$content=$TABLE;

			$pdf = new Pdf([
				'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
				'content' => $content,
				'format'=>Pdf::FORMAT_LETTER,
				'marginLeft'=>5,
				'marginRight'=>5,
				'marginTop'=>20,
				'orientation'=>'P',
				'destination'=>'I',
				'cssInline'=>'
					table{overlow:warp;font-size:12px;}
				',
				'filename'=>'Matakuliah -'.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'All').'-'.date('YmdHis').'.pdf',
				'options' => [
					'title' => 'Matakuiah '.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'Semua Jurusan'),
					'subject' => 'Matakulah '.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'Semua Jurusan'),
					'watermarkText'=>"DIREKTORAT SISTEM INFORMASI & MULTIMEDIA;",
					'showWatermarkText'=>true,
				],
				'methods' => [
					'SetHeader' => ['DIREKTORAT SISTEM INFORMASI & MULTIMEDIA<br /> <br />||' . date("r")."<br />Page {PAGENO}"],
					'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'||Page {PAGENO}'],
				]
			]);			
			return $pdf->render();


			//$sql="select * from t_nilai where npm in($chk)";
			
			
		}

		
		//print_r($chk);
	}

    public function actionView($id){
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCetakTranskrip(){
        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),"jr.jr_id in(select jr_id from tbl_jurusan where jr_jenjang='s2')");
		$dataProvider->pagination=false;
		//\app\models\Funct::LOGS('Mengakses Halaman Mahasiswa');
        return $this->render('c_transkrip1', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate(){
		return $this->redirect(['index']);
        $model = new Nilai();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    public function actionUpdate($id){
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionPindah($id){
		$ModNil		= Nilai::find()->where(['npm'=>$id])->orderBy(['kode_mk'=>SORT_ASC])->all();
		$db 		= yii::$app->db2;
		$Transkrip	= \app\models\Funct::getDsnAttribute('dbname', $db->dsn);
		
		$ModMhs 	= \app\models\Mahasiswa::find()->where(" mhs_nim=:n and jr_id in(select jr_id from tbl_jurusan where jr_jenjang='s2')",[':n'=>$id])->one();
		
		
		$ModKrs	= "
		select 
			krs.jdwl_id,bn.mtk_kode, mk.mtk_nama,mk.mtk_sks
			,krs.mhs_nim,krs.mtk_kode_,krs.mtk_nama_,krs.sks_,krs.krs_grade,kl.kr_kode 
		from tbl_krs krs, tbl_jadwal jd, tbl_bobot_nilai bn, tbl_matkul mk,tbl_kalender kl
		where jd.jdwl_id=krs.jdwl_id
		and bn.id=jd.bn_id
		and bn.mtk_kode=mk.mtk_kode
		and bn.kln_id=kl.kln_id
		and (
				(krs.RStat is null or krs.RStat='0')
			and (jd.RStat is null or jd.RStat='0')
			and (bn.RStat is null or bn.RStat='0')
			and (mk.RStat is null or mk.RStat='0')
		)
		and krs.mhs_nim='$ModMhs->mhs_nim'
		order by bn.mtk_kode asc
		";
		
		if(isset($_POST['ok'])&&$_POST['ok']==1){
			$Ins="
				insert into $Transkrip.dbo.t_nilai(npm,kode_mk,nama_mk,semester,sks,huruf,nilai,tahun,tgl_input,stat,kat)
				select 
					krs.mhs_nim, bn.mtk_kode, mk.mtk_nama,mk.mtk_semester,krs.sks_,krs.krs_grade
					,iif(krs.krs_grade='A','4',iif(krs.krs_grade='B','3',iif(krs.krs_grade='C','2',iif(krs.krs_grade='D','1','0')))) nil
					,kl.kr_kode,GETDATE(),'0','0'
				from tbl_krs krs, tbl_jadwal jd, tbl_bobot_nilai bn, tbl_matkul mk,tbl_kalender kl
				where jd.jdwl_id=krs.jdwl_id
				and bn.id=jd.bn_id
				and bn.mtk_kode=mk.mtk_kode
				and bn.kln_id=kl.kln_id
				and (
						(krs.RStat is null or krs.RStat='0')
					and (jd.RStat is null or jd.RStat='0')
					and (bn.RStat is null or bn.RStat='0')
					and (mk.RStat is null or mk.RStat='0')
				)
				and krs.krs_grade in('A','B','C','D','E')
				and krs.mhs_nim='$ModMhs->mhs_nim'
				and not EXISTS(
					select * from $Transkrip.dbo.t_nilai tn 
					where mk.mtk_kode=tn.kode_mk
					and krs.krs_grade=tn.huruf
					and tn.npm =krs.mhs_nim
				)				
			";
			$Ins=Yii::$app->db->createCommand($Ins)->execute();
			if($Ins){
				$this->redirect(['pindah','id'=>$id]);
			}
		}
		$ModKrs=Yii::$app->db->createCommand($ModKrs)->queryAll();
        return $this->render('pindah',[
			'ModMhs'=>$ModMhs,
			'ModNil'=>$ModNil,
			'ModKrs'=>$ModKrs,
		]);
    }

    public function actionKonversi($id,$idn=''){
		$model	= new Nilai;
		$add	= true;
		if($idn!=''){
				$add=false;
				$model	= Nilai::findOne(['id'=>$idn,'npm'=>$id]);
		}
		
		$ModNil	= Nilai::find()->where("
			npm='$id' and(stat is null or stat='0')
		")->orderBy(['kode_mk'=>SORT_ASC])->all();
		$ModMhs 	= \app\models\Mahasiswa::find()->where(" mhs_nim=:n and jr_id in(select jr_id from tbl_jurusan where jr_jenjang='s2')",[':n'=>$id])->one();
		
        if ($model->load(Yii::$app->request->post())) {
			$Nil=['A'=>'4.00','B'=>'3.00','C'=>'2.00','D'=>'1.00','E'=>'0.00',];
			if($ModMhs){
				$Matkul = \app\models\Matkul::findOne($model->kode_mk);
				if(	$model->kat==0 && !$add){
					$NewNil	= new Nilai;
					$NewNil->attributes = $model->attributes;
					//$NewNil->stat 		= 0;
					$NewNil->nilai 		= $Nil[$NewNil->huruf];
					$NewNil->save(false);
					$Rid=$NewNil->id;
					$Krs	= \app\models\Krs::find()
						->innerJoin("tbl_jadwal jd"," jd.jdwl_id= tbl_krs.jdwl_id and (jd.RStat is null or jd.RStat='0')")
						->innerJoin("tbl_bobot_nilai bn"," bn.id= jd.bn_id and (bn.RStat is null or bn.RStat='0') and bn.mtk_kode='$model->kode_mk'")
						->innerJoin("tbl_kalender kl"," kl.kln_id= bn.kln_id and (kl.RStat is null or kl.RStat='0') and kl.kr_kode='$model->tahun'")
						->where(['mhs_nim'=>$model->npm,])
						->orderBy(['krs_id'=>SORT_DESC])
						->One();
					$Krs->krs_grade = $NewNil->huruf;
					$Krs->save(false);
					$model->stat='1';
					$model->save(false);
				}else{
					$model->npm			= $id;
					$model->nama_mk 	= $Matkul->mtk_nama;
					$model->semester	= $Matkul->mtk_semester;
					$model->sks 		= $Matkul->mtk_sks;
					$model->nilai 		= $Nil[$model->huruf];
					$model->kat 		='1';
					$model->stat 		='0';
					$model->save(false);
					$Rid=$model->id;
				}
				
				
	            return $this->redirect(['konversi', 'id'=>$id,'#'=>($add?false:"tr_".$Rid)]);
			}
        }
        return $this->render('konversi',[
			'ModMhs'=>$ModMhs,
			'ModNil'=>$ModNil,
			'model'=>$model,
			'add'=>$add,
		]);
    }

	public function actionDokNil($id){
		$ModJdwl = \app\models\Jadwal::findOne($id);

        $query = Krs::find()->where(['jdwl_id'=>$ModJdwl->jdwl_id]);
		$query->orderBy(['mhs_nim'=>SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query->andWhere(" isnull(RStat,0)=0"),
            'pagination' =>false,
			
        ]);
		

		return $this->render('doknil',[
			'ModJdwl'=>$ModJdwl,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionCek(){
		$a="asd333";
		$a=filter_var($a, FILTER_SANITIZE_NUMBER_INT);
		return (int)$a;
	}

	public function actionSimpanTranskrip($id){
		$ArrNil=['A'=>'4.00','B'=>'3.00','C'=>'2.00','D'=>'1.00','E'=>'0.00',];
		$ModJadwal=\app\models\Jadwal::findOne($id);
		if($ModJadwal){
			if(isset($_POST['chk'])){
				$KrsId=$_POST['chk'];
				$ModKrs=Krs::find()->where(['krs_id'=>$KrsId])->all();
				foreach($ModKrs as $d){
					$Nil = new Nilai;
					$Nil->npm=$d->mhs_nim;
					$Nil->kode_mk=$d->jdwl->bn->mtk_kode;
					$Nil->nama_mk=$d->jdwl->bn->mtk->mtk_nama;
					$Nil->semester=$d->jdwl->bn->mtk->mtk_semester;
					$Nil->sks=$d->jdwl->bn->mtk->mtk_sks;
					$Nil->huruf=$d->krs_grade;
					$Nil->nilai=$ArrNil[$d->krs_grade];
					$Nil->tahun=$d->kr_kode_;
					$Nil->stat='0';
					$Nil->kat='0';
					$Nil->save(false);
					
				}
			}
			if($ModJadwal->Lock>0){
				$ModJadwal->Lock='64';	
			}else{$ModJadwal->Lock+='64';}
			$ModJadwal->save(false);
		}
		$this->redirect(['nilai/dok-nil','id'=>$id]);
	}

    public function actionNilaiUrgent($id,$idn=''){
		$model	= new Nilai;
		$ModNil	= Nilai::find()->where("
			npm='$id' and(stat is null or stat='0')
		")->orderBy(['kode_mk'=>SORT_ASC])->all();
		$ModMhs 	= \app\models\Mahasiswa::find()->where(" mhs_nim=:n and jr_id in(select jr_id from tbl_jurusan where jr_jenjang='s2')",[':n'=>$id])->one();
        if ($model->load(Yii::$app->request->post())) {
			$Nil=['A'=>'4.00','B'=>'3.00','C'=>'2.00','D'=>'1.00','E'=>'0.00',];
			
			if($ModMhs){
				
				$Matkul = \app\models\Matkul::findOne($model->kode_mk);
				$model->npm			= $id;
				$model->nama_mk 	= $Matkul->mtk_nama;
				$model->semester	= $Matkul->mtk_semester;
				$model->sks 		= $Matkul->mtk_sks;
				$model->nilai 		= $Nil[$model->huruf];
				$model->tahun 		= $model->tahun;
				$model->kat 		='2';
				$model->stat 		='0';
				$model->save(false);
	            return $this->redirect(['nilai-urgent', 'id'=>$id,'#'=>($add?false:"tr_".$model->id)]);
			}
        }
		
        return $this->render('urgent',[
			'ModMhs'=>$ModMhs,
			'ModNil'=>$ModNil,
			'model'=>$model,
			'add'=>$add,
		]);
    }


    public function actionMhs(){
        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),"jr.jr_id in (select jr_id from tbl_jurusan where jr_jenjang='s2')");
		//\app\models\Funct::LOGS('Mengakses Halaman Mahasiswa');
        return $this->render('mhs_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    public function actionDelete($id)
    {
        $model=$this->findModel($id);
		$model->stat='1';
		$model->save(false);
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionCetak($id,$idn=''){
		$model	= new Nilai;
		$add	= true;
		if($idn!=''){
				$add=false;
				$model	= Nilai::findOne(['id'=>$idn,'npm'=>$id]);
		}
		
		$ModNil	= Nilai::find()->where("
			npm='$id' and(stat is null or stat='0')
		")->orderBy(['kode_mk'=>SORT_ASC])->all();
		$ModMhs 	= \app\models\Mahasiswa::find()->where(" mhs_nim=:n and jr_id in(select jr_id from tbl_jurusan where jr_jenjang='s2')",[':n'=>$id])->one();
		
        if ($model->load(Yii::$app->request->post())) {
			$Nil=['A'=>'4.00','B'=>'3.00','C'=>'2.00','D'=>'1.00','E'=>'0.00',];
			if($ModMhs){
				$Matkul = \app\models\Matkul::findOne($model->kode_mk);
				if(	$model->kat==0 && !$add){
					$NewNil	= new Nilai;
					$NewNil->attributes = $model->attributes;
					//$NewNil->stat 		= 0;
					$NewNil->nilai 		= $Nil[$NewNil->huruf];
					$NewNil->save(false);
					$Rid=$NewNil->id;
					$Krs	= \app\models\Krs::find()
						->innerJoin("tbl_jadwal jd"," jd.jdwl_id= tbl_krs.jdwl_id and (jd.RStat is null or jd.RStat='0')")
						->innerJoin("tbl_bobot_nilai bn"," bn.id= jd.bn_id and (bn.RStat is null or bn.RStat='0') and bn.mtk_kode='$model->kode_mk'")
						->innerJoin("tbl_kalender kl"," kl.kln_id= bn.kln_id and (kl.RStat is null or kl.RStat='0') and kl.kr_kode='$model->tahun'")
						->where(['mhs_nim'=>$model->npm,])
						->orderBy(['krs_id'=>SORT_DESC])
						->One();
					$Krs->krs_grade = $NewNil->huruf;
					$Krs->save(false);
					$model->stat='1';
					$model->save(false);
				}else{
					$model->npm			= $id;
					$model->nama_mk 	= $Matkul->mtk_nama;
					$model->semester	= $Matkul->mtk_semester;
					$model->sks 		= $Matkul->mtk_sks;
					$model->nilai 		= $Nil[$model->huruf];
					$model->kat 		='1';
					$model->stat 		='0';
					$model->save(false);
					$Rid=$model->id;
				}
				
				
			}
        }
		$ni = $this->renderPartial('cetak_nilai',[
			'ModMhs'=>$ModMhs,
			'ModNil'=>$ModNil,
			'model'=>$model,
			'add'=>$add,
		]);

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

    protected function findModel($id)
    {
        if (($model = Nilai::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	public function actionData($q = null, $id = null){
		
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$db 	= yii::$app->db1;
		$db2 	= yii::$app->db2;
		$keuangan 	= \app\models\Funct::getDsnAttribute('dbname', $db->dsn);
		$transkrip 	= \app\models\Funct::getDsnAttribute('dbname', $db2->dsn);
		$out = ['results' => ['id' => '', 'text' => '']];
		if (!is_null($q)) {
			$query = new Query;
			$query->select(
				[
					'id'=>'mhs_nim',
					//'text'=>'mhs_nim',
					'text'=>"concat(mhs_nim,' : ',p.Nama COLLATE Latin1_General_CI_AS,' (',jr.jr_jenjang,' ',jr.jr_nama,')')",
					
				]	
			)
				->from('tbl_mahasiswa mhs')
				->innerjoin("tbl_jurusan jr"," jr.jr_id = mhs.jr_id")
				->innerjoin("$keuangan.dbo.student s"," s.nim COLLATE Latin1_General_CI_AS = mhs.mhs_nim ")
				->innerJoin("$keuangan.dbo.people p"," p.No_Registrasi = s.no_registrasi ")
				->where(['like', "mhs_nim", $q])
				->andWhere(
					" mhs_nim not in(
						select npm from $transkrip.dbo.t_head
					)"
				)
				
				->limit(10);
			$command = $query->createCommand();
			$data = $command->queryAll();
			$out['results'] = array_values($data);
		}
		elseif ($id > 0) {
				
				$out['results'] = ['id' => $id, 'text' => Mahasiswa::find($id)->mhs_nim];
		}
		return $out;			
	}

}
