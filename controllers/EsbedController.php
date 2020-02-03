<?php
namespace app\controllers;
use  yii\web\Session;
use yii\data\ActiveDataProvider;
use Yii;

use app\models\Fakultas;
use app\models\FakultasSearch;

use app\models\Matkul;
use app\models\MatkulSearch;

use kartik\mpdf\Pdf;
use yii\helpers\Url;

use app\models\Kurikulum;
use app\models\KurikulumSearch;


use app\models\Kalender;
use app\models\KalenderSearch;

use app\models\Jadwal;
use app\models\JadwalSearch;

use app\models\Krs;
use app\models\KrsSearch;

use app\models\BobotNilai;
use app\models\BobotNilaiSearch;

use app\models\Mahasiswa;
use app\models\MahasiswaSearch;

use app\models\Dosen;
use app\models\DosenSearch;

use app\models\Wali;
use app\models\WaliSearch;


use app\models\KPembayarankrs;

use app\models\Funct;
use app\models\Akses;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use yii\filters\VerbFilter;
use yii\helpers\Json;

use yii\db\Query;
use yii\data\ArrayDataProvider;

use app\modules\transkrip\models\Nilai;
use app\modules\transkrip\models\NilaiSearch;

$connection = \Yii::$app->db;


class EsbedController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    public function sub(){return Akses::akses();}


	public function actionIndex(){return $this->render('@app/views/site/index');}
	/* Kurikulum */

    public function actionKr(){
        $searchModel = new KurikulumSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        return $this->render('kr_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionKrView($id)
    {
        $model=Kurikulum::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	return $this->redirect(['kr-view', 'id' => $model->kr_kode]);
        } else {
        	return $this->render('kr_view', ['model' => $model]);
		}
    }


	/* End Kurikulum */

	 
	/* Matakul */	
	public function actionMtk(){
        $searchModel = new MatkulSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		\app\models\Funct::LOGS('Mengakses Halaman Matakuliah','','','r');
        return $this->render('mtk_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
    public function actionMtkView($id){
        $model = Matkul::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['mtk_view', 'id' => $model->mtk_kode]);
        } else {
			\app\models\Funct::LOGS('Mengakses Halaman Detail Matakuliah ('."$model->mtk_kode : $model->mtk_nama)",new Matkul(),$id,'r');
        	return $this->render('mtk_view', ['model' => $model]);
		}
    }
	/* end Matkul */


	/* jadwal */
    public function actionJdw()
    {
		
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams());
        $_SESSION['query_jadwal'] = Yii::$app->request->getQueryParams();
        return $this->render('jdw_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionJdwDetail($id)
    {
        $model= Jadwal::findOne($id);
		$searchModel = new KrsSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),['jdwl_id'=>$id]);
        return $this->render('jdw_detail',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model' => $model,
        ]);
    }

    public function actionUts()
    {
		if(isset($_POST['kalender'])){
			Yii::$app->session->open();
			if(!empty($_POST['kalender']['tahun'])){
				$_SESSION['Ckln']=(int)$_POST['kalender']['tahun'];
			}else {unset($_SESSION['Ckln']);}
			//return $this->redirect(['jdw']);
		}

        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),'(jdwl_uts is not null)',["jdwl_uts"=>SORT_ASC]);

        return $this->render('jdw_uts', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionUas()
    {
		if(isset($_POST['kalender'])){
			Yii::$app->session->open();
			if(!empty($_POST['kalender']['tahun'])){
				$_SESSION['Ckln']=(int)$_POST['kalender']['tahun'];
			}else {unset($_SESSION['Ckln']);}
			//return $this->redirect(['jdw']);
		}

        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),'(jdwl_uas is not null)',["jdwl_uas"=>SORT_ASC]);

        return $this->render('jdw_uas', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionJdwView($id)
    {
        $model =  Jadwal::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	return $this->redirect(['jdw-view', 'id' => $model->jdwl_id]);
        } else {
        return $this->render('jdw_view', ['model' => $model]);
		}
    }

	/* end jadwal */

	/* Pengajar */
	/*end ajar */

	/* Mahasiswa */
    public function actionMhs(){
        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
		//\app\models\Funct::LOGS('Mengakses Halaman Mahasiswa');
        return $this->render('mhs_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    public function actionMhsView($id){
        $model 	=  Mahasiswa::findOne($id);
		$ModKe	=  KPembayarankrs::find()
		->where(['nim'=>$id])
		->orderBy(['substring(tahun,2,2)'=>SORT_ASC,'substring(tahun,1,1)'=>SORT_ASC,])
		;
		$ModKe = new ActiveDataProvider([
            'query' => $ModKe,
        ]);

        $searchKrs 	= new KrsSearch;
        $dataKrs 	= $searchKrs->search(Yii::$app->request->getQueryParams());
		
		//$ModKrs = Krs::find()->
		$query = new Query;
		$query
			->select(' kln.kln_id,krs.mhs_nim ,kln.kr_kode, bn.mtk_kode , mk.mtk_sks,krs_grade')
			->from('tbl_krs krs, tbl_jadwal jd,tbl_bobot_nilai bn, tbl_kalender kln, tbl_matkul mk')
			->where("
				krs.jdwl_id=jd.jdwl_id 
				and bn.id=jd.bn_id
				and bn.mtk_kode=mk.mtk_kode
				and bn.kln_id=kln.kln_id
				and mhs_nim='$model->mhs_nim'
				and (
					(bn.RStat is null or bn.RStat='0')
					and (krs.RStat is null or krs.RStat='0')
					and (kln.RStat is null or kln.RStat='0')
				)					
			")
			->orderBy(['substring(kln.kr_kode,2,4)'=>SORT_ASC,'substring(kln.kr_kode,1,1)'=>SORT_ASC,'bn.mtk_kode'=>SORT_ASC])
			;

		$command = $query->createCommand();
		$data = $command->queryAll();  		

		$n=0;
		$kod="";
		$kod="";
		$ITEM=array();
		
		foreach($data as $d){
			if($kod!=$d['kr_kode']){
				$n++;
				$totmk=1;
				$TotSks=0;
				$GradeSks=0;
				$mk='';
				
				if($mk!=$d['mtk_kode']){
					$mk		= $d['mtk_kode'];
					$TotSks	= $d['mtk_sks'];					
				}
				$kod=$d['kr_kode'];
			}else{
				if($mk!=$d['mtk_kode']){
					$totmk=$totmk+1;
					$mk=$d['mtk_kode'];
					$TotSks = $TotSks + $d['mtk_sks'];	
				}
			}
			
			$ITEM[$n]['Tahun_Akademik']=$d['kr_kode'];
			$ITEM[$n]['Total_Matakuliah']=$totmk;
			$ITEM[$n]['Total_SKS']=$TotSks;
			$ITEM[$n]['kln_id']=$d['kln_id'];
			$ITEM[$n]['nim']=$d['mhs_nim'];
		}

		$dataProvider = new ArrayDataProvider([
			'allModels'=>$ITEM,
			'key'=>'kln_id',
			'pagination' => [
        		'pageSize' => 20,
    		],

		]);

		
		
        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {

        	return $this->redirect(['mhs-view', 'id' => $model->mhs_nim]);
        } else {
        	return $this->render('mhs_view', [
					'model' 	=> $model,
					'ThnAkdm'	=> $dataProvider,
					'ModKe'		=> $ModKe
				]
			);
		}
    }




    public function actionMhsKrs($id,$kode)
    {
        $model 	=  Mahasiswa::findOne($id);

        $searchKrs 	= new KrsSearch;
        $dataKrs 	= $searchKrs->search(Yii::$app->request->getQueryParams());
		
		//$ModKrs = Krs::find()->
		$query = new Query;
		$query
			->select(' krs.krs_id ,kln.kr_kode, krs.mhs_nim , bn.mtk_kode ,mk.mtk_nama, mk.mtk_sks,krs_grade,ds_nidn,pr.pr_nama,jd.jdwl_kls')
			->from('tbl_krs krs, tbl_jadwal jd,tbl_bobot_nilai bn, tbl_kalender kln, tbl_matkul mk,tbl_program pr')
			->where("
				krs.jdwl_id = jd.jdwl_id 
				and bn.id=jd.bn_id
				and bn.mtk_kode=mk.mtk_kode
				and bn.kln_id=kln.kln_id
				and kln.pr_kode=pr.pr_kode
				and mhs_nim='$model->mhs_nim'
				and kln.kr_kode =(select distinct kr_kode from tbl_kalender where kln_id='".$kode."') 
				and (
						(krs.RStat='0' or krs.RStat is null )
					and (bn.RStat='0' or bn.RStat is null )
					and (kln.RStat='0' or kln.RStat is null )
					and (jd.RStat='0' or jd.RStat is null )
				)
			")
			->orderBy(['substring(kln.kr_kode,2,4)'=>SORT_ASC,'substring(kln.kr_kode,1,1)'=>SORT_ASC,'bn.mtk_kode'=>SORT_ASC])
			;

		$command = $query->createCommand();
		$data = $command->queryAll();  		

		$n=0;
		$kod="";
		$kod="";
		$ITEM=array();
		$InfTahun="";
		$TotKrs=0;
		$TotGrade=0;
		foreach($data as $d){
			
			$InfTahun=$d['kr_kode'];
			$grade=0;
			if($d['krs_grade']!='-' && !empty($d['krs_grade'])){
				$grade=Funct::Mutu($d['krs_grade']);
			}
			$ITEM[$n]['Id']	=$d['krs_id'];
			
			$ITEM[$n]['Kode']	=$d['mtk_kode'];
			$ITEM[$n]['Kelas']	=$d['jdwl_kls'];
			$ITEM[$n]['Program']=$d['pr_nama'];
			$ITEM[$n]['Matakuliah']=$d['mtk_nama'];
			$ITEM[$n]['SKS']	= $d['mtk_sks'];
			$ITEM[$n]['Dosen']	= Funct::DSN()[$d['ds_nidn']];
			$ITEM[$n]['Grade']	= $d['krs_grade'];
			$ITEM[$n]['Total']	= ($grade * $d['mtk_sks']);
			$ITEM[$n]['nim']	= $d['mhs_nim'];
			$ITEM[$n]['no']	= ($n+1);
			
			$TotKrs+=$ITEM[$n]['SKS'];
			$TotGrade+=$ITEM[$n]['Total'];
			$n++;
			
		}
		@$IPK =0;
		if($TotGrade>0){$IPK = $TotGrade/$TotKrs;}
		
		$dataProvider = new ArrayDataProvider([
			'allModels'=>$ITEM,
			'pagination' => [
        		'pageSize' => 20,
    		],

		]);


        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
        	return $this->redirect(['mhs-krs', 'id' => $model->mhs_nim]);
        } else {
        	return $this->render('mhs_krs', [
					'model' => $model,
					'ThnAkdm'=>$dataProvider,
					'Tahun'=>$InfTahun,
					'IP'=>$IPK,
					
				]
			);
		}
    }

    public function actionMhsKhs($id)
    {
        $model 	=  Mahasiswa::findOne($id);

        $searchKrs 	= new KrsSearch;
        $dataKrs 	= $searchKrs->search(Yii::$app->request->getQueryParams());
		
		//$ModKrs = Krs::find()->
		$query = new Query;
		$query
			->select(' kln.kr_kode,  bn.mtk_kode ,mk.mtk_nama, mk.mtk_sks,mk.mtk_semester,krs_grade,ds_nidn')
			->from('tbl_krs krs, tbl_jadwal jd,tbl_bobot_nilai bn, tbl_kalender kln, tbl_matkul mk')
			->where("
				krs.jdwl_id = jd.jdwl_id 
				and bn.id=jd.bn_id
				and bn.mtk_kode=mk.mtk_kode
				and bn.kln_id=kln.kln_id
				and mhs_nim='$model->mhs_nim'
				and krs_ulang='1'
				and krs.RStat='0'
			");
			
		$PerTahun 	= $query->orderBy(
			['substring(kln.kr_kode,2,4)'=>SORT_ASC,'substring(kln.kr_kode,1,1)'=>SORT_ASC,'bn.mtk_kode'=>SORT_ASC]
		)->createCommand()->queryAll();
		
		$PerSemester= $query->orderBy(['mk.mtk_semester'=>SORT_ASC,'mk.mtk_sks'=>SORT_ASC,])->createCommand()->queryAll();

		$command = $query->createCommand();
		$data = $query->orderBy(['mk.mtk_kode'=>SORT_ASC])->createCommand()->queryAll();

		$n=0;
		$kode="";
		$TotKrs=0;
		$TotGrade=0;
		foreach($data as $d){
			$grade=0;
			if($d['krs_grade']!='-' && !empty($d['krs_grade'])){
				$grade=Funct::Mutu($d['krs_grade']);
			}
	
			if($kode!=$d['mtk_kode']){
				$kode=$d['mtk_kode'];	
				$total 				= $d['mtk_sks'] * $grade;
				@$TotKrs		= $TotKrs+$d['mtk_sks'];
				@$TotGrade   	= $TotGrade+$total;
			}else{
				if($grade!=0){
				$total 				= $d['mtk_sks'] * $grade;
				
				@$TotKrs	= $TotKrs+$d['mtk_sks'];
				@$TotGrade	= $TotGrade+$total;
				}
			}
		}
		
		$IPK = " ( NA / Total SKS ) : $TotGrade/$TotKrs = ".number_format(($TotGrade/$TotKrs),2);
		return $this->render('mhs_khs', [
				'model' => $model,
				'IP'=>$IPK,
				'DataTahun'=>$PerTahun,
				'DataSemester'=>$PerSemester,
				
			]
		);
    }
	/* end Mahasiswa */
	
	/* KRS */
	/* end KRS */
	

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    protected function findModel($id)
    {
        if (($model = Fakultas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    // protected function findModelthn($id)
    // {
    //     if (($model = BobotNilai::findOne($id)) !== null) {
    //         return $model;
    //     } else {
    //         throw new NotFoundHttpException('The requested page does not exist.');
    //     }
    // }    

// ========================= drop ==============================
    public function actionKlass() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
			$id=explode('|',$id);
			if(count($id)==1){
				$id = end($_POST['depdrop_parents']);
			}else if(count($id)>1){
				$id = $id[1];	
			}
			
            $list = Matkul::find()->andWhere(['jr_id'=>$id])->asArray()->all();
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
				//$out[0] = ['id' => NULL, 'name' => 'Pilih Matakuliah' ];
                foreach ($list as $i => $kota) {
                    $out[] = ['id' => $kota['mtk_kode'], 'name' => $kota['mtk_kode']." ". $kota['mtk_nama'] ];
                    //if ($i == 0) {$selected = $kota['mtk_kode'];}
                }
				$selected = '';
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }

    // public function actionThn($id) {
    //     $model=$this->findModelthn($id);
    //     $out = [];
    //     if (isset($_POST['depdrop_parents'])) {
    //         $id = end($_POST['depdrop_parents']);
    //         $list = BobotNilai::find()->select(' kln_id ')->distinct(true)->andWhere(['id'=>$id])->asArray()->all();
    //         $selected  = null;
    //         if ($id != null && count($list) > 0) {
    //             $selected = '';
    //             foreach ($list as $i => $kota) {
    //                 $kode=$kota['jr_id'];
    //                 $out[] = ['id' => $kota['kr_kode']."|".$kota['jr_id'], 'name' => Funct::JURUSAN()[$kode] ];
    //                 if ($i == 0) {
    //                     $selected = $kota['kr_kode']."|".$kota['jr_id'];
    //                 }
    //             }
    //             // Shows how you can preselect a value
    //             echo Json::encode(['output' => $out, 'selected'=>$selected]);
    //             return;
    //         }
    //     }
    //     echo Json::encode(['output' => '', 'selected'=>'']);
    // }

    public function actionKlnjur() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = Kalender::find()->select(' kr_kode, jr_id ')->distinct(true)->andWhere(['kr_kode'=>$id])->asArray()->all();
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $kota) {
					$kode=$kota['jr_id'];
                    $out[] = ['id' => $kota['kr_kode']."|".$kota['jr_id'], 'name' => Funct::JURUSAN()[$kode] ];
                    if ($i == 0) {
                        $selected = $kota['kr_kode']."|".$kota['jr_id'];
                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }

    public function actionKlnpro() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
			$id=explode('|',$id);
            $list = Kalender::find()->andWhere(['jr_id'=>$id[1],'kr_kode'=>$id[0],])->asArray()->all();
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $kota) {
					$kode=$kota['pr_kode'];
					
                    $out[] = ['id' => $kota['kln_id'], 'name' => Funct::PROGRAM()[$kode] ];
                    if ($i == 0) {
                        $selected = $kota['kln_id'];
                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }

    public function actionDropmhs() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = Mahasiswa::find()->andWhere(['jr_id'=>$id,])->asArray()->all();
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $kota) {
					$kode=$kota['mhs_nim'];
                    $out[] = ['id' => $kode, 'name' => $kode /*Funct::MHS()[$kode]*/ ];
                    if ($i == 0) {
                        $selected = $kota['mhs_nim'];
                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }


    public function actionDropwali() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = Funct::DataWali($jns='jr',$kon="and kr_kode='$id'");
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
				$i=0;
                foreach ($list as $k=>$v) {
                    $out[] = ['id' => $k, 'name' => $v/*Funct::MHS()[$kode]*/ ];
                    if ($i == 0) {
                        $selected = $k;
                    }
					$i++;
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }


    public function actionDropwalids() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = Funct::DataWali($jns='ds',$kon="and concat(kr_kode,'#',jr_id)='$id'");
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
				$i=0;
                foreach ($list as $k=>$v) {
                    $out[] = ['id' => $k, 'name' => $v/*Funct::MHS()[$kode]*/ ];
                    if ($i == 0) {
                        $selected = $k;
                    }
					$i++;
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }




//=================== end drop ===============


    public function actionReportMatakuliah() {
 
        $searchModel = Matkul::find()->select('*');
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
                    <h4><b>Data Matakuliah</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Jurusan</th>
                                <th>Kode</th>
                                <th>Matakuliah</th>
                                <th>SKS</th>
                                <th>Semester</th>
                                <th>Jenis</th>
                            </tr>
                        </thead>
                    ';
        foreach ($models as $key => $value) {
            $a=" ";
                    if($value->mtk_jenis=='0'){$a=" Teori ";}
                    if($value->mtk_jenis=='1'){$a=" Praktek ";}
                    if($value->mtk_jenis=='2'){$a=" Teori + Praktek";}
            $content .= '
                    <tbody>
                        <tr>
                            <td>'.$value->jr->jr_jenjang.'-'.$value->jr->jr_nama.'</td>
                            <td>'.$value->mtk_kode.'</td>
                            <td>'.$value->mtk_nama.'</td>
                            <td>'.$value->mtk_sks.'</td>
                            <td>'.$value->mtk_semester.'</td>
                            <td>
                                '.$a.'
                            </td>
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
                'SetHeader'=>['Data Matakuliah - Universitas Sangga Buana YPKP Bandung'], 
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

//kehadiran

 public function actionReportKehadiran($id) {
        $ModBn = BobotNilai::findOne($id);
        $searchModel = Kalender::find()->select('*');
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
                <table class="table table-striped table-bordered">
                    <tr>
                        <td colspan="4" style="text-align:center">
                            <h3>DAFTAR HADIR UJIAN AKHIR SEESTER</h3>
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            <h5>Kode Matakuliah</h5>
                            <h5>Mata Kuliah</h5>
                            <h5>Nama Dosen</h5>
                            <h5>Hari, Tanggal / Jam</h5>
                        </td>
                        <td>
                            <h5>'.$ModBn->mtk_kode.'</h5>
                            <h5>'.$ModBn->mtk->mtk_nama.'</h5>
                            <h5>'.$ModBn->ds->ds_nm.'</h5>
                            <h5>&nbsp;</h5>
                        </td>
                        <td>
                            <h5>Ruangan</h5>
                            <h5>Program</h5>
                            <h5>Semester</h5>
                        </td>
                        <td>
                            <h5>tes</h5>
                            <h5>tes</h5>
                            <h5>tes</h5>
                        </td>
                    </tr>
                </table>
                <br>
                    <h4><b>Data Kalender Akademik</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Jurusan</th>
                                <th>Program</th>
                                <th>KRS</th>
                                <th>Perkuliahan</th>
                                <th>UTS</th>
                                <th>UAS</th>
                            </tr>
                        </thead>
                    ';
        

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
                'SetHeader'=>['Data Kalendaer Akademik - Universitas Sangga Buana YPKP Bandung'], 
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

//




    public function actionReportKalenderAkademik() {
 
        $searchModel = Kalender::find()->select('*');
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
                    <h4><b>Data Kalender Akademik</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Jurusan</th>
                                <th>Program</th>
                                <th>KRS</th>
                                <th>Perkuliahan</th>
                                <th>UTS</th>
                                <th>UAS</th>
                            </tr>
                        </thead>
                    ';
        foreach ($models as $key => $value) {
            $content .= '
                    <tbody>
                        <tr>
                            <td>'.$value->kr_kode.'</td>
                            <td>'.$value->jr->jr_jenjang.'-'.$value->jr->jr_nama.'</td>
                            <td>'.$value->pr->pr_nama.'</td>
                            <td>'.$value->kln_krs.'</td>
                            <td>'.$value->kln_masuk.'</td>
                            <td>'.$value->kln_uts.'</td>
                            <td>'.$value->kln_uas.'</td>
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
                'SetHeader'=>['Data Kalendaer Akademik - Universitas Sangga Buana YPKP Bandung'], 
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

    public function actionReportMahasiswa() {
 
        $searchModel = Mahasiswa::find()->select('*');
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
                    <h4><b>Data Mahasiswa</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Angkatan</th>
                                <th>Nama</th>
                                <th>Jurusan</th>
                            </tr>
                        </thead>
                    ';
        foreach ($models as $key => $value) {
            $content .= '
                    <tbody>
                        <tr>
                            <td>'.@$value->mhs_nim.'</td>
                            <td>'.@$value->mhs_angkatan.'</td>
                            <td>'.@$value->mhs->people->Nama.'</td>
                            <td>'.@$value->jr->jr_jenjang.'-'.@$value->jr->jr_nama.'</td>
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
                'SetHeader'=>['Data Mahasiswa - Universitas Sangga Buana YPKP Bandung'], 
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

    public function actionReportPengajar() {
 
        $searchModel = BobotNilai::find()->select('*');
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
                    <h4><b>Data Pengajar</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Tahun Akademik</th>
                                <th>Jurusan</th>
                                <th>Program</th>
                                <th>Matakuliah</th>
                                <th>Dosen</th>
                            </tr>
                        </thead>
                    ';
        foreach ($models as $key => $value) {
            $content .= '
                    <tbody>
                        <tr>
                            <td>'.@$value->kln->kr_kode.'</td>
                            <td>'.@$value->kln->jr->jr_jenjang.'-'.@$value->kln->jr->jr_nama.'</td>
                            <td>'.@$value->kln->pr->pr_nama.'</td>
                            <td>'.@$value->mtk->mtk_nama .'</td>
                            <td>'.@$value->ds->ds_nm.'</td>
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
                'SetHeader'=>['Data Pengajar - Universitas Sangga Buana YPKP Bandung'], 
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

    public function actionReportJadwalKuliah() {

       


        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs($_SESSION['query_jadwal']);

        //var_dump($dataProvider->getModels());die();
 
        //$searchModel = Jadwal::find()->select('*');
       /* $dataProvider = new ActiveDataProvider([
            'query' => $searchModel,
                'pagination' => [
                'pageSize' => 0,
            ],
        ]);*/
        $models = $dataProvider->getModels();
        
        $content = '
                <table class="table table-bordered">
                    <tr>
                        <td align="center"><img src="'. Url::to("@web/ypkp.png") .'" width="10%"></td>
                        <td align="center"><h4>Universitas Sangga Buana YPKP Bandung</h4><p><b>Direktorat Sistem Informasi & Multimedia</b></p></td>
                    </tr>
                </table>
                <br>
                    <h4><b>Data Jadwal Kuliah</b></h4>
                    <table style="font-size:10px" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Jurusan</th>
                                <th>Program</th>
                                <th>Hari</th>
                                <th>Jadwal</th>
                                <th>Kelas</th>
                                <th>Matakuliah</th>
                                <th>Nama Dosen</th>
                                <th>Ruang</th>
                            </tr>
                        </thead>
                    ';
        foreach ($models as $key => $value) {
            $a=" ";
                    if($value->jdwl_hari=='0'){$a="Minggu";}
                    if($value->jdwl_hari=='1'){$a="Senin";}
                    if($value->jdwl_hari=='2'){$a="Selasa";}
                    if($value->jdwl_hari=='3'){$a="Rabu";}
                    if($value->jdwl_hari=='4'){$a="Kamis";}
                    if($value->jdwl_hari=='5'){$a="Jumat";}
                    if($value->jdwl_hari=='6'){$a="Sabtu";}
            $content .= '
                    <tbody>
                        <tr>
                            <td>'.@$value->bn->kln->jr->jr_jenjang.'-'.@$value->bn->kln->jr->jr_nama.'</td>
                            <td>'.@$value->bn->kln->pr->pr_nama.'</td>
                            <td>'.@$a.'</td>
                            <td>'.@$value->jdwl_masuk.'-'.@$value->jdwl_keluar.'</td>
                            <td>'.@$value->jdwl_kls .'</td>
                            <td>'.@$value->bn->mtk->mtk_nama .'</td>
                            <td>'.@$value->bn->ds->ds_nm .'</td>
                            <td>'.@$value->rg->rg_nama .'</td>
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
                'SetHeader'=>['Data Jadwal Kuliah - Universitas Sangga Buana YPKP Bandung'], 
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

    public function actionReportJadwalUts() {
 
        $searchModel = Jadwal::find()->select('*');
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

    public function actionReportJadwalUas() {
 
        $searchModel = Jadwal::find()->select('*');
        $dataProvider = new ActiveDataProvider([
            'query' => $searchModel,
                'pagination' => [
                'pageSize' => 120,
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
                    <h4><b>Data Jadwal UAS</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Jurusan</th>
                                <th>Program</th>
                                <th>Jadwal</th>
                                <th>Kelas</th>
                                <th>Matakuliah</th>
                                <th>UAS</th>
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
                            <td>'.@$value->jdwl_uas.'<br>'.@$value->jdwl_uas_out.'</td>
                            <td>'.@$value->rg_uas .'</td>
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
                'SetHeader'=>['Data Jadwal UAS - Universitas Sangga Buana YPKP Bandung'], 
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



	// absen harian
	// ouo

	// Nil
    public function actionNil()
    {

        $searchModel = new KrsSearch;
        $dataProvider = $searchModel->nilai(Yii::$app->request->getQueryParams());

        return $this->render('nil_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
	// end NIL
    public function actionNilV2(){
        $searchModel = new NilaiSearch();
        $dataProvider = $searchModel->searchDetail(Yii::$app->request->queryParams);
        return $this->render('nil_index2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

	// Perwalian
    public function actionPrw()
    {

        $searchModel = new KrsSearch;
        $dataProvider = $searchModel->wali(Yii::$app->request->getQueryParams());

        return $this->render('perwalian_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
			'subAkses'=>self::sub()
        ]);
    }

    public function actionPrwDet(){
        $searchModel = new KrsSearch;
        $dataProvider = $searchModel->detail(Yii::$app->request->getQueryParams());
        return $this->render('perwalian_detail', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
			'subAkses'=>self::sub()
        ]);
    }
	// end

}
