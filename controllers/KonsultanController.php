<?php

namespace app\controllers;

use app\models\Pendaftaran;
use app\models\PendaftaranSearch;
use Yii;
use app\models\Konsultan;
use app\models\KonsultanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KonsultanController implements the CRUD actions for Konsultan model.
 */
class KonsultanController extends Controller
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
     * Lists all Konsultan models.
     * @return mixed
     */
    public function actionIndex(){
        $searchModel = new KonsultanSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    public function actionMahasiswa(){
        $searchModel = new PendaftaranSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    public function actionView($id){
        $model = $this->findModel($id);

        $searchPr= new \app\models\ProgramDaftarSearch;
        $dataPr= $searchPr->search(Yii::$app->request->getQueryParams());

        $qPr="select * from program WHERE party='$model->kode'";
        $qPr=Yii::$app->db->createCommand($qPr)->queryAll();
        $tbPr='<table class="table table-border table-striped table-condensed">
            <thead> 
                <tr>
                    <th> No </th>
                    <th> Program </th>
                    <th> Status </th>
                    <th> </th>
                </tr>
            </thead>
            <tbody>';
        $n=0;
        foreach ($qPr as $d) {
            $n++;
            $tbPr.="
            <tr>
                <td> $n </td>
                <td> $d[nama_program] </td>
                <td> $d[aktif] </td>
                <td> <i class='fa fa-bar-chart'></i> </td>
            </tr>";
        }
        $tbPr.='</tbody></table>';



        return $this->render('view',[
            'model' => $model,
            'dataPr' => $dataPr,
            'searchPr' => $searchPr,
            'dataProgram'=>$tbPr,
        ]);

    }

    /**
     * Creates a new Konsultan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Konsultan;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->kode]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Konsultan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->kode]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Konsultan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Konsultan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Konsultan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Konsultan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
