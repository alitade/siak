<?php

namespace app\controllers;

use Yii;
use app\models\Gedung;
use app\models\GedungSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Expression;

/**
 * GedungController implements the CRUD actions for Gedung model.
 */
class GedungController extends Controller
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
     * Lists all Gedung models.
     * @return mixed
     */
    public function actionIndex(){
        $searchModel = new GedungSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id){
        $model = $this->findModel($id);
        if($model->load(Yii::$app->request->post())) {
			$model->uuid	= Yii::$app->user->identity->id;
			$model->utgl 	= new  Expression("getdate()");
			if($model->save()){
				\app\models\Funct::LOGS("Mengubah Data Gedung($id) ",new Gedung,$id,'u');		
				return $this->redirect(['view', 'id' => $model->Id]);
			}
        }
		return $this->render('view', ['model' => $model]);
		
    }

    public function actionCreate(){
        $model = new Gedung;
        if ($model->load(Yii::$app->request->post())) {
			$model->cuid	= Yii::$app->user->identity->id;
			$model->ctgl 	= new  Expression("getdate()");
			if($model->save()){
				\app\models\Funct::LOGS("Menambah Data Gedung($model->Id) ",$model,$model->Id,'c');
				return $this->redirect(['view', 'id' => $model->Id]);
			}
        }
		return $this->render('create', [
			'model' => $model,
		]);
    }

    public function actionUpdate($id){
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$model->uuid	= Yii::$app->user->identity->id;
			$model->utgl 	= new  Expression("getdate()");
			if($model->save()){
				\app\models\Funct::LOGS("Mengubah Data Gedung($id) ",new Gedung,$id,'u');
				return $this->redirect(['view', 'id' => $model->Id]);
			}
        }
		return $this->render('update', [
			'model' => $model,
		]);
	
    }

    public function actionDelete($id){
        $model=$this->findModel($id);
		$model->RStat=1;
		$model->duid	= Yii::$app->user->identity->id;
		$model->dtgl 	= new  Expression("getdate()");
		if($model->save()){
			\app\models\Funct::LOGS("Menghapus Data Gedung($id) ",new Gedung,$id,'d');
		}
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Gedung::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
