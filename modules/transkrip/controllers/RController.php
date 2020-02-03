<?php

namespace app\modules\transkrip\controllers;

use Yii;
use app\modules\transkrip\models\Nilai;
use app\modules\transkrip\models\NilaiSearch;

use app\models\Mahasiswa;
use app\models\MahasiswaSearch;

use app\models\Krs;
use app\models\KrsSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * NilaiController implements the CRUD actions for Nilai model.
 */
class RController extends ModController
{

    /**
     * Lists all Nilai models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NilaiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('/nilai/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Nilai model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Nilai model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Nilai();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Nilai model.
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
     * Deletes an existing Nilai model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */


    public function actionPindah($id){
		$ModNil		= Nilai::find()->where(['npm'=>$id])->orderBy(['kode_mk'=>SORT_ASC])->all();
		$db 	= yii::$app->db2;
		$Transkrip	= \app\models\Funct::getDsnAttribute('dbname', $db->dsn);
		$ModMhs = \app\models\Mahasiswa::findOne($id);
		
		
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
		$ModMhs = \app\models\Mahasiswa::findOne($id);
		
        if ($model->load(Yii::$app->request->post())) {
			$Nil=['A'=>'4.00','B'=>'3.00','C'=>'2.00','D'=>'1.00','E'=>'0.00',];
			if($ModMhs){
				$Matkul = \app\models\Matkul::findOne($model->kode_mk);
				if(	$model->kat==0 && !$add){
					$NewNil	= new Nilai;
					$NewNil->attributes = $model->attributes;
					$NewNil->nilai 		= $Nil[$NewNil->huruf];
					$NewNil->save();
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
				}
				
				
	            return $this->redirect(['konversi', 'id'=>$id,'#'=>($add?false:"tr_".$model->id)]);
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
            'query' => $query->andWhere("RStat is null or RStat='0'"),
            'pagination' =>false,
			
        ]);
		

		return $this->render('doknil',[
			'ModJdwl'=>$ModJdwl,
			'dataProvider' => $dataProvider,
		]);
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
			$ModJadwal->Lock='2';
			$ModJadwal->save(false);
		}
		$this->redirect(['nilai/dok-nil','id'=>$id]);
	}

    public function actionNilaiUrgent($id,$idn=''){
		$model	= new Nilai;
		$ModNil	= Nilai::find()->where("
			npm='$id' and(stat is null or stat='0')
		")->orderBy(['kode_mk'=>SORT_ASC])->all();
		$ModMhs = \app\models\Mahasiswa::findOne($id);
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
				$model->save();
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
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
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

    public function actionCetak($id)
    {
           
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
             
            'defaultFontSize' => 12,
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            'destination' => Pdf::DEST_BROWSER, 
            'content' => $table,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
            'methods' => [ 
                'SetHeader'=>[''], 
                'SetFooter'=>[' ('.date('d-m-Y H:i:S').') Halaman-{PAGENO}'],
            ]
        ]);

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
		$headers->add('Content-Disposition: inline; filename="Jadwal Kuliah"');
		$headers->add('Content-Transfer-Encoding: binary');
		$headers->add('Accept-Ranges: bytes');
 
        // return the pdf output as per the destination setting
        return $pdf->render(); 


		$this->layout = "blank";
		return $this->render('cetak_nilai');


    }

    protected function findModel($id)
    {
        if (($model = Nilai::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
