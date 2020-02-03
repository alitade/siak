<?php

namespace app\controllers;

use Yii;
use app\models\ProdukHarga;
use app\models\ProdukHargaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProdukHargaController implements the CRUD actions for ProdukHarga model.
 */
class ProdukHargaController extends Controller
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
     * Lists all ProdukHarga models.
     * @return mixed
     */
    public function actionIndex(){
        $searchModel = new ProdukHargaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single ProdukHarga model.
     * @param integer $harga
     * @param string $kode_produk
     * @return mixed
     */
    public function actionView($harga, $kode_produk)
    {
        $model = $this->findModel($harga, $kode_produk);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->harga]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    public function actionAktif($id, $h){
        $model 	= $this->findModel($h,$id);
		$mod  	= ProdukHarga::UpdateAll(['aktif'=>'0'],['kode_produk'=>$id]);
		$model->aktif='1';
		$model->save();
		return $this->redirect(['/produk/view', 'id' => $model->kode_produk]);
    }



    /**
     * Creates a new ProdukHarga model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProdukHarga;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'harga' => $model->harga, 'kode_produk' => $model->kode_produk]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ProdukHarga model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $harga
     * @param string $kode_produk
     * @return mixed
     */
    public function actionUpdate($harga, $kode_produk)
    {
        $model = $this->findModel($harga, $kode_produk);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'harga' => $model->harga, 'kode_produk' => $model->kode_produk]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ProdukHarga model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $harga
     * @param string $kode_produk
     * @return mixed
     */
    public function actionDelete($harga, $kode_produk)
    {
        $this->findModel($harga, $kode_produk)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProdukHarga model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $harga
     * @param string $kode_produk
     * @return ProdukHarga the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($harga, $kode_produk)
    {
        if (($model = ProdukHarga::findOne(['harga' => $harga, 'kode_produk' => $kode_produk])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
