<?php

namespace app\controllers;

use Yii;
use app\models\Funct;
use app\models\User;
use app\models\Jadwal;
use app\models\DosenPengganti;
use app\models\DosenPenggantiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DosenPenggantiController implements the CRUD actions for DosenPengganti model.
 */
class DosenPenggantiController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all DosenPengganti models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DosenPenggantiSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single DosenPengganti model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new DosenPengganti model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id=null)
    {
    	

    	if (empty($id)) {
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}

    	$models = DosenPengganti::find()->where("id=$id")->all();
    	$jadwal = Jadwal::findOne(['jdwl_id' => $id]);
    	$Tanggals = Funct::TanggalPengganti($jadwal->jdwl_hari);
		$DaftarPengganti="
		SELECT t.*,dp.ds_nm,dp.hadir
		from dbo.sesiDosen($id,DEFAULT) t 
		FULL JOIN 
		(
			SELECT ds.ds_nm,dp.tgl,dp.Id,dp.RStat,dp.hadir  
			FROM dosen_pengganti dp
			INNER JOIN tbl_dosen ds on(ds.ds_id=dp.ds_id)
		)dp on(dp.Id=t.jdwl_id and dp.tgl=t.tgl and(dp.RStat is null or dp.RStat='0'))
		where t.sesi is not null
		ORDER BY t.tgl
		";
		$DaftarPengganti=Yii::$app->db->createCommand($DaftarPengganti)->queryAll();
		
    	$Tanggals = "
			select t.tgl,t.sesi,dbo.cekHari(DATEPART(dw,t.tgl)+1) hari 
			from dbo.sesiDosen($id,DEFAULT) t 
			LEFT JOIN dosen_pengganti dp on(dp.Id=t.jdwl_id and(dp.RStat is null or dp.RStat='0') and dp.tgl=t.tgl)
			WHERE (hadir is NULL or hadir='0')			
			";
			//CAST(concat(t.tgl,' ',t.masuk) as datetime)>=CAST(GETDATE()as datetime)
			
		$Tanggals=Yii::$app->db->createCommand($Tanggals)->queryAll();
		$arr[NULL]='- Tanggal Pengganti -';
        foreach ($Tanggals as $k) {
            $arr[$k['tgl']]= $k['tgl']." ( Pertemuan Ke $k[sesi])";
        }
		$Tanggals=$arr;
		
		/*	
        foreach ($models as $k) {
            unset($Tanggals[$k->Tgl]);
        }
		*/
    	
    	$model = new DosenPengganti;
	    $model->Id= $id;
		
		//die();
        
        if ($model->load(Yii::$app->request->post())) {
			$q = " delete from dosen_pengganti where tgl='$model->Tgl' and id in( select jdwl_id from tbl_jadwal where GKode='$jadwal->GKode')";
			
			$ins="
			insert into dosen_pengganti (Id,ds_id,Tgl)
			select jdwl_id,$model->ds_id,'$model->Tgl' from tbl_jadwal where GKode='$jadwal->GKode'
			";
			
			$update="
			update tf set ds_fid1=( 
				select u.Fid from user_ u
				inner join tbl_dosen ds on(ds.ds_user=u.username and ds.ds_id='$model->ds_id')
			 )
			FROM tbl_jadwal jd
			inner join transaksi_finger tf on(tf.jdwl_id=jd.jdwl_id and tf.tgl_ins='$model->Tgl')
			where jd.GKode='$jadwal->GKode'
			";

			$q=Yii::$app->db->createCommand($q)->execute();
			$ins=Yii::$app->db->createCommand($ins)->execute();
			$up=Yii::$app->db->createCommand($up)->execute();
            return $this->redirect(['create', 'id' => $jadwal->jdwl_id]);
        } else {
            return $this->render('create', [
            	'tanggals' => $Tanggals,
            	'jadwal' => $jadwal,
                'model' => $model,
                'pengganti' => $DaftarPengganti,
            ]);
        }



    }

    /**
     * Updates an existing DosenPengganti model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DosenPengganti model.
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
     * Finds the DosenPengganti model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DosenPengganti the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DosenPengganti::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
