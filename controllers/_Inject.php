<?php
namespace app\controllers;
use yii\web\Session;
use yii\data\ActiveDataProvider;
use Yii;

use app\models\Krs;
use app\models\KrsSearch;



use app\models\Absensi;
use app\models\Matkul;
use app\models\MatkulSearch;

use kartik\mpdf\Pdf;
use yii\helpers\Url;


use yii\data\SqlDataProvider;


use app\models\KPembayarankrs;

use app\models\Funct;


use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

use yii\db\Query;
use yii\data\ArrayDataProvider;

$connection = \Yii::$app->db;


class _Inject
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

	#===
	public function reKrsBentrok($kr=''){
		$sql="
			SELECT distinct mhs_nim NPM
			FROM tbl_krs krs 
			WHERE ISNULL(krs.RStat,0)=0
			AND dbo.mhsBentrokKrs(krs.mhs_nim,krs.kr_kode_,krs.jdwl_id)=1
			and krs.kr_kode_=$kr
			AND dbo.cekIgMk(krs.mtk_kode_)=0
			order by krs.mhs_nim
		 ";
		 /*
		 print_r($sql);
		 die();
		 #*/
		$sql=Yii::$app->db->createCommand($sql)->queryAll();
		if($sql){
			foreach($sql as $d){
				// hapus data KRS awal Mahasiswa
				$qKrs="
					-- /*select krs.*
					update krs set krs.sys_='R',krs.RStat='1'
					from tbl_krs krs 
					INNER JOIN tbl_jadwal jd on(jd.jdwl_id=krs.jdwl_id and ISNULL(jd.RStat,0)=0)
					INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and ISNULL(bn.RStat,0)=0)
					INNER JOIN tbl_kalender kl on(kl.kln_id=bn.kln_id and ISNULL(kl.RStat,0)=0 and kl.kr_kode='$kr')
					where krs.mhs_nim='$d[NPM]'
					and isnull(krs.RStat,0)=0
				";
				
				$qKrs=Yii::$app->db->createCommand($qKrs)->execute();
				//=======

				if($qKrs){
					// Cari data krs mahasiswa yang sudah di hapus
					$qKrs_="
						select krs.jdwl_id,krs.mhs_nim,getdate() tgl,'$kr' thn,bn.ds_nidn,ds.ds_nm,mtk.mtk_kode,mtk.mtk_nama,mtk.mtk_sks
						from tbl_krs krs 
						INNER JOIN tbl_jadwal jd on(jd.jdwl_id=krs.jdwl_id and ISNULL(jd.RStat,0)=0)
						INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and ISNULL(bn.RStat,0)=0)
						INNER JOIN tbl_dosen ds on(ds.ds_id=bn.ds_nidn and ISNULL(ds.RStat,0)=0)
						INNER JOIN tbl_kalender kl on(kl.kln_id=bn.kln_id and ISNULL(kl.RStat,0)=0 and kl.kr_kode='$kr')
						INNER JOIN tbl_matkul mtk on(mtk.mtk_kode=bn.mtk_kode and ISNULL(mtk.RStat,0)=0)
						where krs.mhs_nim='$d[NPM]'
						and krs.sys_='R'
					";
					
					$qKrs_=Yii::$app->db->createCommand($qKrs_)->queryAll();
					$cJdw="";$sJdw=1;
					$tot=0;
					foreach($qKrs_ as $d_){
						// Cek krs yang sudah ada
						$cek = Krs::find()->where(" mhs_nim='$d_[mhs_nim_]' and jdwl_id='$d_[jdwl_id]' and isnull(RStat,0)=0 ")->count();
						if($cek==0){
							$que ="select 
							dbo.avKrs_v1('".$d_['mhs_nim']."','". $d_['mtk_kode']."','".$d_['thn']."') avKrsMk
							,dbo.cekIgMk(bn.mtk_kode) Ig
							,isnull(dbo.ValidasiKrs(j.jdwl_id,'$d_[mhs_nim]'),0) AvKrs
							from tbl_jadwal j,tbl_bobot_nilai bn where bn.id=j.bn_id and jdwl_id='$d_[jdwl_id]'
							";
							$cekJdw=Yii::$app->db->createCommand($que)->queryOne();
							if($cekJdw && $cekJdw['Ig']==0){
								if($cekJdw['AvKrs']==0){$sJdw=0;$cJdw.=" $d_[mtk_kode], ";}
							}
							if($tot<=24){
								if($sJdw==1){
									$model=new Krs;        
									#/*
									$model->jdwl_id		= $d_['jdwl_id'];
									$model->mhs_nim		= $d_['mhs_nim'];
									$model->krs_tgl		= $d_['tgl'];
									$model->kr_kode_	= $d_['thn'];
									$model->ds_nidn_ 	= $d_['ds_nidn'];
									$model->ds_nm_ 		= $d_['ds_nm'];
									$model->mtk_kode_ 	= $d_['mtk_kode'];
									$model->mtk_nama_ 	= $d_['mtk_nama'];
									$model->sks_ 		= $d_['mtk_sks'];
									#*/
									if($model->save(false)){
										if($cekJdw && $cekJdw['Ig']==0){
											$tot +=$d_['mtk_sks'];
										}
									}
								} 
							 }else{echo"SKS $d_[mhs_nim] melebihi Batas<br />";}
						}
						$sJdw=1;
					}
					if($cJdw!=''){
						echo "Bentrok Jam Perkuliah $d[NPM] Dengan Kode MK. ".substr($cJdw,0,-1)."<br />";
					}else{
						echo "ReKrs $d[NPM] Sukses<br /> ";
						
					}
				}
			}		 
		}
	}
	#===/

}
