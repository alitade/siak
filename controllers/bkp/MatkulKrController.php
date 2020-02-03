<?php

namespace app\controllers;

use app\models\Akses;
use app\models\Jurusan;
use app\models\Mahasiswa;
use app\models\MahasiswaSearch;
use app\models\Matkul;
use app\models\MatkulKrDet;
use app\models\MatkulKrDetSearch;
use app\models\MatkulKrSub;
use Yii;
use app\models\Funct;
use app\models\MatkulKr;
use app\models\MatkulKrSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

/**
 * MatkulKrController implements the CRUD actions for MatkulKr model.
 */
class MatkulKrController extends Controller{
    private function ID(){return Yii::$app->user->identity->id;}

    public function sub(){return Akses::akses();}

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

    public function actionAktif($id){
        $model=MatkulKr::findOne($id);

        $mod=MatkulKr::updateAll(["aktif"=>'0'],["jr_id"=>$model->jr_id]);
        $model->aktif='1';
        $model->save(false);
        return $this->redirect(["view",'id'=>$model->id]);

    }

    public function actionIndex(){
		
		if(!Akses::acc('/matkul-kr/index')){throw new ForbiddenHttpException("Forbidden access");}
        $searchModel = new MatkulKrSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,(self::sub()?['parent'=>NULL,'jr_id'=>self::sub()['jurusan']]:"parent is null"));

        return $this->render('/matkul-kr/index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
			'subAkses'=>self::sub(),
        ]);
    }

    public function actionInfo(){
		if(!Akses::acc('/matkul-kr/info')){throw new ForbiddenHttpException("Forbidden access");}
        $sql="";
        if($_POST['S']['jr_id']){
            $id = $_POST['S']['jr_id'];
            $sql=Yii::$app->db->createCommand("SELECT * FROM prediksiMatkul('$id')")->queryAll();
            if(isset($_POST['ex'])){return $this->redirect(['info-ex','id'=>$id]);}
        }

        return $this->render('/matkul-kr/info', [
            'sql'=>$sql,
			'subAkses'=>self::sub()
        ]);
    }

    public function actionInfoEx($id){
        $JR= Jurusan::findOne($id);
        $filename = 'Data-'.Date('YmdGis').'-'.$JR->jr_jenjang.'-'.$JR->jr_nama.'.xls';
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=".$filename);
        $sql=Yii::$app->db->createCommand("SELECT * FROM prediksiMatkul('$id')")->queryAll();
        echo' <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th rowspan="2">No</th><th rowspan="2">Kode</th><th rowspan="2">Matkul</th><th rowspan="2">SKS</th><th rowspan="2">&sum;Mhs</th><th rowspan="2">&sum;Tercapai</th><th colspan="6">Detail </th><th rowspan="2">&sum;Sisa</th>
                </tr>
                <tr><th>A</th><th>B</th><th>C</th><th>D</th><th>E</th><th>N</th></tr>
                </thead>
                <tbody>';
                $n=0; foreach($sql as $d):
                    $n++;
                    echo"
                    <tr>
                        <td>$n</td>
                        <td> $d[kode] </td>
                        <td>$d[matkul]</td>
                        <td>$d[sks] </td>
                        <td>$d[mhs] </td>
                        <td>$d[total]</td>
                        <td>$d[A]</td>
                        <td>$d[B]</td>
                        <td>$d[C]</td>
                        <td>$d[D]</td>
                        <td>$d[E]</td>
                        <td>$d[N]</td>
                        <td>$d[sisa]</td>
                    </tr>";
                endforeach;
                echo '</tbody>
            </table>';
    }

    public function actionView($id){

        $model = $this->findModel($id);
        $searchModel = new MatkulKrDetSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),['id_kr'=>$model->id,"isnull(Rstat,'0')"=>'0']);
        $totSks=0;
        $m =MatkulKrDet::find()->where(['id_kr'=>$model->id,"isnull(Rstat,'0')"=>'0'])->all();
        $subTot=[];
        foreach ($model->subKr as $d){$subTot[$d->id]=0;foreach($d->mkDet as $d1){$subTot[$d->id]+=$d1->sks;}}
        $totSks+=max($subTot);

        foreach($m as $d){$totSks+=$d->sks;}
        $dataProvider->pagination=false;

        $k=Yii::$app->request->post();
        //var_dump($k);
        if($k['kode']['kode']){
            foreach ($k['kode']['kode'] as $k=>$v){
                $MATKUL= Matkul::findOne($v);
                $SubMatkul = $MATKUL->subMk;
                $sql=Yii::$app->db->createCommand("
                insert into matkul_kr_det(id_kr,kode,matkul,sks,cuid,kode_,matkul_)                  
                select '$model->id',mtk_kode,mtk_nama,mtk_sks,".$this->ID().", 
                '$SubMatkul->mtk_kode','$SubMatkul->mtk_nama'
                from tbl_matkul WHERE mtk_kode='$v'
                AND  mtk_kode not in(SELECT kode FROM matkul_kr_det WHERE id_kr='$model->id' and isnull(RStat,0)=0)
                ")->execute();
            }
            return $this->redirect(['view','id'=>$model->id]);
        }

        #add new matkul
        $ModMtk = new Matkul;
        $ActiveForm=false;
        if ($ModMtk->load(Yii::$app->request->post())) {
            $ModMtk->jr_id = $model->jr_id;
            $ModMtk->mtk_stat='1';
            if($ModMtk->save()){
                $sql=Yii::$app->db->createCommand("
                insert into matkul_kr_det(id_kr,kode,matkul,sks,cuid)                  
                select '$model->id',mtk_kode,mtk_nama,mtk_sks,".$this->ID()." from tbl_matkul WHERE mtk_kode in ('$ModMtk->mtk_kode')
                AND  mtk_kode not in(SELECT kode FROM matkul_kr_det WHERE id_kr='$model->id' and isnull(RStat,0)=0)
                ")->execute();
                return $this->redirect(['view','id'=>$model->id]);
            }else{$ActiveForm=true;}
        }


        #view sub
        $totSub =sizeof($model->subKr);
        $viewSub = false;
        if($totSub>0){
            $searchSub = new MatkulKrSearch;
            $subData = $searchSub->search(Yii::$app->request->getQueryParams(),['parent'=>$model->id]);
            $viewSub=true;
        }
        #end sub

        return $this->render('view', [
            'model' => $model,
            'viewSub'=>$viewSub,
            'ActiveForm'=>$ActiveForm,
            'dataProvider' => $dataProvider,
            'SKS'=>$totSks,
            'searchModel' => $searchModel,
            'ModMtk'=>$ModMtk,

            'searchSub'=>$searchSub,
            'subData'=>$subData,

        ]);
    }

    public function actionSubView($id){
        $model  = $this->findModel($id);
        $Parent = $this->findModel($model->parent);
        $SKS=0;
        foreach($Parent->mkDet as $d){$SKS+=$d->sks;}

        $searchModel = new MatkulKrDetSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),['id_kr'=>$model->id,"isnull(Rstat,'0')"=>'0']);
        $totSks=$SKS;

        $m =MatkulKrDet::find()->where(['id_kr'=>$model->id,"isnull(Rstat,'0')"=>'0'])->all();
        foreach($m as $d){$totSks+=$d['sks'];}
        $totSks=$totSks-$Parent->totSks;
        
        $dataProvider->pagination=false;

        $k=Yii::$app->request->post();
        //var_dump($k);
        if($k['kode']['kode']){
            foreach ($k['kode']['kode'] as $k=>$v){
                $MATKUL= Matkul::findOne($v);
                $SubMatkul = $MATKUL->subMk;
                $sql=Yii::$app->db->createCommand("
                insert into matkul_kr_det(id_kr,kode,matkul,sks,cuid,kode_,matkul_)                  
                select '$model->id',mtk_kode,mtk_nama,mtk_sks,".$this->ID().", 
                '$SubMatkul->mtk_kode','$SubMatkul->mtk_nama'
                from tbl_matkul WHERE mtk_kode='$v'
                AND  mtk_kode not in(SELECT kode FROM matkul_kr_det WHERE id_kr='$model->id' and isnull(RStat,0)=0)
                ")->execute();
            }
            return $this->redirect(['view','id'=>$model->id]);
        }

        #add new matkul
        $ModMtk = new Matkul;
        $ActiveForm=false;
        if ($ModMtk->load(Yii::$app->request->post())) {
            $ModMtk->jr_id = $Parent->jr_id;
            $ModMtk->mtk_stat='1';
            if($ModMtk->save()){
                $sql=Yii::$app->db->createCommand("
                insert into matkul_kr_det(id_kr,kode,matkul,sks,cuid)                  
                select '$model->id',mtk_kode,mtk_nama,mtk_sks,".$this->ID()." from tbl_matkul WHERE mtk_kode in ('$ModMtk->mtk_kode')
                AND  mtk_kode not in(SELECT kode FROM matkul_kr_det WHERE id_kr='$model->id' and isnull(RStat,0)=0)
                ")->execute();
                return $this->redirect(['sub-view','id'=>$model->id]);
            }else{$ActiveForm=true;}
        }

        return $this->render('sub_view', [
            'model' => $model,
            'Parent'=>$Parent,
            'ActiveForm'=>$ActiveForm,
            'dataProvider' => $dataProvider,
            'SKS'=>$totSks,
            'searchModel' => $searchModel,
            'ModMtk'=>$ModMtk
        ]);
    }

	#akses untuk tambah matakuliah
	public function actionAddMatkul(){return true;}

    public function actionCreate(){
        $model = new MatkulKr;
        $model->cuid=$this->ID();
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                return $this->redirect(['view','id'=>$model->id]);
            }
        }
        return $this->render('create', ['model' => $model,]);
    }

    public function actionSubCreate($id){
        $Parent         = $this->findModel($id);
        $model          = new MatkulKrSub();

        $model->cuid    = $this->ID();
        $model->jr_id   = $Parent->jr_id;
        $model->kode    = $Parent->kode;
        $model->parent  = $Parent->id;

        if ($model->load(Yii::$app->request->post())) {


            if($model->save()){
                return $this->redirect(['view','id'=>$model->id]);
            }
            var_dump($model->getErrors());
        }
        return $this->render('sub_create', [
            'model' => $model,
            'Parent' => $Parent,
        ]);
    }

    public function actionUpdate($id){
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', ['model' => $model,]);
    }

    public function actionDelete($id){
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }


    public function actionMhs($id){
        $model=$this->findModel($id);
        $searchModel  = new MahasiswaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),['mk_kr'=>$model->id]);

        $searchMk = new MatkulKrDetSearch;
        $dataMk = $searchMk->search(Yii::$app->request->getQueryParams(),['id_kr'=>$model->id,"isnull(Rstat,'0')"=>'0']);

        $MHS=Yii::$app->db->createCommand("select count(*) mhs from tbl_mahasiswa WHERE isnull(RStat,0)=0 and mk_kr=$model->id and jr_id='$model->jr_id'")->queryOne();

        #Funct::v($dataProvider);
        return $this->render('mhs_kr',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'dataMk' => $dataMk,
            'searchMk' => $searchMk,
            'totMhs'=>$MHS,
            'model'=>$model,
        ]);

    }

    public function actionMhsCreate($id){
        $model=$this->findModel($id);
        $searchModel  = new MahasiswaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),"tbl_mahasiswa.jr_id='$model->jr_id' and isnull(tbl_mahasiswa.RStat,0)=0 and isnull(mk_kr,0)<>$model->id");


        $searchMk = new MatkulKrDetSearch;
        $dataMk = $searchMk->search(Yii::$app->request->getQueryParams(),['id_kr'=>$model->id,"isnull(Rstat,'0')"=>'0']);

        $MHS=Yii::$app->db->createCommand("select count(*) mhs from tbl_mahasiswa WHERE isnull(RStat,0)=0 and mk_kr=$model->id and jr_id='$model->jr_id'")->queryOne();
        if(isset($_POST[u])){
            if(isset($_POST['selection'])){
                Mahasiswa::updateAll(['mk_kr'=>$model->id,'uuid'=>Yii::$app->user->identity->id,'utgl'=>new  Expression("getdate()")],[
                    'mhs_nim'=>$_POST['selection']
                ]);
                return $this->redirect(['mhs-create','id'=>$model->id]);
            }
        }

        #Funct::v($dataProvider);
        return $this->render('mhs_add',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'dataMk' => $dataMk,
            'searchMk' => $searchMk,
            'totMhs'=>$MHS,
            'model'=>$model,
        ]);

    }

    public function actionMhsSv($id){
        return "";
    }


    protected function findModel($id){
        if (($model = MatkulKr::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
