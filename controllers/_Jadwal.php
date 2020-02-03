<?php

# only view action
namespace app\controllers;
use app\models\Akses;
use  yii\web\Session;
use yii\data\ActiveDataProvider;
use Yii;

use app\models\Ruang;

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


$connection = \Yii::$app->db;


class _Jadwal{
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

	public function jadwal($id){
		$err=false;
		$model		= Jadwal::findOne($id);
		$modInfo    = Jadwal::findOne($id);
		$gabungan	= Jadwal::find()->where(['GKode'=>$model->GKode,'jdwl_masuk'=>$model->jdwl_masuk,'jdwl_hari'=>$model->jdwl_hari])->all();
        $subAkses = Akses::akses();
        if($subAkses){if(!isset(array_flip($subAkses['jurusan'])[$model->bn->kln->jr_id])){throw new ForbiddenHttpException("Forbidden access");}}


		$a_hari=$model->jdwl_hari;	
		$a_masuk=$model->jdwl_masuk;	
		$a_keluar=$model->jdwl_keluar;	
		$a_Gkode=$model->GKode;	

		$dataBentrok="";
		$cBentrok=0;
        $countBentrok=0;
		if($model->load(Yii::$app->request->post())){
			$vMasuk	= $model->jdwl_masuk;
			$vKeluar= $model->jdwl_keluar;
			$vHari 	= $model->jdwl_hari;

            $tgl = date('Y-m-d');
            $tgl1 = date('Y-m-d',strtotime($model->bn->kln->kln_krs));
            $tgl2 = date('Y-m-d',strtotime($model->bn->kln->krs_akhir));
            if($tgl>=$tgl1 and $tgl<=$tgl2){
                $q = Yii::$app->db->createCommand("exec CekBentrokJadwalKrs $model->jdwl_id, '$vHari', '$vMasuk', '$vKeluar'")->queryAll();
                $dataBentrok='<table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tipe</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Dosen</th>
                            <th>Matakuliah</th>
                            <th>Jadwal</th>
                        </tr>        
                    </thead>
                    <tbody>
                ';
                foreach($q as $d){
                    $cBentrok++;
                    $dataBentrok.='
                    <tr>    
                        <td>'.$cBentrok.'</td>
                        <td>'.$d['tipe'].'</td>
                        <td>'.$d['nama'].'</td>
                        <td>'.$d['nim'].'</td>
                        <td>'.$d['dosen'].'</td>
                        <td>'.$d['mtk_kode'].' '.$d['mtk_nama'].'</td>
                        <td>'.Funct::HARI()[$d['h']].", ".substr($d[m],0,5)."-".substr($d[k],0,5).'</td>
                    </tr>';

                }
                $dataBentrok.='</tbody></<table>';
            }


            #echo $dataBentrok;
			#die($q);
			$tglUts=
			$UTS	= ($model->jdwl_uts? $model->jdwl_uts." ".$model->uts_masuk:NULL);
			$UTSOUT = ($model->jdwl_uts? $model->jdwl_uts." ".$model->uts_keluar:NULL);

			$UAS	= ($model->jdwl_uas? $model->jdwl_uas." ".$model->uas_masuk:NULL);
			$UASOUT	= ($model->jdwl_uas? $model->jdwl_uas." ".$model->uas_keluar:NULL);

			$vCount=0;
			
			if( $a_hari!=$model->jdwl_hari||$a_masuk!=$model->jdwl_masuk||$a_keluar!=$model->jdwl_keluar){
				#cek bentrok jadwal dosen
				$qBentrok="
                select t.jdwl_id,jd.* from tbl_jadwal jd
                inner join tbl_bobot_nilai bn on(bn.id=jd.bn_id and bn.ds_nidn=".$model->bn->ds_nidn." and isnull(bn.RStat,0)=0)
                inner join tbl_kalender kl on(kl.kln_id=bn.kln_id and kl.kr_kode=".$model->bn->kln->kr_kode." and isnull(kl.RStat,0)=0)
                LEFT JOIN(
                    SELECT t.* from tbl_jadwal t
                    INNER JOIN tbl_jadwal t1 on(t1.jdwl_id='$id' and t1.GKode=t.GKode)
                    and isnull(t.RStat,0)=0
                ) t on(t.jdwl_id=jd.jdwl_id)
                where
                t.jdwl_id is NULL and(
                    (CAST(DATEADD(MINUTE,1,'$vMasuk') as time(0))  BETWEEN CAST(DATEADD(MINUTE,0,jd.jdwl_masuk) as time(0)) 
                        AND CAST(DATEADD(MINUTE,0,jd.jdwl_keluar) as time(0))) 
                    or
                    (CAST(DATEADD(MINUTE,-1,'$vKeluar') as time(0))  BETWEEN CAST(DATEADD(MINUTE,0,jd.jdwl_masuk) as time(0)) 
                    AND CAST(DATEADD(MINUTE,0,jd.jdwl_keluar) as time(0)))
                )
                and jd.jdwl_hari = '1' and isnull(jd.RStat,0)=0
                
				";

				$countBentrok=Yii::$app->db->createCommand($qBentrok)->queryAll();
			}

            if($countBentrok){
                $err=true;
                Yii::$app->getSession()->setFlash('error',"Jadwal Hari ".Funct::HARI()[$model->jdwl_hari]." Jam ".$model->jdwl_masuk.'-'.$model->jdwl_keluar.' Bentrok');
            }else{
                $ModUbah=Jadwal::updateAll([
                    'jdwl_hari'=>$model->jdwl_hari,
                    'jdwl_masuk'=>$model->jdwl_masuk,
                    'jdwl_keluar'=>$model->jdwl_keluar,
                    'jdwl_uts'=>$UTS,
                    'jdwl_uts_out'=>$UTSOUT,
                    'rg_kode'=>$model->rg_kode,
                    'rg_uts'=>$model->rg_uts,
                    'jdwl_uas'=>$UAS,
                    'jdwl_uas_out'=>$UASOUT,
                    'rg_uas'=>$model->rg_uas,
                ],['GKode'=>$a_Gkode,'jdwl_hari'=>$a_hari,'jdwl_masuk'=>$a_masuk,]);
                $model->save(false);

                #Funct::v($ModUbah->);

                Yii::$app->getSession()->setFlash('success',"Jadwal Berhasil Di ubah");
                return $this->redirect(['jadwal/gab-update','id'=>$model->jdwl_id]);
            }

		}

		return $this->render('gkode/update',[
            'model'=>$model,
            'modInfo'=>$modInfo,
			'gabung'=>$gabungan,
            'cBentrok'=>$cBentrok,
            'dataBentrok'=>$dataBentrok,
		]);
	}



}
