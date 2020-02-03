<?php

namespace app\controllers;

use Yii;
use app\models\Produk;
use app\models\ProdukSearch;
use app\models\ProdukHarga;
use app\models\ProdukHargaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * ProdukController implements the CRUD actions for Produk model.
 */
class ProdukController extends Controller
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
     * Lists all Produk models.
     * @return mixed
     */
    public function actionIndex(){
        $searchModel = new ProdukSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
		$cuid=Yii::$app->user->identity->id;

		$model= new Produk;
        if ($model->load(Yii::$app->request->post())) {			
			$model->cuid=$cuid;
			if($model->save()){
				$modHrg = new ProdukHarga;
				$modHrg->kode_produk=$model->kode;
				$modHrg->harga=$model->harga;
				$modHrg->cuid=$cuid;
				$modHrg->aktif='1';
				//$modHrg->ctgl = new  Expression("getdate()");							
				if($modHrg->save()){
					return $this->redirect(['index', 'ProdukSearch[kode]'=>$model->kode]);
				}
					var_dump($modHrg->getErrors());

			}
            //return $this->redirect(['view', 'id' => $model->kode]);
        }
		
		
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
			'model'=>$model,
        ]);
    }

    /**
     * Displays a single Produk model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $searchModel = new ProdukHargaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),['kode_produk'=>$model->kode]);


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->kode]);
        } 
		
		
		#Tambah Data Harga
		$cuid=Yii::$app->user->identity->id;
		$modHrg = new ProdukHarga;
   		if ($modHrg->load(Yii::$app->request->post())) {
			$modHrg->kode_produk=$model->kode;
			$modHrg->cuid=$cuid;
			//$modHrg->ctgl = new  Expression("getdate()");							
			if($modHrg->save()){
				return $this->redirect(['view', 'id' => $model->kode]);
			}
        } 
			
		
		
		return $this->render('view', [
			'model' => $model,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
			'modHrg'=>$modHrg,
			
		]);
        
    }

    /**
     * Creates a new Produk model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Produk;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->kode]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Produk model.
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
     * Deletes an existing Produk model.
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
     * Finds the Produk model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Produk the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Produk::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
