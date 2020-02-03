<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\SubAksesDet;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Funct;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id){
		$modAkses = SubAksesDet::find()->where(['user_id'=>$id])->all();
		
        return $this->render('view', [
            'model' => $this->findModel($id),
			'modAkses'=>$modAkses,
        ]);
    }

	#subAkses
	public function actionAddSub($id){
		$model=$this->findModel($id);
		$k=Yii::$app->request->post();
        if($k['kode']['kode']){
            foreach ($k['kode']['kode'] as $k=>$v){
				$sql="
				insert into sub_akses_det(user_id,kode,created_at)
				select $model->id,kode,".time()." from sub_akses where kode='$v'
				and kode not in(select kode from sub_akses_det where user_id='$model->id')
				";
				$sql=Yii::$app->db->createCommand($sql)->execute();
            }
            return $this->redirect(['/user/view','id'=>$model->id]);
        }		
	}
	
	public function actionSubDelete($id,$kode){
		SubAksesDet::deleteAll(["user_id"=>$id,'kode'=>$kode]);
		return $this->redirect(['/user/view','id'=>$id]);
	}
	
	#endSubAkses
    public function actionCreate()
    {
        $model = new User();
       
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->name = $_POST['User']['name'];
            $model->username = $_POST['User']['username']; 
            $model->posisi = $_POST['User']['posisi']; 
			$tipe=$_POST['User']['tipe']; 
			
            $model->tipe = $tipe;
            $model->stat = "1";
            $model->status = "10";
			$passkode	= Funct::acak(10);
			$pass		= md5('ypkp@#1234'.$passkode.$tipe);
            $model->pass_kode = $passkode;
            $model->password =$pass;
            $model->save();
            if($model->ChangePassword($model,$_POST['User']['password']) == 1)
                return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

	public function actionReset($id){
		$model 				= User::findOne($id);
		$passkode			= Funct::acak(10);
		$pass				= md5("ypkp@#1234".$passkode.$model->tipe);
		$model->pass_kode 	= $passkode;
		$model->password	= $pass;
		if($model->save(false)){
			$this->redirect(['index']);
		}else{return 'ah';}
	}

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->name = $_POST['User']['name'];
            $model->username = $_POST['User']['username']; 
            $model->posisi = $_POST['User']['posisi']; 
            $model->tipe = $_POST['User']['tipe']; 
            $model->stat = "1";
            $model->status = "10";
            $model->password =($_POST['User']['password']);
            $model->save();
            if($model->ChangePassword($model,$_POST['User']['password']) == 1)
                return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
