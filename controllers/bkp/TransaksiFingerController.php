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
use yii\helpers\Html;


/**

 * TransaksiFingerController implements the CRUD actions for TransaksiFinger model.
 */
class TransaksiFingerController extends Controller
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
     * Lists all TransaksiFinger models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransaksiFingerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPerkuliahan()
    {
        $searchModel = new TransaksiFingerSearch();
        $dataProvider = $searchModel->perkuliahan(Yii::$app->request->queryParams);

        return $this->render('perkuliahan',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TransaksiFinger model.
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
     * Creates a new TransaksiFinger model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TransaksiFinger();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TransaksiFinger model.
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
     * Deletes an existing TransaksiFinger model.
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
     * Finds the TransaksiFinger model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TransaksiFinger the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TransaksiFinger::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionMasukAwal($id)
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

    public function actionPulangAwal($id)
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
		
		
		$viewAbsen = "
		select left(ds_masuk,5) ds_masuk, left(ds_keluar,5) ds_keluar, 
		DATEDIFF(MINUTE,'".date('H:i')."',jdwl_keluar) durasi 
		from transaksi_finger where jdwl_id='$model->jdwl_id' and tgl='".date('Y-m-d')."'";
		$viewAbsen=Yii::$app->db->createCommand($viewAbsen)->queryOne();
		$vieJadwal=Yii::$app->db->createCommand($vieJadwal)->queryAll();
        
		if(isset($_POST['awl'])){
			$jam=Html::encode($_POST['awl']['keluar']);
			$thn=date('Y-m-d');
			$ins="
				insert into absen_awal(GKode,jdwl_masuk,jdwl_keluar,tgl,tipe,ds_fid,jdwl_id)
				select distinct 
					'".$model->GKode."',tf.jdwl_masuk,'$jam','$thn','2',tf.ds_fid,tf.jdwl_id
				from transaksi_finger tf
				INNER JOIN tbl_jadwal jd on(jd.jdwl_id=tf.jdwl_id and jd.GKode='".$model->GKode."' )
				AND NOT EXISTS(SELECT * FROM absen_awal WHERE GKode='".$model->GKode."'  AND tgl='$thn') 		
			";

			//
			$update="
				update tf set tf.jdwl_keluar='$jam'
				from transaksi_finger tf
				inner join tbl_jadwal jd on(jd.jdwl_id=tf.jdwl_id and jd.GKode='".$model->GKode."' )
				AND NOT EXISTS(SELECT * FROM absen_awal WHERE GKode='".$model->GKode."'  AND  tgl='$thn')
			";
			
			$update=Yii::$app->db->createCommand($update)->execute();
			$ins=Yii::$app->db->createCommand($ins)->execute();
			return $this->redirect(['perkuliahan', 'id' => $id]);
		}

		return $this->render('pulang_awal', [
			'model' => $model,
			'vieJadwal'=>$vieJadwal,
			'viewAbsen'=>$viewAbsen,				
		]);

    }

    public function assets(){

    }



}
