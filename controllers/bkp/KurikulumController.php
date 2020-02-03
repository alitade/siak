<?php

namespace app\controllers;

use Yii;
use app\models\Kurikulum;
use app\models\KurikulumSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class KurikulumController extends Controller{
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }


    public function actionIndex(){
        $searchModel = new KurikulumSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('/kurikulum/kr_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id)
    {
        $model=$this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	return $this->redirect(['/kurikulum/kr-view', 'id' => $model->kr_kode]);
        } 
		return $this->render('/kurikulum/kr_view', ['model' => $model]);
    }

    public function actionCreate()
    {
        $model = new Kurikulum;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/kurikulum/kr-view', 'id' => $model->kr_kode]);
        }
		return $this->render('/kurikulum/kr_create', [
			'model' => $model,
		]);
    }

    public function actionUpdate($id)
    {
        $model=$this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/kurikulum/kr-view', 'id' => $model->kr_kode]);
        } 
		return $this->render('/kurikulum/kr_update',[
			'model' => $model,
		]);
    }

    public function actionDelete($id){
        $model=$this->findModel($id);
		$model->RStat=1;
		$model->save();
        return $this->redirect(['kr']);
    }



/*
    public function actionIndex(){
        $searchModel = new KurikulumSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	return $this->redirect(['view', 'id' => $model->kr_kode]);
        } else {
        	return $this->render('view', ['model' => $model]);
		}
    }

    public function actionCreate()
    {
        $model = new Kurikulum;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->kr_kode]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->kr_kode]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model=$this->findModel($id);
		$model->RStat=1;
		$model->save();
        return $this->redirect(['index']);
    }
*/

    protected function findModel($id)
    {
        if (($model = Kurikulum::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
