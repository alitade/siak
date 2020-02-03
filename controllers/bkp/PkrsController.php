<?php

namespace app\controllers;

use Yii;
use app\models\Pkrs;
use app\models\PkrsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PkrsController implements the CRUD actions for Pkrs model.
 */
class PkrsController extends Controller
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
     * Lists all Pkrs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PkrsSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Pkrs model.
     * @param string $kr_kode
     * @param string $mhs_nim
     * @return mixed
     */
    public function actionView($kr_kode, $mhs_nim)
    {
        $model = $this->findModel($kr_kode, $mhs_nim);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->kr_kode]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new Pkrs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pkrs;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'kr_kode' => $model->kr_kode, 'mhs_nim' => $model->mhs_nim]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Pkrs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $kr_kode
     * @param string $mhs_nim
     * @return mixed
     */
    public function actionUpdate($kr_kode, $mhs_nim)
    {
        $model = $this->findModel($kr_kode, $mhs_nim);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'kr_kode' => $model->kr_kode, 'mhs_nim' => $model->mhs_nim]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Pkrs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $kr_kode
     * @param string $mhs_nim
     * @return mixed
     */
    public function actionDelete($kr_kode, $mhs_nim)
    {
        $this->findModel($kr_kode, $mhs_nim)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Pkrs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $kr_kode
     * @param string $mhs_nim
     * @return Pkrs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($kr_kode, $mhs_nim)
    {
        if (($model = Pkrs::findOne(['kr_kode' => $kr_kode, 'mhs_nim' => $mhs_nim])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
