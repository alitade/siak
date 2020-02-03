<?php
namespace app\controllers;

use Yii;
use app\models\Keuangan;
use app\models\Mahasiswa;
use app\models\Jurusan;
use app\models\Program;
use app\models\Dosen;
use app\models\Funct;
use yii\data\SqlDataProvider; 
use app\controllers\CDbCriteria;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \mPdf;
use kartik\mpdf\Pdf;
use yii\helpers\Url;

class KeuanganController extends Controller{
    //public $layout = true;
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

    public function actionMhs(){
    	$model = new Keuangan();
    	if($model->load(Yii::$app->request->post())){
    		$sql = "exec Keuanganfix.dbo.lstMahasiswa '".$model->searchString."'";
    		$dataProvider = new SqlDataProvider([
                'sql'=>$sql,
                'pagination' => false,
            ]);
    	}
    	return $this->render('mhs',array(
            'dataProvider'=>$dataProvider,
            //'SummaryTotal'=>$total,
            'model'=>$model,
        )
        );
    }

    public function actionPembayaran($id){
        $mhs    = Mahasiswa::findOne($id);
        $jr     = Jurusan::findOne($mhs->jr_id);
        $pr     = Program::findOne($mhs->pr_kode);
        $ds     = Dosen::findOne($mhs->ds_wali);
        $pkrs = new SqlDataProvider([
            'sql'=>"exec keuanganfix.dbo.lstKRS '".$id."'",
            'pagination' => false,
        ]);
        $pbeban = new SqlDataProvider([
            'sql'=>"exec keuanganfix.dbo.lstBEBAN '".$id."'",
            'pagination' => false,
        ]);
        $data = array(
            'mhs'=>$mhs,
            'jr'=>$jr,
            'pr'=>$pr,
            'ds'=>$ds,
            'pkrs'=>$pkrs,
            'pbeban'=>$pbeban,
        );
        return $this->render('pembayaran',$data);
    }

    public function actionKeuangandetail($tipe, $id, $ket){
        //$this->layout = false;
        if($tipe=="KRS"){
            $sql = "exec keuanganfix.dbo.historyKRS '".$id."'";
        }else{
            $sql = "exec keuanganfix.dbo.historyBEBAN '".$id."'";
        }
        $history = new SqlDataProvider([
            'sql'=>$sql,
            'pagination' => false,
        ]);
        return $this->renderAjax('detail',array(
            'history'=> $history,
            'ket'=> $ket,
        ));
    }

    public function actionCetak($id){
        $db = Yii::$app->db1;
        $mhs    = Mahasiswa::findOne($id);
        $jr     = Jurusan::findOne($mhs->jr_id);
        $pr     = Program::findOne($mhs->pr_kode);
        $ds     = Dosen::findOne($mhs->ds_wali);
        $pkrs = $db->createCommand("exec dbo.sppembayaran '".$id."'")->queryAll();
        $pbeban = $db->createCommand("exec dbo.sppbayarbeban '".$id."'")->queryAll();
        $data = [
            'mhs'=>$mhs,
            'jr'=>$jr,
            'pr'=>$pr,
            'ds'=>$ds,
            'pkrs'=>$pkrs,
            'pbeban'=>$pbeban,
        ];
        $this->layout = 'blank';
        $content = $this->renderPartial('keuangan_pdf',$data);
        return $content;
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
                'filename'=>'Keuangan-'.$id.'.pdf',
                'options' => [
                    'title' => 'Keuangan '.$id,
                    'subject' => 'Keuangan '.$id,
                    'watermarkText'=>'DIREKTORAT SISTEM INFORMASI & MULTIMEDIA',
                    'watermarkTextAlpha'=>0.2,
                    'showWatermarkText'=>true,
                    //'debug'=>true,
                    'cssFile' => '@webroot/css/bootstrap.min.css',
                ],
                //'config' => [
                  //  'cssFile' => '@webroot/css/bootstrap.min.css',
                //],
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
                    'SetFooter' => ['|Page {PAGENO}'.'|'.date("r")],
                ]
            ]);         
            //return $pdf->render();
    }
}
?>