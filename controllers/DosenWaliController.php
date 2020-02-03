<?php

namespace app\controllers;

use app\models\Akses;
use app\models\Dosen;
use app\models\Functdb;
use app\models\Funct;
use app\models\JurusanProgram;
use app\models\Mahasiswa;
use app\models\MahasiswaSearch;
use app\models\MatkulKrDetSearch;
use Yii;
use app\models\DosenWali;
use app\models\DosenWaliSearch;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DosenWaliController implements the CRUD actions for DosenWali model.
 */
class DosenWaliController extends Controller
{
    public function behaviors(){
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
     * Lists all DosenWali models.
     * @return mixed
     */
    public function actionIndex(){
        $subAkses = Akses::akses();
        #Funct::v($subAkses);
        $searchModel = new DosenWaliSearch;
        $dataProvider = $searchModel->cari(Yii::$app->request->getQueryParams());

        $model=new DosenWali;
        if ($model->load(Yii::$app->request->post())) {
            #\app\models\Funct::v($model);

            $model->cuid=Yii::$app->user->identity->id;
            $model->ctgl=new Expression('getdate()');
            if($model->save()){
                Functdb::insLog($model::$ID,$model->jr_id.'|'.$model->ds_id,'C',
                    "Menadaftarkan ".$model->ds->ds_nm." (".$model->ds->ds_nidn.") sebagai dosen wali"
                );
                return $this->redirect(['dosen-wali/index', 'id' => $model->ds_id]);
            }
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model'=>$model,
            'subAkses'=>$subAkses,

        ]);
    }

    public function actionView($id1, $id){

        $model=$this->findModel($id1,$id);
        $searchModel  = new MahasiswaSearch;
        $dataProvider = $searchModel->aktif(Yii::$app->request->getQueryParams()," isnull(ds_wali,0)=$model->ds_id  and jr.jr_id =$model->jr_id");
        #$MHS=Yii::$app->db->createCommand("select count(*) mhs from tbl_mahasiswa WHERE isnull(RStat,0)=0 and isnull(ds_wali,0)<>$id")->queryOne();
        if(isset($_POST[u])){
            if(isset($_POST['selection'])){
                $update=Mahasiswa::updateAll(['ds_wali'=>null,'uuid'=>Yii::$app->user->identity->id,'utgl'=>new  Expression("getdate()")],['mhs_nim'=>$_POST['selection']]);
                Yii::$app->getSession()->setFlash('success',"Penghapusan Dosen Wali Untuk $update Mahasiswa Berhasil Dilakukan");
                return $this->redirect(Yii::$app->request->referrer ? :['view','id'=>$model->jr_id,'id1'=>$model->ds_id]);
                #return $this->redirect(['view','id'=>$model->jr_id,'id1'=>$model->ds_id]);
            }
        }

        #Funct::v($JrId);
        return $this->render('view',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model'=>$model,
        ]);

    }

    /**
     * Creates a new DosenWali model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DosenWali;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'ds_id' => $model->ds_id, 'jr_id' => $model->jr_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    public function actionUpdate($ds_id, $jr_id){
        $model = $this->findModel($ds_id, $jr_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'ds_id' => $model->ds_id, 'jr_id' => $model->jr_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    public function actionDelete($id1, $id){
        $model=$this->findModel($id1, $id);
        Mahasiswa::updateAll(['ds_wali'=>null,'uuid'=>Yii::$app->user->identity->id,'utgl'=>new  Expression("getdate()")],['ds_wali'=>$model->ds_id,'jr_id'=>$model->jr_id]);
        $model->delete();
        return $this->redirect(['index']);
    }

    public function actionMhsCreate($id,$id1){
        $model=$this->findModel($id1,$id);
        $searchModel  = new MahasiswaSearch;
        $dataProvider = $searchModel->aktif(Yii::$app->request->getQueryParams()," isnull(ds_wali,0)!=$model->ds_id  and jr.jr_id =$model->jr_id and isnull(tbl_mahasiswa.ds_wali,0)=0 ");
        #$MHS=Yii::$app->db->createCommand("select count(*) mhs from tbl_mahasiswa WHERE isnull(RStat,0)=0 and isnull(ds_wali,0)<>$id")->queryOne();
        if(isset($_POST[u])){
            if(isset($_POST['selection'])){
                $update= Mahasiswa::updateAll(['ds_wali'=>$model->ds_id,'uuid'=>Yii::$app->user->identity->id,'utgl'=>new  Expression("getdate()")],['mhs_nim'=>$_POST['selection']]);
                Yii::$app->getSession()->setFlash('success',"Perubahan Dosen Wali Untuk $update Mahasiswa Berhasil Dilakukan");
                return $this->redirect(Yii::$app->request->referrer ? :['mhs-create','id'=>$model->jr_id,'id1'=>$model->ds_id]);
                #return $this->redirect(['mhs-create','id'=>$model->jr_id,'id1'=>$model->ds_id]);
            }
        }

        #Funct::v($JrId);
        return $this->render('mhs_add',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model'=>$model,
        ]);

    }

    public function actionMhsDel($id){
        $model=Mahasiswa::findOne($id);
        $dsid=$model->ds_wali;
        $model->ds_wali=null;
        $model->uuid=Yii::$app->user->identity->id;
        $model->utgl=new Expression('getdate()');
        $model->save(false);
        return $this->redirect(['view','id'=>$model->jr_id,'id1'=>$dsid]);
    }

    public function actionAktif($id,$id1){
        $model=$this->findModel($id1, $id);
        $model->aktif=($model->aktif==1?0:1);
        $model->save(false);
        return $this->redirect(['index']);
    }


    protected function findModel($ds_id, $jr_id)
    {
        if (($model = DosenWali::findOne(['ds_id' => $ds_id, 'jr_id' => $jr_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
