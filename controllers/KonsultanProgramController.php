<?php

namespace app\controllers;

use Yii;
use app\models\KonsultanProgram;
use app\models\KonsultanProgramSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KonsultanProgramController implements the CRUD actions for KonsultanProgram model.
 */
class KonsultanProgramController extends Controller
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
     * Lists all KonsultanProgram models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KonsultanProgramSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single KonsultanProgram model.
     * @param integer $jurusan_id
     * @param integer $konsultan_id
     * @param integer $program_id
     * @return mixed
     */
    public function actionView($jurusan_id, $konsultan_id, $program_id)
    {
        $model = $this->findModel($jurusan_id, $konsultan_id, $program_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->jurusan_id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new KonsultanProgram model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KonsultanProgram;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'jurusan_id' => $model->jurusan_id, 'konsultan_id' => $model->konsultan_id, 'program_id' => $model->program_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing KonsultanProgram model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $jurusan_id
     * @param integer $konsultan_id
     * @param integer $program_id
     * @return mixed
     */
    public function actionUpdate($jurusan_id, $konsultan_id, $program_id)
    {
        $model = $this->findModel($jurusan_id, $konsultan_id, $program_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'jurusan_id' => $model->jurusan_id, 'konsultan_id' => $model->konsultan_id, 'program_id' => $model->program_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing KonsultanProgram model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $jurusan_id
     * @param integer $konsultan_id
     * @param integer $program_id
     * @return mixed
     */
    public function actionDelete($jurusan_id, $konsultan_id, $program_id)
    {
        $this->findModel($jurusan_id, $konsultan_id, $program_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the KonsultanProgram model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $jurusan_id
     * @param integer $konsultan_id
     * @param integer $program_id
     * @return KonsultanProgram the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($jurusan_id, $konsultan_id, $program_id)
    {
        if (($model = KonsultanProgram::findOne(['jurusan_id' => $jurusan_id, 'konsultan_id' => $konsultan_id, 'program_id' => $program_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
