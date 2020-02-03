<?php

namespace app\controllers;

use app\models\Fakultas;
use app\models\Funct;
use app\models\Functdb;
use app\models\Jurusan;
use app\models\Konsultan;
use app\models\Program;
use Yii;
use app\models\Tarif;
use app\models\Tarifdetail;
use app\models\TarifdetailSearch;
use app\models\Biaya;
use app\models\TarifSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Expression;

/**
 * TarifController implements the CRUD actions for Tarif model.
 */
class TarifController extends Controller
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
     * Lists all Tarif models.
     * @return mixed
     */
    public function actionIndex(){
        $searchModel = new TarifSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        $model = new Tarif;
        $mBiaya= new Biaya;

        if ($model->load(Yii::$app->request->post()) && $mBiaya->load(Yii::$app->request->post())){

            $model->total=str_replace(',','',$model->total);
            $model->total=str_replace('.','',$model->total);
            #Funct::v($model->total);
            if($model->validate() && $mBiaya->validate()){
                $info="";
                #VN:JOB-FK:0-JR:0-PR:0-TH:0-KR:0-TP:0
                if($mBiaya->vendor){$info.="Konsultan: ".Konsultan::find()->select(['vendor'])->where(['id'=>$mBiaya->vendor])->one()->vendor.' | ';}else{$info.="Seluruh Konsultan".' | ';}
                if($mBiaya->fk){$info.="Fakultas: ".Fakultas::find()->select(['fk_nama'])->where(['id'=>$mBiaya->fk])->one()->fk_nama.' | ';}else{$info.="Seluruh Fakultas".' | ';}
                if($mBiaya->jr){
                    $jr=Jurusan::find()->select(['jr_nama','jr_jenjang'])->where(['id'=>$mBiaya->jr])->one();
                    $info.="Jurusan: ".$jr->jr_jenjang.' '.$jr->jr_nama.' | ';
                }else{$info.="Seluruh Jurusan".' | ';}
                if($mBiaya->pr){$info.="Program Perkuliahan: ".Program::find()->select(['pr_nama'])->where(['id'=>$mBiaya->pr])->one()->pr_nama.' | ';}else{$info.="Seluruh Program Perkuliahan".' | ';}
                if($mBiaya->thn){$info.="Angkatan: ".$mBiaya->thn.' | ';}else{$info.="Seluruh Angkatan | ";}
                if($mBiaya->kurikulum ){$info.="Kurikulum: ".$mBiaya->kurikulum .' | ';}else{$info.="Seluruh Kurikulum | ";}
                $mhs=[0=>'Baru','Linier','Non Linier'];
                if($mBiaya->jns){$info.="Mahasiswa : ".$mhs[$mBiaya->jns];}else{$info.="Seluruh Mahasiswa";}
                $model->info = $info;


                $mBiaya->fk         = 'FK:'.($mBiaya->fk?: "0");
                $mBiaya->jr         = 'JR:'.($mBiaya->jr?:"0");
                $mBiaya->pr         = 'PR:'.($mBiaya->pr?:"0");
                $mBiaya->thn        = 'TH:'.($mBiaya->thn?:"0");
                $mBiaya->kurikulum  = 'KR:'.($mBiaya->kurikulum?:"0");
                $mBiaya->jns        = 'TP:'.($mBiaya->jns?:"0"); #[baru,linier,non linier]
                $mBiaya->vendor     = 'VN:'.($mBiaya->vendor?:"0");
                #$mBiaya->jnsBayar   = 'FK:'.($mBiaya->jnsBayar?:"0"; #[ paket,cicilan ]
                #VN:JOB-FK:0-JR:0-PR:0-TH:0-KR:0-TP:0
                $kode = $mBiaya->vendor."-".$mBiaya->fk."-".$mBiaya->jr."-".$mBiaya->pr."-".$mBiaya->thn."-".$mBiaya->kurikulum.'-'.$mBiaya->jns;

                $qKriteria = Yii::$app->db->createCommand("exec dbo.tarifKriteria '$model->id'")->queryAll();
                $model->kode = $kode;
                $model->ket= $mBiaya->ket?:"-";
                $model->cuid=Yii::$app->user->identity->id;
                $model->ctgl=new Expression('getdate()');

                if($model->save()){
                    $model=Tarif::findOne(['kode_tarif'=>$model->kode_tarif]);
                    Functdb::insLog($model::$ID,$model->id,'C',"Menambah Kode Tarif: $model->kode_tarif");
                    return $this->redirect(['tarif/view','id'=>$model->id]);
                }
            }else{ Funct::v($model->getErrors()); }

        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model'=>$model,
            'mBiaya'=>$mBiaya,
        ]);
    }


    public function actionAddBiaya(){
        $model      = new Biaya;
        $mTarifD   = new Tarifdetail;
        $mTarif   = new Tarif;

        #session tarif
        if(isset($_GET['d']) and $_GET['d']==1){unset($_SESSION['tarif']);return $this->redirect(['add-biaya']);}
        if(isset($_GET['dt'])){$_GET['dt']=(int)$_GET['dt'];unset($_SESSION['tarif'][$_GET['dt']]);return $this->redirect(['add-biaya']);}
        if ($mTarifD->load(Yii::$app->request->post())){
            $DPP = implode('',explode(',',$mTarifD->dpp)) ;
            $DPP = implode('',explode('.',$DPP)) ;
            $_SESSION['tarif'][$mTarifD->urutan]=$DPP;
            ksort($_SESSION['tarif']);
            return $this->redirect(['add-biaya']);
        }
        #end session tarif

        if ($model->load(Yii::$app->request->post())){
            $model->fk= $model->fk?: "0";
            $model->jr=$model->jr?:"0";
            $model->pr=$model->pr?:"0";
            $model->thn=$model->thn?:"0";
            $model->kurikulum=$model->kurikulum?:"0";
            $model->jns=$model->jns?:"0";
            $kode = $model->vendor."-".$model->fk."-".$model->jr."-".$model->pr."-".$model->thn."-".$model->kurikulum.'-'.$model->jns;

            $mTarif->kode=$kode;
            $mTarif->ctgl=new  Expression("getdate()");
            $mTarif->cuid=Yii::$app->user->identity->id;


            if($mTarif->load(Yii::$app->request->post())){
                if(isset($_SESSION['tarif']) && count($_SESSION['tarif'])>0 ){

                   if($mTarif->save()){
                       $ins="";
                       foreach ($_SESSION['tarif'] as $k=>$v){$ins.=",('$mTarif->id','$v','$k')";}
                       $ins="insert into tarifdetail(idtarif,dpp,urutan) VALUES ".substr($ins,1);
                       if( Yii::$app->db->createCommand($ins)->execute()){
                           echo "suksess";
                           unset($_SESSION['tarif']);
                       }
                   }

                }
                Funct::v($mTarif);
            }

            #Funct::v($model);

        }

        return $this->render('form_biaya',[
            'model'=>$model,
            'mTarifD'=>$mTarifD,
            'mTarif'=>$mTarif,
        ]);
    }
    /**
     * Displays a single Tarif model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id){
        $model  = $this->findModel($id);
        $mDetail= new Tarifdetail;

        $qKriteria = Yii::$app->db->createCommand("exec dbo.tarifKriteria '$model->id'")->queryAll();

        $searchModel = new TarifdetailSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),['idtarif'=>$model->id]);

        return $this->render('view', [
            'model' => $model,
            'mDetail' => $mDetail,
            'qKriteria'=>$qKriteria,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

    }


    public function actionCreate()
    {
        $model = new Tarif;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Tarif model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public  function actionDetail($id){
        $model  = \app\modules\keuangan\models\Tarif::findOne($id);
        $Detail = \app\modules\keuangan\models\Tarifdetail::find()->where(['idtarif'=>$model->id])->all();
        $tbl='<table class="table table-bordered"><thead><tr><th> No </th><th> DPP </th><th> SKS </th><th> PRAKTEK </th><th> TIPE </th><th> Urutan </th></tr></thead><tbody>';
        $n=0;
        $totDpp=0;
        foreach ($Detail as $d){
            $totDpp+=$d->dpp;
            $n++;$tbl.="<tr><td>$n</td><td>".number_format($d->dpp)."</td><td>$d->sks</td><td>$d->praktek</td><td>$d->tipe</td><td>$d->urutan</td></tr>";
        }
        $tbl.='<tr><td>Total</td><td>'.number_format($totDpp).'</td></tr></tbody></table>';
        return $tbl;

    }

    protected function findModel($id)
    {
        if (($model = Tarif::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
