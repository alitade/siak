<?php

namespace app\controllers;

use app\models\Kalender;
use app\models\Mahasiswa;
use app\models\Regmhs;
use Yii;
use app\models\TKrsHead;
use app\models\TKrsHeadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TKrsHeadController implements the CRUD actions for TKrsHead model.
 */
class TKrsHeadController extends Controller{
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


    public function actionIndex()
    {
        $searchModel = new TKrsHeadSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single TKrsHead model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->kode]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new TKrsHead model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TKrsHead;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->kode]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TKrsHead model.
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
     * Deletes an existing TKrsHead model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    #Method Mahasiswa
    public function actionMhs(){

        $user   = Yii::$app->user->identity->username;


        $mMhs   = Mahasiswa::findOne(['mhs_nim'=>$user]);

        if(!$mMhs){throw new NotFoundHttpException('The requested page does not exist.');}
        #cari kalender akademik aktif
        $mKr    = Kalender::find()->where("
            isnull(RStat,0)=0
            and CAST(GETDATE() as date) BETWEEN kln_krs and krs_akhir
            and jr_id='$mMhs->jr_id' and pr_kode='$mMhs->pr_kode' 
        ")->one();


        #Cek Registrasi
        $mReg = Regmhs::findOne(['tahun'=>$mKr->kr_kode,'nim'=>$mMhs->mhs_nim]);
        #$model= $this->findModel(['mhs_']);

        return $this->render('ins_mhs',[
            'mMhs'=>$mMhs,
            'mKr'=>$mKr,
            'mReg'=>$mReg,
        ]);



    }


    #end Method Mahasiswa

    protected function findModel($id)
    {
        if (($model = TKrsHead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
