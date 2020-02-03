<?php
namespace app\controllers;
use  yii\web\Session;
use yii\data\ActiveDataProvider;
use Yii;

use app\models\Ruang;

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
use app\models\JadwalDosen;

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


use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

use yii\db\Query;
use yii\data\ArrayDataProvider;

$connection = \Yii::$app->db;


class FormController extends Controller{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }
	
	public function actionIndex(){return $this->render('@app/views/site/index');}

    public function actionCetakAbsensi($id,$jenis,$t=''){
        $ModJdw=Jadwal::findOne($id);
        // absensi berdasarkan data finger

        $tgl="jdwl_uts";
        $rg = 'jd.rg_uts';

        if ($jenis==1) {
            $jenis = "UJIAN TENGAH SEMESTER";
            $rg = 'jd.rg_uts';
        }else{
            $tgl="jdwl_uas";
            $jenis = "UJIAN AKHIR SEMESTER";
            $rg = 'jd.rg_uas';
        }


        $sql ="SELECT
                    matakuliah = mk.mtk_kode + ' / ' + mk.mtk_nama,
                    dosen = ds.ds_nm,
                    tanggal = CAST (jd.$tgl AS DATE),
                    jam = CAST (jd.$tgl AS TIME(0)),
                    ruang = rg.rg_nama,
                    kode = jr.jr_id,
                    kelas = jd.jdwl_kls,
                    program = UPPER(pr.pr_nama),
                    jurusan = UPPER( jr.jr_jenjang +' '+ jr.jr_nama),
                    semester = kr.kr_nama,
                    kl.kr_kode
                FROM
                    tbl_jadwal jd
                JOIN tbl_bobot_nilai bn ON bn.id = jd.bn_id
                JOIN tbl_matkul mk ON mk.mtk_kode = bn.mtk_kode
                JOIN tbl_dosen ds ON ds.ds_id = bn.ds_nidn
                JOIN tbl_ruang rg ON rg.rg_kode = $rg
                JOIN tbl_kalender kl ON kl.kln_id = bn.kln_id
                JOIN tbl_program pr on pr.pr_kode = kl.pr_kode
                JOIN tbl_jurusan jr on jr.jr_id = kl.jr_id
                JOIN tbl_kurikulum kr on kr.kr_kode = kl.kr_kode
                WHERE jd.jdwl_id = $id";


        $header =   Yii::$app->db->createCommand($sql)->queryOne();
        #if (empty($header)) {die("JADWAL UJIAN BELUM DI SETTING");}


        $db = Yii::$app->db1;
        $keuangan = Funct::getDsnAttribute('dbname', $db->dsn);
        $vd=0;
        if ($jenis=='UJIAN AKHIR SEMESTER') {
            $vd=1;
            /*
            $sql = "SELECT DISTINCT kr.mhs_nim, IIF(ISNULL(lunas,'')<>'' OR SUBSTRING(kr.mhs_nim,11,1) IN(3,4)
            or SUBSTRING(kr.mhs_nim,1,4) in('B202','A201','A202','2212')
            , p.Nama,'')as nama,
                    absen =( select sum(iif(jdwl_stat=1,1,0)) from tbl_absensi where kr.krs_id=krs_id )
                    FROM tbl_absensi ab
                    JOIN tbl_krs kr on kr.krs_id = ab.krs_id
                    JOIN tbl_mahasiswa mh ON mh.mhs_nim = kr.mhs_nim
                    JOIN tbl_jadwal jd on jd.jdwl_id = kr.jdwl_id
                    JOIN tbl_bobot_nilai bn on bn.id = jd.bn_id
                    JOIN $keuangan.dbo.student s ON s.nim COLLATE Latin1_General_CI_AS = mh.mhs_nim
                    JOIN $keuangan.dbo.people p ON p.No_Registrasi = s.no_registrasi
                    LEFT JOIN (SELECT nim, lunas ='OK' FROM $keuangan.dbo.pembayarankrs WHERE tahun = '$header[kr_kode]'
                               AND (status = 'Lunas' or sisa  <= 0)) ht
                    ON ht.nim COLLATE Latin1_General_CI_AS = kr.mhs_nim
                    WHERE kr.jdwl_id = $id
                    and(
                        (kr.RStat is null or kr.RStat='0')
                        and (mh.RStat is null or mh.RStat='0')
                    )
                    ORDER BY kr.mhs_nim
                    ";
            */
            if($t==''){
                if(strtoupper($ModJdw->bn->kln->jr->jr_jenjang=='S2' || $ModJdw->bn->kln->pr_kode=='6' || $ModJdw->bn->kln->pr_kode=='7')){
                    $vd=0;#$sql="EXEC dbo.CetakAbsenKuliah_v2 $id";
                }
                /*
                if($ModJdw->bn->kln->pr_kode=='6' || $ModJdw->bn->kln->pr_kode=='7'){
                    $sql="
                    SELECT DISTINCT kr.mhs_nim, IIF(ISNULL(lunas,'')<>'' OR SUBSTRING(kr.mhs_nim,11,1) IN(3,4)
                        or SUBSTRING(kr.mhs_nim,1,4) in('B202','A201','A202') or mh.pr_kode in(6,7)
                    , p.Nama,'')as nama,
                    --absen =( select sum(iif(jdwl_stat=1,1,0)) from tbl_absensi where kr.krs_id=krs_id )
                    absen = 0
                    FROM -- tbl_absensi ab JOIN
                    tbl_krs kr  -- on kr.krs_id = ab.krs_id
                    JOIN tbl_mahasiswa mh ON mh.mhs_nim = kr.mhs_nim
                    JOIN tbl_jadwal jd on jd.jdwl_id = kr.jdwl_id
                    JOIN tbl_bobot_nilai bn on bn.id = jd.bn_id
                    JOIN $keuangan.dbo.student s ON s.nim COLLATE Latin1_General_CI_AS = mh.mhs_nim
                    JOIN $keuangan.dbo.people p ON p.No_Registrasi = s.no_registrasi
                    LEFT JOIN (SELECT nim, lunas ='OK' FROM $keuangan.dbo.pembayarankrs WHERE tahun = '$header[kr_kode]'
                               AND (status = 'Lunas' or sisa  <= 0)) ht
                    ON ht.nim COLLATE Latin1_General_CI_AS = kr.mhs_nim
                    WHERE kr.jdwl_id = $id
                    and(
                        (kr.RStat is null or kr.RStat='0')
                        and (mh.RStat is null or mh.RStat='0')
                    )
                    ORDER BY kr.mhs_nim
                    ";

                }
                */
                $sql=" EXEC dbo.cetakAbsenUjian $id,".$ModJdw->bn->kln->kr_kode.",$vd ";
            }
        }else{
            #$sql="EXEC dbo.CetakAbsenKuliah_v2 $id";
            $sql="EXEC dbo.cetakAbsenUjian $id,".$ModJdw->bn->kln->kr_kode.",$vd ";
        }


        #Funct::v($sql);

        /*
        echo"<pre>";
        print_r($sql);
        echo"</pre>";
        die();
        #*/

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        return $this->renderPartial('cetak_absensi', [
            'data' => $data,
            'header' => $header,
            'jenis' => $jenis,
        ]);

    }

    public function actionCetakAbsensiDev($id,$jenis,$t=''){
			// absensi berdasarkan data finger
			
			$tgl="jdwl_uts";
			$rg = 'jd.rg_uts';

            if ($jenis==1) {
                $jenis = "UJIAN TENGAH SEMESTER";
                $rg = 'jd.rg_uts';
            }else{
				$tgl="jdwl_uas";
                $jenis = "UJIAN AKHIR SEMESTER";
                $rg = 'jd.rg_uas';
            }
            
            
            $sql ="SELECT
                    matakuliah = mk.mtk_kode + ' / ' + mk.mtk_nama,
                    dosen = ds.ds_nm,
                    tanggal = CAST (jd.$tgl AS DATE),
                    jam = CAST (jd.$tgl AS TIME(0)),
                    ruang = rg.rg_nama,
                    kode = jr.jr_id,
                    kelas = jd.jdwl_kls,
                    program = UPPER(pr.pr_nama),
                    jurusan = UPPER( jr.jr_jenjang +' '+ jr.jr_nama),
                    semester = kr.kr_nama,
                    kl.kr_kode
                FROM
                    tbl_jadwal jd
                JOIN tbl_bobot_nilai bn ON bn.id = jd.bn_id
                JOIN tbl_matkul mk ON mk.mtk_kode = bn.mtk_kode
                JOIN tbl_dosen ds ON ds.ds_id = bn.ds_nidn
                JOIN tbl_ruang rg ON rg.rg_kode = $rg
                JOIN tbl_kalender kl ON kl.kln_id = bn.kln_id
                JOIN tbl_program pr on pr.pr_kode = kl.pr_kode
                JOIN tbl_jurusan jr on jr.jr_id = kl.jr_id
                JOIN tbl_kurikulum kr on kr.kr_kode = kl.kr_kode
                WHERE jd.jdwl_id = $id";


            $header =   Yii::$app->db->createCommand($sql)->queryOne();     
            if (empty($header)) {
                die("JADWAL UJIAN BELUM DI SETTING");
            }
                    

            $db = Yii::$app->db1;
            $keuangan = Funct::getDsnAttribute('dbname', $db->dsn);

            if ($jenis=='UJIAN AKHIR SEMESTER') {

            	$sql = "SELECT DISTINCT kr.mhs_nim, IIF(ISNULL(lunas,'')<>'' OR SUBSTRING(kr.mhs_nim,11,1) IN(3,4)
				or SUBSTRING(kr.mhs_nim,1,4) in('B202','A201','A202','2212')
				, p.Nama,'')as nama, 
            			absen =( select sum(iif(jdwl_stat=1,1,0)) from tbl_absensi where kr.krs_id=krs_id )
						FROM tbl_absensi ab 
						JOIN tbl_krs kr on kr.krs_id = ab.krs_id 
						JOIN tbl_mahasiswa mh ON mh.mhs_nim = kr.mhs_nim 
						JOIN tbl_jadwal jd on jd.jdwl_id = kr.jdwl_id
						JOIN tbl_bobot_nilai bn on bn.id = jd.bn_id
						JOIN $keuangan.dbo.student s ON s.nim COLLATE Latin1_General_CI_AS = mh.mhs_nim 
						JOIN $keuangan.dbo.people p ON p.No_Registrasi = s.no_registrasi
						LEFT JOIN (SELECT nim, lunas ='OK' FROM $keuangan.dbo.pembayarankrs WHERE tahun = '$header[kr_kode]' 
							       AND (status = 'Lunas' or sisa  <= 0)) ht
						ON ht.nim COLLATE Latin1_General_CI_AS = kr.mhs_nim
						WHERE kr.jdwl_id = $id
						and(
							(kr.RStat is null or kr.RStat='0')
							and (mh.RStat is null or mh.RStat='0')
						)
						ORDER BY kr.mhs_nim
						";						
						
				if($t==''){
					$ModJdw=Jadwal::findOne($id);
					echo "<!-- as ".$ModJdw	->bn->kln->pr_kode."-->";
					
					
					
					$sql="
						select DISTINCT 
							m.mhs_nim,
							t.absen,
							--t.nama,
							iif(mhs_angkatan='2016',t.nama,
								IIF(ISNULL(lunas,'')<>'' OR SUBSTRING(m.mhs_nim,11,1) IN(3,4)
									or SUBSTRING(m.mhs_nim,1,4) in('B202','A201','A202','2212')
								, t.nama,'')
							) nama,
							t.jmlSesi,t.persen
						from absensiPerjadwal_v2('$id') t
						inner join tbl_mahasiswa m	on(m.mhs_nim=t.npm)
						LEFT JOIN (SELECT nim, lunas ='OK' FROM $keuangan.dbo.pembayarankrs WHERE tahun = '$header[kr_kode]' 
							AND (status = 'Lunas' or sisa  <= 0)) ht
						ON (ht.nim COLLATE Latin1_General_CI_AS = m.mhs_nim)
	
					";
					if($ModJdw->bn->kln->pr_kode=='6' || $ModJdw->bn->kln->pr_kode=='7'){
						$sql="
						
						SELECT DISTINCT kr.mhs_nim, IIF(ISNULL(lunas,'')<>'' OR SUBSTRING(kr.mhs_nim,11,1) IN(3,4)
							or SUBSTRING(kr.mhs_nim,1,4) in('B202','A201','A202') or mh.pr_kode in(6,7)
						, p.Nama,'')as nama, 
						--absen =( select sum(iif(jdwl_stat=1,1,0)) from tbl_absensi where kr.krs_id=krs_id )
						absen = 0
                        FROM -- tbl_absensi ab JOIN 
						tbl_krs kr  -- on kr.krs_id = ab.krs_id 
                        JOIN tbl_mahasiswa mh ON mh.mhs_nim = kr.mhs_nim 
                        JOIN tbl_jadwal jd on jd.jdwl_id = kr.jdwl_id
                        JOIN tbl_bobot_nilai bn on bn.id = jd.bn_id
                        JOIN $keuangan.dbo.student s ON s.nim COLLATE Latin1_General_CI_AS = mh.mhs_nim 
                        JOIN $keuangan.dbo.people p ON p.No_Registrasi = s.no_registrasi
                        LEFT JOIN (SELECT nim, lunas ='OK' FROM $keuangan.dbo.pembayarankrs WHERE tahun = '$header[kr_kode]' 
                                   AND (status = 'Lunas' or sisa  <= 0)) ht
                        ON ht.nim COLLATE Latin1_General_CI_AS = kr.mhs_nim
                        WHERE kr.jdwl_id = $id
                        and(
                            (kr.RStat is null or kr.RStat='0')
                            and (mh.RStat is null or mh.RStat='0')
                        )
                        ORDER BY kr.mhs_nim						
						";
					}
					

				}		

            }else{
                $sql = "select DISTINCT 
							m.mhs_nim,
							t.absen,
							t.nama,
							t.jmlSesi,t.persen
						from absensiPerjadwal_v2('$id') t
						inner join tbl_mahasiswa m	on(m.mhs_nim=t.npm and isnull(m.RStat,0)=0)
	                    ORDER BY m.mhs_nim 
                    ";
					$sql="EXEC dbo.CetakAbsenKuliah $id";
					
            }
			
            $data = Yii::$app->db->createCommand($sql)->queryAll();             
            return $this->renderPartial('cetak_absensi', [
                'data' => $data,
                'header' => $header,
                'jenis' => $jenis,
            ]);
        
    }



    public function actionCetakAbsensi2($id,$jenis){
            $tgl="jdwl_uts";
            $rg = 'jd.rg_uts';

            if ($jenis==1) {
                $jenis = "UJIAN TENGAH SEMESTER";
                $rg = 'jd.rg_uts';
            }else{
                $tgl="jdwl_uas";
                $jenis = "UJIAN AKHIR SEMESTER";
                $rg = 'jd.rg_uas';
            }
            
            
            $sql ="SELECT
                    matakuliah = mk.mtk_kode + ' / ' + mk.mtk_nama,
                    dosen = ds.ds_nm,
                    tanggal = CAST (jd.$tgl AS DATE),
                    jam = CAST (jd.$tgl AS TIME(0)),
                    ruang = rg.rg_nama,
                    kode = jr.jr_id,
                    kelas = jd.jdwl_kls,
                    program = UPPER(pr.pr_nama),
                    jurusan = UPPER( jr.jr_jenjang +' '+ jr.jr_nama),
                    semester = kr.kr_nama,
                    kl.kr_kode
                FROM
                    tbl_jadwal jd
                JOIN tbl_bobot_nilai bn ON bn.id = jd.bn_id
                JOIN tbl_matkul mk ON mk.mtk_kode = bn.mtk_kode
                JOIN tbl_dosen ds ON ds.ds_id = bn.ds_nidn
                JOIN tbl_ruang rg ON rg.rg_kode = $rg
                JOIN tbl_kalender kl ON kl.kln_id = bn.kln_id
                JOIN tbl_program pr on pr.pr_kode = kl.pr_kode
                JOIN tbl_jurusan jr on jr.jr_id = kl.jr_id
                JOIN tbl_kurikulum kr on kr.kr_kode = kl.kr_kode
                WHERE jd.jdwl_id = $id";


            $header =   Yii::$app->db->createCommand($sql)->queryOne();     
            if (empty($header)) {
                die("JADWAL UJIAN BELUM DI SETTING");
            }
                    

            $db = Yii::$app->db1;
            $keuangan = Funct::getDsnAttribute('dbname', $db->dsn);

            if ($jenis=='UJIAN AKHIR SEMESTER') {

                $sql="SELECT DISTINCT kr.mhs_nim, IIF(ISNULL(lunas,'')<>'' OR SUBSTRING(kr.mhs_nim,11,1) IN(3,4)
                or SUBSTRING(kr.mhs_nim,1,4) in('B202','A201','A202')
                , p.Nama,'')as nama, 
                        absen =( select sum(iif(jdwl_stat=1,1,0)) from tbl_absensi where kr.krs_id=krs_id )
                        FROM tbl_absensi ab 
                        JOIN tbl_krs kr on kr.krs_id = ab.krs_id 
                        JOIN tbl_mahasiswa mh ON mh.mhs_nim = kr.mhs_nim 
                        JOIN tbl_jadwal jd on jd.jdwl_id = kr.jdwl_id
                        JOIN tbl_bobot_nilai bn on bn.id = jd.bn_id
                        JOIN $keuangan.dbo.student s ON s.nim COLLATE Latin1_General_CI_AS = mh.mhs_nim 
                        JOIN $keuangan.dbo.people p ON p.No_Registrasi = s.no_registrasi
                        LEFT JOIN (SELECT nim, lunas ='OK' FROM $keuangan.dbo.pembayarankrs WHERE tahun = '$header[kr_kode]' 
                                   AND (status = 'Lunas' or sisa  <= 0)) ht
                        ON ht.nim COLLATE Latin1_General_CI_AS = kr.mhs_nim
                        WHERE kr.jdwl_id = $id
                        and(
                            (kr.RStat is null or kr.RStat='0')
                            and (mh.RStat is null or mh.RStat='0')
                        )
                        ORDER BY kr.mhs_nim
					";
				
				$sql="exec absensiPerjadwal_ '$id'";
                
            }else{
                $sql = "SELECT DISTINCT kr.mhs_nim,p.Nama as nama,
                        (SELECT
                            SUM (iif(m.mhs_stat = 1 or m.mhs_stat=2, 1, 0))
                            FROM
                                        m_transaksi_finger m
                            WHERE
                                    m.krs_id = kr.krs_id
                        ) absen
                    FROM tbl_absensi ab
                    JOIN tbl_krs kr on kr.krs_id = ab.krs_id
                    inner JOIN tbl_jadwal jd on(jd.jdwl_id=kr.jdwl_id and (jd.RStat is null or jd.RStat='0'))
                    inner JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and (bn.RStat is null or bn.RStat='0'))
                    JOIN tbl_mahasiswa mh ON mh.mhs_nim = kr.mhs_nim
                    JOIN $keuangan.dbo.student s ON s.nim COLLATE Latin1_General_CI_AS  = mh.mhs_nim
                    JOIN $keuangan.dbo.people p ON p.No_Registrasi = s.no_registrasi
                    WHERE kr.jdwl_id = $id 
                    and (
                        (kr.RStat is null or kr.RStat='0')
                        and (mh.RStat is null or mh.RStat='0')
                    )
                    GROUP  BY kr.krs_id,kr.mhs_nim,p.Nama
                    ORDER BY mhs_nim 
                    ";
            }
            


            $data = Yii::$app->db->createCommand($sql)->queryAll();   

                    $content = $this->renderPartial('cetak_absensi_pdf', [
                        'data' => $data,
                        'header' => $header,
                        'jenis' => $jenis,
                    ]);

                $pdf = new Pdf([
                    // set to use core fonts only
                    'mode' => Pdf::MODE_CORE, 
                    // A4 paper format
                    'format' => Pdf::FORMAT_LETTER, 
                    // portrait orientation
                    'orientation' => Pdf::ORIENT_PORTRAIT, 
                    // stream to browser inline
                    'destination' => Pdf::DEST_BROWSER, 
                    // your html content input
                    'content' => $content,  
                    'marginHeader'=>0,
                    'marginFooter'=>0,
                    'marginLeft'=>0,
                    'marginRight'=>1,
                    'marginTop'=>8,        
                    // format content from your own css file if needed or use the
                    // enhanced bootstrap css built by Krajee for mPDF formatting 
                    //'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css', 
                     // call mPDF methods on the fly
                    'methods' => [ 
                        //'SetHeader'=>['Data Dosen Wali - Universitas Sangga Buana YPKP Bandung'], 
                        //'SetFooter'=>[' ('.date('d-m-Y H:i:S').') Halaman-{PAGENO}'],
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


}
