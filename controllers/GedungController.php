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

        return $this ->render('index',C1Controller::actionD119());

    }

    public function actionView($id){

        $model = Gedung::findOne($id);
        $searchModel = new \app\models\M113Search;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('view', [
            'model' => $model->Id,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
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

        return C2Controller::D119($id);

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
