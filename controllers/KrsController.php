<?php

namespace app\controllers;
use app\models\Akses;
use app\models\Dosen;
use app\models\JadwalSearch;
use app\models\Kurikulum;
use app\models\MahasiswaSearch;
use kartik\mpdf\Pdf;
use Yii;

use app\models\Funct;
use app\models\Pkrs;
use app\models\Functdb;
use app\models\Jadwal;
use app\models\Kalender;
use app\models\Mahasiswa;
use app\models\Regmhs;
use app\models\TKrsDet;
use app\models\TKrsHead;
use app\models\Krs;
use app\models\KrsSearch;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Expression;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KrsController implements the CRUD actions for Krs model.
 */
class KrsController extends Controller{

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

    /**
     * Lists all Krs models.
     * @return mixed
     */
    public function actionIndex(){
        $searchModel = new KrsSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Krs model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->krs_id]);
        } else {
        return $this->render('view', ['model' => $model]);
}
    }

    /**
     * Creates a new Krs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Krs;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->krs_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Krs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->krs_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Krs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
		$model->RStat=1;
		$model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Krs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Krs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Krs::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCetakKrs() {

        $searchModel = Krs::find()->select('*');
        $dataProvider = new ActiveDataProvider([
            'query' => $searchModel,
                'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $models = $dataProvider->getModels();

        $content = '
                <table class="table table-bordered">
                    <tr>
                        <td align="center"><img src="'. Url::to("@web/ypkp.png") .'" width="10%"></td>
                        <td align="center"><h4>Universitas Sangga Buana YPKP Bandung</h4><p><b>Direktorat Sistem Informasi & Multimedia</b></p></td>
                    </tr>
                </table>
                <br>
                    <h4><b>Data Jadwal UTS</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Jurusan</th>
                                <th>Program</th>
                                <th>Jadwal</th>
                                <th>Kelas</th>
                                <th>Matakuliah</th>
                                <th>UTS</th>
                                <th>Ruang</th>
                            </tr>
                        </thead>
                    ';
        foreach ($models as $key => $value) {
            $content .= '
                    <tbody>
                        <tr>
                            <td>'.@$value->bn->kln->jr->jr_jenjang.'-'.@$value->bn->kln->jr->jr_nama.'</td>
                            <td>'.@$value->bn->kln->pr->pr_nama.'</td>
                            <td>'.@$value->jdwl_masuk.'-'.@$value->jdwl_keluar.'</td>
                            <td>'.@$value->jdwl_kls .'</td>
                            <td>'.@$value->bn->mtk->mtk_nama .'</td>
                            <td>'.@$value->jdwl_uts.'<br>'.@$value->jdwl_uts_out.'</td>
                            <td>'.@$value->rg_uts .'</td>
                        </tr>
                    </tbody>
                    ';
        }

        $content.='</table>';

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
             // call mPDF methods on the fly
            'methods' => [
                'SetHeader'=>['Data Jadwal UTS - Universitas Sangga Buana YPKP Bandung'],
                'SetFooter'=>[' ('.date('d-m-Y H:i:S').') Halaman-{PAGENO}'],
            ]
        ]);

        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    #KHUSUS MAHASISWA
    public function actionMhs(){

        $user   = Yii::$app->user->identity->username;
        $mMhs   = Mahasiswa::findOne(['mhs_nim'=>$user]);
        if(!$mMhs){throw new NotFoundHttpException('The requested page does not exist.');}

        #cari kalender akademik aktif
        $mKr    = Kalender::find()->where("
            isnull(RStat,0)=0
            and CAST(GETDATE() as date) BETWEEN kln_krs and krs_akhir
            and jr_id='$mMhs->jr_id' and pr_kode='$mMhs->pr_kode' 
        ")->one();
        #Funct::v($mKr);
        $vkr=0;

        $tgl=date('Y-m-d');
        $tgl1=date('Y-m-d',strtotime($mKr->kln_krs));
        $tgl2=date('Y-m-d',strtotime($mKr->krs_akhir));
        #$tgl2=date('Y-m-d',strtotime('2018-09-09'));
        $open=false;
        if($tgl >= $tgl1 and $tgl<=$tgl2){
            $open=true;
        }else{
            $pkrs = Pkrs::findOne(['kr_kode'=>$mKr->kr_kode,'mhs_nim'=>"$user"]);
            if($pkrs->mhs_nim!==null){
                $tgl=date('Y-m-d');
                $tgl1=date('Y-m-d',strtotime($pkrs->tgl_awal));
                $tgl2=date('Y-m-d',strtotime($pkrs->tgl_akhir));
                if($tgl >= $tgl1 and $tgl<=$tgl2){$open=true;}
            }
        }

        if($mKr->kr_kode!=null){$vkr=substr($mKr->kr_kode,0,1);}
        $vid=[0=>0,1=>85,2=>86,3=>87];
        $maxSks = "select nil from aturan WHERE  id=$vid[$vkr]";
        $maxSks=Yii::$app->db->createCommand($maxSks)->queryOne();

        #Cek Registrasi
        $mReg = Regmhs::findOne(['tahun'=>$mKr->kr_kode,'nim'=>$mMhs->mhs_nim]);

        #cek data KRS
        $mHkrs  = TKrsHead::findOne(['nim'=>$mMhs->mhs_nim,'kr_kode'=>$mKr->kr_kode]);
        $listKrs="
            SELECT
                iif(mkd.kode is null,0,1)mkkr,
                jd.jdwl_id,ds.ds_nm,
                krs.id,krs.krs_ulang,
                mk.ig,mk.mtk_kode,mk.mtk_nama,mk.mtk_sks,
                jd.jdwl_hari,jd.jdwl_masuk,jd.jdwl_keluar,jd.jdwl_kls,  
                dbo.subJdwl(jd.jdwl_id) subjadwal,
                krs.ket,
                krs.krs_stat
            from t_krs_det krs
            INNER JOIN tbl_jadwal jd on(jd.jdwl_id=krs.jdwl_id)
            INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
            INNER JOIN tbl_dosen ds on(ds.ds_id=bn.ds_nidn and isnull(ds.RStat,0)=0)
            INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and isnull(mk.RStat,0)=0)
            INNER JOIN tbl_mahasiswa mhs on(mhs.mhs_nim=krs.mhs_nim)
            LEFT JOIN matkul_kr_det mkd on(mkd.id_kr=mhs.mk_kr AND mkd.kode=mk.mtk_kode)
            WHERE krs.kr_kode='$mKr->kr_kode' AND krs.mhs_nim='$mMhs->mhs_nim' AND isnull(krs.RStat,0)=0
            order by mk.mtk_semester,mk.mtk_kode
        ";
        #Funct::v($listKrs);
        $listKrs = Yii::$app->db->createCommand($listKrs)->queryAll();
        $idLog =[];
        foreach($mHkrs->krsdet as $d){$idLog[]=$d['id'];}
        #Funct::v($idLog);
        $LOG="";
        if(count($idLog)>0){$LOG=Functdb::vLog(TKrsDet::$ID,$idLog);}
        #cek jadwal
        $qTot=" select count(*) from tbl_jadwal jd inner join tbl_bobot_nilai bn on(jd.bn_id=bn.id and isnull(bn.RStat,0)=0 and bn.kln_id = ".( $mKr->kln_id?:0)." ) ";
        $countJdwl = Yii::$app->db->createCommand($qTot)->queryOne();



        #Funct::v($mHkrs);
        #$hKRS=KrsHead::findOne(['mhs_nim']);

        return $this->render('ins_mhs',[
            'mMhs'=>$mMhs,
            'mHkrs'=>$mHkrs,
            'listKrs'=>$listKrs,
            'mKr'=>$mKr,
            'maxSks'=>$maxSks,
            'mReg'=>$mReg,
            'LOG'=>$LOG,
            'countJdwl'=>$countJdwl,
            'open'=>$open,
        ]);

    }

    public function actionMhsDel($id){
        $model=TKrsDet::findOne(['id'=>$id]);

        if($model->mhs_nim!=Yii::$app->user->identity->username){
            throw new NotFoundHttpException('The requested page does not exist.');
        }else{
            $subJdwl="select dbo.subJdwl($model->jdwl_id) sub";
            $subJdwl=Yii::$app->db->createCommand($subJdwl)->queryOne();
            $jdwl=explode("|",$subJdwl['sub']);$jd="";
            foreach($jdwl as $k=>$v){$Info=explode('#',$v);$ket=Funct::HARI()[$Info[1]].", $Info[2]-$Info[3]";$jd .=$ket." & ";}
            $act = $model->jdwl->bn->mtk->mtk_kode.' '.$model->jdwl->bn->mtk->mtk_nama." (". substr($jd,0,-2).")";
            $model->RStat   = '1';
            $model->duid    = Yii::$app->user->identity->id;
            $model->dtgl   = new Expression('getdate()');

            if($model->krs_stat==0){
                $model->save(false);
                Yii::$app->db->createCommand("update tbl_jadwal set mhs-=1 WHERE jdwl_id=$model->jdwl_id")->execute();
                Functdb::insLog($model::$ID,$model->id,'D',"Menghapus Data KRS $act",'-');
            }else{
                Yii::$app->getSession()->setFlash('error',"Matakuliah $act  Tidak bisa dihapus!");
            }
        }
        #return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        return $this->redirect(['krs/mhs']);

    }

    #--- CETAK KRS KHUSUS MAHASISWA
    public  function actionMhsCetak($id){
        $model = TKrsHead::findOne(['id'=>$id]);
        $db = Yii::$app->db;
        $ID = Yii::$app->user->identity->username;
        if($ID!=$model->nim){throw new NotFoundHttpException('The requested page does not exist.');}

        $sql = "
                select 
                    krs.krs_stat,krs.krs_ulang,
                    mk.mtk_kode,mk.mtk_nama,mk.mtk_sks,
					dbo.subJdwl(jd.jdwl_id) jadwal,jd.jdwl_kls
                from t_krs_det krs
                INNER JOIN tbl_jadwal jd on (jd.jdwl_id=krs.jdwl_id AND isnull(jd.RStat,0)=0 )
                INNER JOIN tbl_bobot_nilai bn on (bn.id=jd.bn_id AND isnull(bn.RStat,0)=0)
                INNER JOIN tbl_kalender kl on (kl.kln_id=bn.kln_id AND isnull(kl.RStat,0)=0)
                INNER JOIN tbl_matkul mk on (mk.mtk_kode=bn.mtk_kode AND isnull(mk.RStat,0)=0)
                where krs.id_head=$model->id and isnull(krs.krs_stat,0)<>2 and isnull(krs.RStat,0)=0
        ";

        $krs = $db->createCommand($sql)->queryAll();

        $data = [
            'model'=>$model,
            'krs'=>$krs,
        ];

        $this->layout = 'blank';
        #return
        $content = $this->renderPartial('mhs_pdf',$data);


        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'content' => $content,
            //'format'=>[148,210],
            'marginLeft'=>5,
            'marginRight'=>5,
            'marginTop'=>20,
            'marginHeader'=>5,
            'orientation'=>'P',
            'destination'=>'I',
            'cssInline'=>'
					table{overlow:warp;font-size:12px;}
					body{font-size: 12px;}
				',
            'filename'=>'KRS-'.$model->nim.'-'.date('YmdHis').'.pdf',
            'options' => [
                'title' => 'KRS '.$model->nim,
                'subject' => 'KRS '.$model->nim,
                'watermarkText'=>'DIREKTORAT SISTEM INFORMASI & MULTIMEDIA',
                'watermarkTextAlpha'=>0.2,
                'showWatermarkText'=>true,
                //'debug'=>true,
            ],
            'methods' => [
                'SetHeader' => ['
					<table>
						<tr>
							<td><img src="'.Url::to('@web/ypkp.png').'" width="50"></td>
							<td>
							<b>UNIVERSITAS SANGGA BUANA YPKP</b>
							<p><small>Jl. PHH Mustopa No 68, Telp. (022) 7202233. Bandung 40124 
							&nbsp;E-mail : sim@usbypkp.ac.id. Homepage : www.usbypkp.ac.id</small></p>
								'.'
							</td>
						</tr>
					</table>'],
                'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'|Page {PAGENO}'.'|'.date("r")],
            ]
        ]);
        return $pdf->render();
    }

    /* DATA t_krs_det */
    public function actionMhsJadwal(){
        $user   = Yii::$app->user->identity->username;
        $mMhs   = Mahasiswa::findOne(['mhs_nim'=>$user]);
        if(!$mMhs){throw new NotFoundHttpException('The requested page does not exist.');}

        #cari kalender akademik aktif
        $mKr    = Kalender::find()->where("
            isnull(RStat,0)=0
            and CAST(GETDATE() as date) BETWEEN kln_krs and krs_akhir
            and jr_id='$mMhs->jr_id' and pr_kode='$mMhs->pr_kode' 
        ")->one();

        $tgl=date('Y-m-d');
        $tgl1=date('Y-m-d',strtotime($mKr->kln_krs));
        $tgl2=date('Y-m-d',strtotime($mKr->krs_akhir));
        #$tgl2=date('Y-m-d',strtotime('2018-09-09'));
        $open=false;
        if($tgl >= $tgl1 and $tgl<=$tgl2){$open=true;
        }else{
            $pkrs = Pkrs::findOne(['kr_kode'=>$mKr->kr_kode,'mhs_nim'=>"$user"]);
            if($pkrs->mhs_nim!==null){
                $tgl=date('Y-m-d');
                $tgl1=date('Y-m-d',strtotime($pkrs->tgl_awal));
                $tgl2=date('Y-m-d',strtotime($pkrs->tgl_akhir));
                if($tgl >= $tgl1 and $tgl<=$tgl2){$open=true;}
            }
        }


        $vkr=substr($mKr->kr_kode,0,1);
        $vid=[1=>85,2=>86,3=>87];
        $maxSks = "select nil from aturan WHERE  id=$vid[$vkr]";
        $maxSks=Yii::$app->db->createCommand($maxSks)->queryOne();

        #kouta mahasiswa
        //$kuota= "select nil,aktif from aturan WHERE  id=66";
        //$kuota = Yii::$app->db->createCommand($kuota)->queryOne();
        //$kuota = $kuota['nil'] && $kuota['aktif']=='1'?$kuota['nil']:0;

        $listJadwal ="exec MenuJadwalKrs '$mMhs->mhs_nim','$mKr->kr_kode'";

        $dataProvider = new SqlDataProvider([
            'sql'=>$listJadwal,
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $listJadwal=Yii::$app->db->createCommand($listJadwal)->queryAll();


        $listKrs="
            SELECT
                jd.jdwl_id,
                mk.ig,mk.mtk_kode,mk.mtk_sks,
                jd.jdwl_hari,jd.jdwl_masuk,jd.jdwl_keluar,krs.krs_stat
            from t_krs_det krs
            LEFT JOIN tbl_jadwal jd on( isnull(jd.jdwl_parent,jd.jdwl_id)=krs.jdwl_id)
            INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
            INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and isnull(mk.RStat,0)=0)
            WHERE krs.kr_kode='$mKr->kr_kode' AND krs.mhs_nim='$mMhs->mhs_nim' AND isnull(krs.RStat,0)=0
        ";
        #Funct::v($listKrs);
        $listKrs = Yii::$app->db->createCommand($listKrs)->queryAll();
        #Funct::v($listKrs);
        $JD=[];
        foreach($listKrs as $data){

            if($data['ig']==0){
                if($data['krs_stat']!=2){
                    $JD['JD'][$data['jdwl_hari']][]=[
                        'm'=>$data['jdwl_masuk'],
                        'k'=>$data['jdwl_keluar'],
                    ];
                }
            }
            // if ($data['krs_stat']!=2){
                $JD['MK'][$data['mtk_kode']]=1;
                $JD['ID'][$data['jdwl_id']]=$data['krs_stat'];
            // }

            }

        //}
        //$JD['K']=$kuota;
        #echo $sql = " exec menuKrsMhs '$mMhs->mhs_nim','$mKr->kr_kode'";
        return $this->render('mhs_jdwl',[
            'data'=>$dataProvider,
            'mKr'=>$mKr,
            'maxSks'=>$maxSks,
            'listJadwal'=>$listJadwal,
            'listKrs'=>$listKrs,
            'JD'=>$JD,
            'model'=>'',
            'open'=>$open
        ]);
    }

    public function actionMhsProc(){
        if(isset($_POST['jdwl'])){
            $jd = $_POST['jdwl'];
            $ketMk='';$ketJm='';$ketQ='';$ketSks='';$JD=[];
            $vJdwl=[];
            #cek total jadwal yang dipilih

            if(count($jd)>0){

                #validasi kapasitas kls
                $qKouta="select nil, aktif,isnull(def,0) def from aturan where id=66";
                $qKouta=Yii::$app->db->createCommand($qKouta)->queryOne();

                #if($qKouta['aktif']==1){$kuota=$qKouta['nil'];}

                $user=Yii::$app->user->identity;
                $modJd=Jadwal::findOne(['jdwl_id'=>$jd[0],"isnull(RStat,0)"=>0 ]);
                if(!$modJd){throw new NotFoundHttpException('The requested page does not exist.');}
                $mhs=Mahasiswa::findOne($user->username);
                if(!$mhs){throw new NotFoundHttpException('The requested page does not exist.');}

                $listKrs="
                    SELECT
                        jd.jdwl_id,
                        isnull(mk.ig,0) ig,mk.mtk_kode,mk.mtk_sks,
                        jd.jdwl_hari,jd.jdwl_masuk,jd.jdwl_keluar,
                        isnull(krs.krs_stat,0) krs_stat
                    from t_krs_det krs
                    LEFT JOIN tbl_jadwal jd on( isnull(jd.jdwl_parent,jd.jdwl_id)=krs.jdwl_id)
                    INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
                    INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and isnull(mk.RStat,0)=0)
                    WHERE krs.kr_kode='".$modJd->bn->kln->kr_kode."' AND krs.mhs_nim='$mhs->mhs_nim' AND isnull(krs.RStat,0)=0
                    
                ";

                $listKrs = Yii::$app->db->createCommand($listKrs)->queryAll();

                $totSks=0;
                foreach($listKrs as $data){
                    if($data['krs_stat']!=2){
                        $totSks+=$data['mtk_sks'];
                        if($data['ig']==0){
                            $JD['JD'][$data['jdwl_hari']][]=[
                                'm'=>$data['jdwl_masuk'],
                                'k'=>$data['jdwl_keluar'],
                            ];
                        }

                    }
                    #$totSks+=$data['mtk_sks'];
                    $JD['MK'][$data['mtk_kode']]=1;
                    $JD['ID'][$data['jdwl_id']]=1;
                }
                #Funct::v($totSks);

                #INSERT Transaksi Head KRS * 041044- dibuat dinamis
                $kode = '041044-'.$modJd->bn->kln->kr_kode.'-'.$mhs->mhs_nim;
                $modHkrs = TKrsHead::findOne($kode);
                if(!$modHkrs){
                    $modHkrs = new  TKrsHead;
                    $modHkrs->kode      = $kode;
                    $modHkrs->nim       = $mhs->mhs_nim;
                    $modHkrs->ds_id     = $mhs->ds_wali;
                    $modHkrs->kr_kode   = $modJd->bn->kln->kr_kode;
                    $modHkrs->cuid      = $user->id;
                    #/*
                    if($modHkrs->save()){
                        $modHkrs = TKrsHead::findOne($kode);
                        Functdb::insLog($modHkrs::$ID,$modHkrs->id,'C',"Menambah Data Awal KRS $kode - $mhs->mhs_nim",'Data Awal');
                        $modHkrs = TKrsHead::findOne($kode);
                    }else{Funct::v($modHkrs->getErrors());}
                    #*/
                }

                $vkr=substr($modHkrs->kr_kode,0,1);
                $vid=[1=>85,2=>86,3=>87];
                $maxSks = "select nil from aturan WHERE  id=$vid[$vkr]";
                $maxSks=Yii::$app->db->createCommand($maxSks)->queryOne();

                $modJdAll = Jadwal::find()->where(['jdwl_id'=>$jd])->all();

                #Funct::v($jd);
                #Funct::v($maxSks);
                $n=0;
                foreach ($modJdAll as $data){
                    $n++;
                    $ig=$data->bn->mtk->ig?:0;
                    $dis = false;
                    $subJdwl="select dbo.subJdwl($data->jdwl_id) sub";
                    $subJdwl=Yii::$app->db->createCommand($subJdwl)->queryOne();
                    $jdwl=explode("|",$subJdwl['sub']);$jd="";

                    if($ig==0){
                        foreach($jdwl as $k=>$v){
                            $Info=explode('#',$v);
                            $ket=Funct::HARI()[$Info[1]].", $Info[2]-$Info[3]";$jd .=$ket." & ";
                            $vJdwl[$Info[1]][]=['m'=>$Info[2],'k'=>$Info[3]];
                            if(isset($JD['JD'][$Info[1]])){
                                foreach($JD['JD'][$Info[1]] as $d){
                                    $M = date('H:i',strtotime($d['m']));$K=date('H:i',strtotime($d['k']));
                                    $m = date('H:i',strtotime($Info[2]));
                                    $k = date('H:i',strtotime($Info[3]));
                                    #Perbandingan KRS Dengan Jadwal
                                    if($m >= $M && $m <$K){$dis=true;}if($k > $M && $k <$K){$dis=true;}
                                    #Perbandingan KRS Dengan Jadwal
                                    if($M >= $m && $M <$k){$dis=true;}if($K > $m && $K <$k){$dis=true;}
                                }
                            }
                        }

                    }

                    if($dis){$ketJm.=",".$data->bn->mtk->mtk_kode.' '.$data->bn->mtk->mtk_nama." (". substr($jd,0,-2).") ";}
                    if(isset($JD['MK'][$data->bn->mtk_kode])){$ketMk.=",".$data->bn->mtk->mtk_kode.' '.$data->bn->mtk->mtk_nama;$dis=true;}
                    if(!isset($JD['ID'][$data->jdwl_id])){

                        if(!$dis){
                            $totSks+=$data->bn->mtk->mtk_sks;
                            if($totSks > $maxSks['nil']){
                                $ketSks=" SKS MELEBIHI BATAS!";
                            }else{
                                $act = $data->bn->mtk->mtk_kode.' '.$data->bn->mtk->mtk_nama." (". substr($jd,0,-2).")";
                                $modKrs             = new TKrsDet;
                                $kode               = $mhs->mhs_nim.'-'.$data->bn->kln->kr_kode.'-'.$data->jdwl_id;
                                $modKrs->kode       = $kode;
                                $modKrs->id_head    = $modHkrs->id;
                                $modKrs->jdwl_id    = $data->jdwl_id;
                                $modKrs->mtk_kode   = $data->bn->mtk->mtk_kode;
                                $modKrs->mtk_sks    = $data->bn->mtk->mtk_sks;
                                $modKrs->mtk_nama   = $data->bn->mtk->mtk_nama;
                                $modKrs->mhs_nim    = $mhs->mhs_nim;
                                $modKrs->kr_kode    = $data->bn->kln->kr_kode;
                                $modKrs->tgl_jdwl   = new Expression("getdate()");
                                $modKrs->cuid       = Yii::$app->user->identity->id;
                                $modKrs->krs_ulang  =$_POST['s'][$data->jdwl_id]=='B'?"0":"1";

                                $save="";
                                $update="update tbl_jadwal set mhs = isnull(mhs,0)+1 WHERE jdwl_id=$modKrs->jdwl_id";

                                if($qKouta['aktif']==1 && $ig==0 ){
                                    $kuota = $data->rg->kapasitas?:$qKouta['nil'];
                                    if($qKouta['def']==1){$kuota= $qKouta['nil'];}
                                    $que="
                                        insert into t_krs_det(kode,id_head,jdwl_id,mtk_kode,mtk_sks,mtk_nama,mhs_nim,kr_kode,tgl_jdwl,cuid,krs_ulang)
                                        SELECT '$kode', '".$modKrs->id_head."', '".$modKrs->jdwl_id."', '".$modKrs->mtk_kode."', '".$modKrs->mtk_sks."', '".$modKrs->mtk_nama."',
                                        '".$modKrs->mhs_nim."', '".$modKrs->kr_kode."',getdate(), '".$modKrs->cuid."'
                                        ,'".$modKrs->krs_ulang."'
                                        from tbl_jadwal jd inner join(
                                            select isnull(GKode,jdwl_id) GKode,sum(isnull(mhs,0)) mhs from tbl_jadwal where isnull(RStat,0)=0
                                            and isnull(GKode,jdwl_id)='".($data->GKode?:$data->jdwl_id)."'
                                            GROUP BY isnull(GKode,jdwl_id) 
                                        ) t on (t.GKode=isnull(jd.GKode,jd.jdwl_id) and isnull(jd.RStat,0)=0)
                                        INNER JOIN (SELECT '".($data->GKode?:$data->jdwl_id)."' kode,  nil,aktif,def from aturan where id=66) v on(v.kode=isnull(jd.GKode,jd.jdwl_Id))
                                        LEFT JOIN tbl_ruang rg on(rg.rg_kode=jd.rg_kode and isnull(rg.RStat,0)=0)
                                        where jd.jdwl_id=$data->jdwl_id AND (isnull(iif(v.def=1,v.nil,rg.kapasitas),v.nil)-isnull(t.mhs,0)) > 0
                                        ";

                                    $save=Yii::$app->db->createCommand($que)->execute();
                                    if(!$save){$ketQ.=",".$data->bn->mtk->mtk_kode.' '.$data->bn->mtk->mtk_nama." (". substr($jd,0,-2).") ";}
                                }else{$save=$modKrs->save();}

                                if($save){
                                    #die();
                                    Yii::$app->db->createCommand($update)->execute();
                                    $modKrs=TKrsDet::findOne(['kode'=>$kode,"isnull(RStat,0)"=>0]);
                                    Functdb::insLog($modKrs::$ID,$modKrs->id,'C',"Menambah Data KRS $act",'-');
                                    $JD['JD']=$vJdwl;
                                    $JD['MK'][$data->bn->mtk_kode]=1;$JD['ID'][$data->jdwl_id]=1;
                                }
                            }
                        }
                    }
                }
                if($ketMk!=''||$ketJm!=''||$ketQ!=''||$ketSks!=''){

                    if($ketMk !=''){$ketMk="Kode Matakuliah ".substr($ketMk,1)." Sudah Diambil. ";}
                    if($ketJm !=''){$ketJm="Jadwal Kode Matakuliah ".substr($ketJm,1)." Bersisipan Dengan Jadwal Lain. ";}
                    if($ketQ !=''){$ketQ="Kuota Jadwal Kode Matakuliah ".substr($ketQ,1)." Sudah Penuh. ";}
                    Yii::$app->getSession()->setFlash('error',"$ketMk $ketJm $ketQ $ketSks");
                    return $this->redirect(['/krs/mhs-jadwal']);
                }else{
                    echo "Sukses";
                    return Yii::$app->getResponse()->redirect(['/krs/mhs']);
                }
                #Funct::v($JD);
            }else{throw new NotFoundHttpException('The requested page does not exist.');}
        }#else{
        return Yii::$app->getResponse()->redirect(['/krs/mhs-jadwal']);
        #}

    }

    /* DATA di tbl_krs */
    public function actionMhsJadwal_v_tblKrs(){
        $user   = Yii::$app->user->identity->username;
        $mMhs   = Mahasiswa::findOne(['mhs_nim'=>$user]);
        if(!$mMhs){throw new NotFoundHttpException('The requested page does not exist.');}

        #cari kalender akademik aktif
        $mKr    = Kalender::find()->where("
            isnull(RStat,0)=0
            and CAST(GETDATE() as date) BETWEEN kln_krs and krs_akhir
            and jr_id='$mMhs->jr_id' and pr_kode='$mMhs->pr_kode' 
        ")->one();

        $listJadwal ="
        SELECT 
            mk.ig,mk.mtk_kode,mk.mtk_nama,mk.mtk_sks,mk.mtk_semester	
            ,ds.ds_id,ds.ds_nm
            ,isnull(jd.jdwl_parent,jd.jdwl_id) jdwl_id
            ,dbo.subJdwl(ISNULL(jd.jdwl_parent,jd.jdwl_id)) subJadwal
            ,jd.jdwl_hari,jd.jdwl_masuk,jd.jdwl_keluar,jd._totmhs,jd.jdwl_kls
            ,rg.rg_kode,rg.rg_nama
        FROM tbl_kalender kl 
        INNER JOIN tbl_bobot_nilai bn on(bn.kln_id=kl.kln_id and isnull(bn.RStat,0)=0)
        INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and isnull(mk.RStat,0)=0)
        INNER JOIN tbl_dosen ds on(ds.ds_id=bn.ds_nidn and isnull(ds.RStat,0)=0)
        INNER JOIN tbl_jadwal jd on(jd.bn_id=bn.id and isnull(jd.RStat,0)=0)
        INNER JOIN tbl_ruang rg on(rg.rg_kode=jd.rg_kode and isnull(rg.RStat,0)=0)
        WHERE kl.kln_id=$mKr->kln_id 
        AND  jdwl_id=isnull(jd.jdwl_parent,jd.jdwl_id)
        ORDER BY cast(mk.mtk_semester as tinyint) ASC, mk.mtk_kode ASC,jd.jdwl_hari, jd.jdwl_masuk
        ";

        #$listJadwal=Yii::$app->db->createCommand($listJadwal)->queryAll();

        $dataProvider = new SqlDataProvider([
            'sql'=>$listJadwal,
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $listJadwal=Yii::$app->db->createCommand($listJadwal)->queryAll();


        $listKrs="
            SELECT
                jd.jdwl_id,
                mk.ig,mk.mtk_kode,mk.mtk_sks,
                jd.jdwl_hari,jd.jdwl_masuk,jd.jdwl_keluar
            from tbl_krs krs
            INNER JOIN tbl_jadwal jd on( isnull(jd.jdwl_parent,jd.jdwl_id)=krs.jdwl_id)
            INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
            INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and isnull(mk.RStat,0)=0)
            WHERE krs.kr_kode_='$mKr->kr_kode' AND krs.mhs_nim='$mMhs->mhs_nim' AND isnull(krs.RStat,0)=0
        ";
        $listKrs = Yii::$app->db->createCommand($listKrs)->queryAll();
        $JD=[];
        foreach($listKrs as $data){
            $JD['JD'][$data['jdwl_hari']][]=[
                'm'=>$data['jdwl_masuk'],
                'k'=>$data['jdwl_keluar'],
            ];
            $JD['MK'][$data['mtk_kode']]=1;
            $JD['ID'][$data['jdwl_id']]=1;
        }

        #echo $sql = " exec menuKrsMhs '$mMhs->mhs_nim','$mKr->kr_kode'";
        return $this->render('mhs_jdwl',[
            'data'=>$dataProvider,
            'mKr'=>$mKr,
            'listJadwal'=>$listJadwal,
            'listKrs'=>$listKrs,
            'JD'=>$JD,
            'model'=>''
        ]);
    }

    /* KHS */
    public function actionMhsKhs(){
        $user   = Yii::$app->user->identity;
        $model  = Mahasiswa::findOne(['mhs_nim'=>$user->username]);
        $modKhs =KrsHead::find()->where(['nim'=>$model->mhs_nim,'app'=>1])
            ->orderBy(["LEFT(kr_kode,1)"=>SORT_ASC,"RIGHT(kr_kode,2)"=>SORT_ASC])
            ->all();

        return $this->render('mhs/khs',[
            'model'=>$model,
            'modKhs'=>$modKhs
        ]);


    }


    #KHUSUS DOSEN
    public function actionDosen(){
        $usr    = Yii::$app->user->identity->username;
        $dosen  = Dosen::findOne(['ds_user'=>$usr]);
        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->perwalianaktif(Yii::$app->request->queryParams,$dosen->ds_id);
        #echo $dosen->ds_id;

        return $this->render('dosen/perwalian', [
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
        ]);

    }

    public function actionDsMhs($id){
        $model=TKrsHead::findOne(['id'=>$id]);
        $listKrs="
            SELECT
                iif(mkd.kode is null,0,1)mkkr,
                jd.jdwl_id,ds.ds_nm,
                krs.id,krs.krs_ulang,
                mk.ig,mk.mtk_kode,mk.mtk_nama,mk.mtk_sks,
                jd.jdwl_hari,jd.jdwl_masuk,jd.jdwl_keluar,jd.jdwl_kls,
                dbo.subJdwl(jd.jdwl_id) subjadwal,
                krs.ket,
                krs.krs_stat
            from t_krs_det krs
            INNER JOIN tbl_jadwal jd on(jd.jdwl_id=krs.jdwl_id)
            INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
            INNER JOIN tbl_dosen ds on(ds.ds_id=bn.ds_nidn and isnull(ds.RStat,0)=0)
            INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and isnull(mk.RStat,0)=0)
            INNER JOIN tbl_mahasiswa mhs on(mhs.mhs_nim=krs.mhs_nim)
            LEFT JOIN matkul_kr_det mkd on(mkd.id_kr=mhs.mk_kr AND mkd.kode=mk.mtk_kode)
            WHERE id_head='$model->id' AND isnull(krs.RStat,0)=0
            order by isnull(krs.krs_stat,0) ASC,
            mk.mtk_semester,mk.mtk_kode
        ";
        #Funct::v($listKrs);
        $listKrs = Yii::$app->db->createCommand($listKrs)->queryAll();
        $idLog =[];
        foreach($model->krsdet as $d){$idLog[]=$d['id'];}
        $LOG="";
        #echo TKrsHead::$ID;
        #Funct::v($idLog);

        if(count($idLog)>0){$LOG=Functdb::vLog(TKrsDet::$ID,$idLog);}

        $vkr=substr($model->kr_kode,0,1);
        $vid=[1=>85,2=>86,3=>87];
        $maxSks = "select nil from aturan WHERE  id=$vid[$vkr]";
        $maxSks=Yii::$app->db->createCommand($maxSks)->queryOne();

        #$detNil = Yii::$app->db2->createCommand("Exec dbo.detailnilai '$model->nim'")->queryAll();
        #$listNilai="exec MenuJadwalKrs '$mMhs->mhs_nim','$mKr->kr_kode'";

        $dataProvider = new SqlDataProvider([
            'sql'=>" Exec dbo.detailnilai '$model->nim'",
            'db'=>'db2',
            'pagination' => ['pageSize' => 0,],
        ]);

        #Funct::v($dataProvider);

        return $this->render('dosen/krs',[
            'model'=>$model,
            'LOG'=>$LOG,
            'maxSks'=>$maxSks,
            'listKrs'=>$listKrs,
            'dataProvider'=>$dataProvider
        ]);

        #Funct::v($model);

    }

    public function actionDsApp(){
        $statUbah=0;
        $idH=$_POST['head'];
        $hKrs=TKrsHead::findOne(['id'=>$idH]);
        $vJdwl=[];
        if($_POST['id']){
            $id=$_POST['id'];
            $uuid=Yii::$app->user->identity->id;
            $vkr=substr($hKrs->kr_kode,0,1);
            $vid=[1=>85,2=>86,3=>87];
            $maxSks = "select nil from aturan WHERE  id=$vid[$vkr]";
            $maxSks=Yii::$app->db->createCommand($maxSks)->queryOne();
            $ketMk='';$ketJm='';$ketQ='';$ketSks='';$JD=[];
            $ambilSks=Yii::$app->db->createCommand("
              select sum(tkd.mtk_sks) sks from t_krs_head tkh 
              INNER JOIN t_krs_det tkd on(tkd.id_head=tkh.id and isnull(tkd.RStat,0)=0 and isnull(tkd.krs_stat,0)=1) 
            ")->queryOne();
            $totSks = $ambilSks['sks'];

            #var_dump($_POST[app]);
            #count($_POST[app]);
            #Funct::v($_POST[app]);
            #die();
            $ketSks="";


            $listKrs="
                    SELECT
                        jd.jdwl_id,
                        isnull(mk.ig,0) ig,mk.mtk_kode,mk.mtk_sks,
                        jd.jdwl_hari,jd.jdwl_masuk,jd.jdwl_keluar,
                        isnull(krs.krs_stat,0) krs_stat
                    from t_krs_det krs
                    LEFT JOIN tbl_jadwal jd on( isnull(jd.jdwl_parent,jd.jdwl_id)=krs.jdwl_id)
                    INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
                    INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and isnull(mk.RStat,0)=0)
                    WHERE krs.kr_kode='".$hKrs->kr_kode."' AND krs.mhs_nim='$hKrs->nim' AND isnull(krs.RStat,0)=0
                    and isnull(krs.krs_stat,0)<>2
                    
                ";

            $listKrs = Yii::$app->db->createCommand($listKrs)->queryAll();

            $totSks=0;
            foreach($listKrs as $data){
                if($data['krs_stat']!=2){
                    #$totSks+=$data['mtk_sks'];
                    if($data['ig']==0){
                        $JD['JD'][$data['jdwl_hari']][]=[
                            'm'=>$data['jdwl_masuk'],
                            'k'=>$data['jdwl_keluar'],
                        ];
                    }
                }
                #$totSks+=$data['mtk_sks'];
                $JD['MK'][$data['mtk_kode']]=1;
                $JD['ID'][$data['jdwl_id']]=1;
            }

            #Funct::v($JD);

            foreach ($id as $k=>$v){
                $model= TKrsDet::findOne(['id'=>$v]);
                $ig=$model->jdwl->bn->mtk->ig?:0;
                $dis = false;
                $subJdwl="select dbo.subJdwl($model->jdwl_id) sub";
                $subJdwl=Yii::$app->db->createCommand($subJdwl)->queryOne();
                $jdwl=explode("|",$subJdwl['sub']);$jd="";

                if($_POST['app'][$model->jdwl_id]=='on'){$_POST['app'][$model->jdwl_id]=1;}
                if(!isset($_POST['app'][$model->jdwl_id])){$_POST['app'][$model->jdwl_id]=2;}
                #echo "<br>$model->jdwl_id $model->krs_stat : ".$_POST['app'][$model->jdwl_id];
                if($statUbah!=$model->krs_stat){$statUbah++;}
                foreach($jdwl as $k=>$v){$Info=explode('#',$v);$ket=Funct::HARI()[$Info[1]].", $Info[2]-$Info[3]";$jd .=$ket." & ";}

                #echo $totSks." 2<br>";
                $act = $model->jdwl->bn->mtk->mtk_kode.' '.$model->jdwl->bn->mtk->mtk_nama." (". substr($jd,0,-2).")";
                if($model->head->app==0){
                    #echo $totSks."<br>";
                    if($model->krs_stat!=$_POST['app'][$model->jdwl_id]){
                        if($_POST['app'][$model->jdwl_id]==2){$totSks -=$model->mtk_sks;}
                        else if($_POST['app'][$model->jdwl_id]==1){
                            $totSks+=$model->mtk_sks;
                            if($totSks > $maxSks[nil]){$ketSks.=",".$model->jdwl->bn->mtk->mtk_kode.' '.$model->jdwl->bn->mtk->mtk_nama;}
                        }
                    }
                    #echo $totSks." 2<br>";

                    $model->uuid= $uuid;
                    $model->utgl=new Expression('getdate()');

                    if(!$dis){
                        if($totSks <= $maxSks[nil]){
                            $krsStat=$model->krs_stat;
                            #ada perubahan
                            if($model->krs_stat!=$_POST['app'][$model->jdwl_id]){
                                if($ig==0 && $model->krs_stat==2){
                                    foreach($jdwl as $k=>$v){
                                        $Info=explode('#',$v);
                                        $ket=Funct::HARI()[$Info[1]].", $Info[2]-$Info[3]";$jd .=$ket." & ";
                                        $vJdwl[$Info[1]][]=['m'=>$Info[2],'k'=>$Info[3]];
                                        if(isset($JD['JD'][$Info[1]])){
                                            foreach($JD['JD'][$Info[1]] as $d){
                                                $M = date('H:i',strtotime($d['m']));$K=date('H:i',strtotime($d['k']));
                                                $m = date('H:i',strtotime($Info[2]));
                                                $k = date('H:i',strtotime($Info[3]));
                                                #Perbandingan KRS Dengan Jadwal
                                                if($m >= $M && $m <$K){$dis=true;}if($k > $M && $k <$K){$dis=true;}
                                                #Perbandingan KRS Dengan Jadwal
                                                if($M >= $m && $M <$k){$dis=true;}if($K > $m && $K <$k){$dis=true;}
                                            }
                                        }
                                    }

                                }
                                if($dis){$ketJm.=",".$model->jdwl->bn->mtk->mtk_kode.' '.$model->jdwl->bn->mtk->mtk_nama." (". substr($jd,0,-2).") ";}

                                if(!$dis){
                                    $model->krs_stat = $_POST['app'][$model->jdwl_id];
                                    if( $model->save(false)){
                                        $ket="-";
                                        if($model->krs_stat==2){Yii::$app->db->createCommand("update tbl_jadwal set mhs-=1 WHERE  jdwl_id=$model->jdwl_id")->execute();}
                                        if($krsStat==2){
                                            $JD['JD']=$vJdwl;
                                            Yii::$app->db->createCommand("update tbl_jadwal set mhs+=1 WHERE  jdwl_id=$model->jdwl_id")->execute();
                                        }
                                        if($_POST['app'][$model->jdwl_id]==1){
                                            $ket="Menyetujui";
                                            $JD['JD']=$vJdwl;
                                            #$JD['MK'][$model->jdwl->bn->mtk_kode]=1;$JD['ID'][$model->jdwl->jdwl_id]=1;

                                        }
                                        if($_POST['app'][$model->jdwl_id]==2){$ket="Menolak";}
                                        Functdb::insLog($model::$ID,$model->id,'U',"$ket KRS $model->mhs_nim: $act",'-');
                                    }#else{ Funct::v($model->getErrors());}

                                }
                            }
                        }

                    }

                }
            }
            #die();
            #Funct::v($totSks);

            if($ketJm!=''||$ketSks!=''){
                if($ketMk !=''){$ketMk="Kode Matakuliah ".substr($ketMk,1)." Sudah Diambil. ";}
                if($ketJm !=''){$ketJm="Jadwal Kode Matakuliah ".substr($ketJm,1)." Bersisipan Dengan Jadwal Lain. ";}
                #if($ketSks !=''){$ketSks="SKS Melebihi Batas ( ".substr($ketSks,1)." ).";}
                #if($ketQ !=''){$ketQ="Kuota Jadwal Kode Matakuliah ".substr($ketQ,1)." Sudah Penuh. ";}
                Yii::$app->getSession()->setFlash('error',$ketSks.' '.$ketJm);
            }else{
                if(isset($_POST['sk'])){
                    $hKrs->app='1';
                    $hKrs->app_date=new Expression("getdate()");
                    $hKrs->uuid=$uuid;
                    $hKrs->utgl=new Expression("getdate()");
                    if($hKrs->save(false)){
                        Functdb::insLog($hKrs::$ID,$hKrs->id,'U',"Mengunci KRS $model->mhs_nim - $hKrs->kr_kode",'-');
                    }#else{ Funct::v($hKrs->getErrors());}
                }
            }

        }
        return $this->redirect(['krs/ds-mhs','id'=>$idH]);
        #Funct::v($model);
    }

    public function actionDsUnapp($id){
        $uuid=Yii::$app->user->identity->id;
        $model=TKrsHead::findOne(['id'=>$id]);
        #Funct::v($model);
        $model->app='0';
        $model->uuid=$uuid;
        $model->utgl=new Expression("getdate()");
        $model->save(false);
        Functdb::insLog($model::$ID,$model->id,'U',"Membuka KRS $model->nim - $model->kr_kode",'-');
        return $this->redirect(['krs/ds-mhs','id'=>$model->id]);
    }

    public function actionDsJdwl($id){

        $mMhs   = Mahasiswa::findOne(['mhs_nim'=>$id]);
        if(!$mMhs){throw new NotFoundHttpException('The requested page does not exist.');}

        #cari kalender akademik aktif
        $mKr = Kalender::find()->where("
            isnull(RStat,0)=0
            and CAST(GETDATE() as date) BETWEEN kln_krs and krs_akhir
            and jr_id='$mMhs->jr_id' and pr_kode='$mMhs->pr_kode' 
        ")->one();

        $vkr=0;
        if($mKr->kr_kode!=null){$vkr=substr($mKr->kr_kode,0,1);}
        $vid=[0=>0,1=>85,2=>86,3=>87];
        $maxSks = "select nil from aturan WHERE  id=$vid[$vkr]";
        #Funct::v($maxSks);
        $maxSks=Yii::$app->db->createCommand($maxSks)->queryOne();

        #kouta mahasiswa
        //$kuota= "select nil,aktif from aturan WHERE  id=66";
        //$kuota = Yii::$app->db->createCommand($kuota)->queryOne();
        //$kuota = $kuota['nil'] && $kuota['aktif']=='1'?$kuota['nil']:0;

        $listJadwal ="exec MenuJadwalKrs '$mMhs->mhs_nim','$mKr->kr_kode'";

        $dataProvider = new SqlDataProvider([
            'sql'=>$listJadwal,
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        $listJadwal=Yii::$app->db->createCommand($listJadwal)->queryAll();


        $listKrs="
            SELECT
                jd.jdwl_id,
                mk.ig,mk.mtk_kode,mk.mtk_sks,
                jd.jdwl_hari,jd.jdwl_masuk,jd.jdwl_keluar,krs.krs_stat
            from t_krs_det krs
            LEFT JOIN tbl_jadwal jd on( isnull(jd.jdwl_parent,jd.jdwl_id)=krs.jdwl_id)
            INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
            INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and isnull(mk.RStat,0)=0)
            WHERE krs.kr_kode='$mKr->kr_kode' AND krs.mhs_nim='$mMhs->mhs_nim' AND isnull(krs.RStat,0)=0
        ";
        #Funct::v($listKrs);
        $listKrs = Yii::$app->db->createCommand($listKrs)->queryAll();
        #Funct::v($listKrs);
        $JD=[];
        foreach($listKrs as $data){

            if($data['ig']==0){
                $JD['JD'][$data['jdwl_hari']][]=[
                    'm'=>$data['jdwl_masuk'],
                    'k'=>$data['jdwl_keluar'],
                ];
            }
            $JD['MK'][$data['mtk_kode']]=1;
            $JD['ID'][$data['jdwl_id']]=$data['krs_stat'];
        }
        //$JD['K']=$kuota;
        #echo $sql = " exec menuKrsMhs '$mMhs->mhs_nim','$mKr->kr_kode'";
        return $this->render('dosen/mhs_jdwl',[
            'data'=>$dataProvider,
            'mKr'=>$mKr,
            'maxSks'=>$maxSks,
            'listJadwal'=>$listJadwal,
            'listKrs'=>$listKrs,
            'JD'=>$JD,
            'model'=>$mMhs
        ]);

    }
    #--KHUSUS DOSEN--

    #--KHUSUS ADMIN
    public function actionAdmin(){
        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->perwalianPaket(Yii::$app->request->queryParams);
        #echo $dosen->ds_id;

        return $this->render('admin/perwalian', [
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
        ]);

    }

    public function actionAdmin2(){
        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->perwalianPaket(Yii::$app->request->queryParams,"tkh.uuid is not null and tkh.uuid=134603");
        #echo $dosen->ds_id;

        return $this->render('admin/perwalian', [
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
        ]);

    }


    public function actionAdminJadwal($id){
        #$user   = Yii::$app->user->identity->username;
        $mMhs   = Mahasiswa::findOne(['mhs_nim'=>$id]);
        if(!$mMhs){throw new NotFoundHttpException('The requested page does not exist.');}

        #cari kalender akademik aktif
        $mKr    = Kalender::find()->where("
            isnull(RStat,0)=0
            and CAST(GETDATE() as date) BETWEEN CAST(GETDATE()as date) and krs_akhir
            and jr_id='$mMhs->jr_id' and pr_kode='$mMhs->pr_kode' 
        ")->one();
        $vkr=substr($mKr->kr_kode,0,1);
        $vid=[1=>85,2=>86,3=>87];
        $maxSks = "select nil from aturan WHERE  id=$vid[$vkr]";
        $maxSks=Yii::$app->db->createCommand($maxSks)->queryOne();

        #kouta mahasiswa
        //$kuota= "select nil,aktif from aturan WHERE  id=66";
        //$kuota = Yii::$app->db->createCommand($kuota)->queryOne();
        //$kuota = $kuota['nil'] && $kuota['aktif']=='1'?$kuota['nil']:0;

        $listJadwal =  Yii::$app->db->createCommand("exec MenuJadwalKrs '$mMhs->mhs_nim','$mKr->kr_kode'")->queryAll();
        //Funct::v($listJadwal);
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$listJadwal,
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);
        //$listJadwal=Yii::$app->db->createCommand($listJadwal)->queryAll();


        $listKrs="
            SELECT
                jd.jdwl_id,
                mk.ig,mk.mtk_kode,mk.mtk_sks,
                jd.jdwl_hari,jd.jdwl_masuk,jd.jdwl_keluar,krs.krs_stat
            from t_krs_det krs
            LEFT JOIN tbl_jadwal jd on( isnull(jd.jdwl_parent,jd.jdwl_id)=krs.jdwl_id)
            INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
            INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and isnull(mk.RStat,0)=0)
            WHERE krs.kr_kode='$mKr->kr_kode' AND krs.mhs_nim='$mMhs->mhs_nim' AND isnull(krs.RStat,0)=0
        ";
        #Funct::v($listKrs);
        $listKrs = Yii::$app->db->createCommand($listKrs)->queryAll();
        #Funct::v($listKrs);
        $JD=[];
        foreach($listKrs as $data){

            if($data['ig']==0){
                if($data['krs_stat']!=2){
                    $JD['JD'][$data['jdwl_hari']][]=[
                        'm'=>$data['jdwl_masuk'],
                        'k'=>$data['jdwl_keluar'],
                    ];
                }
            }
            $JD['MK'][$data['mtk_kode']]=1;
            $JD['ID'][$data['jdwl_id']]=$data['krs_stat'];
        }
        //$JD['K']=$kuota;
        #echo $sql = " exec menuKrsMhs '$mMhs->mhs_nim','$mKr->kr_kode'";
        return $this->render('admin/mhs_jdwl',[
            'data'=>$dataProvider,
            'mKr'=>$mKr,
            'maxSks'=>$maxSks,
            'listJadwal'=>$listJadwal,
            'listKrs'=>$listKrs,
            'JD'=>$JD,
            'model'=>$mMhs
        ]);
    }

    public function actionAdminProc($id){
        if(isset($_POST['jdwl'])){
            $jd = $_POST['jdwl'];
            $ketMk='';$ketJm='';$ketQ='';$ketSks='';$JD=[];
            #cek total jadwal yang dipilih

            if(count($jd)>0){
                #validasi kapasitas kls
                $qKouta="select nil, aktif,isnull(def,0) def from aturan where id=66";
                $qKouta=Yii::$app->db->createCommand($qKouta)->queryOne();

                $user=Yii::$app->user->identity;
                $modJd=Jadwal::find()->where(['jdwl_id'=>$jd[0], "isnull(RStat,0)"=>0 ])->one();
                if(!$modJd){throw new NotFoundHttpException('The requested page does not exist.');}
                $mhs=Mahasiswa::findOne($id);
                if(!$mhs){throw new NotFoundHttpException('The requested page does not exist.');}

                $listKrs="
                    SELECT
                        jd.jdwl_id,
                        isnull(mk.ig,0) ig ,mk.mtk_kode,mk.mtk_sks,
                        jd.jdwl_hari,jd.jdwl_masuk,jd.jdwl_keluar,
                        isnull(krs.krs_stat,0) krs_stat
                    from t_krs_det krs
                    LEFT JOIN tbl_jadwal jd on( isnull(jd.jdwl_parent,jd.jdwl_id)=krs.jdwl_id)
                    INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
                    INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and isnull(mk.RStat,0)=0)
                    WHERE krs.kr_kode='".$modJd->bn->kln->kr_kode."' AND krs.mhs_nim='$mhs->mhs_nim' AND isnull(krs.RStat,0)=0
                    
                ";

                $listKrs = Yii::$app->db->createCommand($listKrs)->queryAll();

                $totSks=0;
                foreach($listKrs as $data){
                    if($data['krs_stat']!=2){
                        $totSks+=$data['mtk_sks'];
                        if($data['ig']=='0'){
                            $JD['JD'][$data['jdwl_hari']][]=[
                                'm'=>$data['jdwl_masuk'],
                                'k'=>$data['jdwl_keluar'],
                            ];

                        }

                    }
                    #$totSks+=$data['mtk_sks'];
                    $JD['MK'][$data['mtk_kode']]=1;
                    $JD['ID'][$data['jdwl_id']]=1;
                }
                #Funct::v($totSks);

                #INSERT Transaksi Head KRS * 041044- dibuat dinamis
                $kode = '041044-'.$modJd->bn->kln->kr_kode.'-'.$mhs->mhs_nim;
                $modHkrs = TKrsHead::findOne($kode);
                if(!$modHkrs){
                    $modHkrs = new  TKrsHead;
                    $modHkrs->kode      = $kode;
                    $modHkrs->nim       = $mhs->mhs_nim;
                    $modHkrs->ds_id     = $mhs->ds_wali;
                    $modHkrs->kr_kode   = $modJd->bn->kln->kr_kode;
                    $modHkrs->cuid      = $user->id;

                    $modHkrs->app='1';
                    $modHkrs->app_date=new Expression("getdate()");
                    $modHkrs->uuid=$user->id;
                    $modHkrs->utgl=new Expression("getdate()");


                    #/*
                    if($modHkrs->save()){
                        $modHkrs = TKrsHead::findOne($kode);
                        Functdb::insLog($modHkrs::$ID,$modHkrs->id,'C',"Menambah Data Paket KRS $kode - $mhs->mhs_nim",'PAKET KRS');
                    }else{Funct::v($modHkrs->getErrors());}
                    #*/
                }

                $vkr=substr($modHkrs->kr_kode,0,1);
                $vid=[1=>85,2=>86,3=>87];
                $maxSks = "select nil from aturan WHERE id=$vid[$vkr]";
                $maxSks=Yii::$app->db->createCommand($maxSks)->queryOne();

                $modJdAll = Jadwal::find()->where(['jdwl_id'=>$jd])->all();

                foreach ($modJdAll as $data){
                    $ig=$data->bn->mtk->ig?:0;

                    $dis = false;
                    $subJdwl="select dbo.subJdwl($data->jdwl_id) sub";
                    $subJdwl=Yii::$app->db->createCommand($subJdwl)->queryOne();
                    $jdwl=explode("|",$subJdwl['sub']);$jd="";
                    $vJdwl=[];
                    if($ig==0){
                        foreach($jdwl as $k=>$v){
                            $Info=explode('#',$v);
                            $ket=Funct::HARI()[$Info[1]].", $Info[2]-$Info[3]";$jd .=$ket." & ";
                            $vJdwl[$Info[1]][]=['m'=>$Info[2],'k'=>$Info[3]];
                            if(isset($JD['JD'][$Info[1]])){
                                foreach($JD['JD'][$Info[1]] as $d){
                                    $M = date('H:i',strtotime($d['m']));$K=date('H:i',strtotime($d['k']));
                                    $m = date('H:i',strtotime($Info[2]));
                                    $k = date('H:i',strtotime($Info[3]));
                                    #Perbandingan KRS Dengan Jadwal
                                    if($m >= $M && $m <$K){$dis=true;}if($k > $M && $k <$K){$dis=true;}
                                    #Perbandingan KRS Dengan Jadwal
                                    if($M >= $m && $M <$k){$dis=true;}if($K > $m && $K <$k){$dis=true;}
                                }
                            }
                        }

                    }

                    if($dis){$ketJm.=",".$data->bn->mtk->mtk_kode.' '.$data->bn->mtk->mtk_nama." (". substr($jd,0,-2).") ";}

                    if(isset($JD['MK'][$data->bn->mtk_kode])){$ketMk.=",".$data->bn->mtk->mtk_kode.' '.$data->bn->mtk->mtk_nama;$dis=true;}
                    if(!isset($JD['ID'][$data->jdwl_id])){

                        if(!$dis){
                            $totSks+=$data->bn->mtk->mtk_sks;
                            if($totSks > $maxSks['nil']){
                                $ketSks=" SKS MELEBIHI BATAS!";
                            }else{
                                $act = $data->bn->mtk->mtk_kode.' '.$data->bn->mtk->mtk_nama." (". substr($jd,0,-2).")";
                                $modKrs             = new TKrsDet;
                                $kode               = $mhs->mhs_nim.'-'.$data->bn->kln->kr_kode.'-'.$data->jdwl_id;
                                $modKrs->kode       = $kode;
                                $modKrs->krs_stat   = '1';
                                $modKrs->id_head    = $modHkrs->id;
                                $modKrs->jdwl_id    = $data->jdwl_id;
                                $modKrs->mtk_kode   = $data->bn->mtk->mtk_kode;
                                $modKrs->mtk_sks    = $data->bn->mtk->mtk_sks;
                                $modKrs->mtk_nama   = $data->bn->mtk->mtk_nama;
                                $modKrs->mhs_nim    = $mhs->mhs_nim;
                                $modKrs->kr_kode    = $data->bn->kln->kr_kode;
                                $modKrs->tgl_jdwl   = new Expression("getdate()");
                                $modKrs->cuid       = Yii::$app->user->identity->id;
                                $modKrs->krs_ulang  =$_POST['s'][$data->jdwl_id]=='B'?"0":"1";

                                $save="";
                                $update="update tbl_jadwal set mhs = isnull(mhs,0)+1 WHERE jdwl_id=$modKrs->jdwl_id";
                                if($qKouta['aktif']==1 && $ig==0 ){
                                    $kuota = $data->rg->kapasitas?:$qKouta['nil'];
                                    if($qKouta['def']==1){$kuota= $qKouta['nil'];}
                                    $que="
                                        insert into t_krs_det(kode,id_head,jdwl_id,mtk_kode,mtk_sks,mtk_nama,mhs_nim,kr_kode,tgl_jdwl,cuid,krs_ulang,krs_stat)
                                        SELECT '$kode', '".$modKrs->id_head."', '".$modKrs->jdwl_id."', '".$modKrs->mtk_kode."', '".$modKrs->mtk_sks."', '".$modKrs->mtk_nama."',
                                        '".$modKrs->mhs_nim."', '".$modKrs->kr_kode."',getdate(), '".$modKrs->cuid."'
                                        ,'".$modKrs->krs_ulang."',1
                                        from tbl_jadwal jd inner join(
                                            select isnull(GKode,jdwl_id) GKode,sum(isnull(mhs,0)) mhs from tbl_jadwal where isnull(RStat,0)=0
                                            and isnull(GKode,jdwl_id)='".($data->GKode?:$data->jdwl_id)."'
                                            GROUP BY isnull(GKode,jdwl_id) 
                                        ) t on (t.GKode=isnull(jd.GKode,jd.jdwl_id) and isnull(jd.RStat,0)=0)
                                        INNER JOIN (SELECT '".($data->GKode?:$data->jdwl_id)."' kode,  nil,aktif,def from aturan where id=66) v on(v.kode=isnull(jd.GKode,jd.jdwl_Id))
                                        LEFT JOIN tbl_ruang rg on(rg.rg_kode=jd.rg_kode and isnull(rg.RStat,0)=0)
                                        where jd.jdwl_id=$data->jdwl_id AND (isnull(iif(v.def=1,v.nil,rg.kapasitas),v.nil)-isnull(t.mhs,0)) > 0
                                        ";
                                    $save=Yii::$app->db->createCommand($que)->execute();
                                    if(!$save){$ketQ.=",".$data->bn->mtk->mtk_kode.' '.$data->bn->mtk->mtk_nama." (". substr($jd,0,-2).") ";}
                                }
                                else{$save=$modKrs->save();}

                                if($save){
                                    #die();
                                    Yii::$app->db->createCommand($update)->execute();
                                    $modKrs=TKrsDet::find()->where(['kode'=>$kode,"isnull(RStat,0)"=>0])->one();
                                    Functdb::insLog($modKrs::$ID,$modKrs->id,'C',"Menambah Data KRS $act",'-');
                                    $JD['JD']=$vJdwl;
                                    $JD['MK'][$data->bn->mtk_kode]=1;$JD['ID'][$data->jdwl_id]=1;

                                }

                            }
                        }
                    }
                }
                if($ketMk!=''||$ketJm!=''||$ketQ!=''||$ketSks!=''){

                    if($ketMk !=''){$ketMk="Kode Matakuliah ".substr($ketMk,1)." Sudah Diambil. ";}
                    if($ketJm !=''){$ketJm="Jadwal Kode Matakuliah ".substr($ketJm,1)." Bersisipan Dengan Jadwal Lain. ";}
                    if($ketQ !=''){$ketQ="Kuota Jadwal Kode Matakuliah ".substr($ketQ,1)." Sudah Penuh. ";}
                    Yii::$app->getSession()->setFlash('error',"$ketMk $ketJm $ketQ $ketSks");
                    return $this->redirect(['/krs/admin-jadwal','id'=>$modHkrs->nim]);
                }else{
                    return Yii::$app->getResponse()->redirect(['/krs/admin-mhs','id'=>$modHkrs->id]);
                }
                #Funct::v($JD);
            }else{throw new NotFoundHttpException('The requested page does not exist.');}
        }#else{
        return Yii::$app->getResponse()->redirect(['/krs/admin-jadwal','id'=>$modHkrs->nim]);
        #}

    }

    public function actionAdminMhs($id){
        $model=TKrsHead::findOne(['id'=>$id]);
        if(!$model){throw new NotFoundHttpException('The requested page does not exist.');}
        #cari kalender akademik aktif
        $mKr    = Kalender::find()->where("
            isnull(RStat,0)=0
            and kr_kode = $model->kr_kode 
            and jr_id='".$model->mhs->jr_id."' and pr_kode='".$model->mhs->pr_kode."' 
        ")->one();
        #Funct::v($mKr);
        $vkr=0;
        if($model->kr_kode!=null){$vkr=substr($mKr->kr_kode,0,1);}
        $vid=[0=>0,1=>85,2=>86,3=>87];
        $maxSks = "select nil from aturan WHERE id=$vid[$vkr]";
        $maxSks=Yii::$app->db->createCommand($maxSks)->queryOne();

        #Cek Registrasi
        $mReg = Regmhs::findOne(['tahun'=>$model->kr_kode,'nim'=>$model->nim]);

        #cek data KRS
        #$mHkrs  = TKrsHead::findOne(['nim'=>$model->nim,'kr_kode'=>$model->kr_kode]);

        $listKrs="
            SELECT
                iif(mkd.kode is null,0,1)mkkr,
                jd.jdwl_id,ds.ds_nm,
                krs.id,krs.krs_ulang,
                mk.ig,mk.mtk_kode,mk.mtk_nama,mk.mtk_sks,
                jd.jdwl_hari,jd.jdwl_masuk,jd.jdwl_keluar,jd.jdwl_kls,  
                dbo.subJdwl(jd.jdwl_id) subjadwal,
                krs.ket,
                krs.krs_stat
            from t_krs_det krs
            INNER JOIN tbl_jadwal jd on(jd.jdwl_id=krs.jdwl_id)
            INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and isnull(bn.RStat,0)=0)
            INNER JOIN tbl_dosen ds on(ds.ds_id=bn.ds_nidn and isnull(ds.RStat,0)=0)
            INNER JOIN tbl_matkul mk on(mk.mtk_kode=bn.mtk_kode and isnull(mk.RStat,0)=0)
            INNER JOIN tbl_mahasiswa mhs on(mhs.mhs_nim=krs.mhs_nim)
            LEFT JOIN matkul_kr_det mkd on(mkd.id_kr=mhs.mk_kr AND mkd.kode=mk.mtk_kode)
            WHERE krs.kr_kode='$model->kr_kode' AND krs.mhs_nim='$model->nim' AND isnull(krs.RStat,0)=0
            order by mk.mtk_semester,mk.mtk_kode
        ";
        $listKrs = Yii::$app->db->createCommand($listKrs)->queryAll();
        $idLog =[];
        foreach($model->krsdet as $d){$idLog[]=$d['id'];}
        $LOG="";
        if(count($idLog)>0){$LOG=Functdb::vLog(TKrsDet::$ID,$idLog);}
        $qTot=" select count(*) from tbl_jadwal jd inner join tbl_bobot_nilai bn on(jd.bn_id=bn.id and isnull(bn.RStat,0)=0 and bn.kln_id = ".( $mKr->kln_id?:0)." ) ";
        $countJdwl = Yii::$app->db->createCommand($qTot)->queryOne();

        return $this->render('admin/ins_mhs',[
            'model'=>$model,
            'listKrs'=>$listKrs,
            'mKr'=>$mKr,
            'maxSks'=>$maxSks,
            'mReg'=>$mReg,
            'LOG'=>$LOG,
            'countJdwl'=>$countJdwl,
        ]);

    }

    public  function actionAdminCetak($id){
        $model = TKrsHead::findOne(['id'=>$id]);
        $db = Yii::$app->db;
        $ID = Yii::$app->user->identity->username;
        #if($ID!=$model->nim){throw new NotFoundHttpException('The requested page does not exist.');}
        $sql = "
                select 
                    krs.krs_stat,krs.krs_ulang,
                    mk.mtk_kode,mk.mtk_nama,mk.mtk_sks,
					dbo.subJdwl(jd.jdwl_id) jadwal,jd.jdwl_kls
                from t_krs_det krs
                INNER JOIN tbl_jadwal jd on (jd.jdwl_id=krs.jdwl_id AND isnull(jd.RStat,0)=0 )
                INNER JOIN tbl_bobot_nilai bn on (bn.id=jd.bn_id AND isnull(bn.RStat,0)=0)
                INNER JOIN tbl_kalender kl on (kl.kln_id=bn.kln_id AND isnull(kl.RStat,0)=0)
                INNER JOIN tbl_matkul mk on (mk.mtk_kode=bn.mtk_kode AND isnull(mk.RStat,0)=0)
                where krs.id_head=$model->id and isnull(krs.krs_stat,0)<>2 and isnull(krs.RStat,0)=0
        ";

        $krs = $db->createCommand($sql)->queryAll();

        $data = [
            'model'=>$model,
            'krs'=>$krs,
        ];

        $this->layout = 'blank';
        #return
        $content = $this->renderPartial('mhs_pdf',$data);


        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'content' => $content,
            //'format'=>[148,210],
            'marginLeft'=>5,
            'marginRight'=>5,
            'marginTop'=>20,
            'marginHeader'=>5,
            'orientation'=>'P',
            'destination'=>'I',
            'cssInline'=>'
					table{overlow:warp;font-size:12px;}
					body{font-size: 12px;}
				',
            'filename'=>'KRS-'.$model->nim.'-'.date('YmdHis').'.pdf',
            'options' => [
                'title' => 'KRS '.$model->nim,
                'subject' => 'KRS '.$model->nim,
                'watermarkText'=>'DIREKTORAT SISTEM INFORMASI & MULTIMEDIA',
                'watermarkTextAlpha'=>0.2,
                'showWatermarkText'=>true,
                //'debug'=>true,
            ],
            'methods' => [
                'SetHeader' => ['
					<table>
						<tr>
							<td><img src="'.Url::to('@web/ypkp.png').'" width="50"></td>
							<td>
							<b>UNIVERSITAS SANGGA BUANA YPKP</b>
							<p><small>Jl. PHH Mustopa No 68, Telp. (022) 7202233. Bandung 40124 
							&nbsp;E-mail : sim@usbypkp.ac.id. Homepage : www.usbypkp.ac.id</small></p>
								'.'
							</td>
						</tr>
					</table>'],
                'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'|Page {PAGENO}'.'|'.date("r")],
            ]
        ]);
        return $pdf->render();
    }

    public function actionAdminDel($id){
        $model=TKrsDet::findOne(['id'=>$id]);

        $subJdwl="select dbo.subJdwl($model->jdwl_id) sub";
        $subJdwl=Yii::$app->db->createCommand($subJdwl)->queryOne();
        $jdwl=explode("|",$subJdwl['sub']);$jd="";
        foreach($jdwl as $k=>$v){$Info=explode('#',$v);$ket=Funct::HARI()[$Info[1]].", $Info[2]-$Info[3]";$jd .=$ket." & ";}
        $act = $model->jdwl->bn->mtk->mtk_kode.' '.$model->jdwl->bn->mtk->mtk_nama." (". substr($jd,0,-2).")";
        $model->RStat   = '1';
        $model->duid    = Yii::$app->user->identity->id;
        $model->dtgl   = new Expression('getdate()');

        $model->save(false);
        Yii::$app->db->createCommand("update tbl_jadwal set mhs-=1 WHERE jdwl_id=$model->jdwl_id")->execute();
        Functdb::insLog($model::$ID,$model->id,'D',"Menghapus Data KRS $act",'-');
        #return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        return $this->redirect(['krs/admin-mhs','id'=>$model->id_head]);

    }

    public function actionExcelPerwalian(){
        $subAkses=self::sub();
        if(Akses::acc('MasterJadwal')){$subAkses="";}
        if(!Akses::acc('/jadwal/index')){throw new ForbiddenHttpException("Forbidden access");}

        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->perwalian(Yii::$app->request->getQueryParams(),($subAkses? ['kl.jr_id'=>$subAkses['jurusan']]:""));
        $dataProvider->pagination=false;
        $dataArray=[];
        $n=0;
        $kr="";
        foreach ($dataProvider->getModels() as $d){
            $n++;
            $dataArray[]=[
                $n,$d['kr_kode'],$d['jr_jenjang'].' '.$d['jr_nama'],$d['pr_nama'],$d['jdwl_kls'],$d['mtk_kode'],$d['mtk_nama'],$d['mtk_sks'],$d['ds_nm'],Funct::HARI()[$d['jdwl_hari']].', '.$d['jadwal'],
                $d['peserta']?:'0',$d['app']?:'0',$d['jum']?:'0'
            ];
        }

        if($_GET['t']==1){
            $shetName = "Perwalian".'-'.date('Ymd');
            if(!empty($_GET['JadwalSearch']['jr_id'])){
                $jr=$_GET['JadwalSearch']['jr_id'];
                $ModJr = \app\models\Jurusan::find()->where('jr_id=:jr',[':jr'=>$jr])->one();
                if($ModJr!==null){
                    $shetName = "Perwalian-".$jr.'-'.date('Ymd');
                }
            }
            $fileName="Laporan-".$shetName;

            $objPHPExcel=new \PHPExcel;
            $objPHPExcel->getProperties()
                ->setCreator("sia.usbypkp.ac.id")->setLastModifiedBy("sia.usbypkp.ac.id")->setTitle($fileName)
                ->setSubject($fileName);

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1",'No');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1",'Kurikulum');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1",'Program Studi');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D1",'Program Perkuliahan');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E1",'Kls.');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F1",'Kode');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G1",'Matakuliah');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H1",'SKS');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I1",'Dosen');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J1",'Jadwal');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K1",'Tot.');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L1",'App.');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M1",'UnApp.');
            $objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');
            $objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());

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

    public function actionJadwalAktif(){
        $subAkses=self::sub();
        if(Akses::acc('MasterJadwal')){$subAkses="";}
        if(!Akses::acc('/jadwal/index')){throw new ForbiddenHttpException("Forbidden access");}

        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->perwalian(Yii::$app->request->getQueryParams(),($subAkses? ['kl.jr_id'=>$subAkses['jurusan']]:""));




        $_SESSION['query_jadwal'] = Yii::$app->request->getQueryParams();
        if($_GET['c']==1){
            if($_GET['JadwalSearch']['kr_kode']!=''){
                $kr=(int)$_GET['JadwalSearch']['kr_kode'];
                $ModKur = \app\models\Kurikulum::find()->where(['kr_kode'=>$kr])->one();
                if($_GET['JadwalSearch']['kr_kode']!=''){
                    $jr=(int)$_GET['JadwalSearch']['jr_id'];
                    $ModJr = \app\models\Jurusan::find()->where(['jr_id'=>$jr])->one();
                }
            }
            $this->layout = 'blank';
            $content = $this->renderPartial('/jadwal/jdw_pdf',[
                'dataProvider' => $dataProvider,
            ]);

            $pdf = new Pdf([
                'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
                'content' => $content,
                'format'=>Pdf::FORMAT_LETTER,
                'marginLeft'=>5,
                'marginRight'=>5,
                'marginTop'=>20,
                'orientation'=>'L',
                'destination'=>'D',
                //'watermarkText'=>'asd',
                //'cssFile'=>Url::to('@web/css/kv-grid.css'),
                'cssInline'=>"
					a{
						TEXT-DECORATION:none;
					}
					
				",
                'filename'=>'JadwalPerkuliahan-'.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'All').'-'.$ModKur->kr_kode.'-'.date('YmdHis').'.pdf',
                'options' => [
                    'title' => 'Jadwal Perkuliahan '.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'Semua Jurusan').' Tahun Akademik '.$ModKur->kr_kode.'/'.$ModKur->kr_nama,
                    'subject' => 'Jadwal Perkuliahan '.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'Semua Jurusan').' Tahun Akademik '.$ModKur->kr_kode.'/'.$ModKur->kr_nama,
                    'watermarkText'=>'DIREKTORAT SISTEM INFORMASI & MULTIMEDIA',
                    'showWatermarkText'=>true,

                ],
                'methods' => [
                    'SetHeader' => ['DIREKTORAT SISTEM INFORMASI & MULTIMEDIA<br />Jadwal Perkuliahan '.($ModJr?$ModJr->jr_jenjang.' '.$ModJr->jr_nama:'Semua Jurusan').' Tahun Akademik '.$ModKur->kr_kode.'/'.$ModKur->kr_nama.'||' . date("r")],
                    'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'||Page {PAGENO}'],
                    //'SetWatermakText' =>"asd",
                    //'ShowWatermarkText'=>true,
                ]
            ]);

            return $pdf->render();
        }

        return $this->render('jdw_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'subAkses'=>$subAkses,
        ]);
    }

    public function actionSplit($id){
        $model=Jadwal::find()
            ->select(['tbl_jadwal.*','peserta'=>'krs.tot',])
            ->leftJoin("(SELECT kr_kode,jdwl_id ,sum(iif(isnull(krs_stat,0)=2,0,1)) tot,sum(iif(krs_stat=1,1,0)) app,sum(iif(krs_stat=0,1,0)) unapp FROM t_krs_det WHERE  jdwl_id=$id GROUP BY jdwl_id,kr_kode ) krs","(tbl_jadwal.jdwl_id=krs.jdwl_id)")
            ->where(['tbl_jadwal.jdwl_id'=>$id])->one();

        $modelPilih=Jadwal::find()
            ->select(['tbl_jadwal.*','peserta'=>'krs.tot',])
            ->innerJoin("tbl_bobot_nilai bn","(bn.id=tbl_jadwal.bn_id and isnull(bn.RStat,0)=0 and bn.kln_id=".$model->bn->kln_id." and bn.mtk_kode='".$model->bn->mtk_kode."')")
            ->leftJoin("(
                    SELECT kr_kode,jdwl_id ,sum(iif(isnull(krs_stat,0)=2,0,1)) tot,sum(iif(krs_stat=1,1,0)) app,sum(iif(krs_stat=0,1,0)) unapp FROM t_krs_det 
                    WHERE  jdwl_id in (
                        select jdwl_id from tbl_jadwal jd
                        inner join tbl_bobot_nilai bn on (bn.id=jd.bn_id and isnull(bn.RStat,0)=0 and bn.kln_id=".$model->bn->kln_id." and bn.mtk_kode='".$model->bn->mtk_kode."')
                        where isnull(jd.RStat,0)=0 and jd.jdwl_id <> $id
                    )GROUP BY jdwl_id,kr_kode 
                ) krs","(tbl_jadwal.jdwl_id=krs.jdwl_id)")
            ->where("isnull(tbl_jadwal.RStat,0)=0 and tbl_jadwal.jdwl_id <> $id ")->all();
        return $this->render('split',[
            'model'=>$model,
            'modelPilih'=>$modelPilih,
        ]);



    }

    public function actionSplitManual($id,$split){
        return false;
        $model=Jadwal::find()
            ->select(['tbl_jadwal.*','peserta'=>'krs.tot',])
            ->leftJoin("(SELECT kr_kode,jdwl_id ,sum(iif(isnull(krs_stat,0)=2,0,1)) tot,sum(iif(krs_stat=1,1,0)) app,sum(iif(krs_stat=0,1,0)) unapp FROM t_krs_det WHERE  isnull(RStat,0)=0 and jdwl_id=$id GROUP BY jdwl_id,kr_kode ) krs","(tbl_jadwal.jdwl_id=krs.jdwl_id)")
            ->where(['tbl_jadwal.jdwl_id'=>$id])->one();

        $gab['master'] = "
            SELECT sum(iif(isnull(krs_stat,0)=2,0,1)) tot,sum(iif(krs_stat=1,1,0)) app,sum(iif(krs_stat=0,1,0)) unapp 
            FROM t_krs_det WHERE  isnull(RStat,0)=0 and jdwl_id in(SELECT  jdwl_id FROM tbl_jadwal WHERE  isnull(RStat,0)=0 and GKode='$model->GKode') 
          ";
        $gab['master'] = Yii::$app->db->createCommand($gab['master'])->queryOne();

        $modelPilih=Jadwal::find()
            ->select(['tbl_jadwal.*','peserta'=>'krs.tot',])
            ->leftJoin("(SELECT kr_kode,jdwl_id ,sum(iif(isnull(krs_stat,0)=2,0,1)) tot,sum(iif(krs_stat=1,1,0)) app,sum(iif(krs_stat=0,1,0)) unapp FROM t_krs_det WHERE isnull(RStat,0)=0 and jdwl_id=$split GROUP BY jdwl_id,kr_kode ) krs","(tbl_jadwal.jdwl_id=krs.jdwl_id)")
            ->where(['tbl_jadwal.jdwl_id'=>$split])->one();

        $gab['sub'] = "
            SELECT sum(iif(isnull(krs_stat,0)=2,0,1)) tot,sum(iif(krs_stat=1,1,0)) app,sum(iif(krs_stat=0,1,0)) unapp 
            FROM t_krs_det WHERE  isnull(RStat,0)=0 and jdwl_id in(SELECT  jdwl_id FROM tbl_jadwal WHERE  isnull(RStat,0)=0 and GKode='$modelPilih->GKode') 
          ";
        $gab['sub']= Yii::$app->db->createCommand($gab['sub'])->queryOne();


        $listJadwal ="
        select t.jdwl_id,t.id,t.mhs_nim,bd.Nama 
        from t_krs_det t
        LEFT JOIN keuanganfix.dbo.student pd on(pd.nim COLLATE Latin1_General_CI_AS=t.mhs_nim)
        LEFT JOIN keuanganfix.dbo.people bd on(bd.No_Registrasi=pd.No_Registrasi)
        WHERE t.jdwl_id = $model->jdwl_id and isnull(t.RStat,0)=0 and isnull(krs_stat,0)<>2
        
        ORDER  by mhs_nim asc";

        $dataProvider = new SqlDataProvider([
            'sql'=>$listJadwal,
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);

        return $this->render('split_manual',[
            'data'=>$dataProvider,
            'gab'=>$gab,
            'model'=>$model,
            'modelPilih'=>$modelPilih,
        ]);


    }

    public function actionSplitmProc($id,$split){
        $model=Jadwal::findOne($id);
        $modelPilih=Jadwal::findOne($split);
        if($model==null || $modelPilih==null){throw new NotFoundHttpException('The requested page does not exist.');}
        if($_POST['selection']){
            $id=$_POST['selection'];
            TKrsDet::updateAll(['jdwl_id'=>$modelPilih->jdwl_id],['jdwl_id'=>$model->jdwl_id,'mhs_nim'=>$id]);
            return $this->redirect(['split-manual','id'=>$model->jdwl_id,'split'=>$split]);
        }
        return $this->redirect(['split-manual','id'=>$model->jdwl_id,'split'=>$split]);
    }

    public function actionSplitProc($id,$split){
        $model = Jadwal::findOne($id);
        echo each($_POST['id'])['key'];
        Funct::v();

    }

    public function actionTransfer(){
        $n=0;
        $model =TKrsHead::find()->limit('500')->where("isnull(RStat,0)=0 and isnull(tf,0)=0 and isnull(app,0)=1")->all();
        foreach ($model as $d){
            $statSave=0;
            if($d->tf!=1){
                foreach ($d->krsdet as $d1){
                    if(($d1->RStat?:0)==0 && $d1->krs_stat==1){
                        $krs = Krs::findOne(['jdwl_id'=>$d1->jdwl_id,'mhs_nim'=>$d1->mhs_nim]);
                        if($krs===null){
                            $krs = new Krs;
                            $krs->krs_tgl=$d1->ctgl;
                            $krs->jdwl_id=$d1->jdwl_id;
                            $krs->mhs_nim=$d1->mhs_nim;
                            $krs->krs_stat=$d1->krs_stat;
                            $krs->krs_ulang=$d1->krs_ulang;
                            $krs->kr_kode_=$d1->kr_kode;
                            $krs->ds_nidn_=$d1->jdwl->bn->ds_nidn;
                            $krs->ds_nm_=$d1->jdwl->bn->ds->ds_nm;
                            $krs->mtk_kode_=$d1->mtk_kode;
                            $krs->mtk_nama_=$d1->mtk_nama;
                            $krs->sks_=$d1->mtk_sks;
                            $krs->RStat=$d1->RStat;
                            $krs->cuid=$d1->cuid;
                            $krs->ctgl=$d1->ctgl;
                            #(krs_tgl,jdwl_id,mhs_nim,krs_stat,krs_ulang,kr_kode_,ds_nidn_,ds_nm_,mtk_kode_,mtk_nama_,sks_,RStat,cuid,ctgl)
                            #echo"<pre>";
                            #print_r($krs->attributes);
                            #echo"</pre>";
                            if($krs->save(false)){$statSave=1;}else{$statSave=0;}
                            #echo $d1->id ." <br>";
                        }
                    }
                }
            }
            if($statSave==1){TKrsHead::updateAll(['tf'=>1,'tf_date'=>new  Expression("getdate()")],['id'=>$d->id]);
            $n++;
            }
        }
        echo $n;

    }

    public function actionBatch(){
        $n=0;
        $model =TKrsHead::find()->limit(10)->where("isnull(RStat,0)=0 and isnull(tf,0)=1 and isnull(app,0)=1")->all();
        $row=[];
        foreach ($model as $d){
            $statSave=0;
            if($d->tf==1){
                foreach ($d->krsdet as $d1){
                    if(($d1->RStat?:0)==0 && $d1->krs_stat==1){
                        $krs = Krs::findOne(['jdwl_id'=>$d1->jdwl_id,'mhs_nim'=>$d1->mhs_nim]);
                        if($krs===null){
                            $row[]=[
                                $d1->ctgl,
                                $d1->jdwl_id,
                                $d1->mhs_nim,
                                $d1->krs_stat,
                                $d1->krs_ulang,
                                $d1->kr_kode,
                                $d1->jdwl->bn->ds_nidn,
                                $d1->jdwl->bn->ds->ds_nm,
                                $d1->mtk_kode,
                                $d1->mtk_nama,
                                $d1->mtk_sks,
                                $d1->RStat,
                                $d1->cuid,
                                $d1->ctgl,
                            ];
                            #(krs_tgl,jdwl_id,mhs_nim,krs_stat,krs_ulang,kr_kode_,ds_nidn_,ds_nm_,mtk_kode_,mtk_nama_,sks_,RStat,cuid,ctgl)
                            #echo"<pre>";
                            #print_r($krs->attributes);
                            #echo"</pre>";
                            #if($krs->save(false)){$statSave=1;}else{$statSave=0;}
                            #echo $d1->id ." <br>";
                        }
                    }
                }
            }
        }
        echo $n;

        #(krs_tgl,jdwl_id,mhs_nim,krs_stat,krs_ulang,kr_kode_,ds_nidn_,ds_nm_,mtk_kode_,mtk_nama_,sks_,RStat,cuid,ctgl)
        echo"<pre>";
        print_r($row);
        echo"</pre>";


    }

    #Split perwalian
    public function actionSplitPerwalian($id){
        $model=Jadwal::find()
            ->select(['tbl_jadwal.*','peserta'=>'krs.tot',])
            ->leftJoin("(SELECT kr_kode,jdwl_id ,sum(iif(isnull(krs_stat,0)=2,0,1)) tot,sum(iif(krs_stat=1,1,0)) app,sum(iif(krs_stat=0,1,0)) unapp FROM t_krs_det WHERE  jdwl_id=$id and isnull(RStat,0)=0 and isnull(krs_stat,0) <> 2 GROUP BY jdwl_id,kr_kode ) krs","(tbl_jadwal.jdwl_id=krs.jdwl_id)")
            ->where(['tbl_jadwal.jdwl_id'=>$id])->one();

        $modelPilih=Jadwal::find()
            ->select(['tbl_jadwal.*','peserta'=>'krs.tot',])
            ->innerJoin("tbl_bobot_nilai bn","(bn.id=tbl_jadwal.bn_id and isnull(bn.RStat,0)=0 and bn.kln_id=".$model->bn->kln_id." and bn.mtk_kode='".$model->bn->mtk_kode."')")
            ->leftJoin("(
                    SELECT kr_kode,jdwl_id ,sum(iif(isnull(krs_stat,0)=2,0,1)) tot,sum(iif(krs_stat=1,1,0)) app,sum(iif(krs_stat=0,1,0)) unapp FROM t_krs_det 
                    WHERE  jdwl_id in (
                        select jdwl_id from tbl_jadwal jd
                        inner join tbl_bobot_nilai bn on (bn.id=jd.bn_id and isnull(bn.RStat,0)=0 and bn.kln_id=".$model->bn->kln_id." and bn.mtk_kode='".$model->bn->mtk_kode."')
                        where isnull(jd.RStat,0)=0 and jd.jdwl_id <> $id
                    )
                    and isnull(krs_stat,0)<>2
                    and isnull(RStat,0)=0
                    GROUP BY jdwl_id,kr_kode 
                ) krs","(tbl_jadwal.jdwl_id=krs.jdwl_id)")
            ->where("isnull(tbl_jadwal.RStat,0)=0 and tbl_jadwal.jdwl_id <> $id ")->all();
        return $this->render('split_perwalian',[
            'model'=>$model,
            'modelPilih'=>$modelPilih,
        ]);



    }

    public function actionSplitPerwalianManual($id,$split){
        $model=Jadwal::find()
            ->select(['tbl_jadwal.*','peserta'=>'krs.tot',])
            ->leftJoin("(SELECT kr_kode,jdwl_id ,sum(iif(isnull(krs_stat,0)=2,0,1)) tot,sum(iif(krs_stat=1,1,0)) app,sum(iif(krs_stat=0,1,0)) unapp FROM t_krs_det WHERE  isnull(RStat,0)=0 and isnull(krs_stat,0)<>2 and jdwl_id=$id GROUP BY jdwl_id,kr_kode ) krs","(tbl_jadwal.jdwl_id=krs.jdwl_id)")
            ->where(['tbl_jadwal.jdwl_id'=>$id])->one();

        $gab['master'] = "
            SELECT sum(iif(isnull(krs_stat,0)=2,0,1)) tot,sum(iif(krs_stat=1,1,0)) app,sum(iif(krs_stat=0,1,0)) unapp 
            FROM t_krs_det WHERE  isnull(RStat,0)=0 and jdwl_id in(SELECT  jdwl_id FROM tbl_jadwal WHERE  isnull(RStat,0)=0 and GKode='$model->GKode') 
          ";
        $gab['master'] = Yii::$app->db->createCommand($gab['master'])->queryOne();

        $modelPilih=Jadwal::find()
            ->select(['tbl_jadwal.*','peserta'=>'krs.tot',])
            ->leftJoin("(SELECT kr_kode,jdwl_id ,sum(iif(isnull(krs_stat,0)=2,0,1)) tot,sum(iif(krs_stat=1,1,0)) app,sum(iif(krs_stat=0,1,0)) unapp FROM t_krs_det WHERE isnull(RStat,0)=0 and isnull(krs_stat,0)<>2 and jdwl_id=$split GROUP BY jdwl_id,kr_kode ) krs","(tbl_jadwal.jdwl_id=krs.jdwl_id)")
            ->where(['tbl_jadwal.jdwl_id'=>$split])->one();

        $gab['sub'] = "
            SELECT sum(iif(isnull(krs_stat,0)=2,0,1)) tot,sum(iif(krs_stat=1,1,0)) app,sum(iif(krs_stat=0,1,0)) unapp 
            FROM t_krs_det WHERE  isnull(RStat,0)=0 and jdwl_id in(SELECT  jdwl_id FROM tbl_jadwal WHERE  isnull(RStat,0)=0 and GKode='$modelPilih->GKode') 
          ";
        $gab['sub']= Yii::$app->db->createCommand($gab['sub'])->queryOne();


        $listJadwal ="
        select t.jdwl_id,t.id id,t.mhs_nim,bd.Nama 
        from t_krs_det t
        LEFT JOIN keuanganfix.dbo.student pd on(pd.nim COLLATE Latin1_General_CI_AS=t.mhs_nim)
        LEFT JOIN keuanganfix.dbo.people bd on(bd.No_Registrasi=pd.No_Registrasi)
        WHERE t.jdwl_id = $model->jdwl_id and isnull(t.RStat,0)=0 and isnull(krs_stat,0)<>2
        ORDER  by mhs_nim asc";


        $listBentrok = Yii::$app->db->createCommand("exec dbo.bentrokSplitPerwalian '$model->jdwl_id','$modelPilih->jdwl_id'")->queryAll();
        $bentrok=[];
        foreach($listBentrok as $d){$bentrok[$d['mhs_nim']][]='<span class="" style="font-size:14px;font-weight: bold">'.Funct::getHari()[$d['jdwl_hari']].", $d[jdwl_masuk] - $d[jdwl_keluar] |". " $d[ds_nm] | $d[mtk_kode] : $d[mtk_nama] </span>";}

        #Funct::v($bentrok);

        $dataProvider = new SqlDataProvider(['sql'=>$listJadwal,'pagination' => ['pageSize' => 0,],]);

        return $this->render('split_perwalian_manual',[
            'bentrok'=>$bentrok,
            'data'=>$dataProvider,
            'gab'=>$gab,
            'model'=>$model,
            'modelPilih'=>$modelPilih,
        ]);


    }

    public function actionSplitmPerwalianProc($id,$split){
        $model=Jadwal::findOne($id);
        $modelPilih=Jadwal::findOne($split);
        if($model==null || $modelPilih==null){throw new NotFoundHttpException('The requested page does not exist.');}
        if($_POST['selection']){
            $id=$_POST['selection'];
            TKrsDet::updateAll(['jdwl_id'=>$modelPilih->jdwl_id],['jdwl_id'=>$model->jdwl_id,'id'=>$id]);
            return $this->redirect(['split-perwalian-manual','id'=>$model->jdwl_id,'split'=>$split]);
        }
        return $this->redirect(['split-perwalian-manual','id'=>$model->jdwl_id,'split'=>$split]);
    }
    #End

    #Split Perkuliahan
    public function actionSplitPerkuliahan($id){
        $model=Jadwal::find()
            ->select(['tbl_jadwal.*','peserta'=>'krs.tot',])
            ->leftJoin("(
                SELECT 
                kr_kode_,jdwl_id ,
                sum(iif(isnull(krs_stat,0)=2,0,1)) tot,
                sum(iif(krs_stat=1,1,0)) app,
                sum(iif(krs_stat=0,1,0)) unapp 
                FROM tbl_krs WHERE  jdwl_id=$id and isnull(RStat,0)=0 GROUP BY jdwl_id,kr_kode_ ) krs","(tbl_jadwal.jdwl_id=krs.jdwl_id)"
            )
            ->where(['tbl_jadwal.jdwl_id'=>$id])->one();

        $modelPilih=Jadwal::find()
            ->select(['tbl_jadwal.*','peserta'=>'krs.tot',])
            ->innerJoin("tbl_bobot_nilai bn","(bn.id=tbl_jadwal.bn_id and isnull(bn.RStat,0)=0 and bn.kln_id=".$model->bn->kln_id." and bn.mtk_kode='".$model->bn->mtk_kode."')")
            ->leftJoin("(
                    SELECT kr_kode_,jdwl_id,
                    sum(iif(isnull(krs_stat,0)<>2,1,0)) tot
                    from tbl_krs
                    WHERE  jdwl_id in (
                        select jdwl_id from tbl_jadwal jd
                        inner join tbl_bobot_nilai bn on (bn.id=jd.bn_id and isnull(bn.RStat,0)=0 and bn.kln_id=".$model->bn->kln_id." and bn.mtk_kode='".$model->bn->mtk_kode."')
                        where isnull(jd.RStat,0)=0 and jd.jdwl_id <> $id
                    )
                    and isnull(RStat,0)=0
                    GROUP BY jdwl_id,kr_kode_ 
                ) krs","(tbl_jadwal.jdwl_id=krs.jdwl_id)")
            ->where("isnull(tbl_jadwal.RStat,0)=0 and tbl_jadwal.jdwl_id <> $id ")->all();
        return $this->render('split_perkuliahan',['model'=>$model,'modelPilih'=>$modelPilih,]);
    }

    public function actionSplitPerkuliahanManual($id,$split){
        $model=Jadwal::find()
            ->select(['tbl_jadwal.*','peserta'=>'krs.tot',])
            ->leftJoin("(
                    SELECT kr_kode_,jdwl_id ,sum(iif(isnull(krs_stat,0)=1,1,0)) tot 
                    FROM tbl_krs WHERE  isnull(krs_stat,0)=1 and isnull(RStat,0)=0 and jdwl_id=$id 
                    GROUP BY jdwl_id,kr_kode_ 
                ) krs",
                "(tbl_jadwal.jdwl_id=krs.jdwl_id)")
            ->where(['tbl_jadwal.jdwl_id'=>$id])->one();

        $gab['master'] = "
            SELECT sum(iif(isnull(krs_stat,0)=1,1,0)) tot 
            FROM tbl_krs WHERE isnull(krs_stat,0)=1 and isnull(RStat,0)=0 and jdwl_id in(SELECT  jdwl_id FROM tbl_jadwal WHERE  isnull(RStat,0)=0 and GKode='$model->GKode') 
          ";
        $gab['master'] = Yii::$app->db->createCommand($gab['master'])->queryOne();

        $modelPilih=Jadwal::find()
            ->select(['tbl_jadwal.*','peserta'=>'krs.tot',])
            ->leftJoin("(
                    SELECT kr_kode_,jdwl_id, sum(iif(isnull(krs_stat,0)=1,1,0)) tot 
                    FROM tbl_krs WHERE isnull(krs_stat,0)=1 and isnull(RStat,0)=0 
                    and jdwl_id=$split 
                    GROUP BY jdwl_id,kr_kode_ 
                ) krs",
                "(tbl_jadwal.jdwl_id=krs.jdwl_id)")
            ->where(['tbl_jadwal.jdwl_id'=>$split])->one();

        $gab['sub'] = "
            SELECT sum(iif(isnull(krs_stat,0)=1,1,0)) tot 
            FROM tbl_krs WHERE isnull(RStat,0)=0 and isnull(krs_stat,0)=1 
            and jdwl_id in(SELECT jdwl_id FROM tbl_jadwal WHERE  isnull(RStat,0)=0 and GKode='$modelPilih->GKode') 
          ";
        $gab['sub']= Yii::$app->db->createCommand($gab['sub'])->queryOne();


        $listJadwal ="
        select t.jdwl_id,t.krs_id id,t.mhs_nim,bd.Nama 
        from tbl_krs t
        LEFT JOIN keuanganfix.dbo.student pd on(pd.nim COLLATE Latin1_General_CI_AS=t.mhs_nim)
        LEFT JOIN keuanganfix.dbo.people bd on(bd.No_Registrasi=pd.No_Registrasi)
        WHERE t.jdwl_id = $model->jdwl_id and isnull(t.RStat,0)=0 and isnull(krs_stat,0)<>2
        ORDER  by mhs_nim asc";


        $listBentrok = Yii::$app->db->createCommand("exec dbo.bentrokSplitPerkuliahan '$model->jdwl_id','$modelPilih->jdwl_id'")->queryAll();
        $bentrok=[];
        foreach($listBentrok as $d){$bentrok[$d['mhs_nim']][]=
            '<span class="" style="font-size:14px;font-weight: bold">'.Funct::getHari()[$d['jdwl_hari']].", $d[jdwl_masuk] - $d[jdwl_keluar] |". " $d[ds_nm] | $d[mtk_kode] : $d[mtk_nama] </span>";}

        #Funct::v($bentrok);

        $dataProvider = new SqlDataProvider([
            'sql'=>$listJadwal,
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);

        return $this->render('split_perkuliahan_manual',[
            'bentrok'=>$bentrok,
            'data'=>$dataProvider,
            'gab'=>$gab,
            'model'=>$model,
            'modelPilih'=>$modelPilih,
        ]);


    }

    public function actionSplitmPerkuliahanProc($id,$split){
        $model=Jadwal::findOne($id);
        $modelPilih=Jadwal::findOne($split);
        if($model==null || $modelPilih==null){throw new NotFoundHttpException('The requested page does not exist.');}
        if($_POST['selection']){
            $id=$_POST['selection'];
            Krs::updateAll(['jdwl_id'=>$modelPilih->jdwl_id],['jdwl_id'=>$model->jdwl_id,'krs_id'=>$id]);
            return $this->redirect(['split-perkuliahan-manual','id'=>$model->jdwl_id,'split'=>$split]);
        }
        return $this->redirect(['split-perkuliahan-manual','id'=>$model->jdwl_id,'split'=>$split]);
    }

    public function actionExPerwalian(){
        $kr = new  Kurikulum;
        $dataProvider=false;

        if($kr->load(Yii::$app->request->get()) ){
            $dataProvider = new SqlDataProvider([
                'sql'=>" exec dbo.vExportPerwalian '$kr->kr_kode'",
                'pagination' => ['pageSize' => 0,],
            ]);
        }

        return $this->render('ex',[
            'kr'=>$kr,
            'dataProvider'=>$dataProvider,
        ]);
    }

    public function actionExProc($id){
        Yii::$app->db->createCommand("exec dbo.exportPerwalian $id")->execute();
        return $this->redirect(Yii::$app->request->referrer."#NO".$id?:Yii::$app->homeUrl);
    }

    public function actionExProcApp($id){
        Yii::$app->db->createCommand("exec dbo.appPerwalian $id")->execute();
        return $this->redirect(Yii::$app->request->referrer."#NO".$id?:Yii::$app->homeUrl);
    }

    public function actionExProcs(){
        if(isset($_POST['app'])){foreach ($_POST['app'] as $k=>$v ){Yii::$app->db->createCommand("exec dbo.appPerwalian $v")->execute();}}
        if(isset($_POST['ex'])){foreach ($_POST['ex'] as $k=>$v ){Yii::$app->db->createCommand("exec dbo.exportPerwalian $v")->execute();}}
        return $this->redirect(Yii::$app->request->referrer?:Yii::$app->homeUrl);
    }

}
