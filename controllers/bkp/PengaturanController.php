<?php

namespace app\controllers;

use Yii;
use app\models\Pengaturan;
use app\models\Aturan;
use app\models\Funct;

use app\controllers\CDbCriteria;
use yii\web\Controller;
use yii\db\Expression;
/**
 * MahasiswaController implements the CRUD actions for Mahasiswa model.
 */
class PengaturanController extends Controller{

    public  function  actionIndex(){
        #print_r(Yii::$app->vd->vd());
        $sql="
          select *,(select count(*) FROM aturan a1 WHERE a1.parent=a.id and a.id!=a1.id) tot  from aturan a
          where a.parent is null";
        $sql=Yii::$app->db->createCommand($sql)->queryAll();
        return $this->render('/pengaturan/index',['sql'=>$sql]);

    }

    public  function  actionVdAktif($id){
        $uid=Yii::$app->user->identity->id;
        $model  = Pengaturan::find()->where(['id'=>$id])->andWhere(['id'=>[19,29,30,31]])->one();
        $mod    = Pengaturan::findOne($id);
        if($model->id){
            $aktif=($model->nil==0?"Mengaktifkan Validasi":"Menonaktifkan Validasi");
            \app\models\Funct::LOGS($aktif.' ('."$mod->kd: $mod->ket)",$mod,$mod->id,'u');
            $model->nil=($model->nil==0?1:0);$model->uuid=$uid;$model->utgl=new  Expression("getdate()");
            if($model->save(false)){unset($_SESSION['L']);}
        }
        return $this->redirect(['/pengaturan/index','#'=>$model->kd]);

    }

    public  function  actionDefault($id){
        $uid=Yii::$app->user->identity->id;
        $model  = Pengaturan::findOne($id);
        if($model->id){
            $aktif=($model->def==0?"Mengaktifkan Nilai Default":"Menonaktifkan Nilai Default");
            \app\models\Funct::LOGS($aktif.' ('."$model->kd: $model->ket)",$model,$model->id,'u');
            $model->def=($model->def==0?1:0);$model->uuid=$uid;$model->utgl=new  Expression("getdate()");
            if($model->save(false)){unset($_SESSION['L']);}
        }
        return $this->redirect(['/pengaturan/view','id'=>($model->parent?:$model->id),'#'=>$model->kd]);
    }

    public  function  actionAktif($id){
        $uid=Yii::$app->user->identity->id;
        $model  = Pengaturan::findOne($id);
        if($model->id){
            $aktif=($model->aktif==0?"Mengaktifkan Pengaturan":"Menonaktifkan Pengaturan");
            \app\models\Funct::LOGS($aktif.' ('."$model->kd: $model->ket)",$model,$model->id,'u');
            $model->aktif=($model->aktif==0?1:0);$model->uuid=$uid;$model->utgl=new  Expression("getdate()");
            if($model->save(false)){unset($_SESSION['L']);}
        }
        return $this->redirect(['/pengaturan/view','id'=>($model->parent?:$model->id),'#'=>$model->kd]);
    }

    public  function  actionAktivasi(){
        #print_r(Yii::$app->vd->vd());
        $sql="select * from aturan where parent is null";
        $sql=Yii::$app->db->createCommand($sql)->queryAll();
        return $this->render('/pengaturan/index',['sql'=>$sql]);

    }

    public  function  actionNilai(){
        #print_r(Yii::$app->vd->vd());
        $sql="select * from aturan where parent is null";
        $sql=Yii::$app->db->createCommand($sql)->queryAll();
        return $this->render('/pengaturan/index',['sql'=>$sql]);

    }

    public  function  actionView($id){
        $sql="select * from aturan where id=$id";
        $sql=Yii::$app->db->createCommand($sql)->queryOne();
        $sql1="select * from aturan where parent=$id";
        $sql1=Yii::$app->db->createCommand($sql1)->queryAll();
        return $this->render('/pengaturan/view',[
            'sql'=>$sql,
            'sql1'=>$sql1
        ]);

    }

    public  function  actionUpdate($id){
        $uid=Yii::$app->user->identity->id;
        $model=new Aturan;
        $mod= Pengaturan::findOne($id);
        if ($model->load(Yii::$app->request->post())){
            \app\models\Funct::LOGS('Merubah Nilai ('."$mod->kd: $mod->ket)",$mod,$mod->id,'u');
            $mod->nil=$model->$mod->kd;
            $mod->uuid=$uid;$mod->utgl=new  Expression("getdate()");
            $mod->save(false);
            return $this->redirect(['/pengaturan/view','id'=>($mod->parent?:$mod->id),'#'=>$mod->kd]);
        }

        return $this->render('/pengaturan/update',[
            'mod'=>$mod,
            'model'=>$model,
        ]);

    }


}
