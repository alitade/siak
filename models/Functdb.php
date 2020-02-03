<?php

namespace app\models;
use yii\helpers\ArrayHelper;
use Yii;


class Functdb {


    public static function USR($id){return User::findOne($id);}

    public static function uID(){return Yii::$app->db->createCommand("select newid() id")->queryOne()['id'];}

    public static function insLog($tb,$pk,$kd,$act,$ket=''){
        $model = new LogTransaksi;
        $model->user_id		= Yii::$app->user->identity->id;
        $model->ip4	        = $_SERVER['REMOTE_ADDR'];
        $model->user_agent	= $_SERVER['HTTP_USER_AGENT'];;
        $model->ket	        = $ket;
        $model->aktifitas	= $act;
        $model->tb          = $tb;
        $model->pk          = "$pk";
        $model->kode		= $kd;
        $model->link        = substr($_SERVER['REQUEST_URI'],0,500);
        $sv=$model->save();
        if(!$sv){Funct::v($model->getErrors());}
    }

    public static function vLog($id,$pk){
        $log    = LogTransaksi::find()->where(['tb'=>$id,'pk'=>$pk])->orderBy(['tgl'=>SORT_DESC])->one();
        $tgl=explode(" ",$log['tgl']);
        return  " <b>Riwayat Data Terakhir[".Funct::TANGGAL($tgl[0],2)." ".substr($tgl[1],0,8)."]</b> <br>".@$log->usr->name.": ".$log->aktifitas;
    }

    public static function informasi($tipe=0,$kon=0){
        $mod=Informasipendaftaran::find()->all();
        if($kon!=''){$mod=Informasipendaftaran::find()->where($kon)->all();}
        $Var = ArrayHelper::map($mod,'id','jenis_informasi');
        if($tipe==1){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
        return $Var;
    }


    public static function jenjang_dft($tipe=0,$kon=0){
        $mod=Jenjang::find()->all();
        if($kon!=''){$mod=Jenjang::find()->where($kon)->all();}
        $Var = ArrayHelper::map($mod,'id','jenjang');
        if($tipe==1){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
        return $Var;
    }

    public static function agama_dft($tipe=0,$kon=0){
        $mod=MasterAgama::find()->all();
        if($kon!=''){$mod=MasterAgama::find()->where($kon)->all();}
        $Var = ArrayHelper::map($mod,'id','agama');
        if($tipe==1){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
        return $Var;
    }

    public static function kerja_dft($tipe=0,$kon=0){
        $mod=Jenispekerjaan::find()->all();
        if($kon!=''){$mod=Jenispekerjaan::find()->where($kon)->all();}
        $Var = ArrayHelper::map($mod,'id','jenis_pekerjaan');
        if($tipe==1){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
        return $Var;
    }

    public static function jr_dft($tipe=0,$kon=0){
        $mod=Jurusansekolah::find()->all();
        if($kon!=''){$mod=Jurusansekolah::find()->where($kon)->all();}
        $Var = ArrayHelper::map($mod,'id','nama_jurusan');
        if($tipe==1){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
        return $Var;
    }

    public static function statKTP($tipe=0,$kon=0){
        $mod=StatusKtp::find()->all();
        if($kon!=''){$mod=StatusKtp::find()->where($kon)->all();}
        $Var = ArrayHelper::map($mod,'id','status');
        if($tipe==1){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
        return $Var;
    }

    public static function sch_dft($tipe=0,$kon=0){
        $mod=AsalSekolah::find()->all();
        if($kon!=''){$mod=AsalSekolah::find()->where($kon)->all();}
        $Var = ArrayHelper::map($mod,'id','sekolah');
        if($tipe==1){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
        return $Var;
    }

    public static function beasiswa($tipe=0,$kon=0){
        $mod=Beasiswajenis::find()->all();
        if($kon!=''){$mod=Beasiswajenis::find()->where($kon)->all();}
        $Var = ArrayHelper::map($mod,'id','namabeasiswa');
        if($tipe==1){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
        return $Var;
    }

    public static function jurusan_plh($tipe=0,$kon=0){
        $mod=Jurusan::find()->all();
        if($kon!=''){$mod=Jurusan::find()->where($kon)->all();}
        $Var = ArrayHelper::map($mod,'jr_id',function($model,$defaultValue){
            return @$model->jr_jenjang." : ".$model->jr_nama;},function($model,$defaultValue){return "Fakultas ".@$model->fk->fk_nama;}
        );
        if($tipe==1){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}


        #Funct::v($Var);
        return $Var;
    }


    public static function vendor($tipe=0,$kon=0){
        $mod=Konsultan::find()->all();
        if($kon!=''){$mod=Konsultan::find()->where($kon)->all();}
        $Var = ArrayHelper::map($mod,'kode','vendor');
        if($tipe==1){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
        return $Var;
    }

    public static function fakultas($tipe=0,$kon=0){
        $mod=Fakultas::find()->all();
        if($kon!=''){$mod=Fakultas::find()->where($kon)->all();}
        $Var = ArrayHelper::map($mod,'fk_id','fk_nama');
        if($tipe==1){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
        return $Var;
    }

    public static function program($tipe=0,$kon=0){
        $mod=Program::find()->all();
        if($kon!=''){$mod=Program::find()->where($kon)->all();}
        $Var = ArrayHelper::map($mod,'pr_kode','pr_nama');
        if($tipe==1){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
        return $Var;
    }


    public static function pilihTarif($vn,$kd){
        $sql="
            SELECT * FROM(
                SELECT id ,kode,aktif 
                ,(
                    select 
                    sum(iif(t1.item=t.item,POWER(2,(t.n-1)),0)) s
                    FROM(SELECT ROW_NUMBER() OVER(ORDER BY RAND()) n,item  from dbo.SplitString('".$kd."','-')) t
                    INNER JOIN(SELECT ROW_NUMBER() OVER(ORDER BY RAND()) n ,item  from dbo.SplitString(kode,'-')) t1 on(t1.n=t.n)
                    HAVING sum(IIF(SUBSTRING(t1.item,4,len(t1.item)-3)='0',0,iif(t1.item=t.item,0,1)))=0
                ) s
                from tarif WHERE isnull(aktif,0)=1
            ) t WHERE s is not NULL  ORDER BY s asc
        ";

        return Yii::$app->db->createCommand($sql)->queryAll();

    }


    public static function tipeDosen($tipe=0,$kon=0){
        $mod=DosenTipe::find()->all();
        if($kon!=''){$mod=DosenTipe::find()->where($kon)->all();}
        $Var = ArrayHelper::map($mod,'id',
            function($model,$defaultValue){return $model->tipe." ($model->maxsks sks) ";}

        );
        if($tipe==1){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
        return $Var;
    }



}
