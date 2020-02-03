<?php

namespace app\controllers;

use app\models\Funct;
use app\models\LabsenDosenDet;
use Yii;
use app\models\LAbsenDosen;
use app\models\LAbsenDosenSearch;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class LaporanController extends Controller{

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
     * Lists all LAbsenDosen models.
     * @return mixed
     */
    public function actionIndex(){
        $searchModel = new LAbsenDosenSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    public function actionPloatDosen(){
        $title="";
        $ploat="";
        if(isset($_POST['ploat'])){
            $ploat =$_POST['ploat'];
            if($ploat['kr_kode']){
                $kls="and ((jd.jdwl_masuk<'17:00' and jd.jdwl_hari BETWEEN 1 and 5) or (jd.jdwl_masuk<'12:00' and jd.jdwl_hari=6))";
                if($ploat['tipe']=='1'){$kls="and ((jd.jdwl_masuk>'17:00' and jd.jdwl_hari BETWEEN 1 and 5) or (jd.jdwl_masuk>'12:00' and jd.jdwl_hari=6))";}
                if($ploat['tipe']=='2'){$kls=" ";}

                $q  =" exec ReportPloatDosen '$ploat[kr_kode]','$ploat[tipe]','0' ";
                $q  =Yii::$app->db->createCommand($q)->queryAll(\PDO::FETCH_NUM);
                $def=" 
                    SELECT top 1 jd.GKode from tbl_kalender kl
                    INNER JOIN tbl_jurusan jr on (jr.jr_id=kl.jr_id)
                    INNER JOIN tbl_bobot_nilai bn on (bn.kln_id=kl.kln_id and isnull(bn.RStat,0)=0)
                    INNER JOIN tbl_dosen ds on (ds.ds_id=bn.ds_nidn and isnull(ds.RStat,0)=0)
                    INNER JOIN tbl_matkul mk on (mk.mtk_kode=bn.mtk_kode and isnull(mk.RStat,0)=0 and isnull(mk.ig,0)=0)
                    -- INNER JOIN tbl_jadwal jd on (jd.bn_id=bn.id and isnull(jd.RStat,0)=0 )
                    INNER JOIN tbl_jadwal jd on (
                        jd.bn_id=bn.id
                        AND jd.jdwl_id=isnull(jd.jdwl_parent,jd.jdwl_id)
                        AND isnull(jd.RStat,0)=0 
                        AND isnull(jd._totmhs,0)>0
                        $kls
                    )
                    LEFT JOIN dosen_tipe dt on(dt.id=ds.id_tipe)
                    WHERE isnull(kl.RStat,0)=0
                    AND kl.kr_kode='$ploat[kr_kode]'
                    ORDER BY ds.ds_nm,jd.GKode, jd.jdwl_hari,jd.jdwl_masuk,jd.jdwl_kls 
                  ";
                $def=Yii::$app->db->createCommand($def)->queryOne();

                $title=" Tahun Akademik ".$ploat['kr_kode']." ( KLS ".($ploat['tipe']=='1'?'Sore': ($ploat['tipe']=='2'?'All':"Pagi") )." )";

                if(isset($_POST['ex'])){
                    return $this->redirect(['/laporan/download-ploat-dosen','kr'=>$ploat['kr_kode'],'t'=>$ploat['tipe']]);
                }

            }
        }
        return $this->render('ploat_dosen_view',[
            'ploat'=>$ploat,
            'TITLE'=>$title,
            'q'=>$q,
            'def'=>$def,
        ]);

    }

    public function actionDownloadPloatDosen($kr,$t){
        $kls="and ((jd.jdwl_masuk<'17:00' and jd.jdwl_hari BETWEEN 1 and 5) or (jd.jdwl_masuk<'12:00' and jd.jdwl_hari=6))";
        if($t=='1'){$kls="and ((jd.jdwl_masuk>'17:00' and jd.jdwl_hari BETWEEN 1 and 5) or (jd.jdwl_masuk>'12:00' and jd.jdwl_hari=6))";}
        if($t=='2'){$kls=" ";}
        $q  =" exec ReportPloatDosen '$kr','$t','0' ";
        $q  =Yii::$app->db->createCommand($q)->queryAll(\PDO::FETCH_NUM);
        $def=" 
            SELECT top 1 jd.GKode from tbl_kalender kl
            INNER JOIN tbl_jurusan jr on (jr.jr_id=kl.jr_id)
            INNER JOIN tbl_bobot_nilai bn on (bn.kln_id=kl.kln_id and isnull(bn.RStat,0)=0)
            INNER JOIN tbl_dosen ds on (ds.ds_id=bn.ds_nidn and isnull(ds.RStat,0)=0)
            INNER JOIN tbl_matkul mk on (mk.mtk_kode=bn.mtk_kode and isnull(mk.RStat,0)=0 and isnull(mk.ig,0)=0)
            INNER JOIN tbl_jadwal jd on (
                jd.bn_id=bn.id
                AND jd.jdwl_id=isnull(jd.jdwl_parent,jd.jdwl_id)
                AND isnull(jd.RStat,0)=0 
                AND isnull(jd._totmhs,0)>0
                $kls
            )
            LEFT JOIN dosen_tipe dt on(dt.id=ds.id_tipe)
            WHERE isnull(kl.RStat,0)=0
            AND kl.kr_kode='$kr'
            ORDER BY ds.ds_nm,jd.GKode, jd.jdwl_hari,jd.jdwl_masuk,jd.jdwl_kls 
          ";
        $def=Yii::$app->db->createCommand($def)->queryOne();

        if($def['GKode']){
            $kls=[0=>'Pagi','Sore','All'];
            $shetName = $kls[$t].'-'.date('Ymd');
            $fileName="PLOATING_DOSEN-".$kr."-".$shetName;

            $objPHPExcel=new \PHPExcel;
            $objPHPExcel->getProperties()
                ->setCreator("sia.usbypkp.ac.id")
                ->setLastModifiedBy("sia.usbypkp.ac.id")
                ->setTitle($fileName)
                ->setSubject($fileName)
            ;


            // Add some data
            #$objPHPExcel->setActiveSheetIndex(0);
            $dt=['0'=>'NO.','TIPE DOSEN','MAKS. SKS','DOSEN','MTK.','SKS','JADWAL','KLS.',  'JML. MHS.'];

            $clm=range('A', 'Z');
            $a=0;$a1=0;$cl="";$cl1="";
            for($i=0;$i<=8;$i++){
                $cl=$cl1.$clm[$a];
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$cl".'1',$dt[$i]);
                #echo "$cl".'1'.' '.$dt[$i]." | ";
                $a++;
            }
            #DATA Excel
            $GKode=$def['GKode'];
            $n=1;$KLS="";$pKLS="";$MK="";$pMK="";$td="";$totMhs=0;$SKS=0;
            foreach($q as $d){
                if($GKode!=$d[0]){$GKode=$d[0];$pKLS="";$KLS="";$n++;$MK="";$pMK="";$totMhs=0;$SKS=0;}
                if($SKS<$d[10]){$SKS=$d[10];}
                if($pKLS!=$d[7]){$pKLS=$d[7];$KLS.=",$pKLS";}
                if($pMK!=$d[8]){$pMK=$d[8];$MK[$pMK]="( $d[8] ) $d[9] ";}
                $totMhs+=$d[11];
                $jdwl=explode("|",$d[6]);
                $jd = "";
                foreach($jdwl as $k=>$v){$Info=explode('#',$v);$ket=Funct::HARI()[$Info[1]].", $Info[2]-$Info[3]";$jd .=$ket." | ";}
                //echo "$clm[0]".($n+1)." :$n |"."$clm[1]".($n+1)." :$d[2] |"."$clm[2]".($n+1)." :$d[3] |"."$clm[3]".($n+1)." :$d[4] |"."$clm[4]".($n+1)." :".implode(" | ",$MK)." |";."$clm[5]".($n+1)." :$SKS |"."$clm[6]".($n+1)." :$jd |"."$clm[7]".($n+1)." :$totMhs |"."<br>";

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($clm[0].($n+1),$n);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($clm[1].($n+1),$d[2]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($clm[2].($n+1),$d[3]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($clm[3].($n+1),$d[4]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($clm[4].($n+1),implode(" | ",$MK));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($clm[5].($n+1),$SKS);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($clm[6].($n+1),substr($jd,0,-2));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($clm[7].($n+1),substr($KLS,1));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($clm[8].($n+1),$totMhs);

            }

            #die();

            #END DATA
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle($shetName);
            #echo "<br>";die();

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');

            // If you're serving to IE over SSL, then the following may be needed
            #header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            #header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0

            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        }else{throw new NotFoundHttpException('The requested page does not exist.');}

    }



    public function actionKehadiranDosen(){

        $searchModel = new LAbsenDosenSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('hadir_dosen', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model'=>$model,
        ]);
    }

    public function actionKehadiranDosenAdd(){
        $pr=false;$q=false;$def=false;$cModel=false;
        $model= new LAbsenDosen;
        $uid = Yii::$app->user->identity->id;
        if($model->load(Yii::$app->request->post())) {
            $kls="and ((jd.jdwl_masuk<'17:00' and jd.jdwl_hari BETWEEN 1 and 5) or (jd.jdwl_masuk<'12:00' and jd.jdwl_hari=6))";
            if($model->tipe=='1'){$kls="and ((jd.jdwl_masuk>'17:00' and jd.jdwl_hari BETWEEN 1 and 5) or (jd.jdwl_masuk>'12:00' and jd.jdwl_hari=6))";}

            $cModel=LAbsenDosen::find()->where("
                tipe='$model->tipe' and(
                 (('$model->tgl_awal' between tgl_awal and tgl_akhir) or ('$model->tgl_akhir' between tgl_awal and tgl_akhir)) OR
                 (( tgl_awal between '$model->tgl_awal' and '$model->tgl_akhir') or (tgl_akhir between '$model->tgl_awal' and '$model->tgl_akhir'))
                )
            ")->one();



            if($cModel){
                return $this->render('hadir_dosen_add', [
                    'model'=>$model,
                    'cModel'=>$cModel,
                ]);
            }else{
                if(isset($_POST['pr'])){
                    $pr=true;
                    #echo " exec ReportPersensiDosen_ '$model->kr_kode','$model->tipe','$model->tgl_awal','$model->tgl_akhir'";
                    $q  =Yii::$app->db->createCommand(" exec ReportPersensiDosen_ '$model->kr_kode','$model->tipe','$model->tgl_awal','$model->tgl_akhir'")->queryAll(\PDO::FETCH_NUM);
                    $def=Yii::$app->db->createCommand(" 
                        SELECT TOP 1 jd.GKode,'$model->tgl_awal' tgl_awal,'$model->tgl_akhir' tgl_akhir
                        from tbl_kalender kl 
                        INNER JOIN tbl_bobot_nilai bn on(bn.kln_id=kl.kln_id and isnull(bn.RStat,0)=0)
                        INNER JOIN tbl_jadwal jd on(jd.bn_id=bn.id and isnull(jd.RStat,0)=0 and isnull(jd._totmhs,0)>0 $kls)
                        INNER JOIN m_absen_dosen ma on( ma.jdwl_id=jd.jdwl_id and isnull(ma.RStat,0)=0 and ma.kr_kode_='$model->kr_kode'and isnull(ma.ds_stat,0)=1)
                        INNER JOIN tbl_dosen ds on(ds.ds_id=ma.ds_id and isnull(ds.RStat,0)=0)
                        WHERE kl.kr_kode='$model->kr_kode'
                        ORDER BY ds.ds_nm, jd.jdwl_hari,jd.jdwl_masuk, jd.GKode,jd.jdwl_kls
                     ")->queryOne();

                }
                if(isset($_POST['ex'])){
                    $kode  = date('YmdHis');
                    $model->kode=$kode;
                    $model->cuid=$uid;
                    $model->ctgl=new Expression("getdate()");
                    if($model->save()){
                        $model=LAbsenDosen::findOne(['kode'=>$kode]);
                        $sql="
                            insert into labsen_dosen_det(id_labsen,id_absen,ds_id,ds_tipe,ds_nm,ds_get_id,pelaksana,masuk,keluar,jdwl_id,jdwl_kls,jdwl_hari,jdwl_masuk,jdwl_keluar,mtk_kode,mtk_nama,mtk_sks,sesi,tgl_perkuliahan,tipe,ltipe,GKode_,totmhs)
                            SELECT $model->id,
                            t.id id_absen,t.ds_id ,ds.id_tipe, ds.ds_nm,isnull(t.ds_get_id,t.ds_id),ds1.ds_nm pelaksana
                            ,t.ds_masuk,t.ds_keluar,t.jdwl_id,jd.jdwl_kls,jd.jdwl_hari,jd.jdwl_masuk,jd.jdwl_keluar
                            ,mk.mtk_kode,mk.mtk_nama,mk.mtk_sks,t.sesi,t.tgl_perkuliahan
                            ,NULL tipe,0 ltipe,t.GKode_,jd._totmhs
                            from m_absen_dosen t
                            INNER JOIN tbl_dosen ds on(ds.ds_id=t.ds_id and isnull(ds.RStat,0)=0)
                            INNER JOIN tbl_jadwal jd on(
                              jd.jdwl_id=t.jdwl_id
                              and isnull(jd.RStat,0)=0 
                              AND  isnull(jd.jdwl_parent,jd.jdwl_id)=jd.jdwl_id
                              $kls 
                            ) 
                            INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and ISNULL(bn.RStat,0)=0)
                            INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and isnull(mk.RStat,0)=0)
                            INNER JOIN tbl_dosen ds1 on(ds1.ds_id=isnull(t.ds_get_id,t.ds_id) and isnull(ds1.RStat,0)=0)
                            WHERE kr_kode_='$model->kr_kode'
                            and isnull(t.RStat,0)=0 
                            and isnull(t.ds_stat,0)=1
                            and t.tgl_perkuliahan BETWEEN '$model->tgl_awal' AND '$model->tgl_akhir'
                            AND t.sesi in('1','2','3','4','5','6','7','8','9','10','11','12','13','14')
                            ";
                        if(Yii::$app->db->createCommand($sql)->execute()){return $this->redirect(['/laporan/download-persensi-dosen','id'=>$model->id]);}
                    }
                }
            }
            echo $cModel->id;
            #return $this->redirect(['view', 'id' => $model->id]);
        }

        $searchModel = new LAbsenDosenSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('hadir_dosen_add', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model'=>$model,
            'cModel'=>$cModel,
            'pr'=>$pr,  'q'=>$q,'def'=>$def,
        ]);
    }

    public function actionKehadiranDosenView($id){
        $model= LAbsenDosen::findOne($id);
        if($model){

            $q  =Yii::$app->db->createCommand(" exec ReportPersensiDosen $model->id")->queryAll(\PDO::FETCH_NUM);
            $def=Yii::$app->db->createCommand(" select top 1 * from labsen_dosen_det where id_labsen=$model->id  ORDER BY ds_nm,jdwl_hari,jdwl_masuk, GKode_")->queryOne();
            return $this->render('hadir_dosen_view',[
                "model"=>$model,
                'q'=>$q,
                'def'=>$def,
            ]);
        }else{throw new NotFoundHttpException('The requested page does not exist.');}
    }

    public function actionKehadiranDosenRf($id,$d=0){
        $model= LAbsenDosen::findOne($id);
        if($model){
            if($model->parent && $model->parent!=$model->id){$model = LAbsenDosen::findOne($model->parent);}

            $kls="and ((jd.jdwl_masuk<'17:00' and jd.jdwl_hari BETWEEN 1 and 5) or (jd.jdwl_masuk<'12:00' and jd.jdwl_hari=6))";
            if($model->tipe=='1'){$kls="and ((jd.jdwl_masuk>'17:00' and jd.jdwl_hari BETWEEN 1 and 5) or (jd.jdwl_masuk>'12:00' and jd.jdwl_hari=6))";}

            $q  =Yii::$app->db->createCommand(" exec ReportPersensiDosen_rf $model->id")->queryAll(\PDO::FETCH_NUM);
            $def=Yii::$app->db->createCommand(" 
                	SELECT  jd.GKode 
                            from m_absen_dosen t
                            INNER JOIN tbl_dosen ds on(ds.ds_id=t.ds_id and isnull(ds.RStat,0)=0)
                            INNER JOIN tbl_jadwal jd on(
                              jd.jdwl_id=t.jdwl_id
                              AND isnull(jd.jdwl_parent,jd.jdwl_id)=jd.jdwl_id
                              AND isnull(jd.RStat,0)=0 
                              $kls 
                            ) 
                            INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
                            INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and ISNULL(mk.RStat,0)=0)
                            INNER JOIN tbl_dosen ds1 on(ds1.ds_id=isnull(t.ds_get_id,t.ds_id) and isnull(ds1.RStat,0)=0)
                            LEFT JOIN labsen_dosen_det ld on(ld.id_absen=t.id)
                            WHERE kr_kode_='$model->kr_kode'
                            and isnull(t.RStat,0)=0 and isnull(t.ds_stat,0)=1
                            and t.tgl_perkuliahan BETWEEN '$model->tgl_awal' AND '$model->tgl_akhir'                           
                            AND t.sesi in('1','2','3','4','5','6','7','8','9','10','11','12','13','14')
                            and ld.id is null
                            ORDER BY ds.ds_nm, jd.jdwl_hari,jd.jdwl_masuk, jd.GKode,jd.jdwl_kls
            ")->queryOne();

            if($d==1 && $def['GKode']!=''){
                $model_= new LAbsenDosen;
                $uid = Yii::$app->user->identity->id;

                $kode  = date('YmdHis');
                $model_->kode=$kode;
                $model_->cuid=$uid;
                $model_->ctgl=new Expression("getdate()");
                $model_->kr_kode=$model->kr_kode;
                $model_->tgl_awal=$model->tgl_awal;
                $model_->tgl_akhir=$model->tgl_akhir;
                $model_->tipe=$model->tipe;
                $model_->parent=$model->id;
                $model->rf_count=($model->rf_count?:0)+1;
                $model_->rf_count=$model->rf_count;
                if($model_->save()){
                    $model->save(false);
                    $model_=LAbsenDosen::findOne(['kode'=>$kode]);
                    $sql="
                            insert into labsen_dosen_det(id_labsen,id_absen,ds_id,ds_tipe,ds_nm,ds_get_id,pelaksana,masuk,keluar,jdwl_id,jdwl_kls,jdwl_hari,jdwl_masuk,jdwl_keluar,mtk_kode,mtk_nama,mtk_sks,sesi,tgl_perkuliahan,tipe,ltipe,GKode_,totmhs)
                            SELECT $model_->id,
                            t.id id_absen,t.ds_id ,ds.id_tipe, ds.ds_nm,t.ds_get_id,ds1.ds_nm pelaksana
                            ,t.ds_masuk,t.ds_keluar,t.jdwl_id,jd.jdwl_kls,jd.jdwl_hari,jd.jdwl_masuk,jd.jdwl_keluar
                            ,mk.mtk_kode,mk.mtk_nama,mk.mtk_sks,t.sesi,t.tgl_perkuliahan
                            ,NULL tipe,0 ltipe,t.GKode_,jd._totmhs
                            from m_absen_dosen t
                            INNER JOIN tbl_dosen ds on(ds.ds_id=t.ds_id and isnull(ds.RStat,0)=0)
                            INNER JOIN tbl_jadwal jd on(
                              jd.jdwl_id=t.jdwl_id
                              AND isnull(jd.jdwl_parent,jd.jdwl_id)=jd.jdwl_id
                              AND isnull(jd.RStat,0)=0 
                              $kls 
                            ) 
                            INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
                            INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and ISNULL(mk.RStat,0)=0)
                            INNER JOIN tbl_dosen ds1 on(ds1.ds_id=isnull(t.ds_get_id,t.ds_id) and isnull(ds1.RStat,0)=0)
                            LEFT JOIN labsen_dosen_det ld on(ld.id_absen=t.id)
                            WHERE kr_kode_='$model->kr_kode'
                            and isnull(t.RStat,0)=0 and isnull(t.ds_stat,0)=1
                            and t.tgl_perkuliahan BETWEEN '$model->tgl_awal' AND '$model->tgl_akhir'                           
                            AND t.sesi in('1','2','3','4','5','6','7','8','9','10','11','12','13','14')
                            and ld.id is null
                            ";
                    if(Yii::$app->db->createCommand($sql)->execute()){return $this->redirect(['/laporan/download-persensi-dosen','id'=>$model_->id]);}
                }
            }

            return $this->render('hadir_dosen_ref',[
                "model"=>$model,
                'q'=>$q,
                'def'=>$def,
            ]);
        }else{throw new NotFoundHttpException('The requested page does not exist.');}
    }

    public function actionDownloadPersensiDosen($id,$rf=0){

        $model= LAbsenDosen::findOne($id);
        if($model){
            $bln=array(1=>"Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des");
            $datetime1 = date_create($model->tgl_awal);
            $datetime2 = date_create($model->tgl_akhir);
            $interval = date_diff($datetime1, $datetime2);
            $jmlTgl   = $interval->format('%a');
            $jmlTgl+=11;
            $kls=[0=>'Pagi','Sore'];
            $shetName = $kls[$model->tipe].'-'.date_format($datetime1, 'ymd').'-'.date_format($datetime2, 'ymd');
            $fileName="Kehadiran_Dosen-".$model->kr_kode."-".$shetName;
            $HDR="HADIR";
            if($model->parent){$fileName.="-RV".$model->rf_count;$HDR="PENAMBAHAN";}

            $objPHPExcel=new \PHPExcel;
            $objPHPExcel->getProperties()
                ->setCreator("sia.usbypkp.ac.id")
                ->setLastModifiedBy("sia.usbypkp.ac.id")
                ->setTitle($fileName)
                ->setSubject($fileName)
                #->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                #->setKeywords("office 2007 openxml php")
                #->setCategory("Test result file")
            ;


            // Add some data
            #$objPHPExcel->setActiveSheetIndex(0);
            $dt=['0'=>'NO.','TIPE DOSEN','MAKS. SKS','DOSEN','PELASKSANA','PERGANTIAN','MTK.','SKS','JADWAL','KLS.','JML. MHS.'];

            #Header Excel
            $dt[]=date_format($datetime1, 'y').'/'.date_format($datetime1, 'd').'-'.$bln[abs(date_format($datetime1, 'm'))];
            while($datetime1!=$datetime2){
                date_add($datetime1, date_interval_create_from_date_string('1 days'));
                $dt[]=date_format($datetime1, 'y').'/'.date_format($datetime1, 'd').'-'.$bln[abs(date_format($datetime1, 'm'))];
            }
            $dt[]=$HDR;

            $clm=range('A', 'Z');
            $a=0;$a1=0;$cl="";$cl1="";
            for($i=0;$i<=$jmlTgl;$i++){
                if($i%26==0){$a=0;$a1++;}
                if($i>=26){$cl1= $clm[($a1-2)];}
                $cl=$cl1.$clm[$a];
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$cl".'1',$dt[$i]);
                #echo "$cl".'1'.' '.$dt[$i]." | ";
                $a++;
            }



            #DATA Excel
            $q  =Yii::$app->db->createCommand(" exec ReportPersensiDosen $model->id")->queryAll(\PDO::FETCH_NUM);
            $def=Yii::$app->db->createCommand(" select top 1 * from labsen_dosen_det where id_labsen=$model->id  ORDER BY ds_nm,jdwl_hari,jdwl_masuk, GKode_")->queryOne();

            $GKode=$def['GKode_'];
            #$GKode='';
            $n=1;
            $KLS="";$pKLS="";
            $MK="";$pMK="";
            $td="";$totMhs=0;
            foreach($q as $d){
                if($GKode!=$d[0]){
                    $GKode=$d[0];$pKLS="";$KLS="";$n++;
                    $MK="";$pMK="";$totMhs=0;
                }

                if($pKLS!=$d[9]){$pKLS=$d[9];$KLS.=",$pKLS";}
                if($pMK!=$d[6]){$pMK=$d[6];$MK[$pMK]=$d[6];}
                $totMhs+=$d[10];

                $a=0;$a1=0;$cl="";$cl1="";
                $hSum1="";$hSum2="";
                for($i=0;$i<=$jmlTgl;$i++){
                    if($i==12){$hSum1="$cl".($n+1);}
                    if($i%26==0){$a=0;$a1++;}
                    if($i>=26){$cl1= $clm[($a1-2)];}
                    $cl=$cl1.$clm[$a];
                    if($i==0){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$cl".($n+1),$n);
                        #if($n==9){echo " $totMhs ".$cl." ".$n." # ";}
                    }else if($i==6){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$cl".($n+1),implode(" | ",$MK));
                        #if($n==9){echo $cl." ".implode(" | ",$MK)." # ";}
                    }else if($i==9){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$cl".($n+1),substr($KLS,1));
                        #if($n==9){echo $cl." ".substr($KLS,1)." # ";}
                    }else if($i==10){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$cl".($n+1),$totMhs);
                        #if($n==9){echo $cl." $totMhs # ";}
                    }else{
                        if($i==$jmlTgl){
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$cl".($n+1),"=sum($hSum1:$hSum2)");
                            #if($n==9){echo $cl." =sum($hSum1:$hSum2) # ";}
                        }else{
                            $hSum2="$cl".($n+1);$objPHPExcel->setActiveSheetIndex(0)->setCellValue("$cl".($n+1),$d[$i]);
                            #if($n==9){echo $cl." $d[$i] # ";}
                        }

                    }
                    $a++;
                }#echo"<br>";

                #DATA AKHIR
                #$n

                #-- definisi nilai awal

            }
            #$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
            #die();
            #END DATA
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle($shetName);
            #echo "<br>";die();

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');

            // If you're serving to IE over SSL, then the following may be needed
            #header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            #header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0

            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;



//            $q  =Yii::$app->db->createCommand(" exec ReportPersensiDosen $model->id")->queryAll(\PDO::FETCH_NUM);
//            $def=Yii::$app->db->createCommand(" select top 1 * from labsen_dosen_det where id_labsen=$model->id  ORDER BY ds_nm,jdwl_hari,jdwl_masuk, GKode_")->queryOne();
//            return $this->render('hadir_dosen_view',[
//                "model"=>$model,
//                'q'=>$q,
//                'def'=>$def,
//            ]);
        }else{throw new NotFoundHttpException('The requested page does not exist.');}

    }

    public function actionDownloadPersensiDosenDev($id,$rf=0){

        $model= LAbsenDosen::findOne($id);
        if($model){
            $bln=array(1=>"Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des");
            $datetime1 = date_create($model->tgl_awal);
            $datetime2 = date_create($model->tgl_akhir);
            $interval = date_diff($datetime1, $datetime2);
            $jmlTgl   = $interval->format('%a');
            $jmlTgl+=11;
            $kls=[0=>'Pagi','Sore'];
            $shetName = $kls[$model->tipe].'-'.date_format($datetime1, 'ymd').'-'.date_format($datetime2, 'ymd');
            $fileName="Kehadiran_Dosen-".$model->kr_kode."-".$shetName;
            $HDR="HADIR";
            if($model->parent){$fileName.="-RV".$model->rf_count;$HDR="PENAMBAHAN";}

            $objPHPExcel=new \PHPExcel;
            $objPHPExcel->getProperties()
                ->setCreator("sia.usbypkp.ac.id")
                ->setLastModifiedBy("sia.usbypkp.ac.id")
                ->setTitle($fileName)
                ->setSubject($fileName)
                #->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                #->setKeywords("office 2007 openxml php")
                #->setCategory("Test result file")
            ;


            // Add some data
            #$objPHPExcel->setActiveSheetIndex(0);
            $dt=['0'=>'NO.','TIPE DOSEN','MAKS. SKS','DOSEN','PELASKSANA','PERGANTIAN','MTK.','SKS','JADWAL','KLS.','JML. MHS.'];

            #Header Excel
            $dt[]=date_format($datetime1, 'y').'/'.date_format($datetime1, 'd').'-'.$bln[abs(date_format($datetime1, 'm'))];
            while($datetime1!=$datetime2){
                date_add($datetime1, date_interval_create_from_date_string('1 days'));
                $dt[]=date_format($datetime1, 'y').'/'.date_format($datetime1, 'd').'-'.$bln[abs(date_format($datetime1, 'm'))];

            }
            $dt[]=$HDR;

            $clm=range('A', 'Z');
            $a=0;$a1=0;$cl="";$cl1="";
            for($i=0;$i<=$jmlTgl;$i++){
                if($i%26==0){$a=0;$a1++;}
                if($i>=26){$cl1= $clm[($a1-2)];}
                $cl=$cl1.$clm[$a];
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$cl".'1',$dt[$i]);
                #echo "$cl".'1'.' '.$dt[$i]." | ";
                $a++;
            }


            #die("tes");

            #DATA Excel
            $q  =Yii::$app->db->createCommand(" exec ReportPersensiDosen $model->id")->queryAll(\PDO::FETCH_NUM);
            $def=Yii::$app->db->createCommand(" select top 1 * from labsen_dosen_det where id_labsen=$model->id  ORDER BY ds_nm,jdwl_hari,jdwl_masuk, GKode_")->queryOne();

            $GKode=$def['GKode_'];
            #$GKode='';
            $n=1;
            $KLS="";$pKLS="";
            $MK="";$pMK="";
            $td="";$totMhs=0;
            foreach($q as $d){
                if($GKode!=$d[0]){
                    $GKode=$d[0];$pKLS="";$KLS="";$n++;
                    $MK="";$pMK="";$totMhs=0;
                }

                if($pKLS!=$d[9]){$pKLS=$d[9];$KLS.=",$pKLS";}
                if($pMK!=$d[6]){$pMK=$d[6];$MK[$pMK]=$d[6];}
                $totMhs+=$d[10];

                $a=0;$a1=0;$cl="";$cl1="";
                $hSum1="";$hSum2="";
                for($i=0;$i<=$jmlTgl;$i++){
                    if($i==12){$hSum1="$cl".($n+1);}
                    if($i%26==0){$a=0;$a1++;}
                    if($i>=26){$cl1= $clm[($a1-2)];}
                    $cl=$cl1.$clm[$a];
                    if($i==0){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$cl".($n+1),$n);
                        #if($n==9){echo " $totMhs ".$cl." ".$n." # ";}
                    }else if($i==6){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$cl".($n+1),implode(" | ",$MK));
                        #if($n==9){echo $cl." ".implode(" | ",$MK)." # ";}
                    }else if($i==9){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$cl".($n+1),substr($KLS,1));
                        #if($n==9){echo $cl." ".substr($KLS,1)." # ";}
                    }else if($i==10){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$cl".($n+1),$totMhs);
                        #if($n==9){echo $cl." $totMhs # ";}
                    }else{
                        if($i==$jmlTgl){
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$cl".($n+1),"=sum($hSum1:$hSum2)");
                            #if($n==9){echo $cl." =sum($hSum1:$hSum2) # ";}
                        }else{
                            $hSum2="$cl".($n+1);$objPHPExcel->setActiveSheetIndex(0)->setCellValue("$cl".($n+1),$d[$i]);
                            #if($n==9){echo $cl." $d[$i] # ";}
                        }

                    }
                    $a++;
                }
                #echo"<br>";

                #DATA AKHIR
                #$n

                #-- definisi nilai awal

            }
            #$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
            #die();
            #END DATA
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle($shetName);
            #echo "<br>";die();

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');

            // If you're serving to IE over SSL, then the following may be needed
            #header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            #header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0

            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;



//            $q  =Yii::$app->db->createCommand(" exec ReportPersensiDosen $model->id")->queryAll(\PDO::FETCH_NUM);
//            $def=Yii::$app->db->createCommand(" select top 1 * from labsen_dosen_det where id_labsen=$model->id  ORDER BY ds_nm,jdwl_hari,jdwl_masuk, GKode_")->queryOne();
//            return $this->render('hadir_dosen_view',[
//                "model"=>$model,
//                'q'=>$q,
//                'def'=>$def,
//            ]);
        }else{throw new NotFoundHttpException('The requested page does not exist.');}

    }

    public function actionHadirDosenDel($id){
        $model=LAbsenDosen::findOne($id);
        LabsenDosenDet::deleteAll("id_labsen in(select id from l_absen_dosen where (parent=$model->id or id=$model->id))");
        LAbsenDosen::deleteAll("parent=$model->id");
        $model->delete();
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }

    public function actionView($id){
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    public function actionCreate(){
        $model = new LAbsenDosen;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    public function actionUpdate($id){
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    protected function findModel($id){
        if (($model = LAbsenDosen::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    #perwalian Aktif
    public function actionPerwalian(){
        echo "";

    }

}
