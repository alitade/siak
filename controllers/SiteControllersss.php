<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use mPDF;
use yii\helpers\Html;
use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;


class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['tv','logout'],
                'rules' => [
                    [
                        'actions' => ['tv','login'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionTv()
    {  
        $rows = (new \yii\db\Query())
            ->select(['*'])
            ->from('tbl_berita_2')
            ->where(['status' => 'Publish', 'kategori'=>'Rektorat'])
            ->all();

        $rows1 = (new \yii\db\Query())
            ->select(['*'])
            ->from('tbl_berita_2')
            ->where(['status' => 'Publish', 'kategori'=>'Biro Administrasi Akademik'])
            ->all();

        $rows2 = (new \yii\db\Query())
            ->select(['*'])
            ->from('tbl_berita_2')
            ->where(['status' => 'Publish', 'kategori'=>'Keuangan'])
            ->all();

        $rows3 = (new \yii\db\Query())
            ->select(['*'])
            ->from('tbl_berita_2')
            ->where(['status' => 'Publish', 'kategori'=>"Direktorat IT"])
            ->all();

        $rows4 = (new \yii\db\Query())
            ->select(['*'])
            ->from('tbl_berita_2')
            ->where(['status' => 'Publish', 'kategori'=>"Himpunan"])
            ->all();
                
        $hari = date('N');
        $hari1 = date('N');

          if($hari=="7"){
                $hari="Minggu";
            }elseif ($hari=='1') {
                $hari="Senin";
            }elseif ($hari=="2") {
                $hari="Selasa";
            }elseif ($hari=="3") {
                $hari="Rabu";
            }elseif ($hari=="4") {
                $hari="Kamis";
            }elseif ($hari=="5") {
                $hari="Jumat";
            }elseif ($hari=="6") {
                $hari="Sabtu";
            }

        $query = "  SELECT
        b.*, r.rg_nama, m.mtk_nama, jr.jr_nama, d.ds_nm, jr.jr_jenjang, j.jdwl_masuk, j.jdwl_hari, j.jdwl_keluar
        FROM
            tbl_bobot_nilai b
        LEFT JOIN tbl_jadwal j ON (j.bn_id = b.id)
        LEFT JOIN tbl_matkul m ON (m.mtk_kode = b.mtk_kode)
        LEFT JOIN tbl_ruang r ON (r.rg_kode = j.rg_kode)
        LEFT JOIN tbl_jurusan jr ON (jr.jr_id = m.jr_id)
        LEFT JOIN tbl_dosen d ON (b.ds_nidn = d.ds_id)

        where jdwl_hari = ".$hari1."
        ORDER BY jdwl_masuk ASC";

       // $count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($query) T")->queryScalar();

        $dataProvider = new SqlDataProvider([
            'sql' => $query,
             
           // 'totalCount' => (int)$count,
            /*'sort' => [
                'attributes' => [
                    'age',
                    'name' => [
                        'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
                        'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => 'Name',
                    ],
                ],
            ],*/
            'pagination' => [
                'pageSize' => 6,
            ],
        ]);

        return $this->renderPartial('tv2',[
            'rows'=>$rows,
            'rows1'=>$rows1,
            'rows2'=>$rows2,
            'rows3'=>$rows3,
            'rows4'=>$rows4,
            'items'=>$dataProvider->getModels(),

            'hari'=>$hari,
        ]);

    
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        $this->layout = 'login';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {     
        $this->layout = 'login';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
 
    }


    public function actionChangePassword(){
       
       //$model = User::getModel();
       $User  = Yii::$app->session['user'];
       $model = User::findOne(Yii::$app->user->id);
    
        if (!empty($_POST)) {

                $Pass1 =    $_POST['Reset']['password1'];
                $Pass2 =    $_POST['Reset']['password2'];
                $Pass3 =    $_POST['Reset']['password3'];

                if ($Pass1 != $Pass2) {
                
                    Yii::$app->getSession()->setFlash('danger', [
                        'type' => 'danger',
                        'duration' => 10000,
                        'icon' => 'fa fa-users',
                        'message' => Html::encode('Konfirmasi Password Baru tidak sesuai'),
                        'title' => Yii::t('app', Html::encode('Terjadi Kesalahan')),
                        'positonY' => 'top',
                        'positonX' => 'center'
                    ]);
                
                return $this->render('ch_pw');

                }else{


                    if ($User->validatePassword($Pass3) == 1) {
                       if ($User->changePassword($model,$Pass1) == 1 ) {
                              Yii::$app->getSession()->setFlash('success', [
                                    'type' => 'success',
                                    'duration' => 10000,
                                    'icon' => 'fa fa-users',
                                    'message' => Html::encode('Kata Sandi telah berhasil di Update'),
                                    'title' => Yii::t('app', Html::encode('Informasi')),
                                    'positonY' => 'top',
                                    'positonX' => 'center'
                                ]);
                            return $this->render('ch_pw');
                        } 
                    }else{

                          Yii::$app->getSession()->setFlash('danger', [
                            'type' => 'danger',
                            'duration' => 10000,
                            'icon' => 'fa fa-users',
                            'message' => Html::encode('Password Lama Tidak Sesuai'),
                            'title' => Yii::t('app', Html::encode('Terjadi Kesalahan')),
                            'positonY' => 'top',
                            'positonX' => 'center'
                        ]);
                        return $this->render('ch_pw');

                    }


                }
            
        }
       
       if ($model){
        return $this->render('ch_pw', [
            'model' => $model,
        ]);

       }else{
         //Yii::$app->user->logout();
         //return $this->goHome();
       }
    }


    public function actionCreateMPDF(){
        $mpdf=new mPDF();
        $mpdf->WriteHTML($this->renderPartial('mpdf'));
        $mpdf->Output();
        exit;
        //return $this->renderPartial('mpdf');
    }
    
    public function actionSamplePdf() {
        $mpdf = new mPDF;
        $mpdf->WriteHTML('Sample Text');
        $mpdf->Output();
        exit;
    }

    public function actionForceDownloadPdf(){
        $mpdf=new mPDF();
        $mpdf->WriteHTML($this->renderPartial('mpdf'));
        $mpdf->Output('MyPDF.pdf', 'D');
        exit;
    }

}
