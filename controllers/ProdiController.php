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

use app\models\Kalender;
use app\models\KalenderSearch;

use app\models\Jadwal;
use app\models\JadwalSearch;

use app\models\Krs;
use app\models\KrsDosen;
use app\models\KrsSearch;
use app\models\JadwalDosen;

use app\models\BobotNilai;
use app\models\BobotNilaiSearch;

use app\models\Mahasiswa;
use app\models\MahasiswaSearch;

use app\models\Dosen;
use app\models\DosenSearch;

use app\models\KPembayarankrs;

use app\models\Funct;


use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

use yii\db\Query;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;

$connection = \Yii::$app->db;


class ProdiController extends Controller
{
	public function J(){
		return    yii::$app->user->identity->target;		
	}

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


	public function actionIndex(){return $this->render('@app/views/site/index');}
	 
	/* Matakul */	
	public function actionMtk(){
        $searchModel = new MatkulSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,['jr_id'=>$this->J()]);
        return $this->render('mtk_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
    public function actionMtkView($id){
        $model = Matkul::findOne(['mtk_kode'=>$id,'jr_id'=>$this->J()]);
		if($model){
			return $this->render('mtk_view', ['model' => $model]);
		}else{
			throw new NotFoundHttpException();
		}
		
		
    }


    public function actionMtkCreate(){
        $model = new Matkul;
        if ($model->load(Yii::$app->request->post()) ){ 
			$model->jr_id=$this->J();
			$model->mtk_stat='1';
			if( $model->save()){
				\app\models\Funct::LOGS("Menambah Data Matakuliah($model->mtk_kode)",$model,$model->mtk_kode,'c');
				return $this->redirect(['mtk_view', 'id' => $model->mtk_kode]);
			}
        } else {
            return $this->render('mtk_create', [
                'model' => $model,
				'J'=>$this->J()
            ]);
        }
    }

    public function actionMtkUpdate($id){
        $model=Matkul::findOne(['mtk_kode'=>$id,'jr_id'=>$this->J()]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Mengubah Data Matakuliah($model->mtk_kode)",new Matkul,$id,'u');
            return $this->redirect(['mtk-view', 'id' => $model->mtk_kode]);
        } else {
            return $this->render('mtk_update', [
                'model' => $model,
            ]);
        }
    }

    public function actionMtkDelete($id){
        $model=Matkul::findOne(['mtk_kode'=>$id,'jr_id'=>$this->J()]);
		$model->RStat=1;
		$model->save();
		\app\models\Funct::LOGS("Menghapus Data Matakuliah($model->mtk_kode)",new Matkul,$id,'d');
        return $this->redirect(['mtk_index']);
    }
	/* end Matkul */

	/* Kalender */
    public function actionKln()
    {
        $searchModel = new KalenderSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),['jr_id'=>$this->J()]);

        return $this->render('kln_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionKlnView($id)
    {
       
	    $model = Kalender::findOne(['kln_id'=>$id,'jr_id'=>$this->J()]);
		if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
			return $this->redirect(['kln-view', 'id' => $model->kln_id]);
		}else{
			return $this->render('kln_view', ['model' => $model]);	
		}
    }
	/* End Kalender */

	/* Dosen */
    public function actionDsn()
    {
        $searchModel = new DosenSearch;
        $dataProvider = $searchModel->cari(Yii::$app->request->getQueryParams(),['mhs.jr_id'=>$this->J()]);
        return $this->render('dsn_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionDsnCreate()
    {
        $ModDsn		= new Dosen;
        $ModMhs 	= new Mahasiswa;

        if ($ModMhs->load(Yii::$app->request->post()) && $ModMhs->save()) {
            return $this->redirect(['ajr-view', 'id' => $ModMhs->id]);
        } else {
            return $this->render('dsn_create', [
                'ModDsn' => $ModDsn,
                'ModMhs' => $ModMhs,
            ]);
        }
    }

    public function actionDsnView($id){
        $model = Dosen::findOne($id);
		if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
			return $this->redirect(['dsn-view', 'id' => $model->kln_id]);
		}else{
			return $this->render('dsn_view', ['model' => $model]);	
		}
    }

    public function actionDsnWali($id,$p){
        $model = Dosen::findOne($id);

        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),
				['ds_wali'=>$model->ds_id,'jr_id'=>$this->J(),'pr_kode'=>$p]
		);
		
		if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
			return $this->redirect(['dsn-wali', 'id' => $model->ds_id,'p'=>$p]);
		}else{
			return $this->render('dsn_wali', [
				'model' => $model,
				'dataProvider' => $dataProvider,
				'searchModel' => $searchModel,
			]);	
		}
    }
	/* end Dosen */

	/* jadwal */
    public function actionJdw()
    {
		
		if(isset($_POST['kalender'])){
			Yii::$app->session->open();
			if(!empty($_POST['kalender']['tahun'])){
				$_SESSION['Ckln']=(int)$_POST['kalender']['tahun'];
			}else {unset($_SESSION['Ckln']);}
			//return $this->redirect(['jdw']);
		}

        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),['jr.jr_id'=>$this->J()]);

        return $this->render('jdw_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
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
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),"(jdwl_uts is not null and jr.jr_id='".$this->J()."' )",["jdwl_uts"=>SORT_ASC]);

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
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),"(jdwl_uas is not null)",["jdwl_uas"=>SORT_ASC]);

        return $this->render('jdw_uas', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionJdwView($id){
        $model =  Jadwal::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Mengubah Data Penjadwalan($id) ",new Jadwal,$id,'u');
        	return $this->redirect(['jdw-view', 'id' => $model->jdwl_id]);
        } else {
        return $this->render('jdw_view', ['model' => $model]);
		}
    }

    public function actionJdwCreate(){
        $model = new Jadwal;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Menambah Data Penjadwalan($model->jdwl_id) ",$model,$model->jdwl_id,'c');
            return $this->redirect(['view', 'id' => $model->jdwl_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionJdwUpdate($id){
        $model =  Jadwal::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Mengubah Data Penjadwalan($id) ",new Jadwal,$id,'u');
            return $this->redirect(['view', 'id' => $model->jdwl_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionJdwDelete($id){
        $model=$this->findModel($id);
		$model->RStat=1;
		$model->save();
		\app\models\Funct::LOGS("Menghapus Data Penjadwalan($id) ",new Jadwal,$id,'d');
        return $this->redirect(['index']);
    }

    public function actionJdwDetail($id)
    {
        $model= Jadwal::findOne($id);
		$searchModel = new KrsSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),['jdwl_id'=>$id]);

        return $this->render('jdw_detail', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model' => $model,
        ]);
    }
	/* end jadwal */

	/* Pengajar */
    public function actionAjr()
    {
        $searchModel = new BobotNilaiSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),"tbl_bobot_nilai.kln_id in(select kln_id from tbl_kalender where jr_id='".$this->J()."')");
        return $this->render('ajr_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
			'J' => $this->J(),
        ]);
    }

	public function actionAjrView($id){
		//$ModUser=User::find()->where(['username'])->One();
        $model_jadwal = new Jadwal;
        $model_jadwal->bn_id = $id;
        $model_jadwal->semester = '1';
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),['bn_id'=>$id]);
        $ModBn = BobotNilai::findOne($id);
        $model = new BobotNilai;

		// view absen dosen
		$ModUser=\app\models\User::find()->where(['username'=>$ModBn->ds->ds_user])->One();
		//$QueKuliah="EXEC R_AbsDsn '".$ModUser->Fid."',''";
		//$QueKuliah=Yii::$app->db->createCommand($QueKuliah)->queryAll(\PDO::FETCH_NUM);
		
        if ($model_jadwal->load(Yii::$app->request->post()) && $model_jadwal->save()) {
			\app\models\Funct::LOGS("Menambah Data Penjadwalan  ($model_jadwal->jdwl_id) ",$model_jadwal,$model_jadwal->jdwl_id,'c');
            return $this->redirect(['ajr-view', 'id' => $id]);
        }else {
			return $this->render('ajr_jdw', [
				'dataProvider' => $dataProvider,
				'model'=>$model,
				'ModBn'=>$ModBn,
				'model2' => $model_jadwal,
				'searchModel' => $searchModel,
				'id'=>$id,
				]);
        }
        
    }


    public function actionAjrNilai($id){
         
        try {
        $QHeader = "SELECT ds.ds_nm, mk.mtk_nama,mk.mtk_sks,mk.mtk_kode, isnull(bn.nb_tgs1,0) nb_tgs1, isnull(bn.nb_tgs2,0) nb_tgs2,
                 	isnull(bn.nb_tgs3,0) nb_tgs3,isnull(bn.nb_quis,0) nb_quis, isnull(bn.nb_tambahan,0) nb_tambahan
                	,isnull(bn.nb_uts,0) nb_uts,isnull(bn.nb_uas,0) nb_uas, isnull(bn.B,0) B, isnull(bn.C,0) C, isnull(bn.D,0) D, 
                	isnull(bn.D,'0') D, isnull(bn.E,0) E, 
					jd.jdwl_kls,jd.jdwl_hari, jd.jdwl_masuk, jd.jdwl_keluar
                      FROM tbl_bobot_nilai bn JOIN tbl_jadwal jd
                      on jd.bn_id = bn.id 
                      JOIN tbl_matkul mk on mk.mtk_kode = bn.mtk_kode
                      JOIN tbl_dosen ds on ds.ds_id= bn.ds_nidn
                      WHERE jdwl_id ='$id';";
        $db = Yii::$app->db;
        $Header = $db->createCommand($QHeader)->queryOne(); 	
        $db = Yii::$app->db1;
        $keuangan = Funct::getDsnAttribute('dbname', $db->dsn);
		
        $query ="SELECT krs_id,mh.mhs_nim,p.Nama,
                    isnull(krs.krs_tgs1, '0') krs_tgs1,isnull(krs.krs_tgs2, '0') krs_tgs2,
                    isnull(krs.krs_tgs3, '0') krs_tgs3,isnull(krs.krs_quis, '0') krs_quis,
                    isnull(krs.krs_tambahan, '0') krs_tambahan,isnull(krs.krs_uts, '0') krs_uts,
                    isnull(krs.krs_uas, '0') krs_uas,
                    isnull(krs.krs_grade, '-') krs_grade,
                    krs_tot,
					cast( 
                    (
                        (
                            (isnull(krs.krs_tgs1, 0) * ".(float)$Header['nb_tgs1'].") / 100
                        ) + (
                            (isnull(krs.krs_tgs2, 0) * ".(float)$Header['nb_tgs2'].") / 100
                        ) + (
                            (isnull(krs.krs_tgs3, 0) * ".(float)$Header['nb_tgs3'].") / 100
                        ) + (
                            (isnull(krs.krs_quis, 0) * ".(float)$Header['nb_quis'].") / 100
                        ) + (
                            (isnull(krs.krs_tambahan, 0) * ".(float)$Header['nb_tambahan'].") / 100
                        ) + (
                            (isnull(krs.krs_uts, 0) * ".(float)$Header['nb_uts'].") / 100
                        ) + (
                            (isnull(krs.krs_uas, 0) * ".(float)$Header['nb_uas'].") / 100
                        )
                    ) as decimal(5,2) )total
                FROM tbl_krs krs
                LEFT JOIN tbl_mahasiswa mh ON mh.mhs_nim = krs.mhs_nim
                LEFT JOIN $keuangan.dbo.student st ON st.nim COLLATE Latin1_General_CI_AS= mh.mhs_nim
                LEFT JOIN $keuangan.dbo.people p ON p.No_Registrasi = st.no_registrasi
                WHERE jdwl_id = '$id' AND krs.krs_stat =1
				
				
				";

        $count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($query) T;")->queryScalar();
        $dataProvider = new SqlDataProvider([
            'sql' => $query . " ORDER BY mhs_nim",
            'totalCount' => (int)$count,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        $searchModel = new KrsDosen();
        return $this->render('ajr_nilai', [
            'header'        => $Header,
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
			'ID'	=>$id
        ]);

        } catch (Exception $e) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
      

    }




    public function actionAbsensi($id,$matakuliah=null,$token=null,$cetak=null){
		//
        $matkull = Matkul::findOne(['mtk_kode'=>$matakuliah]);
        $model   = JadwalDosen::findOne(['jdwl_id'=>$id]);
        
        $jum = $matkull->mtk_sesi;
        $AbsDos="
        select 
			MIN(tgl) tgl_absen,(DATEPART(dw,min(tgl))-1) jdwl_hari, max(ds_masuk) masuk,max(ds_keluar) keluar,
			iif(
			isnull(max(ds_get_fid),max(ds_fid))
			=max(ds_fid),1,0
			) m,
			-- isnull(datediff(minute,max(ds_masuk),max(ds_keluar)),0) menit
			sesi
        from m_transaksi_finger where jdwl_id='".$model->jdwl_id."'
		and sesi not in('15','ts','as','')
		and sesi is not null
        GROUP by sesi, jdwl_hari
		order by min(tgl)
        ";

		$AbsDos=Yii::$app->db->createCommand($AbsDos)->queryAll();
		//echo $model->bn->ds_nidn;
        $table ='
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th colspan="14">
						'.$model->bn->ds->ds_nm.' <br />
						'.Funct::Hari()[$model->jdwl_hari].', '.$model->jdwl_masuk.' - '.$model->jdwl_keluar.' : '
						.$model->bn->mtk_kode.' - '.$model->bn->mtk->mtk_nama.'( '.$model->jdwl_kls.' )
					</th>
				</tr>
				<tr>';
				for($i=1;$i<=14;$i++){
					$table.='';//"<th  style='background:".(Funct::StatAbsDsn($id,$i)?'green':'red')."'>Sesi $i</th>";
				}
				$table.='</tr>
			</thead>';
			$row=14;
			$n=0;
			$table.="<tbody><tr>";
			$ThDs="";
			$TdDs="";
			
			echo"<pre>";
			//print_r($AbsDos);
			echo"</pre>";
			foreach($AbsDos as $d){
				$n++;
				$bg="red";
				
				if(Funct::StatAbsDsn($id,$n)){
					$bg="green";
					if(empty($d['masuk'])){$bg="blue";}
				}
				
				$ThDs.="<th  style='background:$bg'>Sesi $n</th>";
				$ThMhs.="<td style='background:$bg'>$n</td>";
				$TdDs.="<td >".date("M d",strtotime($d[tgl_absen])).",<br>".($d[masuk]?substr($d['masuk'],0,5):"?")." - ".($d[keluar]?substr($d['keluar'],0,5):"?")."</td>";
				$row--;
			}
			$table.="<tr>$ThDs</tr><tr>$TdDs</tr>";
			
			for($a=0;$a<$row;$a++){$table.="<td> ? ? - ?</td>";}
        echo "<!-- absen";
         $jum=14;   
        echo "-->";
			$table.="
			<tr>
			</tbody>
			</table>
			<table class='table table-bordered table-hover'>
				<thead>
				<tr>
				<th rowspan='2' style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>No</th>
				<th rowspan='2' style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>NIM</th>
				<th rowspan='2'style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>NAMA</th>
				<th colspan=$jum style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>SESI</th>
				</tr><tr style='align-content: center; text-align: center; font-weight: bold; background-color: rgb(51, 122, 183);'>";
			for ($idx=1; $idx <= (int)$jum; $idx++) { 
				$Sesion = ((int)$idx <=9) ? '0' . (int)$idx : (int)$idx;
				$S[] = $Sesion;
				//$table .= "<td style='background:".(Funct::StatAbsDsn($id,$Sesion)?'green':'red')."'>$Sesion</td>";
			}
        
        $table .= "$ThMhs</tr></thead>";
        if ($model) {     
        $db = Yii::$app->db1;
        $keuangan = Funct::getDsnAttribute('dbname', $db->dsn);
		if(!$keuangan){$keuangan = Funct::getDsnAttribute('Database', $db->dsn);}
		
        $query1 ="
		
		SELECT * FROM(SELECT DISTINCT DENSE_RANK() OVER (PARTITION BY jdwl_id ORDER BY mhs_nim ASC) as id, T.*,p.Nama FROM (
                SELECT krs.mhs_nim, krs.jdwl_id,
                 krs.krs_id, isnull([1],0)[S01],
                isnull([2],0)[S02],isnull([3],0)[S03],isnull([4],0)[S04],
                isnull([5],0)[S05],isnull([6],0)[S06],isnull([7],0)[S07],
                isnull([8],0)[S08],isnull([9],0)[S09],isnull([10],0)[S10],
                isnull([11],0)[S11],isnull([12],0)[S12],isnull([13],0)[S13],
                isnull([14],0)[S14],isnull([15],0)[S15],isnull([16],0)[S16]
                FROM (
						SELECT distinct a.jdwl_id, a.krs_id,
							iif(max(ds_stat)!=0 AND isnull(a.mhs_stat,0)!=0,1,0) jdwl_stat,
							--isnull(a.mhs_stat,0) jdwl_stat, 
							a.sesi
						FROM m_transaksi_finger a
						INNER JOIN tbl_krs k on(k.krs_id=a.krs_id AND ISNULL(k.RStat,0)=0 AND k.jdwl_id=$id)
						INNER JOIN tbl_jadwal j on(j.jdwl_id=k.jdwl_id AND ISNULL(j.RStat,0)=0)
						INNER JOIN tbl_bobot_nilai bn on(bn.id=j.bn_id and ISNULL(bn.RStat,0)=0)
						where a.krs_id=k.krs_id
						and a.jdwl_id=$id							
						and isnull(a.RStat,0)=0
						GROUP BY a.jdwl_id,a.mhs_stat,a.krs_id,a.sesi
					
				) AS src
                    pivot (
                    max(jdwl_stat) FOR Sesi IN ([1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],[12],[13],[14],[15],[16])
                ) AS pvt
                LEFT join tbl_krs krs on krs.krs_id = pvt.krs_id
                LEFT join m_transaksi_finger ab on ab.krs_id = pvt.krs_id 
                WHERE krs.krs_stat ='1'
				and isnull(krs.RStat,0)=0 
				
                ) T  
                LEFT JOIN $keuangan.dbo.student st ON st.nim COLLATE Latin1_General_CI_AS = T.mhs_nim
                LEFT JOIN $keuangan.dbo.people p ON p.No_Registrasi = st.no_registrasi
				where p.Nama is not NULL
				) B
                ORDER BY B.mhs_nim ASC
               ";


        $data = Yii::$app->db->createCommand($query1)->queryAll();
        $no=1;
        foreach ($data as $key) {
            $table .= "<tr>
                        <td style='font-weight: bold; font-size: 14px;'>".$no++."</td>
                        <td style='font-weight: bold; font-size: 14px;'>$key[mhs_nim]</td>
                        <td >$key[Nama]</td>";
                        foreach ($S as $d => $val) {
                            $k = 'S'.$val;
							if(!isset($arrTot[$k])){
								$arrTot[$k]=0;
							}            

                            $attribute = 'data-nim="'.$key['mhs_nim'].'" data-jdwl_id="'.$id.'" data-sesi="'.(int)$val.'" data-krs_id="'.$key['krs_id'].'"';
                            
                            if ($key[$k]==0) {
                                   // $value = '<a href="javascript:;" class="do_attendance btn glyphicon glyphicon-remove-circle" name ="ctrl_attendance" id="'.$k.'" ' .$attribute. ' style="color: red;"></a>';
									$value = '<i class="glyphicon glyphicon-remove-circle" style="color: red;"></i>';
                                }else{
									$arrTot[$k]++;
                                    //$value = '<a href="javascript:;" class="do_attendance btn glyphicon glyphicon-ok-circle"     name ="ctrl_attendance" id="'.$k.'" ' .$attribute. '" style="color: green;"></a>';
                                    $value = '<i class="glyphicon glyphicon-ok-circle"  style="color: green;"></i>';
                            }
            $table  .=  "<td style='text-align: center;'>
                            $value
                        </td>";                            
                        }

            $table  .="</tr>";
        }

        $table .= "
			<tr style='font-weight:bold;text-align:right;'><th colspan='3'>TOTAL</th><th>".implode("</th><th>",$arrTot)."</th></tr>
		</table>";
     
        return $this->render('absensi', [   
            'table' => $table,     
        ]);
         
         }

	//
		
	/*	
        $matkull = Matkul::findOne(['mtk_kode'=>$matakuliah]);
        $model   = JadwalDosen::findOne(['jdwl_id'=>$id]);
        $jum = $matkull->mtk_sesi;
        $jum = 14;//$matkull->mtk_sesi;
		$AbsDos="select * from dosen_absen where jdwl_id='".$model->jdwl_id."'";
		$AbsDos=Yii::$app->db->createCommand($AbsDos)->queryAll();
		//echo $model->bn->ds_nidn;
        $table ='
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th colspan="14">
						'.$model->bn->ds->ds_nm.' <br />
						'.Funct::Hari()[$model->jdwl_hari].', '.$model->jdwl_masuk.' - '.$model->jdwl_keluar.' : '
						.$model->bn->mtk_kode.' - '.$model->bn->mtk->mtk_nama.'( '.$model->jdwl_kls.' )
					</th>
				</tr>
				<tr>
					<th>Sesi 1</th>
					<th>Sesi 2</th>
					<th>Sesi 3</th>
					<th>Sesi 4</th>
					<th>Sesi 5</th>
					<th>Sesi 6</th>
					<th>Sesi 7</th>
					<th>Sesi 8</th>
					<th>Sesi 9</th>
					<th>Sesi 10</th>
					<th>Sesi 11</th>
					<th>Sesi 12</th>
					<th>Sesi 13</th>
					<th>Sesi 14</th>
				</tr>
			</thead>';
			$row=14;
			$table.="<tbody><tr>";
			foreach($AbsDos as $d){
				$table.="<td>".date("M d",strtotime($d[tgl_absen])).", ".($d[masuk]?$d[masuk]:"?")." - ".($d[keluar]?$d[keluar]:"?")."</td>";
				$row--;
			}
			for($a=0;$a<$row;$a++){$table.="<td> ? ? - ?</td>";}
			$table.="
			<tr>
			</tbody>
			</table>
		
				<table class='table table-bordered table-hover'>
                    <thead>
                    <tr>
                    <th rowspan='2' style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>No</th>
                    <th rowspan='2' style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>NIM</th>
                    <th rowspan='2'style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>NAMA</th>
                    <th colspan=$jum style='vertical-align: middle; text-align: center; background-color: rgb(51, 122, 183) none repeat scroll 0% 0%;'>SESI</th>
                    </tr><tr style='align-content: center; text-align: center; font-weight: bold; background-color: rgb(51, 122, 183);'>";

        
        for ($idx=1; $idx <= (int)$jum; $idx++) { 
            $Sesion = ((int)$idx <=9) ? '0' . (int)$idx : (int)$idx;
            $S[] = $Sesion;
            $table .= "<td>$Sesion</td>";
        }
        
        $table .= "</tr></thead>";

        if ($model) {     
        $db = Yii::$app->db1;
        $keuangan = Funct::getDsnAttribute('dbname', $db->dsn);       
        $query ="SELECT * FROM(SELECT DISTINCT DENSE_RANK() OVER (PARTITION BY jdwl_id ORDER BY mhs_nim ASC) as id, T.*,p.Nama FROM (
                SELECT krs.mhs_nim, krs.jdwl_id,
                 krs.krs_id, isnull([1],0)[S01],
                isnull([2],0)[S02],isnull([3],0)[S03],isnull([4],0)[S04],
                isnull([5],0)[S05],isnull([6],0)[S06],isnull([7],0)[S07],
                isnull([8],0)[S08],isnull([9],0)[S09],isnull([10],0)[S10],
                isnull([11],0)[S11],isnull([12],0)[S12],isnull([13],0)[S13],
                isnull([14],0)[S14],isnull([15],0)[S15],isnull([16],0)[S16]
                FROM (
                    SELECT k.jdwl_id,  a.krs_id,cast(isnull(jdwl_stat,0) as smallint) jdwl_stat, jdwl_sesi AS Sesi
                    FROM tbl_absensi a,tbl_krs k 
                    where a.krs_id=k.krs_id
                    and 
                    k.jdwl_id = $id
                    ) AS src
                    pivot (
                    max(jdwl_stat) FOR Sesi IN ([1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],[12],[13],[14],[15],[16])
                ) AS pvt
                LEFT join tbl_krs krs on krs.krs_id = pvt.krs_id
                LEFT join tbl_absensi ab on ab.krs_id = pvt.krs_id 
                WHERE krs.krs_stat =1
                ) T  
                LEFT JOIN $keuangan.dbo.student st ON st.nim COLLATE Latin1_General_CI_AS = T.mhs_nim
                LEFT JOIN $keuangan.dbo.people p ON p.No_Registrasi = st.no_registrasi) B
                ORDER BY B.mhs_nim ASC
               ";

        $data = Yii::$app->db->createCommand($query)->queryAll();
		$no=1;
        foreach ($data as $key) {
		$table .= "
		<tr>
			<td style='font-weight: bold; font-size: 14px;'>".$no++."</td>
			<td style='font-weight: bold; font-size: 14px;'>$key[mhs_nim]</td>
			<td >$key[Nama]</td>";
			foreach ($S as $d => $val) {
				$k = 'S'.$val;
				if ($key[$k]==0) {
					$value = '<span class="do_attendance btn glyphicon glyphicon-remove-circle" name ="ctrl_attendance" style="color: red;"></span>';
				}else{
					 $value = '<span class="do_attendance btn glyphicon glyphicon-ok-circle" name ="ctrl_attendance" " style="color: green;"></span>';
				}
				$table  .=  "<td style='text-align: center;'>$value</td>";                            
			}
            $table  .="</tr>";
        }
        $table .= "</table>";
     
	 	if($cetak==1){
			$pdf = new Pdf([

			'mode' => Pdf::MODE_CORE, 
			'format' => Pdf::FORMAT_LETTER, 
			'orientation' => Pdf::ORIENT_PORTRAIT, 
			'destination' => Pdf::DEST_BROWSER, 
			'content' => $table,  
			'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
			'cssInline' => '.kv-heading-1{font-size:18px}', 
			'options' => ['title' => 'Krajee Report Title'],
			'methods' => [ 
				'SetHeader'=>['Direktorat Sistem Informasi & Multimedia '.date('Y-m-d H:i:s')], 
				'SetFooter'=>['{PAGENO}'],
			]
			]);
			return $pdf->render(); 			
		}
	 
        return $this->render('absensi',[
            'table' => $table,
        ]);
         
         }
    */
	
	}

	public function actionCetakAbsen($id,$matakuliah=null,$token=null,$cetak=null){
		/* */
        $matkull = Matkul::findOne(['mtk_kode'=>$matakuliah]);
        $model   = JadwalDosen::findOne(['jdwl_id'=>$id]);
        
		$ModJur= \app\models\Jurusan::findOne($this->J());
		
        $jum = $matkull->mtk_sesi;
		$AbsDos="select * from dosen_absen where jdwl_id='".$model->jdwl_id."'";

        $AbsDos="
        select 
            tgl tgl_absen,(DATEPART(dw,tgl)-1) jdwl_hari, max(ds_masuk) masuk,max(ds_keluar) keluar,
            iif(
                isnull(max(ds_get_fid),max(ds_fid))
                =max(ds_fid),1,0
            ) m,
            isnull(datediff(minute,max(ds_masuk),max(ds_keluar)),0) m
        from m_transaksi_finger where jdwl_id='".$model->jdwl_id."'
		and sesi not in('15','ts','as','')
		and sesi is not null

        GROUP by tgl, jdwl_hari
		order by tgl
        ";

		$AbsDos=Yii::$app->db->createCommand($AbsDos)->queryAll();
		//echo $model->bn->ds_nidn;
        $table ='
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th colspan="14">
						'.$model->bn->ds->ds_nm.' <br />
						'.Funct::Hari()[$model->jdwl_hari].', '.$model->jdwl_masuk.' - '.$model->jdwl_keluar.' : '
						.$model->bn->mtk_kode.' - '.$model->bn->mtk->mtk_nama.'( '.$model->jdwl_kls.' )
					</th>
				</tr>
				<tr>';
				for($i=1;$i<=14;$i++){
					$table.='';//"<th  style='background:".(Funct::StatAbsDsn($id,$i)?'green':'red')."'>Sesi $i</th>";
				}
				$table.='</tr>
			</thead>';
			$row=14;
			$n=0;
			$table.="<tbody><tr>";
			$ThDs="";
			$TdDs="";
			foreach($AbsDos as $d){
				$n++;
				$bg="red";
				
				if(Funct::StatAbsDsn($id,$n)){
					$bg="green";
					//if(empty($d['masuk'])){$bg="blue";}
					$TdDs.="<td>".date("d M",strtotime($d[tgl_absen])).
					//",<br>".($d[masuk]?substr($d['masuk'],0,5):"?")." - ".($d[keluar]?substr($d['keluar'],0,5):"?").
					"</td>";
				}else{
					$TdDs.="<td> - </td>";
				}
				
				$ThDs.="<th style='color:white;background:$bg'>S.$n</th>";
				$ThMhs.="<td style='color:white;background:$bg'>$n</td>";
				//$TdDs.="<td >".date("M d",strtotime($d[tgl_absen])).",<br>".($d[masuk]?substr($d['masuk'],0,5):"?")." - ".($d[keluar]?substr($d['keluar'],0,5):"?")."</td>";
				$row--;
			}
			$table.="<tr>$ThDs</tr><tr>$TdDs</tr>";
			
			for($a=0;$a<$row;$a++){$table.="<td> ? ? - ?</td>";}
        echo "<!-- absen";
         $jum=14;   
        echo "-->";
			$table.="
			<tr>
			</tbody>
			</table>
			<table class='table table-bordered table-hover'>
				<thead style='color:#fff;'>
				<tr style='color:#fff;'>
				<th rowspan='2' style='vertical-align: middle; text-align: center;'>No</th>
				<th rowspan='2' style='vertical-align: middle; text-align: center;'>NIM</th>
				<th rowspan='2' style='vertical-align: middle; text-align: center;'>NAMA</th>
				<th colspan=$jum style='vertical-align: middle; text-align: center;'>SESI</th>
				</tr><tr style='align-content: center; text-align: center; font-weight: bold; background-color: rgb(51, 122, 183);'>";
			for ($idx=1; $idx <= (int)$jum; $idx++) { 
				$Sesion = ((int)$idx <=9) ? '0' . (int)$idx : (int)$idx;
				$S[] = $Sesion;
				//$table .= "<td style='background:".(Funct::StatAbsDsn($id,$Sesion)?'green':'red')."'>$Sesion</td>";
			}
        
        $table .= "$ThMhs</tr></thead>";
        if ($model) {     
        $db = Yii::$app->db1;
        $keuangan = Funct::getDsnAttribute('dbname', $db->dsn);
        if(!$keuangan){
			$keuangan = Funct::getDsnAttribute('Database',$db->dsn);
		}
		
        $query1 ="SELECT * FROM(SELECT DISTINCT DENSE_RANK() OVER (PARTITION BY jdwl_id ORDER BY mhs_nim ASC) as id, T.*,p.Nama FROM (
                SELECT krs.mhs_nim, krs.jdwl_id,
                 krs.krs_id, isnull([1],0)[S01],
                isnull([2],0)[S02],isnull([3],0)[S03],isnull([4],0)[S04],
                isnull([5],0)[S05],isnull([6],0)[S06],isnull([7],0)[S07],
                isnull([8],0)[S08],isnull([9],0)[S09],isnull([10],0)[S10],
                isnull([11],0)[S11],isnull([12],0)[S12],isnull([13],0)[S13],
                isnull([14],0)[S14],isnull([15],0)[S15],isnull([16],0)[S16]
                FROM (
						SELECT distinct a.jdwl_id, a.krs_id,
							iif(max(ds_stat)!=0 AND isnull(a.mhs_stat,0)!=0,1,0) jdwl_stat,
							--isnull(a.mhs_stat,0) jdwl_stat, 
							a.sesi
						FROM m_transaksi_finger a
						INNER JOIN tbl_krs k on(k.krs_id=a.krs_id AND ISNULL(k.RStat,0)=0 AND k.jdwl_id=$id)
						INNER JOIN tbl_jadwal j on(j.jdwl_id=k.jdwl_id AND ISNULL(j.RStat,0)=0)
						INNER JOIN tbl_bobot_nilai bn on(bn.id=j.bn_id and ISNULL(bn.RStat,0)=0)
						where a.krs_id=k.krs_id
						and a.jdwl_id=$id							
						and isnull(a.RStat,0)=0
						GROUP BY a.jdwl_id,a.mhs_stat,a.krs_id,a.sesi
					
				) AS src
                    pivot (
                    max(jdwl_stat) FOR Sesi IN ([1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],[12],[13],[14],[15],[16])
                ) AS pvt
                LEFT join tbl_krs krs on krs.krs_id = pvt.krs_id
                LEFT join m_transaksi_finger ab on ab.krs_id = pvt.krs_id 
                WHERE krs.krs_stat ='1'
				and isnull(krs.RStat,0)=0 
				
                ) T  
                LEFT JOIN $keuangan.dbo.student st ON st.nim COLLATE Latin1_General_CI_AS = T.mhs_nim
                LEFT JOIN $keuangan.dbo.people p ON p.No_Registrasi = st.no_registrasi) B
                ORDER BY B.mhs_nim ASC
               ";


        $data = Yii::$app->db->createCommand($query1)->queryAll();
        $no=1;
        foreach ($data as $key) {
            $table .= "<tr>
                        <td style='font-weight: bold; font-size: 14px;'>".$no++."</td>
                        <td style='font-weight: bold; font-size: 14px;'>$key[mhs_nim]</td>
                        <td >$key[Nama]</td>";
                        foreach ($S as $d => $val) {
                            $k = 'S'.$val;
							if(!isset($arrTot[$k])){$arrTot[$k]=0;}            

                            $attribute = 'data-nim="'.$key['mhs_nim'].'" data-jdwl_id="'.$id.'" data-sesi="'.(int)$val.'" data-krs_id="'.$key['krs_id'].'"';
                            
                            if ($key[$k]==0) {
                                   // $value = '<a href="javascript:;" class="do_attendance btn glyphicon glyphicon-remove-circle" name ="ctrl_attendance" id="'.$k.'" ' .$attribute. ' style="color: red;"></a>';
									$value = '<i class="glyphicon glyphicon-remove-circle" style="color: red;">X</i>';
                                }else{
									$arrTot[$k]++;
                                    //$value = '<a href="javascript:;" class="do_attendance btn glyphicon glyphicon-ok-circle"     name ="ctrl_attendance" id="'.$k.'" ' .$attribute. '" style="color: green;"></a>';
                                    $value = '<i class="glyphicon glyphicon-ok-circle"  style="color: green;">&radic;</i>';
                            }
            $table  .=  "<td style='text-align: center;'>
                            $value
                        </td>";                            
                        }

            $table  .="</tr>";
        }

        $table .= "
			<tr style='font-weight:bold;text-align:right;'><th colspan='3'>TOTAL</th><th>".implode("</th><th>",$arrTot)."</th></tr>
		</table>
		<table border='0' width='100%'>

			<tr>
				<td width='40%' height='80px' valign='top'>Ketua Program Studi</td>
				<td width='20%'> </td>
				<td width='40%' height='80px' valign='top'>Dekan</td>
			</tr>
			<tr>
				<td>".$ModJur->jr_head."</td>
				<td> </td>
				<td>".$ModJur->fk->fk_head."</td>
			</tr>
		</table>
		
		";
     
         
         }
		// absen mahasiswa
		$QueKuliah="EXEC R_AbsJdwlMhs '".$id."'";
		$QueKuliah=Yii::$app->db->createCommand($QueKuliah)->queryAll(\PDO::FETCH_NUM);
		$hd="";$bd='';$n=0;$n1=0;

		if($QueKuliah){
			foreach($QueKuliah as $d){
				$bd .='<tr>';			
				for($i=0;$i<count($d);$i++){
					$jadwal=$d[(count($d)-7)].", ".$d[(count($d)-6)]."-".$d[(count($d)-5)];
					if($i!=0 && $i!=1 && $i!=(count($d)-4)){
						if($n==0){
							if($i==2){
								$hd .="<th>#</th>";
								$hd .="<th>NPM</th><th>Nama</th>";
							}else{
								$n1++;
								if($i>=4 and $i<(count($d)-4)){
									$hd .="<th>".($i-3)." </th>";
								}else{
									if($i==(count($d)-1)){$hd .='<th> %</th>';}
								}
							}
						}
	
						// Status absen
						if($i==2){
							$bd.='<td>'.($n+1).'</td>';
							$bd.='<td>'.$d[$i].'</td>';
							$bd.='<td>'.$d[($i+1)].'</td>';
						}else{
							if($i>=4 and $i<(count($d)-4)){
								$bd.='
								<td>
									'.(
										$d[$i] > 0?'0':'x'
									).'
								</td>';
							}else{
								if($i==(count($d)-1)){$bd.='<td>'.number_format($d[$i],1).'%</td>';}							
							}
						}
					}
				}
				$bd.='</tr>';
				$n++;
			}
			$DATA.='<table class="table table-bordered"><thead><tr>'.$hd.'</tr></thead><tbody>'.$bd.'</tbody></table>';
		}
		
//echo $table;       
//die();
        if ($model) {
			$pdf = new Pdf([
				'mode' => Pdf::MODE_CORE, 
				'format' => Pdf::FORMAT_LETTER, 
				'orientation' => Pdf::ORIENT_PORTRAIT, 
				'destination' => Pdf::DEST_BROWSER, 
				'marginLeft'=>5,
				'marginRight'=>5,
				'marginTop'=>18,
				'marginHeader'=>1,
				'content' => $table,  
				'cssInline' => '
					td,th{
						padding:4px;
						font-size:12px;
					}
				', 
				'options' => [
					'title' =>'
							'.$model->bn->kln->kr_kode.'-'.
							$model->bn->kln->jr->jr_jenjang.' '.$model->bn->kln->jr->jr_nama.'-
							'.$model->bn->ds->ds_nm.'-
							'.$model->jdwl_hari.'-'.$model->jdwl_masuk.':'.$model->jdwl_keluar.'-'
							.$model->bn->mtk_kode.'-'.$model->bn->mtk->mtk_nama.'( '.$model->jdwl_kls.' )			
					',
					'watermarkText'=>'DIREKTORAT SISTEM INFORMASI & MULTIMEDIA',
					'showWatermarkText'=>true,
				],
				'methods' => [ 
					'SetHeader' => [
						'<table>
							<tr>
								<td><img src="'.Url::to('@web/ypkp.png').'" width="50"></td>
								<td>
								<b>UNIVERSITAS SANGGA BUANA YPKP</b>
								<p><small>Jl. PHH Mustopa No 68, Telp. (022) 7202233. Bandung 40124 
								&nbsp;E-mail : sim@usbypkp.ac.id. Homepage : www.usbypkp.ac.id</small></p>
									'.'
								</td>
							</tr>
						</table>'					
					],
					'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'||'.date('r').' - Page {PAGENO}'],
				]
			]);
			return $pdf->render(); 			
        }
		return $this->redirect(['prodi\index']);
	
	}

		
    public function actionAjrCreate(){
		return $this->redirect(['ajr']);
        $model = new BobotNilai;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Menambah Data Pengajar($model->id) ",$model,$model->id,'c');
            return $this->redirect(['ajr-view', 'id' => $model->id]);
        } else {
            return $this->render('ajr_create', [
                'model' => $model,
            ]);
        }
    }
	/*end ajar */

	/* Mahasiswa */
    public function actionMhs()
    {
        $searchModel = new MahasiswaSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(),['tbl_mahasiswa.jr_id'=>$this->J()]);

        return $this->render('mhs_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    public function actionMhsView($id){
        $model 	=  Mahasiswa::findOne($id);

        $searchKrs 	= new KrsSearch;
        $dataKrs 	= $searchKrs->search(Yii::$app->request->getQueryParams());

		$ModKe	=  KPembayarankrs::find()
		->where(['nim'=>$id])
		->orderBy(['substring(tahun,2,2)'=>SORT_ASC,'substring(tahun,1,1)'=>SORT_ASC,])
		;
		$ModKe = new ActiveDataProvider([
            'query' => $ModKe,
        ]);

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
			\app\models\Funct::LOGS("Mengubah Data Mahasiswa($model->mhs_nim) ",new Mahasiswa,$model->mhs_nim,'u');
        	return $this->redirect(['mhs-view', 'id' => $model->mhs_nim]);
        } else {
        	return $this->render('mhs_view', [
					'model' => $model,
					'ThnAkdm'=>$dataProvider,
					'ModKe'=>$ModKe,
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
			->select(' kln.kr_kode, krs.mhs_nim , bn.mtk_kode ,mk.mtk_nama, mk.mtk_sks,krs_grade,ds_nidn')
			->from('tbl_krs krs, tbl_jadwal jd,tbl_bobot_nilai bn, tbl_kalender kln, tbl_matkul mk')
			->where("
				krs.jdwl_id = jd.jdwl_id 
				and bn.id=jd.bn_id
				and bn.mtk_kode=mk.mtk_kode
				and bn.kln_id=kln.kln_id
				and mhs_nim='$model->mhs_nim'
				and bn.kln_id='$kode'
				
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
			$ITEM[$n]['Kode']=$d['mtk_kode'];
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
		
		$IPK = $TotGrade/$TotKrs;
		$dataProvider = new ArrayDataProvider([
			'allModels'=>$ITEM,
			'pagination' => [
        		'pageSize' => 20,
    		],

		]);


        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
			\app\models\Funct::LOGS("Mengubah Data Mahasiswa($model->mhs_nim) ",new Mahasiswa,$model->mhs_nim,'u');
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

    public function actionMhsCreate(){
        $model = new Mahasiswa;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Menambah Data Mahasiswa($model->mhs_nim) ",$model,$model->mhs_nim,'c');
            return $this->redirect(['mhs-view', 'id' => $model->mhs_nim]);
        } else {
            return $this->render('mhs_create', [
                'model' => $model,
				
            ]);
        }
    }

    public function actionMhsUpdate($id){
        $model =  Mahasiswa::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Mengubah Data Mahasiswa($id) ",new Mahasiswa,$id,'u');
            return $this->redirect(['mhs-view', 'id' => $model->mhs_nim]);
        } else {
            return $this->render('mhs_update', [
                'model' => $model,
            ]);
        }
    }

    public function actionMhsDelete($id){
        $model=$this->findModel($id);
		$model->RStat=1;
		$model->save();
		\app\models\Funct::LOGS("Menghapus Data Mahasiswa($id) ",new Mahasiswa,$id,'d');
        return $this->redirect(['index']);
    }
	/* end Mahasiswa */
	
	/* KRS */
    public function actionKrs(){
        $searchModel = new KrsSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('krs_index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionKrsView($id)
    {
        $model = Krs::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	return $this->redirect(['krs-view', 'id' => $model->krs_id]);
        } else {
        	return $this->render('krs_view', ['model' => $model]);
		}
    }

    public function actionKrsCreate(){
        $model = new Krs;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Menambah Data KRS($model->krs_id) ",$model,$model->krs_id,'c');
            return $this->redirect(['view', 'id' => $model->krs_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionKrsUpdate($id){
        $model=Krs::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\app\models\Funct::LOGS("Mengubah Data KRS($model->krs_id) ",new Krs,$id,'u');
            return $this->redirect(['view', 'id' => $model->krs_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionKrsDelete($id){
        $model=Krs::findOne($id);
		$model->RStat=1;
		if($model->save()){
			\app\models\Funct::LOGS("Menghapus Data KRS($id) ",new Krs,$id,'d');	
		}
        return $this->redirect(['index']);
    }
	/* end KRS */
	

    public function actionView($id){
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Fakultas();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->fk_id]);
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
            return $this->redirect(['view', 'id' => $model->fk_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model=$this->findModel($id);
		$model->RStat=1;
		$model->save();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Fakultas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

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
                foreach ($list as $i => $kota) {
                    $out[] = ['id' => $kota['mtk_kode'], 'name' => $kota['mtk_kode']." ". $kota['mtk_nama'] ];
                    if ($i == 0) {
                        $selected = $kota['mtk_kode'];
                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }

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
                    <h4><b>Data Jadwal Kuliah</b></h4>
                    <table class="table table-striped table-bordered">
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

    public function actionReportDosenWali() {
 
        $searchModel = Dosen::find()->select('*');
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
                    <h4><b>Data Wali Dosen</b></h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Jurusan</th>
                                <th>Program</th>
                                <th>Dosen Wali</th>
                                <th>Total Mahasiswa</th>
                            </tr>
                        </thead>
                    ';
        foreach ($models as $key => $value) {
            $content .= '
                    <tbody>
                        <tr>
                            <td>'.$value->jr->jr_jenjang.'-'.$value->jr->jr_nama.'</td>
                            <td>'.$value->pr->pr_nama.'</td>
                            <td>'.$value->ds_nm.'</td>
                            <td>'.$value->tot.'</td>
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
                'SetHeader'=>['Data Dosen Wali - Universitas Sangga Buana YPKP Bandung'], 
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

// prodi
	public function actionPerkuliahan(){
		$Id	= Yii::$app->user->identity->username;
		$Fid=Yii::$app->user->identity->Fid;
		
		$QueTahun="select * from tbl_kalender kln, tbl_kurikulum kr 
			where kln.kr_kode=kr.kr_kode
			and (GETDATE()
			BETWEEN kln_masuk and DATEADD(day,kln.kln_uas_lama, kln.kln_uas)
			and (kln.RStat is null or kln.RStat='0'))";
		$QueKuliah=" select *, concat(dbo.cekHari(hari),' ',masuk,'-',keluar) jadwal 
			from dbo.ViewJadwalProdi(".$this->J().",GETDATE())";
		$Qthn=Yii::$app->db->createCommand($QueTahun)->queryOne();
		$QueKuliah=Yii::$app->db->createCommand($QueKuliah)->queryAll();
		$data ='<center><b>Perkuliahan Tahun Akademik Semester '."$Qthn[kr_nama] ($Qthn[kr_kode])".'</b> </center><br />';
		$data.="<div class='table-responsive'>".
		'<table class="table table-striped table-hover table-responsive">
			<thead>
				<tr>
					<th>Jadwal</th>
					<th>Dosen</th>
					<th>Matakuliah</th>
					<th>Kelas</th>
					<th> Lantai | Ruang</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
			';
		foreach($QueKuliah as $d){
			$data .='
				<tr>
					<td>'.$d['jadwal'].'</td>
					<td>'.$d['dosen'].'</td>
					<td>'.$d['matakuliah'].'</td>
					<td>'.$d['kelas'].'</td>
					<td>'.$d['lantai'].' | '.$d['ruang'].'</td>
					<td>'.$d['kehadiran'].'</td>
				</tr>';
		}
		$data.="</tbody></table></div>";
		return Json::encode($data);
	}


	public function actionKehadiran(){
		$Id		= Yii::$app->user->identity->username;
		$QueTahun="select * from tbl_kalender kln, tbl_kurikulum kr 
			where kln.kr_kode=kr.kr_kode
			and (GETDATE()
			BETWEEN kln_masuk and DATEADD(day,kln.kln_uas_lama, kln.kln_uas)
			and (kln.RStat is null or kln.RStat='0'))
		";
		$QueKuliah="EXEC R_AbsProdi'".$this->J()."',''";
		$Qthn=Yii::$app->db->createCommand($QueTahun)->queryOne();
		$QueKuliah=Yii::$app->db->createCommand($QueKuliah)->queryAll(\PDO::FETCH_NUM);
		$data ='<center><b>Kehadiran Perkuliahan Tahun Akademik Semester '."$Qthn[kr_nama] ($Qthn[kr_kode])".'</b> </center><br />';
		$n=0;
		$hd="";
		$bd='';
		foreach($QueKuliah as $d){
			$bd .='<tr>';			
			for($i=0;$i<count($d);$i++){
				
				$jadwal=$d[(count($d)-7)].", ".$d[(count($d)-6)]."-".$d[(count($d)-5)];
				if($i!=0 && $i!=1 && $i!=(count($d)-4)){
					if($n==0){
						// head table
						if($i==3){
							$hd .="
								<th rowspan='2'>Jadwal</th>
								<th rowspan='2'>Matakuliah</th>
								<th colspan='".(count($d)-3)."' align='center'>Sesi</th></tr><tr>
							";	
						}else{
							if($i>=3 and $i<(count($d)-7)){
								$hd .="<th width='1%'>".($i-3)." </th>";
							}else{
								if($i==(count($d)-3)){$hd .='<th> Total</th>';}
								if($i==(count($d)-2)){$hd .='<th> Target</th>';}
								if($i==(count($d)-1)){$hd .='<th> %</th>';}
							}
						}
					}

					// Status absen
					if($i==3){
						$bd.="<td>$jadwal</td>";
						$bd.='<td>'.$d[$i].'</td>';
					}else{
						if($i>=3 and $i<(count($d)-7)){
							$bd.='
							<td width="1%">
								<i class="glyphicon glyphicon-'.($d[$i]==0?'remove':'ok').'-circle" style="color:'.($d[$i]==0?'red':'green').';"></i>
							</td>';
						}else{
							//$bd.='<td>'.$d[$i]	.'</td>';
							if($i==(count($d)-3)){$bd.='<td>'.number_format($d[$i],0).'</td>';}
							if($i==(count($d)-2)){$bd.='<td>'.number_format($d[$i],0).'</td>';}
							if($i==(count($d)-1)){$bd.='<td>'.number_format($d[$i],1).'%</td>';}
							
						}
					}
				}
			}
			$bd.='</tr>';
			$n++;
		}
		$data.='
		<div class="table-responsive">
		<table class="table table-bordered table-hover">
			<thead><tr>'.$hd.'</tr></thead>
			<tbody>'.$bd.'</tbody></table>
		</div>
		';
		return Json::encode($data);
	}


    public function actionKehadiranDosen(){
		$kon='';
		$krKode=0;
		$mode=0;
		$render='kehadiran';
		if(isset($_GET['Thn'])){
			if(!empty($_GET['Thn']['thn'])){
				$krKode=(int)$_GET['Thn']['thn'];
				$kon=" ,'Where t.thn=".$krKode."'";
			}
			$mode = (int)$_GET['Thn']['mode'];
			
		}
		if($mode==1){
			$render='kehadiran_waktu';
		}

		$Id		= Yii::$app->user->identity->username;
		$QueTahun="select * from tbl_kalender kln, tbl_kurikulum kr 
			where kln.kr_kode=kr.kr_kode
			and kln.kr_kode='".$krKode."'
			and (kln.RStat is null or kln.RStat='0')
		";
		
		$Qthn=Yii::$app->db->createCommand($QueTahun)->queryOne();


		$QueKuliah="EXEC pvt_hdrdsn '".$this->J()."','$krKode'";
		//echo $QueKuliah;
		if($krKode>0){$QueKuliah=Yii::$app->db->createCommand($QueKuliah)->queryAll(/*\PDO::FETCH_NUM*/);}
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),['jr.jr_id'=>$this->J()]);

        return $this->render($render, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
			'QueKuliah'=>$QueKuliah,
			'Qthn'=>$Qthn,
			//'HDR'=>$This->Hadir($id),
			'KrKd'=>$krKode
			
        ]);


    }

    public function actionCetakKehadiranDosen($kr,$t='')
    {
		$kon='';
		$krKode=(int)$kr;
		if($kr){
			$krKode=(int)$kr;
			$kon=" ,'Where t.thn=".$krKode."'";
	
		}

		$Id		= Yii::$app->user->identity->username;
		$QueTahun="select * from tbl_kalender kln, tbl_kurikulum kr 
			where kln.kr_kode=kr.kr_kode
			and kln.kr_kode='".$krKode."'
			and (kln.RStat is null or kln.RStat='0')
		";
		
		$Qthn=Yii::$app->db->createCommand($QueTahun)->queryOne();
		
		$QueKuliah="EXEC pvt_hdrdsn '".$this->J()."','$krKode'";
		if($krKode>0){$QueKuliah=Yii::$app->db->createCommand($QueKuliah)->queryAll(/*\PDO::FETCH_NUM*/);}
        $searchModel = new JadwalSearch;
        $dataProvider = $searchModel->krs(Yii::$app->request->getQueryParams(),['jr.jr_id'=>$this->J()]);


		$content = $this->renderPartial('kehadiran_pdf',[
		'dataProvider' => $dataProvider,
		'searchModel' => $searchModel,
		'QueKuliah'=>$QueKuliah,
		'Qthn'=>$Qthn,
		//'HDR'=>$This->Hadir($id),
		'KrKd'=>$krKode
		]);

		if($t==1){
			$content = $this->renderPartial('kehadiran_waktu_pdf',[
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
			'QueKuliah'=>$QueKuliah,
			'Qthn'=>$Qthn,
			//'HDR'=>$This->Hadir($id),
			'KrKd'=>$krKode
			]);
		}
		

		
		//die($content);
		$pdf = new Pdf([
			'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
			'content' => $content,
			'format'=>Pdf::FORMAT_LETTER,
			'marginLeft'=>5,
			'marginRight'=>5,
			'marginTop'=>18,
			'marginHeader'=>1,
			'orientation'=>'L',
			//'destination'=>'I',
			'cssInline'=>'
			',
			'cssInline' => '
				td,th{
					padding:4px;
					font-size:8pt;
				}
			', 
			'options' => [
				'title' =>'Laporan Kehadiran Perkuliahan '.$kr,
				'watermarkText'=>'DIREKTORAT SISTEM INFORMASI & MULTIMEDIA',
				'showWatermarkText'=>true,
			],
			'methods' => [
					'SetHeader' => [
						'<table>
							<tr>
								<td><img src="'.Url::to('@web/ypkp.png').'" width="50"></td>
								<td>
								<b>UNIVERSITAS SANGGA BUANA YPKP</b>
								<p><small>Jl. PHH Mustopa No 68, Telp. (022) 7202233. Bandung 40124 
								&nbsp;E-mail : sim@usbypkp.ac.id. Homepage : www.usbypkp.ac.id</small></p>
									'.'
								</td>
							</tr>
						</table>'					
					],
					'SetFooter' => [$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'||'.date('r').' - Page {PAGENO}'],
			]
		]);			
		return $pdf->render();

    }

	
    public function actionCekHadir()
    {
		$Id		= Yii::$app->user->identity->username;
		$QueTahun="select * from tbl_kalender kln, tbl_kurikulum kr 
			where kln.kr_kode=kr.kr_kode
			and kln.kr_kode='".$krKode."'
			and (kln.RStat is null or kln.RStat='0')
		";
		
		//$Qthn=Yii::$app->db->createCommand($QueTahun)->queryOne();
		$QueKuliah="EXEC R_AbsProdi1 '".$this->J()."','',''";
		///if($krKode>0){$QueKuliah=Yii::$app->db->createCommand($QueKuliah)->queryAll(\PDO::FETCH_NUM);}
		//$QueKuliah=Yii::$app->db->createCommand($QueKuliah)->queryAll();
		//$model = new ArrayDataProvider(['allModels'=>$QueKuliah,]);            
			
		echo"<pre>";	
		print_r($_SERVER);
		echo"</pre>";
		die();
        return $this->render('kehadiran1', [
			'QueKuliah'=>$QueKuliah,
			'model'=>$model
        ]);
    }


//



}
