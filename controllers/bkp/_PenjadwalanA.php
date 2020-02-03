<?php

namespace app\controllers;
use Yii;

use app\models\Funct;

use app\models\Jadwal;
use app\models\JadwalSearch;
use app\models\BobotNilai;
use app\models\BobotNilaiSearch;
use app\models\Kalender;
use app\models\KalenderSearch;


use \mPdf;
use kartik\mpdf\Pdf;
use yii\helpers\Url;


use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Session;
use yii\filters\VerbFilter;
use yii\helpers\Json;

use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

$connection = \Yii::$app->db;
/**/
class _PenjadwalanA{
	
	// Akademik
	public function index(){
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams());
        return $this->render('penjadwalan/index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

	}

	public function updatePengajar($id){
		/*for($a=0;$a<=4;$a++){
			echo $a."<br />"	;
			if($a==2){break;}
		}*/
        $model 		=  BobotNilai::findOne($id);
		$Kalender 	=  Kalender::findOne($model->kln_id);
		$modJdw		=  Jadwal::find()
		->select(['*','jadwal'=>"dbo.subJdwl(jdwl_id)",])
		->where("bn_id=$model->id and isnull(RStat,'0')='0' and isnull(jdwl_parent,jdwl_id)=jdwl_id")->all();
		
        if ($model->load(Yii::$app->request->post())) {
			$getBn = BobotNilai::find()
			->select(["tbl_bobot_nilai.*"])
			->innerJoin('tbl_kalender kl',"kl.kln_id=tbl_bobot_nilai.kln_id and kl.kr_kode='".$model->kln->kr_kode."'")
			->where(['ds_nidn'=>$model->ds_nidn])
			->One();
			$modJdw_="exec dbo.valAjrDs $model->id, ".($getBn->id?:0);
			//die();
			$modJdw_=Yii::$app->db->createCommand($modJdw_)->queryAll();
			$nBn="";
			if($modJdw_){
				if(isset($_POST['gab'])){
					$jdwlid="";
					foreach($modJdw_ as $d){$jdwlid=$d['jdwl_id'];}
					$JADWAL_ = Jadwal::findOne($jdwlid);
					$n=0;
					foreach($model->jdw as $d){$jdwlid=$d->jdwl_id;$n++;}
					
					if($n==1){
						$JADWAL=Jadwal::findOne($jdwlid);
						$JADWAL->GKode=$JADWAL_->GKode;
						$JADWAL->save();
					}
					$model->save();
					return $this->redirect(['/pengajar/view','id'=>$id]);				
					
					
				}
				
				$nBn='
				<table class="table">
					<thead>
					<tr>
						<td>Jurusan / Program </td>
						<td>'
						.$getBn->kln->jr->jr_jenjang.' '.$getBn->kln->jr->jr_nama.' / '
						.$getBn->kln->pr->pr_nama
						.'</td>
					</tr>
					<tr>
						<td>Dosen</td>
						<td>'
						.$getBn->ds->ds_nm
						.'</td>
					</tr>
					</thead>
				</table>
				';
				Yii::$app->getSession()->setFlash('error',"Jadwal Perkuliahan Bentrok");
			}else{
				$n=0;
				foreach($model->jdw as $d){
					$JADWAL=Jadwal::findOne($d->jdwl_id);
					$JADWAL->GKode=$model->kln->kr_kode.".".$model->ds_nidn.".".(date("dHis")+$n);
					$JADWAL->save();
					$n++;
				}
				$model->save();
				return $this->redirect(['/pengajar/view','id'=>$id]);				
			}

        }

            return $this->render('ajr_update', [
                'model' => $model,
                'modJdw' => $modJdw,
                'modJdw_' => $modJdw_,
                'kalender' => $Kalender,
                'modBn' => $getBn,
                'nBn' => $nBn,
            ]);



	}

	
	public function create($k){
        $model 		= new Jadwal;
		$modelBn 	= new BobotNilai;

        if ($model->load(Yii::$app->request->post())) {
			//\app\models\Funct::LOGS("Menambah Data Jadwal ($model->ds_id) ",new Dosen,$id,'u');
            //return $this->redirect(['jdw-view', 'id' => $model->jdwl_id]);
        } 
		
		return $this->render('penjadwalan/create', [
			'model' => $model,
			'modelBn' => $modelBn,
		]);
	}
	
	public function pengajar(){
		
	
	}
}
