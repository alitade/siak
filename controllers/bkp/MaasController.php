<?php

namespace app\controllers;

use Yii;
use app\models\Maas;

use yii\data\SqlDataProvider; 
use app\controllers\CDbCriteria;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class MaasController extends Controller
{
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

    public function actionPenerimaan(){
        $model = new Maas();
        if($model->load(Yii::$app->request->post())){
            $total = Yii::$app->db->createCommand("exec keuanganfix.dbo.sumPenerimaan '".$model->dt1."','".$model->dt2."'")->queryScalar();
            $count = ((abs(strtotime ($model->dt2) - strtotime ($model->dt1)))/(60*60*24));
            if($count>=30){
                $sql = $sql = "exec keuanganfix.dbo.penerimaanBulanan '".$model->dt1."','".$model->dt2."'";
            }else{
                $sql = "exec keuanganfix.dbo.penerimaanHarian '".$model->dt1."','".$model->dt2."'";
            }
            
            $dataProvider = new SqlDataProvider([
                'sql'=>$sql,
                'pagination' => false,
            ]);
        }
        return $this->render('penerimaan',array(
            'dataProvider'=>$dataProvider,
            'SummaryTotal'=>$total,
            'model'=>$model,
        )
        );
    }

    public function actionPengeluaran(){
        $model = new Maas();
        if($model->load(Yii::$app->request->post())){
            $total = Yii::$app->db->createCommand("select sum(jumlah) from 
                    keuanganfix.dbo.pencatatantransaksi pt join keuanganfix.dbo.jenistransaksi jt on 
                    (pt.idTransaksi=jt.nomor_akun) where cast(tanggal as date) BETWEEN '".$model->dt1."' and '".$model->dt2."'")->queryScalar();
            $count = ((abs(strtotime ($model->dt2) - strtotime ($model->dt1)))/(60*60*24));
            if($count>=30){
                $sql = "select datename(m,tanggal)+' '+cast(datepart(yyyy,tanggal) as varchar) as Tanggal, jt.pos_nama as Transaksi, sum(jumlah) Jumlah from 
                    keuanganfix.dbo.pencatatantransaksi pt join keuanganfix.dbo.jenistransaksi jt on 
                    (pt.idTransaksi=jt.nomor_akun) where cast(tanggal as date) BETWEEN '".$model->dt1."' and '".$model->dt2."' group by datename(m,tanggal)+' '+cast(datepart(yyyy,tanggal) as varchar), jt.pos_nama";
            }else{
                $sql = "select cast(pt.tanggal as date) Tanggal, jt.pos_nama as Transaksi, jumlah Jumlah from 
                    keuanganfix.dbo.pencatatantransaksi pt join keuanganfix.dbo.jenistransaksi jt on 
                    (pt.idTransaksi=jt.nomor_akun) where cast(tanggal as date) BETWEEN '".$model->dt1."' and '".$model->dt2."'";
            }
            $dataProvider = new SqlDataProvider([
                'sql'=>$sql,
                'pagination' => false,
            ]);
        }
        return $this->render('pengeluaran',array(
            'dataProvider'=>$dataProvider,
            'SummaryTotal'=>$total,
            'model'=>$model,
        )
        );
    }

}
?>