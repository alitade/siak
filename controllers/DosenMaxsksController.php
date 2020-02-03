<?php

namespace app\controllers;

use Yii;
use app\models\DosenMaxsks;
use app\models\DosenMaxsksSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DosenMaxsksController implements the CRUD actions for DosenMaxsks model.
 */
class DosenMaxsksController extends Controller
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
     * Lists all DosenMaxsks models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DosenMaxsksSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        $model = new DosenMaxsks;

        if($searchModel->tahun !='' and $_GET['g']==1){
            $qIns = "
                insert into dosen_maxsks(tahun,id_tipe,maxsks,cuid,ctgl)
                SELECT '$searchModel->tahun',t.id,t.maxsks,".Yii::$app->user->identity->id.",getdate() FROM dosen_tipe t
                LEFT JOIN dosen_maxsks t1 on(t1.id_tipe=t.id and t1.tahun='$searchModel->tahun')
                WHERE t1.id is NULL 
            ";
            if(Yii::$app->db->createCommand($qIns)->execute()){return $this->redirect(['index','DosenMaxsksSearch[tahun]'=>$searchModel->tahun]);}
            else{
                die("error");
            }
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model'=>$model,
        ]);
    }

    /**
     * Displays a single DosenMaxsks model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new DosenMaxsks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DosenMaxsks;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DosenMaxsks model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id){
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'DosenMaxsksSearch[id]'=>$model->id,'DosenMaxsksSearch[tahun]'=>$model->tahun]);
        } else {return $this->render('update',['model' => $model,]);}
    }

    /**
     * Deletes an existing DosenMaxsks model.
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
     * Finds the DosenMaxsks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DosenMaxsks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DosenMaxsks::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
