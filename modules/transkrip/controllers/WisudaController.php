<?php

namespace app\modules\transkrip\controllers;

use Yii;
use app\modules\transkrip\models\Nilai;
use app\models\Mahasiswa;
use app\models\People;

use app\modules\transkrip\models\Wisuda;
use app\modules\transkrip\models\WisudaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WisudaController implements the CRUD actions for Wisuda model.
 */
class WisudaController extends ModController{
    public function actionIndex(){
        $searchModel = new WisudaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Wisuda model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id){
        $model 	= $this->findModel($id);
        $ModMhs = Mahasiswa::findOne($model->npm);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
	        return $this->redirect(['view', 'id' => $model->id]);
        } else {
	        return $this->render('view', [
				'model' => $model,
				'ModMhs' => $ModMhs,
			]);
		}
    }

    public function actionTranskrip($id){
        $model 	= $this->findModel($id);
		$ModNil	= Nilai::find()->where("npm='$model->npm' and (stat is null or stat='0')")->orderBy(['semester'=>SORT_ASC,'kode_mk'=>SORT_ASC,])->All();
		return $this->render('transkrip', [
			'model' => $model,
			'ModNil' => $ModNil,
		]);
    }

    /**
     * Creates a new Wisuda model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Wisuda;
		
        if ($model->load(Yii::$app->request->post())) {
			$ModMhs	=\app\models\Mahasiswa::findOne($model->npm);
			$ModDs	=\app\models\Dosen::findOne($model->ds_id_);
			
			$Urut				=((int)Wisuda::Urut($model->kode)+1);
			$NoUrt				= $Urut."/".$model->kode."-".date("m",strtotime($model->tgl_lulus))."/".date("Y",strtotime($model->tgl_lulus));
			$model->kode_		= $NoUrt;
			$model->nama		= $ModMhs->mhs->people->Nama;
			$model->jr_id		= $ModMhs->jr_id;
			$model->no_urut		= $Urut;
			$model->pembimbing	= $ModDs->ds_nm;
			
			if($model->save()){
				return $this->redirect(['view', 'id' => $model->id]);	
			}else{
				var_dump($model->getErrors());
				die();
				return $this->render('create', [
					'model' => $model,
				]);

			}
			
            
        }// else {
            return $this->render('create', [
                'model' => $model,
            ]);
        //}
    }

    /**
     * Updates an existing Wisuda model.
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

    public function actionDelete($id){
        //$this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Wisuda model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Wisuda the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    protected function findModel($id)
    {
        if (($model = Wisuda::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
