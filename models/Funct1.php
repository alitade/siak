<?php

namespace app\models;
use yii\helpers\ArrayHelper;
use Yii;

class Funct 
{
	public function getDsnAttribute($name, $dsn)
    {
		
        if (preg_match('/' . $name . '=([^;]*)/', $dsn, $match)) {
            return $match[1];
        } else {
            return null;
        }
    }

	public function strip_html( $text )
	{
	    $text = preg_replace(
	        array(
	          // Remove invisible content
	            '@<head[^>]*?>.*?</head>@siu',
	            '@<style[^>]*?>.*?</style>@siu',
	            '@<script[^>]*?.*?</script>@siu',
	            '@<object[^>]*?.*?</object>@siu',
	            '@<embed[^>]*?.*?</embed>@siu',
	            '@<applet[^>]*?.*?</applet>@siu',
	            '@<noframes[^>]*?.*?</noframes>@siu',
	            '@<noscript[^>]*?.*?</noscript>@siu',
	            '@<noembed[^>]*?.*?</noembed>@siu',
	          
	            '@</?((address)|(blockquote)|(center)|(del))@iu',
	            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
	            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
	            '@</?((table)|(th)|(td)|(caption))@iu',
	            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
	            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
	            '@</?((frameset)|(frame)|(iframe))@iu',
	        ),
	        array(
	            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
	            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
	            "\n\$0", "\n\$0",
	        ),
	        $text );
	    return strip_tags( $text );
	}


	public function pagingArray($input, $page, $show_per_page) {
	    $start = ($page-1) * $show_per_page;
	    $end = $start + $show_per_page;
	    $count = count($input);
	    if ($start < 0 || $count <= $start)
	        return array(); 
	    else if ($count <= $end) 
	        return array_slice($input, $start);
	    else
	        return array_slice($input, $start, $end - $start);
	}

	public function GEDUNG(){
		$Var=ArrayHelper::map(Gedung::find()->all(),'Id', 'Name');
		return $Var;
	}

	public function Tipe(){
		$Var=ArrayHelper::map(TblTipe::find()->all(),'tp_id', 'tp_nama');
		return $Var;
	}

	public function HARI(){
		return ['Minggu','Senin','Selasa','Rabu','Kamis','Jum`at','Sabtu'];
	}

	public static function getHari1(){
		return [
			['id' => '1', 'nama'=>'Senin'],
			['id' => '2', 'nama'=>'Selasa'],
			['id' => '3', 'nama'=>'Rabu'],
			['id' => '4', 'nama'=>'Kamis'],
			['id' => '5', 'nama'=>'Jumat'],
			['id' => '6', 'nama'=>'Sabtu'],
			['id' => '7', 'nama'=>'Minggu'],
			];
		}

	public function JURUSAN($tipe=1,$kon=''){
		$mod=Jurusan::find()->orderBy(['jr_jenjang'=>SORT_DESC])->all();
		$Var=ArrayHelper::map($mod,'jr_id',
			function($model,$defaultValue){
					return $model->jr_jenjang." ".@$model->jr_nama;
			}		
		);
		
		if($Var==2){
			$var=[];
			foreach($Var as $k=>$v){$var[]=$v;}	
			$Var=$var;
		}else if($tipe==3){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
		return $Var;
	}

	
	
	public function MHS($tipe=1,$kon=''){
		$mod=Jurusan::find()->orderBy(['jr_jenjang'=>SORT_DESC])->all();
		$Var=ArrayHelper::map($mod,'jr_id',
			function($model,$defaultValue){
					return $model->jr_jenjang." ".@$model->jr_nama;
			}		
		);
		
		if($Var==2){
			$var=[];
			foreach($Var as $k=>$v){$var[]=$v;}	
			$Var=$var;
		}else if($tipe==3){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
		return $Var;
	}

	public function PROGRAM($tipe=1){
		$Var=ArrayHelper::map(Program::find()->all(),'pr_kode','pr_nama');
		if($Var==2){
			$var=[];
			foreach($Var as $k=>$v){
				$var[]=$v;
			}	
			$Var=$var;
		}
		return $Var;
	}

	public function KR($tipe=1){
		$Var=ArrayHelper::map(Kurikulum::find()->all(),'kr_kode','kr_nama');
		if($Var==2){
			$var=[];
			foreach($Var as $k=>$v){
				$var[]=$v;
			}	
			$Var=$var;
		}
		return $Var;
	}

	public function MTKJNS(){
		return ['Teori','Praktek','Teori + Praktek'];
	}
	

	public function MTK($tipe=1,$kon=''){
		$mod=Matkul::find()->all();
		if($kon!=''){
			$mod=Matkul::find()->where($kon)->all();
		}
		
		$Var = ArrayHelper::map($mod,'mtk_kode',
			function($model,$defaultValue){
					return @$model->mtk_kode." : ".$model->mtk_nama;
			}		
		);
		
		if($tipe==2){
			$var=[];foreach($Var as $k=>$v){$var[]=$k;}$Var=$var;
		}else if($tipe==3){
			$Var = ArrayHelper::map($mod,'mtk_kode',
				function($model,$defaultValue){
						return "(".$model->mtk_kode.") : ".@$model->mtk_nama;
				}		
			);
		}else if($tipe==3){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}

		return $Var;
	}

	public function JIL($tipe=1,$kon=''){
		$mod=User::find()->all();
		if($kon!=''){
			$mod=User::find()->where($kon)->all();
		}
		
		$Var = ArrayHelper::map($mod,'posisi',
			function($model,$defaultValue){
					return @$model->posisi;
			}		
		);
		
		if($tipe==2){
			$var=[];foreach($Var as $k=>$v){$var[]=$k;}$Var=$var;
		}else if($tipe==3){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
		return $Var;
	}

	public function FK($tipe=1,$kon=''){
		$mod=Fakultas::find()->all();
		if($kon!=''){
			$mod=Fakultas::find()->where($kon)->all();
		}
		
		$Var = ArrayHelper::map($mod,'fk_id','fk_nama');
		if($tipe==2){
			$var=[];foreach($Var as $k=>$v){$var[]=$k;}$Var=$var;
		}else if($tipe==3){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
		return $Var;
	}

	public function DSN($tipe=1,$s='ds_id',$kon=''){
		$Mod=Dosen::find()->all();
		if($kon!=''){
			$Mod=Dosen::find()->where($kon)->all();
		}
		
		$Var=ArrayHelper::map($Mod,$s,'ds_nm');		
		if($tipe==2){
			$var=[];foreach($Var as $k=>$v){$var[]=$k;}$Var=$var;
		}else if($tipe==3){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
		return $Var;
	}

	public function AKADEMIK($tipe=1){
		$Var=ArrayHelper::map(Kurikulum::find()
		->orderBy(['substring(kr_kode,2,4)'=>SORT_DESC,'substring(kr_kode,1,1)'=>SORT_DESC,])
		->all()
		
		,'kr_kode',
			function($model,$defaultValue){
					return $model->kr_kode." : ".@$model->kr_nama;
			}		
			);		

		if($tipe==2){
			$var=[];foreach($Var as $k=>$v){$var[]=$k;}$Var=$var;
		}else if($tipe==3){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
		return $Var;
	}
	
	public function DSN1($tipe=1){
		$Var=ArrayHelper::map(Dosen::find()->all(),'ds_nidn','ds_nm');		
		if($tipe==2){
			$var=[];foreach($Var as $k=>$v){$var[]=$k;}$Var=$var;
		}else if($tipe==3){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
		return $Var;
	}
	
	public function RUANG($tipe=1){
		$Var=ArrayHelper::map(Ruang::find()->all(),'rg_kode','rg_nama');
		
		if($tipe==2){
			$var=[];foreach($Var as $k=>$v){$var[]=$k;}$Var=$var;
		}else if($tipe==3){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
		return $Var;
	}
	
	public function DataWali($jns='thn',$kon=''){
		$que="
			select t.*,ds_id,ds_nm from 
			(
				select DISTINCT kr_kode,j.jr_id,concat(j.jr_jenjang,' ',jr_nama) JrNm from tbl_kalender k
				INNER JOIN tbl_jurusan j on(j.jr_id=k.jr_id)	
			) t 
			CROSS join tbl_dosen d
			where not EXISTS( select * from tbl_wali where KrKd=t.kr_kode and JrId=jr_id and DsId=ds_id)
		".$kon ;				
		
		$que=Yii::$app->db->createCommand($que)->queryAll();
		$arr=array();
		foreach( $que as $d){
			if($jns=='ds'){$arr[$d['ds_id']]=$d['ds_nm'];}
			else if($jns=='jr'){$arr[$d['kr_kode']."#".$d['jr_id']]=$d['JrNm'];}
			else{$arr[$d['kr_kode']]=$d['kr_kode'];}
		}
		return $arr;		
	}
	
	public function Kalender2(){
		$user 	= Mahasiswa::findOne(Yii::$app->user->identity->username);
		$var	= $user->jr_id.$user->pr_kode;
		$KlnId="
				select DISTINCT kln_id from tbl_bobot_nilai where id in(
					select DISTINCT  bn_id from tbl_jadwal where jdwl_id in(
						select DISTINCT jdwl_id from tbl_krs where mhs_nim='$user->mhs_nim'
					)
				)
		";
		$KlnId=Yii::$app->db->createCommand($KlnId)->queryAll();
		$Id="";
		if($KlnId){
			foreach($KlnId as $d){$Id.=",'$d[kln_id]'";}
			if($Id!=""){$Id=substr($Id,1);}
		}
		
		$kur	= substr($user->mhs->kurikulum,1);
		$table	=Kalender::find()->select('kln_id,kr_kode,jr_id,pr_kode')
				->where("(concat(jr_id,pr_kode)='$var' ".($Id!=""?" or kln_id in($Id)":"").")")
				->andWhere(['kln_stat' => '1'])
				->andWhere(" substring(kr_kode,2,4)>= $kur ")
				->distinct()->all();
		$arr=array();
		$arr['NULL#NULL']="- Tahun Akademik -";
		if($table){
			foreach($table as $data){
				$arr[$data->kln_id] = $data->kr_kode." - ".$data->kr->kr_nama." (".$data->pr->pr_nama.")";
			}
		}
		return $arr;	
	 }	


	
	public function Kalender(){
		$user 	= Mahasiswa::findOne(Yii::$app->user->identity->username);
		$var	=$user->jr_id.$user->pr_kode;
		$KlnId="
				select DISTINCT kln_id from tbl_bobot_nilai where id in(
					select DISTINCT  bn_id from tbl_jadwal where jdwl_id in(
						select DISTINCT jdwl_id from tbl_krs where mhs_nim='$user->mhs_nim'
					)
				)
		";
		
		//$KlnId=Yii::$app->db->createCommand($KlnId)->all();
		
		
		$kur	= substr($user->mhs->kurikulum,1);
		$table	=Kalender::find()->select('kln_id,kr_kode,jr_id,pr_kode')
				->where("concat(jr_id,pr_kode)='$var'")
				->andWhere(['kln_stat' => '1'])
				->andWhere(" substring(kr_kode,2,4)>= $kur ")
				->distinct()->all();
		$arr=array();
		$arr['NULL#NULL']="- Tahun Akademik -";
		if($table){
			foreach($table as $data){
				$arr[$data->kln_id] = $data->kr_kode." - ".$data->kr->kr_nama;
			}
		}
		return $arr;	
	 }	

	public function KalenderB(){
		$nim = $_GET['nim'];
		$user 	=Mahasiswa::findOne($nim);
		//print_r($nim);die();
		$var	=$user->jr_id.$user->pr_kode;
		$kur	= substr($user->mhs->kurikulum,1);
		$table	=Kalender::find()->select('kln_id,kr_kode,jr_id,pr_kode')
				->where([' concat(jr_id,pr_kode)'=>''.$var.''])->andWhere(['kln_stat' => '1'])
				->andWhere(" substring(kr_kode,2,4)>= $kur ")
				->distinct()->all();
		$arr=array();
		$arr['NULL#NULL']="- Tahun Akademik -";
		if($table){
			foreach($table as $data){
				$arr[$data->kln_id] = $data->kr_kode." - ".$data->kr->kr_nama;
			}
		}
		return $arr;	
	 }	

	 public static function KalenderKRS(){
		$table	=Kurikulum::find()
				//->where(['kr_kode in (select kr_kode from tbl_kalender where kln_stat=1)'])
				//->orderBy('SUBSTRING(t.kr_kode,2,5) ASC,SUBSTRING(t.kr_kode,1,1) DESC')->all();
				->orderBy('kr_kode')->all();
		$arr=array();
		//$arr['empty']="- Tahun Akademik -";
		$arr['NULL#NULL']="- Tahun Akademik -";
		if($table){
			foreach($table as $data){
				$arr[$data->kr_kode] = $data->kr_kode." - ".$data->kr_nama;
			}
		}
		//Yii::app()->session['NIM']=NULL;
		//unset(Yii::app()->session['NIM']);
		return $arr;	
	 }

	public function TANGGAL($date,$tipe=1){
		
		$bln=array(1=>"Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des");
		
		$date = explode("-",$date);
		if(count($date)>1){
			if(checkdate($date[1],$date[2],$date[0])){
				$date[1]=(int)$date[1];
				return " $date[2] ".$bln[$date[1]]." $date[0] ";
			}
		}
		return '-';
		 
	}

	public function acak($length =5){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;		
	}


	public static function IdMbr($var){
		
		$var	= trim($var);
		for($a=0;$a < Funct::acak('len');$a++){
			if( stripos( $var,substr( Funct::acak('kode'),$a,1 ) ) ){
				$id=explode(substr(Funct::acak('kode'),$a,1),$var);
				$pembatas=substr(Funct::acak('kode'),$a,1);
			}
		}
		$id[1]=hexdec($id[1]);
		$id = $id[0].$pembatas.dechex(($id[1]+1));
		return $id;
	}


	public static function profMhs($var="",$v="",$jr=''){
		if($var==""){$var=Yii::$app->user->identity->username;}
		if($v==""){$v="nim";}
		$con 	= yii::$app->db1;
		$db 	= yii::$app->db;
		$SIAK	= self::getDsnAttribute('dbname', $db->dsn);
		$q		= "select * 
			from people p, student s,$SIAK.dbo.tbl_mahasiswa mh
			where p.no_registrasi = s.no_registrasi
			and s.nim COLLATE Latin1_General_CI_AS  = mh.mhs_nim
			and s.nim='$var' ".($jr!=''?" and mh.jr_id ='$jr'":"");
		$q=$con->createCommand($q)->queryOne();//or die($q);
		return $q[$v];
	}

	public static function getFoto($var="",$v=""){
		if($var==""){$var=Yii::$app->user->identity->username;}
		if($v==""){$v="pt";}
		$con 	= yii::$app->db1;
		$q		= "select * 
			from people p, student s, tbl_mahasiswa mh
			where p.no_registrasi = s.no_registrasi
			and s.nim COLLATE Latin1_General_CI_AS  = mh.mhs_nim
			and s.nim='$var'";
		$q=$con->createCommand($q)->queryOne();//or die($q);
		return $q[$v];
	}

	public static function nameWali($var="",$v=""){
		$mhs    =Mahasiswa::findOne(Yii::$app->user->identity->username);
		$ds 	= $mhs->ds_wali;
		if($var==""){$var=$ds;}
		if($v==""){$v="ds_nm";}
		$con 	= yii::$app->db;
		$q		= "select * 
			from tbl_mahasiswa m,tbl_dosen d
			where m.ds_wali = d.ds_id
			and d.ds_id='$var'";
		$q=$con->createCommand($q)->queryOne();
		//var_dump($q['ds_nm']);
		//die();
		return $q[$v];
	}
	
	public static function nameWaliK($var="",$v="",$jr=''){
		$nim = @$_GET['nim'];
		$mhs    =Mahasiswa::findOne(@$nim);
		$ds 	= @$mhs->ds_wali;
		if($var==""){$var=$ds;}
		if($v==""){$v="ds_nm";}
		$con 	= yii::$app->db;
		$q		= "select * 
			from tbl_mahasiswa m,tbl_dosen d
			where m.ds_wali = d.ds_id
			and d.ds_id='$var' ".($jr!=''?" and m.jr_id ='$jr'":"");
		$q=$con->createCommand($q)->queryOne();
		//var_dump($q['ds_nm']);
		//die();
		return $q[$v];
	}

	public static function Mutu($var)
	{
		
		
		if($var=='A')
		{
			return 4;
		}else if ($var=='B')
		{
			return 3;
		
		}else if ($var=='C'){
			return 2;
		}
		else if ($var=='D'){
			return 1;
		}
		else{
		return 0;
		}
	}

	public static function MUTU_($var)
	{
		$mutu = ['E','D','C','B','A'];
		if($var=='A')
		{
			return 4;
		}else if ($var=='B')
		{
			return 3;
		
		}else if ($var=='C'){
			return 2;
		}
		else if ($var=='D'){
			return 1;
		}
		else{
		return 0;
		}
	}

	public static function Xmutu($sks,$angka){
		$mutu = $sks * Funct::MUTU_($angka);
		return $mutu;
	}
	public static function getHari(){
		return array(
			'1' => 'Senin',
			'2' => 'Selasa',
			'3' => 'Rabu',
			'4' => 'Kamis',
			'5' => 'Jumat',
			'6' => 'Sabtu',
			'0' => 'Minggu',			
			NULL => '',			
		);
	 }
	public static function Status(){
		return array(
			'0' => 'Cancel',
			'1' => 'Approve',			
			NULL => 'Pending',			
		);
	 }

	 public static function getStatus(){
		return [
			['id' => 'Pending', 'nama'=>'Pending'],
			['id' => 'Publish', 'nama'=>'Publish'],
		];
		}

	public static function MtkJenis(){
		return array(
			'0' => 'T',
			'1' => 'P',
			'2'	=> 'TP',			
			NULL => 'Kosong',			
		);
	 }
	 public static function cun($con){
		$db = Yii::$app->db;
		$query = "Select count (*) from tbl_krs where jdwl_id='".$con."' group by jdwl_id";
		$kuota = $db->createCommand($query)->queryOne();
		var_dump($kuota);
		die();
		return $kuota;	
	}
	public static function cekKrs($jdwl_id)
	{
		$model=Krs::find()->where(["jdwl_id"=>$jdwl_id, "mhs_nim"=>Yii::$app->user->identity->username])->andWhere('(RStat !=1 or RStat is null )')->one();
		if($model)return true;
		return false;			
	}

	public static function cekAbsen($krs_id)
	{
		$con = Yii::$app->db;

		$sql = "SELECT sum(iif(jdwl_stat=1,1,0)) as a from tbl_absensi where krs_id='$krs_id' GROUP BY krs_id";		
		$absen = $con->createCommand($sql)->queryOne();

		return $absen['a'];			
	}
	
	public static function cekKrsB($jdwl_id)
	{
		$model=Krs::find()->where(["jdwl_id"=>$jdwl_id, "mhs_nim"=>$_GET['nim']])->andWhere('(RStat !=1 or RStat is null )')->one();
		if($model)return true;
		return false;			
	}	
	
	 public static function Fakultas($fk_id){
		$medol = Fakultas::findOne($fk_id);
		return $medol->fk_nama;
	 }

	 public static function Kurikulum($kr_kode){
		$medol=Kurikulum::findOne($kr_kode);
		return (isset($medol->kr_nama)) ? $medol->kr_nama : '';	 
	 }
	
	public static function rgNama($id,$var=""){
		$prog=Ruang::find()->where(["rg_kode"=>'$id']);
		if($var==""){
			$var="rg_nama";	
		}
		if(!empty($prog->$var)){
		return $prog->$var;
		}else{
			return "Data Kosong";
			}
	}

	public static function KlnList($jur,$pro,$t=1){
		$model = Kalender::find()
		->where(['jr_id'=>$jur,'pr_kode'=>$pro])
		->all();
	}

	public static function CekTgl($id,$date=''){
		if($date==''){$date=date('Y-m-d');}
		$ModTahun = Kalender::find(['kln_id'=>$id])->select(['kln_krs_lama'=>"datediff(day,'$date',DATEADD(day,kln_krs_lama,kln_krs))"])
		->where(['kln_id'=>$id])
		->one();
		//die (print_r($ModTahun->kln_krs_lama));
		if(@$ModTahun->kln_krs_lama){
			return @$ModTahun->kln_krs_lama >=0;
		}
		return false;
		
	}
	
	public function ResetPass($var){
		//return $var;
		$model 	= User::find()->where(['username'=>$var])->one();
		$kode	= self::acak('10');
		$pass	= 'ypkp@#1234';
		if($model){
			$model->pass_kode=$kode;
			$model->password =md5($pass.$kode.$model->tipe);
			if( $model->save(false) ){return true;}
			return false;
		}else{
			$model = Mahasiswa::findOne($var);
			if($model){
				$model->mhs_pass = md5($pass);
				if($model->save(false)){return true;}
				return false;
			}			
		}
		return false;
	}
		
	public static function getName($var,$v=""){
		//if($var==""){
		//	$var=Yii::$app->user->identity->username;}
		if($v==""){$v="nim";}
		$con 	= yii::$app->db1;
		$q		= "select * 
			from people p, student s
			where p.no_registrasi = s.no_registrasi
			and s.nim='$var'";
		$q=$con->createCommand($q)->queryOne();//or die($q);
		//print_r($q);die();
		return $q[$v];

	}

	public function barcode($nim,$kr){
		$krs =Krs::find()
		->where(['mhs_nim'=>$nim,'kr_kode_'=>$kr])->all();
		$arr=[];
		foreach($krs as $d){
			$arr[]=$d['krs_id'];
		}
		
		if(sizeof($arr)>0)
			return dechex(date('His'))."x".dechex(date('ymd'))."y".dechex($arr[rand(0,sizeof($arr)-1)]);
		else
			return false;		
	}
		

	public function cekPassDef(){
		//return false;
		$Id=@Yii::$app->user->identity->id;	
		$def='ypkp@#1234';
		if($Id){
			$CekUser = User::findOne($Id);
			if($CekUser){
				if(md5($def.$CekUser->pass_kode.$CekUser->tipe)==$CekUser->password){
					return true;	
				}else{return false;}
			}
		}
	}

	public function LOGS($data,$TB="",$ID="",$CD="",$SESSION=true){

		$connection = Yii::$app->db;//get connection
		$DT="";
		if($TB!='' && $ID!='' && $CD!=''){
			$DT		=$TB::tableName()."@";
			foreach($TB::findOne($ID) as $k=>$v){$DT.="$k|$v#";}
			$DT=substr($DT,0,-1);			
		}
		
		$model =  new Logs;
		$model->user_id		= Yii::$app->user->identity->id;
		$model->source_ip	= $_SERVER['REMOTE_ADDR'];
		$model->user_agent	= $_SERVER['HTTP_USER_AGENT'];;
		$model->activity	= $data;
		$model->link		= $_SERVER['REQUEST_URI'];
		$model->data		= $DT;
		$model->kode		= $CD;
		
		if(Yii::$app->user->identity->id && Yii::$app->request->referrer!=$_SERVER['REQUEST_URI']){
			if($SESSION){
				if( !isset($_SESSION['L'])){$_SESSION['L']=md5("L");}
				if($_SESSION['L']!=md5($_SERVER['REQUEST_URI'])){
					$model->save();
					$_SESSION['L']= md5($_SERVER['REQUEST_URI']);
				}
			}else{
				$model->save();
			}
		}
	}
	
	public function DATA($TB,$ID){
		$connection = Yii::$app->db;//get connection
		echo "<pre>";
		print_r($_SERVER);
		echo "</pre>";
		
		if($TB){
			$data		=$TB::tableName()."@";
			foreach($TB::findOne($ID) as $k=>$v){$data.="$k|$v#";}
			$data=substr($data,0,-1);			
		}
		echo $data;
		
	}

	public function TABLES($TABLE){
		$connection = Yii::$app->db;//get connection
		$jr= new $TABLE;
		die(print_r($jr->tableSchema));
		$dbSchema = $connection->schema;
		$tables = $dbSchema->tableNames;
		foreach($tables as $k=>$v){
			echo $v." <br />";
		}
	}

	public function DOSEN($id){
		$model = Dosen::find()->where("ds_user='$id'")->one();
		if($model){return $model->ds_nm;}
		return false;
	}


	public function absen($id){
		$model = Absensi::find()->where("krs_id='$id' and jdwl_stat='1'")->count();
		return $model;
	}


	public function ATT($id){
		$model = Krs::findOne($id);
		$err='';
		$abs=0;
		if(Funct::absen($id)){$abs = Funct::absen($id)/12*100;}
		
		if(!empty($model->jdwl->bn->nb_tgs1) && (empty($model->krs_tgs1) || $model->krs_tgs1==0)){$err[0]="Tugas 1";}
		if(!empty($model->jdwl->bn->nb_tgs2) && (empty($model->krs_tgs2) || $model->krs_tgs2==0)){$err[1]="Tugas 2";}
		if(!empty($model->jdwl->bn->nb_uts) && (empty($model->krs_uts) || $model->krs_uts==0)){$err[2]="UTS";}
		if(!empty($model->jdwl->bn->nb_uas) && (empty($model->krs_uas) || $model->krs_uas==0)){$err[3]="UAS";}

		if(!empty($model->jdwl->bn->nb_tgs3) && (empty($model->krs_tgs3) || $model->krs_tgs3==0)){$err[1]="Tugas 3";}
		if(!empty($model->jdwl->bn->nb_tambahan) && (empty($model->krs_tambahan) || $model->krs_tambahan==0)){$err[2]="Absen / Tambahan";}
		if(!empty($model->jdwl->bn->nb_quis) && (empty($model->krs_quis) || $model->krs_quis==0)){$err[3]="QUIS";}

		/*
		if(empty($model->krs_tgs3) || $model->krs_tgs2==0){$err[1]="Tugas 3";}
		if(empty($model->krs_tambahan) || $model->krs_tgs1==0){$err[0]="Absesn";}
		if(empty($model->krs_quis) || $model->krs_tgs2==0){$err[1]="Quis";}
		*/
		
		if($abs < 75 ){$err[3]="Absen Kurang Dari 75%";}
		return $err;
	}

	public function AKSES(){
		$sql = "select * from auth_item where name not like '/%'";
		$sql = Yii::$app->db->createCommand($sql)->queryAll();
		$data="";
		foreach($sql as $d){$data[$d['name']]=$d['name'];}
		return $data;
	}

	public function TotNil($id){
		$Bobot 			= Krs::findOne($id);
		$tgs1			= ($Bobot->jdwl->bn->nb_tgs1 		> 0 ? $Bobot->krs_tgs1*$Bobot->jdwl->bn->nb_tgs1/100:0);
		$tgs2			= ($Bobot->jdwl->bn->nb_tgs2 		> 0 ? $Bobot->krs_tgs2*$Bobot->jdwl->bn->nb_tgs2/100:0);
		$tgs3			= ($Bobot->jdwl->bn->nb_tgs3 		> 0 ? $Bobot->krs_tgs3*$Bobot->jdwl->bn->nb_tgs3/100:0);
		$tambahan		= ($Bobot->jdwl->bn->nb_tambahan 	> 0 ? $Bobot->krs_tambahan*$Bobot->jdwl->bn->nb_tambahan/100:0);
		$quis			= ($Bobot->jdwl->bn->nb_quis 		> 0 ? $Bobot->krs_quis*$Bobot->jdwl->bn->nb_quis/100:0);
		$uts			= ($Bobot->jdwl->bn->nb_uts 		> 0 ? $Bobot->krs_uts*$Bobot->jdwl->bn->nb_uts/100:0);
		$uas			= ($Bobot->jdwl->bn->nb_uas			> 0 ? $Bobot->krs_uas*$Bobot->jdwl->bn->nb_uas/100:0);
		$Tot			= $tgs1+$tgs2+$tgs3+$tambahan+$quis+$uts+$uas;
		
		$grade='-';
		if($Tot<=$Bobot->jdwl->bn->B&&!empty($Bobot->jdwl->bn->B)){
			$grade="B";
			if($Tot<=$Bobot->jdwl->bn->C&&!empty($Bobot->jdwl->bn->C)){
				$grade="C";
				if($Tot<=$Bobot->jdwl->bn->D&&!empty($Bobot->jdwl->bn->D)){
					$grade="D";
						if($Tot<=$Bobot->jdwl->bn->E&&!empty($Bobot->jdwl->bn->E)){$grade="E";}
				}
				
			}
		}else{$grade="A";}		
		$Bobot->krs_tot 	= $Tot;
		$Bobot->krs_grade 	= $grade;
		$Bobot->save(false);
		return true;
	}

	public function TotNil1($id){
		$Bobot 			= Krs::findOne($id);
		$tgs1			= ($Bobot->jdwl->bn->nb_tgs1 		> 0 ? $Bobot->krs_tgs1*$Bobot->jdwl->bn->nb_tgs1/100:0);
		$tgs2			= ($Bobot->jdwl->bn->nb_tgs2 		> 0 ? $Bobot->krs_tgs2*$Bobot->jdwl->bn->nb_tgs2/100:0);
		$tgs3			= ($Bobot->jdwl->bn->nb_tgs3 		> 0 ? $Bobot->krs_tgs3*$Bobot->jdwl->bn->nb_tgs3/100:0);
		$tambahan		= ($Bobot->jdwl->bn->nb_tambahan 	> 0 ? $Bobot->krs_tambahan*$Bobot->jdwl->bn->nb_tambahan/100:0);
		$quis			= ($Bobot->jdwl->bn->nb_quis 		> 0 ? $Bobot->krs_quis*$Bobot->jdwl->bn->nb_quis/100:0);
		$uts			= ($Bobot->jdwl->bn->nb_uts 		> 0 ? $Bobot->krs_uts*$Bobot->jdwl->bn->nb_uts/100:0);
		$uas			= ($Bobot->jdwl->bn->nb_uas			> 0 ? $Bobot->krs_uas*$Bobot->jdwl->bn->nb_uas/100:0);
		$Tot			= $tgs1+$tgs2+$tgs3+$tambahan+$quis+$uts+$uas;
		
		$grade='-';
		if($Tot<=$Bobot->jdwl->bn->B&&!empty($Bobot->jdwl->bn->B)){
			$grade="B";
			if($Tot<=$Bobot->jdwl->bn->C&&!empty($Bobot->jdwl->bn->C)){
				$grade="C";
				if($Tot<=$Bobot->jdwl->bn->D&&!empty($Bobot->jdwl->bn->D)){
					$grade="D";
						if($Tot<=$Bobot->jdwl->bn->E&&!empty($Bobot->jdwl->bn->E)){$grade="E";}
				}
				
			}
		}else{$grade="A";}		
		return $grade;
		return true;
	}

	public function StatNilDos($id){
		$sql=" select  sum(krs_tot) tot from tbl_krs WHERE jdwl_id='$id'";
		$sql = Yii::$app->db->createCommand($sql)->queryOne();
		return $sql['tot'];
	}

	public function Kuota($id){
		$sql=" select  count(*) tot 
			from tbl_krs krs, tbl_jadwal jd,tbl_bobot_nilai bn
			WHERE krs.jdwl_id=jd.jdwl_id
			and bn.id=jd.bn_id
			and krs.jdwl_id='$id'
			and (
					(krs.RStat is null or krs.RStat='0')
				and (bn.RStat is null or bn.RStat='0')
				and	(jd.RStat is null or jd.RStat='0')
			)
			";
		$sql = Yii::$app->db->createCommand($sql)->queryOne();
		return $sql['tot'];
	}



}
