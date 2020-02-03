<?php

namespace app\models;
use yii\helpers\ArrayHelper;
use Yii;


class Funct {

    public static function v($v){
        echo "<pre>";
        print_r($v);
        echo"<pre>";
        die();
    }

	public static function NilaiAkhir($nim){
		$TR = Yii::$app->db2;
		$TRconn	= Funct::getDsnAttribute('dbname', $TR->dsn);
		if(!$TRconn){$TRconn = Funct::getDsnAttribute('Database', $TR->dsn);}
		$QueTranskrip = " select * from $TRconn.dbo.t_nilai where npm='$nim' and isnull(stat,0)=0 order by id asc";
		$QueTranskrip=Yii::$app->db->createCommand($QueTranskrip)->queryAll();
		$NIL=[];
		foreach($QueTranskrip as $d){
			//$NIL[$d['kode_mk'].'_'.$d['tahun'].'_'.$d['krs_id_']]=['h'=>$d['huruf'],'n'=>$d['nilai']];
			$h=$d['huruf'];$n=$d['nilai'];$s=$d['sks'];$x=$n*$s;
			$dt=['h'=>$h,'n'=>$n,'x'=>$x,'s'=>$s];
			$NIL[$d['kode_mk']][$d['tahun']] =$dt;
			$NIL[$d['kode_mk']][$d['krs_id']]=$dt;
		}
		return $NIL;
	}
	public static function produk($kode){return Produk::findOne($kode);}

	public static function AvKrs($nim,$jdwl_hari,$jdwl_masuk,$jdwl_keluar,$kr_kode){
		$sql="select CAST(isnull(dbo.avKrsTime_v2('$nim','$jdwl_hari','$jdwl_masuk','$jdwl_keluar','$kr_kode'),0) as int) a";
		$d=Yii::$app->db->createCommand($sql)->queryOne();
		$dis =false;
		if( ($d['a']==0)){$dis=true;}
		return $dis;
	}


	public static function lulus($nim){
		$sql="select count(*) a from Transkrip.dbo.t_head where npm='$nim'";
		$d=Yii::$app->db->createCommand($sql)->queryOne();
		if($d['a']>0){return true;}
		return false;
	}


	public static function getAlamat($id){
		$model 	= Mahasiswa::findOne($id);
		$alamat = $model->mhs->people->alamat;

		if(strripos($alamat,'||')){
			$alamat = explode("||",$alamat);
			/*$alamat = ($alamat[0]?"Jl. $alamatp[0] ,":" ").
                ($alamat[1]||$alamat[2]?"RT/RW $alamatp[1]/$alamat[2] ,":" ").
                ($alamat[3]?"Jl. $alamatp[0] ,":" ").
                ($alamat[0]?"Jl. $alamatp[0] ,":" ");

            ;//"Jl. $alamat[0], RT/RW $alamat[1]/$alamat[2] ".();
			*/

			$alamat = implode(", ",$alamat);

		}


		return $alamat;
	}

    public static function  ak(){
	    $id=Yii::$app->user->identity->id;
	    $sql="
            WITH parent AS (
                        SELECT DISTINCT parent
                        FROM auth_item_child ai
                        inner join auth_assignment aa on(aa.item_name=ai.parent and aa.user_id=$id)                        
            ), tree AS (
                SELECT x.parent, x.child
                FROM auth_item_child x
                INNER JOIN parent ON x.Parent = parent.parent
                UNION ALL
                SELECT y.parent, y.child
                FROM auth_item_child y
                INNER JOIN tree t ON y.Parent = t.child and t.parent!=y.parent
            )
            SELECT child FROM tree WHERE tree.Child like '/%'
        ";
	    $arr=[];
	    foreach (yii::$app->db->createCommand($sql)->queryAll() as $d){$arr[]=$d['child'];}
        return $arr;
    }

    public static function acc($L=""){
        if($L==""){return false;}
        $id = $id=Yii::$app->user->identity->id;
        return Yii::$app->authManager->checkAccess($id,$L);
    }


	public static function SIAP($id){
		$q = " SELECT COUNT(*) tot FROM transaksi_finger WHERE tgl_ins=CAST(GETDATE() as DATE)";
		$q =Yii::$app->db->createCommand($q)->queryOne();
		if($q['tot']>0){
			return true;
		}return false;
	}


	public static function UTS($id){
		$q = "select dbo.TotalPertemuanDosen($id) t";
		$q=Yii::$app->db->createCommand($q)->queryOne();			
		if($q['t']==1){
			return true;
		}return false;
	}



	public static function NilAkhir($id){
		$krs = Krs::findOne($id);
		$q="SELECT huruf , sks  from Transkrip.dbo.t_nilai 
			WHERE isnull(stat,0)=0
			and (krs_id_='$id' or ( tahun='".$krs->kr_kode_."' and kode_mk='".$krs->jdwl->bn->mtk_kode."' ))
			and npm='".$krs->mhs_nim."'
			order by huruf desc";
		$q=Yii::$app->db->createCommand($q)->queryOne();			
		if($q){
			return $q;
		}return false;
	}

	public static function FixNil($id){
		$krs = Krs::findOne($id);
		$q="SELECT count(*) tot from Transkrip.dbo.t_nilai 
			WHERE isnull(stat,0)=0
			and (krs_id_='$id' or ( tahun='".$krs->kr_kode_."' and kode_mk='".$krs->jdwl->bn->mtk_kode."' ))
			and npm='".$krs->mhs_nim."'
			";
		$q=Yii::$app->db->createCommand($q)->queryOne();
		if($q['tot']>0){return true;}
		return false;		
	}

	public static function TransNil($id){
		
		$q="SELECT  * from Transkrip.dbo.t_nilai WHERE krs_id_='$id' and isnull(stat,0)=0
		order by id desc
		";
		$q=Yii::$app->db->createCommand($q)->queryOne();
		if($q){return $q;}
		return false;		
	}



	public static function NFinal($id){
		$q="SELECT max(ISNULL(krs_uas,0)) s from tbl_krs WHERE jdwl_id='$id' and isnull(RStat,0)=0";
		$q=Yii::$app->db->createCommand($q)->queryOne();
		if($q['s']>0){return true;}
		return false;		
	}

	public static function BFinal($id){
		$id=(int)$id;
		$q="
		SELECT ISNULL((bn.nb_quis+bn.nb_tambahan+bn.nb_tgs1+bn.nb_tgs2+bn.nb_tgs3+bn.nb_uas+bn.nb_uas),0) bobot
			FROM tbl_jadwal jd
			INNER JOIN tbl_bobot_nilai bn on(bn.id=jd.bn_id and ISNULL(bn.RStat,0)=0)
			AND jd.jdwl_id='$id'
			AND ISNULL(jd.RStat,0)=0";
		$q=Yii::$app->db->createCommand($q)->queryOne();
		if($q['bobot']>0){return true;}
		return false;		
	}

	public static function BOLEH(){
		if((int)date('Ymd')>=20161002){return 0;}
		$nim = Yii::$app->user->identity->username;
		$sql	="select * from heleup where f1='$nim' and (f2 is null or f2 =''  or f2 ='1' ) and 1=0";
		$count	=Yii::$app->db->createCommand($sql)->queryAll();
		return count($count);
	}

	public static function BENTROK($nim){
		//if((int)date('Ymd')>=20161002){return 0;}
		//$nim = Yii::$app->user->identity->username;
		$sql	="select * from heleup where f1='$nim'";
		$count	=Yii::$app->db->createCommand($sql)->queryAll();
		return count($count);
	}



	public static function FID($nim){
		$sql	="select * from bantu where f1='$nim' and (f2 is not null or f2 !='')";
		$count	=Yii::$app->db->createCommand($sql)->queryAll();
		return count($count);
	}

	public static function Hadir($id,$s='DsId'){
		$id	=(int)$id;
		$q	="select $s from dbo.cekPergantian($id)";
		return yii::$app->db->createCommand($q)->queryOne()[$s];
	}

	public static function getDsnAttribute($name, $dsn)
    {
		
        if (preg_match('/' . $name . '=([^;]*)/', $dsn, $match)) {
            return $match[1];
        } else {
            return null;
        }
    }

	public static function strip_html( $text )
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

	public static function pagingArray($input, $page, $show_per_page) {
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


	public static function GEDUNG(){
		$Var=ArrayHelper::map(Gedung::find()->all(),'Id', 'Name');
		return $Var;
	}

	public static function Tipe(){
		$Var=ArrayHelper::map(TblTipe::find()->all(),'tp_id', 'tp_nama');
		return $Var;
	}

	public static function HARI(){
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

	public static function JURUSAN($tipe=1,$kon=''){
		$mod=Jurusan::find()->orderBy(['jr_jenjang'=>SORT_DESC])->all();
		if($kon!=''){
            $mod=Jurusan::find()->where($kon)->orderBy(['jr_jenjang'=>SORT_DESC])->all();

        }
				
		$Var=ArrayHelper::map($mod,'jr_id',
			function($model,$defaultValue){
					return $model->jr_jenjang." ".@$model->jr_nama;
			}		
		);
		
		if($tipe==2){
			$var=[];
			foreach($Var as $k=>$v){$var[]=$k;}	
			$Var=$var;
		}else if($tipe==3){
			$var=[];
			foreach($Var as $k=>$v){$var[]=$v;}
			$Var=$var;
		}
		
		return $Var;
	}



	#prv kot
	public static function PROVINSI($tipe=1,$kon=''){
		$mod=MasterProvinsi::find()->orderBy(['provinsi'=>SORT_ASC])->all();
		$Var=ArrayHelper::map($mod,'id','provinsi');
		
		if($Var==2){
			$var=[];
			foreach($Var as $k=>$v){$var[]=$v;}	
			$Var=$var;
		}else if($tipe==3){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
		return $Var;
	}
	
	public static function KOTA($tipe=1,$kon=''){
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
	#end provkot

	
	
	public static function MHS($tipe=1,$kon=''){
		$mod=Mahasiswa::find()->orderBy(['mhs_nim'=>SORT_DESC])->all();
		$Var=ArrayHelper::map($mod,'mhs_nim',
			function($model,$defaultValue){return $model->mhs_nim." : ".@$model->mhs->people->Nama;}		
		);
		
	}

	public static function PROGRAM($tipe=1){
		$Var=ArrayHelper::map(Program::find()
            ->where("jr_id is null")
            ->all(),'pr_kode','pr_nama');
		if($Var==2){
			$var=[];
			foreach($Var as $k=>$v){
				$var[]=$v;
			}	
			$Var=$var;
		}
		return $Var;
	}

	public static function KR($tipe=1){
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

	public static function MTKJNS(){
		return ['Teori','Praktek','Teori + Praktek'];
	}
	

	public static function MTK($tipe=1,$kon=''){
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

	public static function JIL($tipe=1,$kon=''){
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

	public static function FK($tipe=1,$kon=''){
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




	public static function DSN($tipe=1,$s='ds_id',$kon=''){
		$Mod=Dosen::find()
		->where("isnull(RStat,0)=0")
		->all();
		if($kon!=''){$Mod=Dosen::find()->where("isnull(RStat,0)=0")
		->andWhere($kon)
		->all();
		
		}
		
		$Var=ArrayHelper::map($Mod,$s,'ds_nm');		
		if($tipe==2){
			$var=[];foreach($Var as $k=>$v){$var[]=$k;}$Var=$var;
		}else if($tipe==3){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
		return $Var;
	}

    public static function TDS($tipe=1,$kon=''){
        $mod=DosenTipe::find()->all();
        if($kon!=''){$mod=DosenTipe::find()->where($kon)->all();}
        $Var = ArrayHelper::map($mod,'id','tipe');
        if($tipe==2){
            $var=[];foreach($Var as $k=>$v){$var[]=$k;}$Var=$var;
        }else if($tipe==3){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
        return $Var;
    }



    public static function AKADEMIK($tipe=1){
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
	
	public static function DSN1($tipe=1){
		$Var=ArrayHelper::map(Dosen::find()->all(),'ds_nidn','ds_nm');		
		if($tipe==2){
			$var=[];foreach($Var as $k=>$v){$var[]=$k;}$Var=$var;
		}else if($tipe==3){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
		return $Var;
	}
	
	public static function RUANG($tipe=1){
		$Var=ArrayHelper::map(Ruang::find()->all(),'rg_kode','rg_nama');
		
		if($tipe==2){
			$var=[];foreach($Var as $k=>$v){$var[]=$k;}$Var=$var;
		}else if($tipe==3){$var=[];foreach($Var as $k=>$v){$var[]=$v;}$Var=$var;}
		return $Var;
	}
	
	public static function DataWali($jns='thn',$kon=''){
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
	
	public static function Kalender2(){
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


	
	public static function Kalender(){
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

	public static function KalenderB(){
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

	public static function TANGGAL($date,$tipe=1){
		
		$bln=array(1=>"Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des");
		if($tipe===2){
			$bln=array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
		}
		$date1=$date;
		$date = explode("-",$date);
		if(count($date)>1){
			if($tipe===3){
				return date("F d",strtotime($date1)).'<sup>'.date("S",strtotime($date1)).'</sup> '.$date[0];
			}

			if(checkdate($date[1],$date[2],$date[0])){
				$date[1]=(int)$date[1];
				return " $date[2] ".$bln[$date[1]]." $date[0] ";
			}
		}
		return '-';
		 
	}

	public static function acak($length =5){
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
		if(!$SIAK){$SIAK	= self::getDsnAttribute('Database', $db->dsn);}	
		
		
		$q= "select * 
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
	
	public static function cekKrs($jdwl_id,$nim=""){
		if($nim==""){$nim=Yii::$app->user->identity->username;}		
		
		$model=Krs::find()->where("jdwl_id=:id and mhs_nim=:nim",[':id'=>$jdwl_id,':nim'=>$nim])
			->andWhere('(RStat !=1 or RStat is null )')->one();
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
		if(empty($id)){$id=0;}
		
		$ModTahun = Kalender::find()->select(['kln_krs_lama'=>"datediff(day,'$date',DATEADD(day,kln_krs_lama+1,kln_krs))"])
		->where("kln_id=$id AND getdate() BETWEEN kln_krs and DATEADD(DAY, kln_krs_lama,kln_krs)")
		->one();
		//die (print_r($ModTahun->kln_krs_lama));
		if(@$ModTahun->kln_krs_lama){return @$ModTahun->kln_krs_lama >=0;}
		return false;
		
	}

	public static function ResetPass($var){
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

	public static function getProgramKeuangan($nim){
		$con = yii::$app->db1;
		$q		= "select * from student s
			where s.nim='$nim'";
		$q=$con->createCommand($q)->queryOne();//or die($q);
		//print_r($q);die();
		return $q['program_id'];		
	}

	public static function barcode($nim,$kr){
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
		

	public static function cekPassDef(){
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

	public static function LOGS($data,$TB="",$ID="",$CD="",$SESSION=true){

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
	
	public static function DATA($TB,$ID){
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

	public static function TABLES($TABLE){
		$connection = Yii::$app->db;//get connection
		$jr= new $TABLE;
		die(print_r($jr->tableSchema));
		$dbSchema = $connection->schema;
		$tables = $dbSchema->tableNames;
		foreach($tables as $k=>$v){
			echo $v." <br />";
		}
	}

	public static function DOSEN($id){
		$model = Dosen::find()->where("ds_user='$id'")->one();
		if($model){return $model->ds_nm;}
		return false;
	}


	public static function absen($id){
		$model = Absensi::find()->where("krs_id='$id' and jdwl_stat='1' and (RStat='0' or RStat is null)")->count();
		return $model;
	}

	public static function absen3($jdw,$krs){
		$sql="SELECT * from dbo.PersenAbsenMhs($jdw,$krs)";
		return $sql	=Yii::$app->db->createCommand($sql)->queryOne();
	}

	public static function absen2($id,$nim){
		$sql		="SELECT * from dbo.absensiPerjadwal_v2($id) where npm='$nim'";
		return $sql	=Yii::$app->db->createCommand($sql)->queryOne();
	}

	public static function IconAbs($t,$s=''){
		$i=[
			'M'=>'&radic;',
			'D'=>'D',
			'I'=>'I',
			'A'=>'X'
		];
		if($t=='M'){$s=1;}
		if($t=='A'){$s=0;}
		$bg=['red;','green;'];
		return '<span class="btn badge label-primary" style="background:'.$bg[$s].'border:inset 1px #000;"><b>'.$i[$t].'</b></span>';

	}

	public static function StatAbsDsn($id,$s){
		$q="exec dbo.StatAbsDsn $id,$s";
		$q=Yii::$app->db->createCommand($q)->queryOne();
		return $q['stat'];
	}


	public static function ATT($id){
		$model = Krs::findOne($id);
		$mhs = Mahasiswa::findOne($model->mhs_nim);
		$absensi=Funct::absen3($model->jdwl_id,$model->krs_id);
		$kurang = $absensi['kehadiran'] - $absensi['pertemuan'];

		$err='';
		$abs=0;
		
		$kr=(int)substr($model->kr_kode_,1,4);
		//echo "<!-- $kurang -->";
		if($absensi['persen']){
			$abs = round($absensi['persen']);
		}
		
		if($kr<1617){if(Funct::absen($id)){$abs = Funct::absen($id)/($mhs->pr_kode==1?12:14)*100;}}
		
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
		

		/*		
		if($kr>=1617){
			if($abs < 40){
				//$err[3]="Absen Kurang Dari 70%";
				if($kurang > 3){
					$err[3]="Tidak Hadir Lebih Dari 3X"."<!-- $abs --> ";
				}
			}			
		}else{
			if($abs < 75){
				$err[3]="Absen Kurang Dari 75%";
			}						
		}
		//if($kurang > 3){$err[3]="Tidak Hadir Lebih Dari 3X"."<!-- $kurang -->";}
		*/
		return $err;
	}

	public static function AKSES(){
		$sql = "select * from auth_item where name not like '/%'";
		$sql = Yii::$app->db->createCommand($sql)->queryAll();
		$data="";
		foreach($sql as $d){$data[$d['name']]=$d['name'];}
		return $data;
	}

	public static function TotNil($id){
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

	public static function TotNil1($id){
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

	public static function StatNilDos($id){
		$sql=" select  sum(krs_tot) tot from tbl_krs WHERE jdwl_id='$id'";
		$sql = Yii::$app->db->createCommand($sql)->queryOne();
		return $sql['tot'];
	}

	public static function Kuota($id){
		$ModJadwal = Jadwal::findOne($id);
		$sql=" select  count(*) tot 
			from tbl_krs krs, tbl_jadwal jd,tbl_bobot_nilai bn,tbl_kalender kl
			WHERE krs.jdwl_id=jd.jdwl_id
			and bn.id=jd.bn_id
			and kl.kln_id=bn.kln_id
			and bn.mtk_kode in('".$ModJadwal->bn->mtk_kode."')
			and kl.kr_kode='".$ModJadwal->bn->kln->kr_kode."'
			and (
					(krs.RStat is null or krs.RStat='0')
				and (bn.RStat is null or bn.RStat='0')
				and	(jd.RStat is null or jd.RStat='0')
			)
			";
		$sql = Yii::$app->db->createCommand($sql)->queryOne();
		
		if($ModJadwal->rg->kapasitas===NULL){
			return true;
		}else{
			if((int)$ModJadwal->rg->kapasitas - $sql['tot'] < 1){return false;}
			return ((int)$ModJadwal->rg->kapasitas - $sql['tot']);	
		}
		
	}

	public static function Kuota1($id){
		$ModJadwal = Jadwal::findOne($id);
		$sql=" select  count(distinct krs_id) tot 
			from tbl_krs krs, tbl_jadwal jd,tbl_bobot_nilai bn,tbl_kalender kl
			WHERE krs.jdwl_id=jd.jdwl_id
			and bn.id=jd.bn_id
			and kl.kln_id=bn.kln_id
			and bn.mtk_kode in('".$ModJadwal->bn->mtk_kode."')
			and kl.kr_kode		='".$ModJadwal->bn->kln->kr_kode."'
			and jd.jdwl_hari	='".$ModJadwal->jdwl_hari."'
			and jd.jdwl_masuk	='".$ModJadwal->jdwl_masuk."'
			and bn.ds_nidn		='".$ModJadwal->bn->ds_nidn."'
			and (
					(krs.RStat is null or krs.RStat='0')
				and (bn.RStat is null or bn.RStat='0')
				and	(jd.RStat is null or jd.RStat='0')
			)
			";
		$sql = Yii::$app->db->createCommand($sql)->queryOne();
		return $sql['tot'];
	}

	public static function CekTranskrip($nim,$kode){
		$model = \app\modules\transkrip\models\Nilai::findOne(['npm'=>$nim,'kode_mk'=>$kode,'stat'=>['0',NULL]]);
		if($model){return true;}
		return false;
	}

	public static function CekKonversi($nim){
		$model = \app\modules\transkrip\models\Nilai::find()
//		->leftJoin()
		->where(['npm'=>$nim,'kat'=>'1','stat'=>['0',NULL]])
		//->all()
		;
		if($model){return $model;}
		return false;
	}

	public static function CekKodeTranskrip($kon){
		$model = \app\modules\transkrip\models\Nilai::find()
		->where($kon)
		->orderBy(['substring(tahun,2,4)'=>SORT_ASC,'substring(tahun,1,1)'=>SORT_ASC,])
		->all();
		if($model){return $model;}
		return false;
	}

	public static function StatLock($kode){
		$Lock=[
				'1'=>'Tugas 1',
				'10'=>'Tugas 2',
				'100'=>'Tugas 3',
				'1000'=>'Quiz',
				'10000'=>'UTS',
				'100000'=>'UAS',
				'1000000'=>'Transkrip',
		];
		$kode	= decbin((int)$kode);
		$Arr="";
		foreach(str_split($kode) as $k=>$v){
			$n++;
			if($v!='0'){$Arr[$v.str_repeat('0',strlen($kode)-$n)]=$Lock[$v.str_repeat('0',strlen($kode)-$n)];}
		}
		if($Arr!=''){
			return $Arr;
		}
		return false;
	}

	public static function StatAkses($kode){
		$Lock=[
				//'1'=>'',
				//'10'=>'',
				//'100'=>'',
				//'1000'=>'Quiz',
				//'10000'=>'',
				//'100000'=>'',
				'1000000'=>'Transkrip',
		];
		$kode	= decbin((int)$kode);
		$Arr="";
		foreach(str_split($kode) as $k=>$v){
			$n++;
			if($v!='0'){$Arr[$v.str_repeat('0',strlen($kode)-$n)]=$Lock[$v.str_repeat('0',strlen($kode)-$n)];}
		}
		if($Arr!=''){
			return $Arr;
		}
		return false;
	}

	public static function MtkUjian($id){
		$MOdUjian = Ujian::findOne($id);
		$sql="
			select distinct mtk_kode from tbl_jadwal jd, ujian uj , tbl_bobot_nilai bn
			where uj.GKode=jd.GKode
			and bn.id=jd.bn_id
			and uj.Id='$id'
		";
		$rec="";
		foreach (Yii::$app->db->createCommand($sql)->queryAll()as $d){
			$rec.=",$d[mtk_kode]";	
		}
		if($rec!=""){ $rec = substr($rec,1);}
		return $rec;
			
	}


	public static function TanggalPengganti($Hari){
		$db = Yii::$app->db;
		$query = "EXEC GetDateRangePengganti $Hari";
		$Tanggal = $db->createCommand($query)->queryAll();
		$arr=array();
		$arr['NULL#NULL']="- Tanggal Pengganti -";
		if($Tanggal){
			foreach($Tanggal as $data){

				$arr[$data['DATE']] = $data['DATE']." - ".$data['DayName'];
			}
		}

		
	 
		return $arr;
	}
	

}
