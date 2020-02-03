<?php

namespace app\controllers;

use Yii;
use app\models\BankSoal;
use app\models\BankSoalSearch;
use yii\web\Controller;
use yii\helpers\Url;

use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\UploadedFile;

/**
 * BankSoalController implements the CRUD actions for BankSoal model.
 */
class BankSoalController extends Controller
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
     * Lists all BankSoal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BankSoalSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single BankSoal model.
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
     * Creates a new BankSoal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BankSoal;

        if ($model->load(Yii::$app->request->post())) {
			$file = UploadedFile::getInstance($model,'file');
			$ext = explode('.',$file->name);
			$model->file=$model->mtk_kode.'-'.date('siHdmY').'.'.$ext[1];
			if($file->saveAs('../modules/file/'.$model->file) ){
				$model->save();
				return $this->redirect(['view', 'id' => $model->Id]);
			}else{
				echo "gagal";
				die();
			}
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

	public function actionDownload(){
		$id='';
	}

    /**
     * Updates an existing BankSoal model.
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
     * Deletes an existing BankSoal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$model=$this->findModel($id);
		$model->RStat='1';
		$model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the BankSoal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BankSoal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BankSoal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
