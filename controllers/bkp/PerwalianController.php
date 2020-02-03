<?php
namespace app\controllers;
use  yii\web\Session;
use yii\data\ActiveDataProvider;
use Yii;

use app\models\Jurusan;
use app\models\Krs;
use app\models\Mahasiswa;
use app\models\Program;
use app\models\Dosen;



use app\models\Funct;
use app\models\Akses;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use yii\filters\VerbFilter;
use yii\helpers\Json;

use yii\db\Query;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;

$connection = \Yii::$app->db;


class PerwalianController extends Controller{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    public function sub(){return Akses::akses();}


	public function actionKrsT(){
    $db     = Yii::$app->db;
    $ID     = @$_GET['nim'];
	$krs    =new Krs;
    //$jr     =Jurusan::findOne($this->J());
    $mhs    = Mahasiswa::findOne(['mhs_nim'=>$ID,]);

    $subAkses=self::sub();
    #if($subAkses){if(!isset(array_flip($subAkses['jurusan'])[$mhs->jr_id])){throw new ForbiddenHttpException("Forbidden access");}}

    $pr     =Program::findOne(@$mhs->pr_kode);
	$ds     =Dosen::findOne(@$mhs->ds_wali);
    $sqlon = "
        select 
            tbl_jadwal.*
        from 
            tbl_jadwal join tbl_krs on (tbl_krs.jdwl_id=tbl_jadwal.jdwl_id)
        where
            tbl_krs.mhs_nim='".$ID."'
    ";

    if(isset($_GET['Krs']['kurikulum'])){
        if(empty($_GET['Krs']['kurikulum'])){
        	return $this->redirect(['perwalian/krs-t']);
        }else{
        //$kr=explode('#',$_GET['Krs']['kurikulum']);
        $kr = $_GET['Krs']['kurikulum'];
        $sql="Select * from 
            tbl_krs join tbl_jadwal j on(tbl_krs.jdwl_id=j.jdwl_id)
            left join tbl_bobot_nilai bn on(j.bn_id=bn.id)
            left join tbl_kalender k on(k.kln_id=bn.kln_id)
            left join tbl_matkul m on(m.mtk_kode=bn.mtk_kode)
            left join tbl_dosen d on(d.ds_nidn=bn.ds_nidn) 
            where tbl_krs.mhs_nim='".$ID."' and k.kr_kode='$kr' and 
			(tbl_krs.RStat='0' or tbl_krs.RStat is null)
			
            ORDER BY 
			j.jdwl_hari,j.jdwl_masuk,
			m.mtk_semester ASC
            ";
            $model = new SqlDataProvider([
                    'sql'=>$sql,
                    'pagination' => [
                          'pageSize' => 0,
                     ],
                ]);
            $model1 = new SqlDataProvider([
                    'sql'=>$sqlon,
                ]);
            }
        $sks = "
        Select  
            sum(m.mtk_sks) as sks
        from 
            tbl_krs join tbl_jadwal j on(tbl_krs.jdwl_id=j.jdwl_id)
            left join tbl_bobot_nilai bn on(j.bn_id=bn.id)
            left join tbl_kalender k on(k.kln_id=bn.kln_id)
            left join tbl_matkul m on(m.mtk_kode=bn.mtk_kode)
            left join tbl_dosen d on(d.ds_nidn=bn.ds_nidn) 
            where tbl_krs.mhs_nim='".$ID."' and k.kr_kode='$kr'";
        $sks = $db->createCommand($sks)
            ->queryOne();

        }else{

            $sql="Select * from 
            tbl_krs join tbl_jadwal j on(tbl_krs.jdwl_id=j.jdwl_id)
            left join tbl_bobot_nilai bn on(j.bn_id=bn.id)
            left join tbl_kalender k on(k.kln_id=bn.kln_id)
            left join tbl_matkul m on(m.mtk_kode=bn.mtk_kode)
            left join tbl_dosen d on(d.ds_nidn=bn.ds_nidn) 
            where tbl_krs.mhs_nim='$ID' and k.kr_kode=NULL";
            //$sql="";
            $model = new SqlDataProvider([
                    'sql'=>$sql,
                ]);
            $model1 = new SqlDataProvider([
                    'sql'=>$sqlon,
                ]);
        $sks = "
        Select  
            sum(m.mtk_sks) as sks
        from 
            tbl_krs join tbl_jadwal j on(tbl_krs.jdwl_id=j.jdwl_id)
            left join tbl_bobot_nilai bn on(j.bn_id=bn.id)
            left join tbl_kalender k on(k.kln_id=bn.kln_id)
            left join tbl_matkul m on(m.mtk_kode=bn.mtk_kode)
            left join tbl_dosen d on(d.ds_nidn=bn.ds_nidn) 
            where tbl_krs.mhs_nim='".$ID."' and k.kr_kode=NULL";
        $sks = $db->createCommand($sks)
            ->queryOne();
        }

		if(@$model){
			$ThnId='';
			foreach($model->getModels() as $dat){
				$ThnId=$dat['kln_id'];
			}
		}
	
		$data = array(
			'model'=>$model,
			'ThnId'=>$ThnId,
			'model1'=>$model1,
			'mhs'=>$mhs,
			//'J'=>$this->J(),
			'jr'=>$jr,
			'pr'=>$pr,
			'ds'=>$ds,
			'ID'=>$ID,
			'krs'=>$krs,
			'sks'=>$sks,
		);
	    return $this->render('/perwalian/KRS',$data);	
	}


	public function actionTambahKrs($nim){
        $model  =	new Krs;
        $nim 	= $nim;//Yii::$app->user->identity->username;
        $mhs	= Mahasiswa::findOne($nim);
        $jr=$mhs->jr_id;
        $pr=$mhs->pr_kode;

        $subAkses=self::sub();
        if($subAkses){
            if(!isset(array_flip($subAkses['jurusan'])[$mhs->jr_id])){throw new ForbiddenHttpException("Forbidden access");}
        }

        if(isset($_GET['Krs']['kurikulum'])){
            if($_GET['Krs']['kurikulum']=='empty'){
                $this->redirect(array('getkrs'));
            }else{
                $model->attributes=$_GET['Krs'];
                $id=$_GET['Krs']['kurikulum'];
                //$kod=explode("#",$id);
                $kod = $id;
                if(empty($kod)){
                    $this->redirect(array('getkrs'));
                }
                $con    = Yii::$app->db;
                if(substr($kod,0,1)=='1')
                {
                    $gg=substr($kod,0,1);
                    $thn=substr($kod,1,4);
                    $cokot="select TOP 1 kr_kode_ from tbl_krs where SUBSTRING(kr_kode_,1,1)>$gg and SUBSTRING(kr_kode_,2,4)<$thn";
                    $cokot=$con->createCommand($cokot)->queryOne();
                }else if(substr($kod,0,1)=='2'){
                    $gg=substr($kod,0,1);
                    $thn=substr($kod,1,4);
                    $cokot="select  TOP 1 kr_kode_ from tbl_krs where SUBSTRING(kr_kode_,1,1)<$gg and SUBSTRING(kr_kode_,2,4)=$thn";
                    $cokot=$con->createCommand($cokot)->queryOne();
                } else if (substr($kod,0,1)=='3'){
                    $gg=substr($kod,0,1);
                    $thn=substr($kod,1,4);
                    $cokot="select  TOP 1 kr_kode_ from tbl_krs where SUBSTRING(kr_kode_,1,1)<$gg and SUBSTRING(kr_kode_,2,4)=$thn ORDER BY kr_kode_ desc";
                    $cokot=$con->createCommand($cokot)->queryOne();
                }
                if(empty($cokot['kurikulum'])){
                $asli="NULL";
                }else{
                    $asli=$cokot['kurikulum'];
                }

            if(substr($kod,0,1)=='2' OR substr($kod,0,1)=='1'){     
            $query = "
                select 
                    sum(CAST(tbl_matkul.mtk_sks AS INT)) as sks_  
                from 
                    tbl_krs 
                join 
                    tbl_jadwal on (tbl_jadwal.jdwl_id=tbl_krs.jdwl_id)
                    join
                    tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
                    join
                    tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
                    join
                    tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
                where
                    tbl_krs.mhs_nim='".$nim."' and tbl_krs.krs_stat='1' and tbl_kalender.kr_kode=$asli      
            ";
            $k=$con->createCommand($query)->queryOne();

            $mutu = "
             select 
                        sum(dbo.TotalMutu(krs_grade)*CAST(tbl_matkul.mtk_sks AS INTEGER)) as krs_grade 
                    from 
                        tbl_krs 
                    join 
                        tbl_jadwal on (tbl_jadwal.jdwl_id=tbl_krs.jdwl_id)
                        join
                        tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
                        join
                        tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
                        join
                        tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
                    where
                        tbl_kalender.kr_kode<$kod and
                         tbl_krs.mhs_nim='$nim'
            ";
            $mutu=$con->createCommand($mutu)->queryOne();
            
            $ada="SKS $pr,$kod,$jr";
            $ada=$con->createCommand($ada)->queryOne();
            $ambil="sksambil $nim,$kod";
            $ambil=$con->createCommand($ambil)->queryOne();

/*
						select tbl_jadwal.*,mt.*,pr.*,ds.*,r.*,k.*
							,dbo.subJdwl(tbl_jadwal.jdwl_id) jadwal
							,isnull(dbo.cekIgMk(bn.mtk_kode),0) Ig
							,isnull(dbo.ValidasiKrs(tbl_jadwal.jdwl_id,'$nim'),0) AvKrs

*/
           $sql = "
                select tbl_jadwal.*,mt.*,pr.*,ds.*,r.*,k.*
					 ,dbo.subJdwl(tbl_jadwal.jdwl_id) jadwal
					 ,isnull(dbo.cekIgMk(bn.mtk_kode),0) Ig
					 ,isnull(dbo.ValidasiKrs(tbl_jadwal.jdwl_id,'$nim'),0) AvKrs
					 ,t.tot
                    from tbl_jadwal
                    INNER JOIN tbl_bobot_nilai bn ON bn.id=tbl_jadwal.bn_id and isnull(bn.RStat,0)=0
                    INNER JOIN tbl_kalender k ON k.kln_id=bn.kln_id  and isnull(k.RStat,0)=0
                    INNER JOIN tbl_program pr ON pr.pr_kode=k.pr_kode
                    INNER JOIN tbl_dosen ds ON ds.ds_id=bn.ds_nidn  and isnull(ds.RStat,0)=0
                    INNER JOIN tbl_jurusan jr ON k.jr_id=jr.jr_id
                    INNER JOIN tbl_matkul mt ON jr.jr_id=mt.jr_id  and isnull(mt.RStat,0)=0
                    INNER JOIN tbl_ruang r ON r.rg_kode=tbl_jadwal.rg_kode  and isnull(r.RStat,0)=0              
					LEFT JOIN (
						select isnull(jd.GKode,jd.jdwl_id) GKode, count(krs.krs_id) tot 
						from tbl_krs krs
						inner join tbl_jadwal jd on(jd.jdwl_id=krs.jdwl_id and isnull(jd.RStat,0)=0) 
						where kr_kode_='$kod' and isnull(krs.RStat,0)=0 group by isnull(jd.GKode,jd.jdwl_id)
					) t
					on(t.GKode= isnull(tbl_jadwal.GKode,tbl_jadwal.jdwl_id))
					

                where
						
                    pr.pr_kode='".$pr."' and k.kr_kode='".$kod."' and jr.jr_id='".$jr."' and bn.mtk_kode=mt.mtk_kode
					and isnull(tbl_jadwal.RStat,0)=0
                ORDER BY mt.mtk_semester ASC
            ";

					$sql1 = "
						select tbl_jadwal.*,mt.*,pr.*,ds.*,r.*,k.*
							,dbo.subJdwl(tbl_jadwal.jdwl_id) jadwal
							,dbo.cekIgMk(bn.mtk_kode) Ig
							,isnull(dbo.ValidasiKrs(tbl_jadwal.jdwl_id,'$nim'),0) AvKrs
							,t.tot
						from 
						tbl_jadwal
						JOIN tbl_bobot_nilai bn ON bn.id=tbl_jadwal.bn_id 
						JOIN tbl_kalender k ON k.kln_id=bn.kln_id
						JOIN tbl_program pr ON pr.pr_kode=k.pr_kode
						JOIN tbl_dosen ds ON ds.ds_id=bn.ds_nidn
						JOIN tbl_jurusan jr ON k.jr_id=jr.jr_id
						JOIN tbl_matkul mt ON jr.jr_id=mt.jr_id
						JOIN tbl_ruang r ON r.rg_kode=tbl_jadwal.rg_kode                    
						where
							pr.pr_kode='".$pr."' and k.kr_kode='".$kod."' and jr.jr_id='".$jr."' and bn.mtk_kode=mt.mtk_kode
						and (
							( bn.RStat is null or bn.RStat= 0 )
							and ( tbl_jadwal.RStat is null or tbl_jadwal.RStat= 0 )	
						)
						and isnull(tbl_jadwal.jdwl_parent,tbl_jadwal.jdwl_id)=tbl_jadwal.jdwl_id
						ORDER BY mt.mtk_semester ASC
					";
                $dataProvider = new SqlDataProvider([
                    'sql'=>$sql,
                    'pagination' => [
                          'pageSize' => 0,
                     ],
                ]);

           // print_r($dataProvider->getModels());die();
		   
		   $KR = \app\models\Kurikulum::findOne($kod);

            return $this->render('ins2',array(
            'model'=>$model,
            'MHS'=>$mhs,
			'data'=>$dataProvider,
            'k'=>$k,
            'KR'=>$KR,
            'mutu'=>$mutu,
            'ada'=>$ada,
            'ambil'=>$ambil
        ));
        }else{
            
            $query = "
                select 
                    sum(CAST(tbl_matkul.mtk_sks AS INT)) as sks_  
                from 
                    tbl_krs 
                join 
                    tbl_jadwal on (tbl_jadwal.jdwl_id=tbl_krs.jdwl_id)
                    join
                    tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
                    join
                    tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
                    join
                    tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
                where
                    tbl_krs.mhs_nim='".$nim."' and tbl_krs.krs_stat='1' and tbl_kalender.kr_kode=$asli      
            ";
            $k=$con->createCommand($query)->queryOne();

            $mutu = "
             select 
                        sum(dbo.TotalMutu(krs_grade)*CAST(tbl_matkul.mtk_sks AS INTEGER)) as krs_grade 
                    from 
                        tbl_krs 
                    join 
                        tbl_jadwal on (tbl_jadwal.jdwl_id=tbl_krs.jdwl_id)
                        join
                        tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
                        join
                        tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
                        join
                        tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
                    where
                        tbl_kalender.kr_kode=NULL and tbl_krs.mhs_nim='$nim'
            ";
            $mutu=$con->createCommand($mutu)->queryOne();

            $ada="SKS $pr,$kod[1],$jr";
            $ada=$con->createCommand($ada)->queryOne();
            $ambil="sksambil $nim,$kod[1]";
            $ambil=$con->createCommand($ambil)->queryOne();


           $sql = "
                select tbl_jadwal.*,mt.*,pr.*,ds.*,r.*,k.*
                    from 
                    tbl_jadwal
                    JOIN tbl_bobot_nilai bn ON bn.id=tbl_jadwal.bn_id 
                    JOIN tbl_kalender k ON k.kln_id=bn.kln_id
                    JOIN tbl_program pr ON pr.pr_kode=k.pr_kode
                    JOIN tbl_dosen ds ON ds.ds_id=bn.ds_nidn
                    JOIN tbl_jurusan jr ON k.jr_id=jr.jr_id
                    JOIN tbl_matkul mt ON jr.jr_id=mt.jr_id
                    JOIN tbl_ruang r ON r.rg_kode=tbl_jadwal.rg_kode                    
                where
                    pr.pr_kode='".$pr."' and k.kr_kode='".$kod."' and jr.jr_id='".$jr."' and bn.mtk_kode=mt.mtk_kode
                ORDER BY mt.mtk_semester ASC
            ";

                $dataProvider = new SqlDataProvider([
                    'sql'=>$sql,
                    'pagination' => [
                          'pageSize' => 0,
                     ],
                ]);            

            $con    = Yii::$app->db;
            $ada="SKS $pr,NULL,$jr";
            $ada=$con->createCommand($ada)->queryOne();
            $ambil="sksambil $nim,NULL";
            $ambil=$con->createCommand($ambil)->queryOne();
            
             return $this->render('ins2',array(
            'model'=>$model,
            'data'=>$dataProvider,
            'k'=>$k,
            'mutu'=>$mutu,
            'ada'=>$ada,
            'ambil'=>$ambil
        ));  
        }
    }

        }else{
            
            $sql="
                select tbl_jadwal.* 
                from 
                    tbl_jadwal
                INNER JOIN 
                   tbl_bobot_nilai bn ON bn.id=tbl_jadwal.bn_id 
                    INNER JOIN tbl_kalender k ON k.kln_id=bn.kln_id
                    INNER JOIN tbl_program pr ON pr.pr_kode=k.pr_kode
                where
                    pr.pr_kode='".$pr."' and k.kr_kode=NULL
            ";
            $dataProvider = new SqlDataProvider([
                    'sql'=>$sql,
                ]);

            $con    = Yii::$app->db;
            $ada="SKS $pr,NULL,$jr";
            //$ada=$con->createCommand($ada)->queryRow();
            $ada = $con->createCommand($ada)
                   ->queryOne();            
            $ambil="sksambil $nim,NULL";
            //$ambil=$con->createCommand($ambil)->queryRow();
            $ambil = $con->createCommand($ada)
                   ->queryOne();
            
            return $this->render('ins2',array(
            'model'=>$model,
            'data'=>$dataProvider,
            'ada'=>$ada,
            'ambil'=>$ambil
        ));    
    }
}    


     public function actionSimpanKrs(){
        $k = $_POST['kur'];
        $nim = $_POST['nim'];
		if(isset($_POST['jdwl'])){
			//die();
            $con = Yii::$app->db;
            $k = $_POST['kur'];
            $maks = $_POST['ambil'];
            $jd = $_POST['jdwl'];
            $sks = array();
            $sks = $_POST['sks'];
            $mtk = array();
            $mtk = $_POST['mtk'];
            $mtk_nm = array();
            $mtk_nm = $_POST['mtk_nm'];
            $kr = array();
            $kr = $_POST['kr'];
            $nidn = array();
            $nidn = $_POST['nidn'];
            $ds_nm = array();
            $ds_nm = $_POST['ds_nm'];
            $query = "
                select 
                    sum(CAST(tbl_matkul.mtk_sks AS INT)) as sks_  
                from 
                    tbl_krs 
                join 
                    tbl_jadwal on (tbl_jadwal.jdwl_id=tbl_krs.jdwl_id)
                    join
                    tbl_bobot_nilai on (tbl_bobot_nilai.id=tbl_jadwal.bn_id)
                    join
                    tbl_kalender on (tbl_kalender.kln_id=tbl_bobot_nilai.kln_id)
                    join
                    tbl_matkul on (tbl_matkul.mtk_kode=tbl_bobot_nilai.mtk_kode)
                where
                    tbl_krs.mhs_nim='".$nim."' and tbl_kalender.kr_kode=$k     
            ";
            $q=$con->createCommand($query)->queryOne();
			$cJdw="";$sJdw=1;
			$cuid=Yii::$app->user->identity->id;
			
			$tot = 0+$q['sks_'];
			//var_dump($query);die();
            $mhs=Mahasiswa::findOne($nim);
			foreach($jd as $a){

				$cek = Krs::find()->where(" mhs_nim='$mhs->mhs_nim' and jdwl_id='$a' and (RStat is null or RStat='0')")->count();
				if($cek==0){
					$que ="select 
					dbo.avKrs_v1('".$mhs->mhs_nim."','".$mtk[$a]."','".$kr[$a]."') avKrsMk
					,dbo.cekIgMk(bn.mtk_kode) Ig
					,isnull(dbo.ValidasiKrs(j.jdwl_id,'$mhs->mhs_nim'),0) AvKrs
					from tbl_jadwal j,tbl_bobot_nilai bn where bn.id=j.bn_id and jdwl_id='$a'
					";
					$cekJdw=Yii::$app->db->createCommand($que)->queryOne();
					if($cekJdw && $cekJdw['Ig']==0){if($cekJdw['AvKrs']==0){$sJdw=0;$cJdw.=" $mtk[$a], ";}}
					
					if($sJdw==1){
						$model=new Krs;        
						$model->jdwl_id=$a;
						$model->mhs_nim=$mhs->mhs_nim;
						$model->krs_tgl=date('Y-m-d h:i:s');
						$model->kr_kode_ = $kr[$a];
						$model->ds_nidn_ = $nidn[$a];
						$model->ds_nm_ = $ds_nm[$a];
						$model->mtk_kode_ = $mtk[$a];
						$model->mtk_nama_ = $mtk_nm[$a];
						$model->sks_ = $sks[$a];
						$model->krs_stat ='1';
						$model->cuid =$cuid;
				
						if($model->save(false)){
							if($cekJdw && $cekJdw['Ig']==0){$tot +=$model->sks_;}								
							\app\models\Funct::LOGS("Manambah Data Krs ",$model,$model->krs_id,'c',false);
						}
					} 
				
					$sJdw=1;
					
				}
			}     
			
			if($cJdw!=''){
				Yii::$app->getSession()->setFlash('error',"Bentrok Jam Perkuliah Dengan Kode MK. ".substr($cJdw,0,-1));
				return $this->redirect(['/perwalian/tambah-krs','Krs[kurikulum]'=>$k]);
			}
            //return Yii::$app->getResponse()->redirect(['/perwalian/krs-t','Krs[kurikulum]'=>$k]);

            return Yii::$app->getResponse()->redirect(['/perwalian/krs-t','Krs[kurikulum]'=>$k,'nim'=>$nim]);
        }else{
            return Yii::$app->getResponse()->redirect(['/perwalian/tambah-krs','Krs[kurikulum]'=>$k,'nim'=>$nim]);
        }
    }

    public function actionDeleteKrs($id,$kurikulum=''){
        $con=Yii::$app->db;
        $duid = Yii::$app->user->identity->id;

        //$sql="delete from tbl_krs where krs_id=$id";
        $sql="update tbl_krs set RStat='1',krs_stat='0',duid='$duid',dtgl=getdate() where krs_id=$id";
        $command=$con->createCommand($sql)->execute();

        if($command){
            \app\models\Funct::LOGS("Menghapus Data Krs($id) ",new Krs,$id,'d');
        }
        return $this->redirect(array('perwalian/krs-t','Krs[kurikulum]' => $kurikulum,'nim'=>$_GET['nim']));
    }




}
