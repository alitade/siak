<?php

namespace app\controllers;
use yii\web\Session;

use Yii;
use app\models\Berita;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\BeritaSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * BeritaController implements the CRUD actions for Berita model.
 */
class BeritaController extends Controller
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
     * Lists all Berita models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BeritaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
        if (Yii::$app->request->post('hasEditable')) {
            $_id=$_POST['editableKey'];
            $model = $this->findModel($_id);

            $post = [];
            $posted = current($_POST['Berita']);
            $post['Berita'] = $posted;

            if ($model->load($post)) {
                $model->save();
				\app\models\Funct::LOGS("Mengubah Data Berita($_id) ",new Berita,$_id,'u');
                if (isset($posted['status'])) 
                {
                  $output =  $model->status;
                }
                $out = Json::encode(['output'=>$output, 'message'=>'']);
            } 
            echo $out;
            return;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPending()
    {
        $searchModel = new BeritaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $query = Berita::find()->where(['status' => "Pending"]);
        $detail = new ActiveDataProvider([
             'query' => $query
        ]);

        if (Yii::$app->request->post('hasEditable')) {
            $_id=$_POST['editableKey'];
            $model = $this->findModel($_id);

            $post = [];
            $posted = current($_POST['Berita']);
            $post['Berita'] = $posted;

            if ($model->load($post)) {
                $model->save();
				\app\models\Funct::LOGS("Mengubah Data Berita($_id) ",new Berita,$_id,'u');
                if (isset($posted['status'])) 
                {
                  $output =  $model->status;
                }
                $out = Json::encode(['output'=>$output, 'message'=>'']);
            } 
            echo $out;
            return;
        }

        return $this->render('pending', [   
            'detail' =>$detail,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPublish()
    {
        $searchModel = new BeritaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $query = Berita::find()->where(['status' => "Publish"]);
        $detail = new ActiveDataProvider([
             'query' => $query
        ]);

        if (Yii::$app->request->post('hasEditable')) {
            $_id=$_POST['editableKey'];
            $model = $this->findModel($_id);

            $post = [];
            $posted = current($_POST['Berita']);
            $post['Berita'] = $posted;

            if ($model->load($post)) {
                $model->save();
				\app\models\Funct::LOGS("Mempublish Data Berita($_id) ",new Berita,$_id,'u');
                if (isset($posted['status'])) 
                {
                  $output =  $model->status;
                }
                $out = Json::encode(['output'=>$output, 'message'=>'']);
            } 
            echo $out;
            return;
        }

        return $this->render('publish', [   
            'detail' =>$detail,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Berita model.
     * @param integer $id
     * @return mixed
     */

    public function actionBaa($id)
    {         
        $searchModel = new BeritaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $query = Berita::find()->where(['kategori'=>'Biro Administrasi Akademik']);
        $detail = new ActiveDataProvider([
             'query' => $query
        ]);

        return $this->render('baa', [   
            'detail' =>$detail,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionKeuangan($id)
    {         
        $searchModel = new BeritaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $query = Berita::find()->where(['kategori'=>'Keuangan']);

        $detail = new ActiveDataProvider([ 
             'query' => $query
        ]);

        return $this->render('keuangan', [   
            'detail' =>$detail,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRektorat($id)
    {         
        $searchModel = new BeritaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $query = Berita::find()->where(['kategori'=>'Rektorat']);

        $detail = new ActiveDataProvider([ 
             'query' => $query
        ]);

        return $this->render('rektorat', [   
            'detail' =>$detail,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPudi($id)
    {         
        $searchModel = new BeritaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $query = Berita::find()->where(['kategori'=>'Direktorat IT']);

        $detail = new ActiveDataProvider([ 
             'query' => $query
        ]);

        return $this->render('pudi', [   
            'detail' =>$detail,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionHimpunan($id)
    {         
        $searchModel = new BeritaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $query = Berita::find()->where(['kategori'=>'Himpunan']);

        $detail = new ActiveDataProvider([ 
             'query' => $query
        ]);

        return $this->render('himpunan', [   
            'detail' =>$detail,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionViewBerita($id)
    {
        return $this->render('viewBerita', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionViewRektorat($id)
    {
        return $this->render('viewRektorat', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionViewKeuangan($id)
    {
        return $this->render('viewKeuangan', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionViewPudi($id)
    {
        return $this->render('viewPudi', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionViewHimpunan($id)
    {
        return $this->render('viewHimpunan', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Berita model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Berita();
        $model->status = "Pending";
        $model->tanggal = date('Y-m-d');
        $model->id_user = Yii::$app->user->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Menambah Data Berita($model->id_berita) ",$model,$model->id_berita,'c');
            return $this->redirect(['view', 'id' => $model->id_berita]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreateBaa()
    {
        $model = new Berita();
        $model->status = "Pending";
        $model->tanggal = date('Y-m-d');
        $model->id_user = Yii::$app->user->id;
        $model->kategori = "Biro Administrasi Akademik";
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \app\models\Funct::LOGS("Menambah Data Berita($model->id_berita) ",$model,$model->id_berita,'c');
			return $this->redirect(['view-berita', 'id' => $model->id_berita]);
        } else {
            return $this->render('createBaa', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreateRektorat()
    {
        $model = new Berita();
        $model->status = "Pending";
        $model->tanggal = date('Y-m-d');
        $model->id_user = Yii::$app->user->id;
        $model->kategori = "Rektorat";
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \app\models\Funct::LOGS("Menambah Data Berita($model->id_berita) ",$model,$model->id_berita,'c');
			return $this->redirect(['view-rektorat', 'id' => $model->id_berita]);
        } else {
            return $this->render('createRektorat', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreateKeuangan()
    {
        $model = new Berita();
        $model->status = "Pending";
        $model->tanggal = date('Y-m-d');
        $model->id_user = Yii::$app->user->id;
        $model->kategori = "Keuangan";
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Menambah Data Berita($model->id_berita) ",$model,$model->id_berita,'c');
            return $this->redirect(['view-keuangan', 'id' => $model->id_berita]);
        } else {
            return $this->render('createKeuangan', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreatePudi()
    {
        $model = new Berita();
        $model->status = "Pending";
        $model->tanggal = date('Y-m-d');
        $model->id_user = Yii::$app->user->id;
        $model->kategori = "Direktorat IT";
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Menambah Data Berita($model->id_berita) ",$model,$model->id_berita,'c');
            return $this->redirect(['view-pudi', 'id' => $model->id_berita]);
        } else {
            return $this->render('createPudi', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreateHimpunan()
    {
        $model = new Berita();
        $model->status = "Pending";
        $model->tanggal = date('Y-m-d');
        $model->id_user = Yii::$app->user->id;
        $model->kategori = "Himpunan";
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Menambah Data Berita($model->id_berita) ",$model,$model->id_berita,'c');
            return $this->redirect(['view-himpunan', 'id' => $model->id_berita]);
        } else {
            return $this->render('createHimpunan', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Berita model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Mengubah Data Berita($id) ",new Berita,$id,'u');
            return $this->redirect(['view', 'id' => $model->id_berita]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdateBaa($id)
    {
        $model = $this->findModel($id);
        $model->status = "Pending";
        $model->tanggal = date('Y-m-d');
        $model->id_user = Yii::$app->user->id;
        $model->kategori = "Biro Administrasi Akademik";
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Mengubah Data Berita($id) ",new Berita,$id,'u');
            return $this->redirect(['view-berita', 'id' => $model->id_berita]);
        } else {
            return $this->render('updateBaa', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdateRektorat($id)
    {
        $model = $this->findModel($id);
        $model->status = "Pending";
        $model->tanggal = date('Y-m-d');
        $model->id_user = Yii::$app->user->id;
        $model->kategori = "Rektorat";
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Mengubah Data Berita($id) ",new Berita,$id,'u');
            return $this->redirect(['view-rektorat', 'id' => $model->id_berita]);
        } else {
            return $this->render('updateRektorat', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdateKeuangan($id)
    {
        $model = $this->findModel($id);
        $model->status = "Pending";
        $model->tanggal = date('Y-m-d');
        $model->id_user = Yii::$app->user->id;
        $model->kategori = "Keuangan";
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Mengubah Data Berita($id) ",new Berita,$id,'u');
            return $this->redirect(['view-keuangan', 'id' => $model->id_berita]);
        } else {
            return $this->render('updateKeuangan', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdatePudi($id)
    {
        $model = $this->findModel($id);
        $model->status = "Pending";
        $model->tanggal = date('Y-m-d');
        $model->id_user = Yii::$app->user->id;
        $model->kategori = "Direktorat IT";
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Mengubah Data Berita($id) ",new Berita,$id,'u');
            return $this->redirect(['view-pudi', 'id' => $model->id_berita]);
        } else {
            return $this->render('updatePudi', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdateHimpunan($id)
    {
        $model = $this->findModel($id);
        $model->status = "Pending";
        $model->tanggal = date('Y-m-d');
        $model->id_user = Yii::$app->user->id;
        $model->kategori = "Himpunan";
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Mengubah Data Berita($id) ",new Berita,$id,'u');
            return $this->redirect(['view-himpunan', 'id' => $model->id_berita]);
        } else {
            return $this->render('updateHimpunan', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Berita model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		\app\models\Funct::LOGS("Menghapus Data Berita($id) ",new Berita,$id,'d');
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionDeleteBaa($id)
    {
		\app\models\Funct::LOGS("Menghapus Data Berita($id) ",new Berita,$id,'d');
        $this->findModel($id)->delete();
        return $this->redirect(['baa', 'id'=>Yii::$app->user->id]);
    }

    public function actionDeleteRektorat($id){
		\app\models\Funct::LOGS("Menghapus Data Berita($id) ",new Berita,$id,'d');
        $this->findModel($id)->delete();
        return $this->redirect(['rektorat', 'id'=>Yii::$app->user->id]);
    }

    public function actionDeleteKeuangan($id)
    {
		\app\models\Funct::LOGS("Menghapus Data Berita($id) ",new Berita,$id,'d');	
        $this->findModel($id)->delete();
        return $this->redirect(['keuangan', 'id'=>Yii::$app->user->id]);
    }

    public function actionDeletePudi($id){
		\app\models\Funct::LOGS("Menghapus Data Berita($id) ",new Berita,$id,'d');
        $this->findModel($id)->delete();
        return $this->redirect(['pudi', 'id'=>Yii::$app->user->id]);
    }

    public function actionDeleteHimpunan($id)
    {
		\app\models\Funct::LOGS("Menghapus Data Berita($id) ",new Berita,$id,'d');
        $this->findModel($id)->delete();
        return $this->redirect(['himpunan', 'id'=>Yii::$app->user->id]);
    }

    /**
     * Finds the Berita model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Berita the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Berita::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
