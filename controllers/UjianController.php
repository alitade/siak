<?php

namespace app\controllers;

use Yii;
use app\models\Ujian;
use app\models\UjianSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Funct;

/**
 * UjianController implements the CRUD actions for Ujian model.
 */
class UjianController extends Controller
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

    public function actionCetakAbsensi($id,$jenis){
            $tgl="jdwl_uts";
            $rg = 'jd.rg_uts';
            $Kat = $jenis;

            if ($jenis==2) {
                $jenis = "UJIAN TENGAH SEMESTER";
                $rg = 'jd.rg_uts';
            }elseif($jenis==3){
                $tgl="jdwl_uas";
                $jenis = "UJIAN AKHIR SEMESTER";
                $rg = 'jd.rg_uas';
            }
            
            $this->layout = "blank";
         

            $sql = "SELECT 
                        matakuliah = mk.mtk_kode + ' / ' + mk.mtk_nama,
                        dosen = ds.ds_nm,
                        tanggal = CAST(
                            u.tgl AS DATE
                        ),
                        jam = Masuk +' - '+ Keluar,
                        ruang = rg.rg_nama,
                        kode = jr.jr_id,
                        kelas = jd.jdwl_kls,
                        program = UPPER( pr.pr_nama ),
                        jurusan = UPPER( jr.jr_jenjang + ' ' + jr.jr_nama ),
                        semester = kr.kr_nama,
                        kl.kr_kode,
                        u.Id,
                        jd.jdwl_id,
                        u.RgKode,
						jr.jr_id
                    from ujian u
                    JOIN dbo.tbl_jadwal jd on jd.GKode = u.GKode
                    JOIN tbl_bobot_nilai bn ON
                        bn.id = jd.bn_id
                    JOIN tbl_matkul mk ON
                        mk.mtk_kode = bn.mtk_kode
                    JOIN tbl_dosen ds ON
                        ds.ds_id = bn.ds_nidn
                    JOIN tbl_ruang rg ON
                        rg.rg_kode = u.RgKode
                    JOIN tbl_kalender kl ON
                        kl.kln_id = bn.kln_id
                    JOIN tbl_program pr on
                        pr.pr_kode = kl.pr_kode
                    JOIN tbl_jurusan jr on
                        jr.jr_id = kl.jr_id
                    JOIN tbl_kurikulum kr on
                        kr.kr_kode = kl.kr_kode
                    where u.Id = $id AND Kat = $Kat";


            $header['header'] = Yii::$app->db->createCommand($sql)->queryAll();


            if (empty($header['header'])) {
                die('<p style="text-align: center; font-size: 50px;">JADWAL UJIAN BELUM DI SETTING</p>');
            }


            $db = Yii::$app->db1;
            $keuangan = Funct::getDsnAttribute('dbname', $db->dsn);

            foreach ($header['header'] as $k => $val) {

                 if ($jenis=='UJIAN AKHIR SEMESTER') {

                     $sql = "SELECT DISTINCT kr.mhs_nim, IIF(ISNULL(lunas,'')<>'' OR SUBSTRING(kr.mhs_nim,11,1) IN(3,4)
					 or SUBSTRING(kr.mhs_nim,1,4) in('B202','A201','A202')
					 , p.Nama,'')as nama, 
                            absen =( select sum(iif(jdwl_stat=1,1,0)) from tbl_absensi where kr.krs_id=krs_id )
                            FROM peserta_ujian pu 
                            JOIN tbl_krs kr on kr.krs_id = pu.krs_id 
                            JOIN tbl_mahasiswa mh ON mh.mhs_nim = kr.mhs_nim 
                            JOIN tbl_jadwal jd on jd.jdwl_id = kr.jdwl_id
                            JOIN tbl_bobot_nilai bn on bn.id = jd.bn_id
                            JOIN $keuangan.dbo.student s ON s.nim COLLATE Latin1_General_CI_AS = mh.mhs_nim 
                            JOIN $keuangan.dbo.people p ON p.No_Registrasi = s.no_registrasi
                            LEFT JOIN (SELECT nim, lunas ='OK' FROM $keuangan.dbo.pembayarankrs WHERE tahun = '$val[kr_kode]' 
                                       AND (status = 'Lunas' or sisa  <= 0)) ht
                            ON ht.nim COLLATE Latin1_General_CI_AS = kr.mhs_nim
                            WHERE pu.IdUjian = $val[Id] AND mh.jr_id = $val[jr_id]
                            and(
                                (kr.RStat is null or kr.RStat='0')
                                and (mh.RStat is null or mh.RStat='0')
                            )
                            ORDER BY kr.mhs_nim";

                }else{
                    $sql =" 
                    SELECT DISTINCT kr.mhs_nim,p.Nama as nama,
                        absen =(
                            select sum(iif(jdwl_stat=1,1,0)) 
                            from tbl_absensi
                            where kr.krs_id=krs_id 
                        )
                        FROM tbl_absensi ab
                        JOIN tbl_krs kr on kr.krs_id = ab.krs_id
                        JOIN tbl_mahasiswa mh ON mh.mhs_nim = kr.mhs_nim
                        JOIN $keuangan.dbo.student s ON s.nim COLLATE Latin1_General_CI_AS  = mh.mhs_nim
                        JOIN $keuangan.dbo.people p ON p.No_Registrasi = s.no_registrasi
                        WHERE pu.IdUjian = $val[Id]  AND mh.jr_id = $val[jr_id]
                        and (
                            (kr.RStat is null or kr.RStat='0')
                            and (mh.RStat is null or mh.RStat='0')
                        )
                        ORDER BY mhs_nim 
                        ";
                }

                $header['header'][$k]['student'] = Yii::$app->db->createCommand($sql)->queryAll();   

            }
                  
            return $this->render('cetak_absensi', [
                'header' => $header,
                'jenis' => $jenis,
            ]);
        
    }

    /**
     * Lists all Ujian models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UjianSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ujian model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		//$ModJdw = \app\models\Jadwal::find()->
		$model =$this->findModel($id);
        $searchModel 	= new \app\models\JadwalSearch;
        $dataProvider 	= $searchModel->krs(Yii::$app->request->getQueryParams(),['tbl_jadwal.GKode'=>$model->GKode],
		["(
					select count(*) from tbl_krs 
					where jdwl_id=tbl_jadwal.jdwl_id
					and (RStat is null or RStat='0')
					
				)"=>SORT_ASC]
		);
	
	
		$kuota = \app\models\PesertaUjian::find()->where(['IdUjian'=>$model->Id])->count();
		if(isset($_POST['qty']) && isset($_POST['pindah'])){
			$JadwalId	= (int) key($_POST['pindah']);
			$qty 	 	= (int) $_POST['qty'][$JadwalId];
			$Total 		= $kuota + $qty;
			if(!empty($qty)){
				if($Total > $model->ruang->Qujian){
					$qty = $model->ruang->Qujian  - $kuota;
				}
				$q = "
				insert into peserta_ujian(IdUjian,krs_id,jdwl_id_,RStat)
					select top $qty '$model->Id',krs_id,$JadwalId,0
					from tbl_krs krs
					where jdwl_id='$JadwalId' 
					and (krs.RStat is null or krs.RStat='0')
					and not EXISTS (
						select * from peserta_ujian pu
						where pu.krs_id=krs.krs_id 
						
						and (RStat='0' or RStat is null)
					)					
					order by krs_id desc 
				";
				// and IdUjian=$model->Id 
				yii::$app->db->createCommand($q)->execute();
				//\app\models\Funct::LOGS("Menambah Data Penjadwalan  ($model_jadwal->jdwl_id) ",$model_jadwal,$model_jadwal->jdwl_id,'c');
				return $this->redirect(Yii::$app->request->referrer);
			}
		}

        return $this->render('view', [
            'model' => $model,
			'dataProvider'=>$dataProvider
        ]);
    }

    /**
     * Creates a new Ujian model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ujian();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Ujian model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model 	= $this->findModel($id);
		

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Ujian model.
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
     * Finds the Ujian model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ujian the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ujian::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
