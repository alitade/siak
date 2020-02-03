<?php

namespace app\controllers;

use Yii;
use app\models\Mahasiswa;
use app\models\Student;
use app\models\DosenSearch;
use app\models\Dosen;

use yii\data\SqlDataProvider; 
use app\controllers\CDbCriteria;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MahasiswaController implements the CRUD actions for Mahasiswa model.
 */
class AdjiController extends Controller
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
     * Lists all Mahasiswa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionRekdos()
    {
        $searchModel = new DosenSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('rekDos', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionChart()
    {
        $model = new Student;

        if(isset($_POST['Student'])){
            $model->attributes=$_POST['Student'];

            $sql="
                select COUNT(nim) as total,
                (select TOP 1 COUNT(*) from keuanganfix.dbo.student s where s.jurusan='".$_POST['Student']['jurusan']."' and s.angkatan='".$_POST['Student']['angkatan']."') as total_,
                angkatan,status_mhs from keuanganfix.dbo.student where jurusan='".$_POST['Student']['jurusan']."' and angkatan='".$_POST['Student']['angkatan']."'
                 GROUP BY angkatan,status_mhs ORDER BY angkatan DESC
            ";
            $dataProvider = new SqlDataProvider([
            	'sql'=>$sql,
            ]);

        }else{	
            $sql=" 
             select COUNT(nim) as total,
                (select TOP 1 COUNT(*) from keuanganfix.dbo.student s where s.jurusan='".@$_POST['Student']['jurusan']."' and s.angkatan='".@$_POST['Student']['angkatan']."') as total_,
                angkatan,status_mhs from keuanganfix.dbo.student where jurusan='".@$_POST['Student']['jurusan']."' and angkatan='".@$_POST['Student']['angkatan']."'
                 GROUP BY angkatan,status_mhs ORDER BY angkatan DESC
            ";
           // $sql=" ";
            $dataProvider = new SqlDataProvider(['sql'=>$sql]);

        }
         // var_dump($dataProvider);
         //    die();
        return $this->render('chart', [
            'dataProvider'=>$dataProvider,
            'model'=>$model,
            ]);
    }

    
    public function actionStudentFinance(){
        $model = new Student;

         //$this->performAjaxValidation($model);
        if(isset($_POST['Student'])){
            $model->attributes=$_POST['Student'];
        $sql="
            select
                COUNT(kr.status) as total,jr.nama_jurusan as jurusan,jr.kode_jurusan as kode,kr.tahun as taon,kr.status as status
            from keuanganfix.dbo.student t
            join 
                                    keuanganfix.dbo.pembayarankrs kr on (t.nim=kr.nim) 
                                    join 
                                    keuanganfix.dbo.people p on (p.no_registrasi=t.no_registrasi)
                                    join 
                                    keuanganfix.dbo.jurusan  jr on (jr.kode_jurusan=t.jurusan)
            where 
            jr.kode_jurusan='".$_POST['Student']['jurusan']."' and kr.tahun='".$_POST['Student']['angkatan']."'
            GROUP BY kr.status,jr.nama_jurusan,kr.tahun,jr.kode_jurusan
        ";
        //print_r($sql);die();
        $dataProvider = new SqlDataProvider([
            	'sql'=>$sql,
            ]);
    }
        else{
            $sql="select COUNT(t.nim) as total,t.jurusan as kode,jr.nama_jurusan as jurusan from keuanganfix.dbo.student t join 
                keuanganfix.dbo.jurusan  jr on (jr.kode_jurusan=t.jurusan) GROUP BY t.jurusan,jr.nama_jurusan ";
        //print_r($sql);die();
        $dataProvider = new SqlDataProvider([
            	'sql'=>$sql,
            ]);
        }   
        return $this->render('studentFinance',array(
            'dataProvider'=>$dataProvider,
            'model'=>$model,
        )
        );
    }

    protected function findModel($id)
    {
        if (($model = Dosen::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    

}
