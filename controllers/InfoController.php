<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class InfoController extends Controller{

    public function actionIndex(){
        $model= \app\models\Mahasiswa::find();
		$this->layout = 'info';
		$data=Yii::$app->request->post();
		$bio="";
		$bio2="";
        if(isset($data['mhs']['npm'])){
            $npm=$data['mhs']['npm'];
            $model= \app\models\Mahasiswa::find()->where("mhs_nim=:nim",[':nim'=>$npm])->one();
            $bio=Yii::$app->db1->createCommand("
                select Nama,p.no_ktp,p.alamat,p.tempat_lahir,p.tanggal_lahir,p.no_registrasi 
                from people p inner join student s on(s.no_registrasi=p.no_registrasi and s.nim='$model->mhs_nim') 
            ")->queryOne();
            $bio2=Yii::$app->db->createCommand(
                "
                    SELECT mhs.mhs_nim,u.Fid,b.f2 from tbl_mahasiswa mhs
                    LEFT JOIN user_ u on(u.username=mhs.mhs_nim and u.tipe='5')
                    LEFT JOIN bantu b on(b.f1=mhs.mhs_nim)
                    WHERE mhs.mhs_nim='$model->mhs_nim'                
                ")->queryOne();
            
        }else{$model= \app\models\Mahasiswa::find()->where('1=0')->one();}

		return $this->render('index',[
		    'model'=>$model,
            'bio'=>$bio,
            'bio2'=>$bio2,
            'data'=>$data,

        ]);

    }

    public function actionMaintenance(){
        $this->layout='maintenance';
        return $this->render('maintenance');

    }


}
