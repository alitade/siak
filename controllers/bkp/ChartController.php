<?php

namespace app\controllers;

use app\models\Akses;
use Yii;
use app\models\Jurusan;
use app\models\Student;
use app\models\DosenSearch;
use app\models\Dosen;

use yii\data\SqlDataProvider; 
use app\controllers\CDbCriteria;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MahasiswaController implements the CRUD actions for Mahasiswa model.
 */
class ChartController extends Controller{



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

    public function sub(){return Akses::akses();}

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

    public function actionChart(){



        $model = new Student;
        $TITLE="";
        $tahun=Yii::$app->request->post();

        $subAkses=self::sub();


        //if(Akses::acc('MasterJadwal')){$subAkses="";}
        //if(!Akses::acc('/chart/chart')){throw new ForbiddenHttpException("Forbidden access");}
		$ketJr="";
		if($subAkses['jurusan']){
			$ketJr="'".implode("','",$subAkses['jurusan'])."'";	
		}
		

        if(isset($tahun['thn'])){
            $thn=$tahun['thn'];
            $T=$thn['t'];
            $_1=($thn['_1']?$thn['_1']:$thn['_2']);
            $_2=($thn['_2']?$thn['_2']:$thn['_1']);
            if($_1 > $_2){$_1=$_2;$_2=$thn['_1'];}
            $TITLE="Tahun $_1 - $_2";
            if($_1==$_2){$TITLE="Tahun $_1";}

            $sql="
                    select jurusan,angkatan,kurikulum ,count(id) tot
                    FROM keuanganfix.dbo.student s
                    INNER JOIN tbl_mahasiswa m on(s.nim COLLATE Latin1_General_CI_AS=m.mhs_nim)
                    WHERE 
					".($ketJr? " jr_id in($ketJr) and":"")
					."
					angkatan BETWEEN '$_1' AND  '$_2'
					
					
                    GROUP By jurusan,angkatan,kurikulum";

            /*echo "<pre>";
            print_r($sql);
            echo "</pre>";
            */
            $data=Yii::$app->db->createCommand($sql)->queryAll();
            $_NIL=[];
            $totMhs=0;
            foreach($data as $d){$_NIL[$d['jurusan']][$d['kurikulum']]=$d['tot'];$totMhs+=$d['tot'];}
            if($TITLE!=""){$TITLE.=" (".number_format($totMhs).")";}

            //
            $_Qjr=Jurusan::find()->where(
                ($subAkses? ['jr_id'=>$subAkses['jurusan']]:"")
            )->orderBy(['fk_id'=>SORT_ASC,'jr_jenjang'=>SORT_DESC])->All();
            $_DtJr=[];
            $cat=[];
            $series=[];
            $n=0;
            for($i=$_1;$i<=$_2;$i++){
                $thn=substr($i,2,2);
                for($a=1;$a<=3;$a++){$t=$a."$thn".($thn+1);#$cat[]=$t;
                    $series[$n]['type']="column";
                    $series[$n]['name']="$t";
                    foreach($_Qjr as $d){
                        if($n==0){$cat[]=$d->jr_jenjang.' '.$d->jr_nama;}
                        $series[$n]['data'][]=(isset($_NIL[$d->jr_id][$t])?(int)$_NIL[$d->jr_id][$t]:0);
                    }
                    if($T=='2'){$series[$n]['stack']="$t";}

                    $n++;
                }

            }
        }

        return $this->render('chart', [
            'dataProvider'=>$dataProvider,
            'model'=>$model,
			'Que'=>$data,
			'cat'=>$cat,
            'series'=>$series,
            'TITLE'=>$TITLE,
            ]);
    }


    public function actionPerwalian(){

        $model = new Student;
        $TITLE="";
        $tahun=Yii::$app->request->post();

        $subAkses=self::sub();


        //if(Akses::acc('MasterJadwal')){$subAkses="";}
        //if(!Akses::acc('/chart/chart')){throw new ForbiddenHttpException("Forbidden access");}


        if(isset($tahun['thn'])){


            $thn=$tahun['thn'];
            $T=$thn['t'];
            $_1=($thn['_1']?$thn['_1']:$thn['_2']);
            $_2=($thn['_2']?$thn['_2']:$thn['_1']);
            if($_1 > $_2){$_1=$_2;$_2=$thn['_1'];}
            $TITLE="Tahun $_1 - $_2";
            if($_1==$_2){$TITLE="Tahun $_1";}

            $sql="
                    select jurusan,angkatan,kurikulum ,count(id) tot
                    FROM keuanganfix.dbo.student s
                    INNER JOIN tbl_mahasiswa m on(s.nim COLLATE Latin1_General_CI_AS=m.mhs_nim)
                    WHERE angkatan BETWEEN '$_1' AND  '$_2'
                    GROUP By jurusan,angkatan,kurikulum";

            /*echo "<pre>";
            print_r($sql);
            echo "</pre>";
            */
            $data=Yii::$app->db->createCommand($sql)->queryAll();
            $_NIL=[];
            $totMhs=0;
            foreach($data as $d){$_NIL[$d['jurusan']][$d['kurikulum']]=$d['tot'];$totMhs+=$d['tot'];}
            if($TITLE!=""){$TITLE.=" (".number_format($totMhs).")";}

            //
            $_Qjr=Jurusan::find()->where(
                ($subAkses? ['jr_id'=>$subAkses['jurusan']]:"")
            )->orderBy(['fk_id'=>SORT_ASC,'jr_jenjang'=>SORT_DESC])->All();
            $_DtJr=[];
            $cat=[];
            $series=[];
            $n=0;
            for($i=$_1;$i<=$_2;$i++){
                $thn=substr($i,2,2);
                for($a=1;$a<=3;$a++){$t=$a."$thn".($thn+1);#$cat[]=$t;
                    $series[$n]['type']="column";
                    $series[$n]['name']="$t";
                    foreach($_Qjr as $d){
                        if($n==0){$cat[]=$d->jr_jenjang.' '.$d->jr_nama;}
                        $series[$n]['data'][]=(isset($_NIL[$d->jr_id][$t])?(int)$_NIL[$d->jr_id][$t]:0);
                    }
                    if($T=='2'){$series[$n]['stack']="$t";}

                    $n++;
                }

            }
        }

        return $this->render('chart', [
            'dataProvider'=>$dataProvider,
            'model'=>$model,
            'Que'=>$data,
            'cat'=>$cat,
            'series'=>$series,
            'TITLE'=>$TITLE,
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
