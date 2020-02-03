<?php

namespace app\controllers;

use app\models\Funct;
use app\models\Functdb;
use Yii;
use app\models\Pendaftaran;
use app\models\Biodata;
use app\models\Konsultan;
use app\models\Wali;
use app\models\PendaftaranSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Expression;
use yii\web\UploadedFile;
use app\models\DaftarNpm;
use app\models\Regmhs;
/**
 * PendaftaranController implements the CRUD actions for Pendaftaran model.
 */
class PendaftaranController extends Controller
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
     * Lists all Pendaftaran models.
     * @return mixed
     */
    public function actionIndex(){
        $searchModel = new PendaftaranSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id){
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->No_Registrasi]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    public function actionViewCalon($id){
        $model  = $this->findModel($id);
        $mBio = Biodata::findOne(['id'=>$model->id]);
        $mWali = Wali::findOne(['id'=>$mBio->parent]);
        return $this->render('view_calon', ['model' => $model,'mBio' => $mBio,'mWali' => $mWali,]);
    }

    public function actionNpm($id){
        $model  =DaftarNpm::findOne(['No_Registrasi'=>$id]);
        $mBio   =Biodata::findOne(['id'=>$model->id]);
        $kurikulum='';
        $gNPM='';
        if($model){
            $kurikulum=Yii::$app->db->createCommand("
              SELECT TOP 1 kr_kode from tbl_kalender WHERE jr_id='$model->program_studi' 
              ORDER BY SUBSTRING(kr_kode,2,2) DESC,left(kr_kode,1) DESC
            ")->queryOne()['kr_kode'];
            $gNPM=Yii::$app->db->createCommand(" SELECT dbo.generateNPM('$model->No_Registrasi','$model->semester_lanjutan',DEFAULT) npm ")->queryOne()['npm'];
        }


        #vn-fk-jr-pr-th-kr-ln
        $kr = substr($kurikulum,1,2);
        $thn = substr(date('Y'),0,2);



        $kodeTarif =
            ($model->prdaftar->party?:"0").'-'
            .($model->jr->fk_id?:"0").'-'
            .($model->program_studi?:"0").'-'
            .($model->pr_kode?:"0").'-'
            .($thn.$kr?:"0").'-'
            .($kurikulum?:"0")."-"
            .($model->status_pendaftaran?:"0");


        return $this->render('add_npm', [
            'model' => $model,
            'mBio' => $mBio,
            'kurikulum'=>$kurikulum,
            'listTarif'=>Functdb::pilihTarif(($model->prdaftar->party?:"0"),$kodeTarif),
            'gNPM'=>$gNPM,
        ]);

    }

    public function actionSaveNpm($id){
        $model=DaftarNpm::findOne(['No_Registrasi'=>$id]);

        if ($model->load(Yii::$app->request->post()) ) {


            #simpan data NPM
            #Simpan Data Pembayaran Awal
            #Registrasi Kurikulum
            if($model->save()){
                #Regis mahasiswa di kurikulum berjalan
                $reg = new Regmhs;
                $reg->nim       = $model->npm;
                $reg->tanggal   = new  Expression("getdate()");
                $reg->semester  = $model->semester_lanjutan;
                $reg->tahun     = $model->kurikulum;
                $reg->save();
                return $this->redirect(['npm','id'=>$model->No_Registrasi]);
            }
        }


        return $this->redirect(['npm','id'=>$model->No_Registrasi]);
    }


    public function actionRegis($id){
        $model=Pendaftaran::findOne(['kd_daftar'=>$id]);
        if($model->No_Registrasi==''){
            $model->No_Registrasi=new  Expression("dbo.NoReg()");
            if($model->ket_program!=''){
                $model->pr_kode = Yii::$app->db->createCommand("select kode from program WHERE program_id='$model->ket_program'")->queryOne()['kode'];
            }
            $model->save(false);
        }
        return $this->redirect(['view-calon','id'=>$model->kd_daftar]);


    }

    public function actionUpload($id){
        $model = Pendaftaran::findOne(['kd_daftar'=>$id]);
        return BiodataController::actionUploadPt($model->id,['view-calon','id'=>$model->kd_daftar]);
    }

    public function actionCreate(){
        $model = new Pendaftaran;
        $mBio = new Biodata;
        $mWali = new Wali;

        if ( $model->load(Yii::$app->request->post()) && $mBio->load(Yii::$app->request->post())) {
            Funct::v($mBio);
            #$model->save();
            #return $this->redirect(['view', 'id' => $model->No_Registrasi]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'mBio' => $mBio,
                'mWali' => $mWali,
            ]);
        }
    }

    public function actionOnline(){

        $model  = new Pendaftaran;
        $mBio   = new Biodata;
        $mWali  = new Wali;

        if ( $model->load(Yii::$app->request->post()) && $mBio->load(Yii::$app->request->post())  && $mWali->load(Yii::$app->request->post()) ) {

            $newIdW=Functdb::uID();
            $newIdM=Functdb::uID();
            $model->id=$newIdM;
            $kdD='D-'.time().'-';
            $model->kd_daftar=new  Expression("dbo.kdD('$kdD')");
            $model->jenis_daftar=0;

            $mWali->id=$newIdW;
            $mWali->alamat_ktp       = $mWali->add.'|'.$mWali->rt.'|'.$mWali->rw.'|'.$mWali->keldes.'|'.$mWali->kec.'|'.$mWali->kota;
            $mWali->alamat_tinggal   = $mWali->add1.'|'.$mWali->rt1.'|'.$mWali->rw1.'|'.$mWali->keldes1.'|'.$mWali->kec1.'|'.$mWali->kota_tinggal;

            $mBio->id=$newIdM;
            $mBio->parent=$newIdW;
            #ADD|RT|RW|KelDes|Kec|KabKot|
            $mBio->alamat_ktp       = $mBio->add.'|'.$mBio->rt.'|'.$mBio->rw.'|'.$mBio->keldes.'|'.$mBio->kec.'|'.$mBio->kota;
            $mBio->alamat_tinggal   = $mBio->add1.'|'.$mBio->rt1.'|'.$mBio->rw1.'|'.$mBio->keldes1.'|'.$mBio->kec1.'|'.$mBio->kota_tinggal;

            #Funct::v($mWali);
            if($mWali->save()){if($mBio->save()){$model->save();}}
            #return $this->redirect(['view', 'id' => $model->No_Registrasi]);
        } else {
            return $this->render('online', [
                'model' => $model,
                'mBio' => $mBio,
                'mWali' => $mWali,
            ]);
        }
    }

    public function actionDaftar($k){
        $mKons= Konsultan::findOne($k);

        $model  = new Pendaftaran;
        $mBio   = new Biodata;
        $mWali  = new Wali;

        if($model->load(Yii::$app->request->post()) && $mBio->load(Yii::$app->request->post())  && $mWali->load(Yii::$app->request->post()) ) {

            $newIdW=Functdb::uID();
            $newIdM=Functdb::uID();
            $model->id=$newIdM;
            $kdD='D-'.time().'-';
            $model->kd_daftar=new  Expression("dbo.kdD('$kdD')");
            $model->jenis_daftar=0;

            $mWali->id=$newIdW;
            $mWali->alamat_ktp       = $mWali->add.'|'.$mWali->rt.'|'.$mWali->rw.'|'.$mWali->keldes.'|'.$mWali->kec.'|'.$mWali->kota;
            $mWali->alamat_tinggal   = $mWali->add1.'|'.$mWali->rt1.'|'.$mWali->rw1.'|'.$mWali->keldes1.'|'.$mWali->kec1.'|'.$mWali->kota_tinggal;

            $mBio->id=$newIdM;
            $mBio->parent=$newIdW;
            #ADD|RT|RW|KelDes|Kec|KabKot|
            $mBio->alamat_ktp       = $mBio->add.'|'.$mBio->rt.'|'.$mBio->rw.'|'.$mBio->keldes.'|'.$mBio->kec.'|'.$mBio->kota;
            $mBio->alamat_tinggal   = $mBio->add1.'|'.$mBio->rt1.'|'.$mBio->rw1.'|'.$mBio->keldes1.'|'.$mBio->kec1.'|'.$mBio->kota_tinggal;

            #Funct::v($mWali);
            if($mWali->save()){if($mBio->save()){$model->save();}}
            return $this->redirect(['view-calon','id'=>$model->kd_daftar]);
            
        } else {
            return $this->render('onlinev2', [
                'model' => $model,
                'mKons' =>$mKons,
                'mBio'  => $mBio,
                'mWali' => $mWali,
            ]);
        }
    }

    public function actionUpdate($id){
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->No_Registrasi]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id){
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public  function actionTarif($id){
        $model  = \app\modules\keuangan\models\Tarif::findOne($id);
        $Detail = \app\modules\keuangan\models\Tarifdetail::find()->where(['idtarif'=>$model->id])->all();

        $kriteria = Yii::$app->db->createCommand("exec dbo.tarifKriteria '$model->id'")->queryAll();

        $tbl='';
        if($kriteria){
            foreach($kriteria as $d){
                if($d['n']==1){$tbl.="<span class='badge' style='background: #d9edf7;color:#000;'> Konsultan: ".($d[item]?:"-")." </span> ";}
                if($d['n']==2){$tbl.="<span class='badge' style='background: #d9edf7;color:#000;'> Fakultas: ".($d[item]?:"-")." </span> ";}
                if($d['n']==3){$tbl.="<span class='badge' style='background: #d9edf7;color:#000;'> Jurusan: ".($d[item]?:"-")." </span> ";}
                if($d['n']==4){$tbl.="<span class='badge' style='background: #d9edf7;color:#000;'> Program: ".($d[item]?:"-")." </span> ";}
                if($d['n']==5){$tbl.="<span class='badge' style='background: #d9edf7;color:#000;'> Angkatan: ".($d[item]?:"-")." </span> ";}
                if($d['n']==6){$tbl.="<span class='badge' style='background: #d9edf7;color:#000;'> Kurikulum: ".($d[item]?:"-")." </span> ";}
                if($d['n']==7){$tbl.="<span class='badge' style='background: #d9edf7;color:#000;'> Mahasiswa ".($d[item]?:"-")." </span> ";}
            }
            echo"<p></p>";
        }


        $tbl.='<table class="table table-bordered"><thead><tr><th> No </th><th> DPP </th><th> SKS </th><th> PRAKTEK </th><th> TIPE </th><th> Urutan </th></tr></thead><tbody>';
        $n=0;
        $totDpp=0;
        foreach ($Detail as $d){
            $totDpp+=$d->dpp;
            $n++;$tbl.="<tr><td>$n</td><td>".number_format($d->dpp)."</td><td>$d->sks</td><td>$d->praktek</td><td>$d->tipe</td><td>$d->urutan</td></tr>";
        }
        $tbl.='<tr><td>Total</td><td>'.number_format($totDpp).'</td></tr></tbody></table>';

        return '<div style="padding: 2px">'.$tbl.' </div>';

    }

    protected function findModel($id)
    {
        if (($model = Pendaftaran::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
