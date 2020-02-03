<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
/**
 * This is the model class for table "tbl_jadwal".
 *
 * @property integer $jdwl_id
 * @property integer $bn_id
 * @property string $rg_kode
 * @property string $semester
 * @property string $jdwl_hari
 * @property string $jdwl_masuk
 * @property string $jdwl_keluar
 * @property string $jdwl_kls
 * @property string $jdwl_uts
 * @property string $jdwl_uas
 * @property string $jdwl_uts_out
 * @property string $jdwl_uas_out
 * @property string $rg_uts
 * @property string $rg_uas
 *
 * @property TblBobotNilai $bn
 * @property TblRuang $rgKode
 * @property TblJadwalTmp[] $tblJadwalTmps
 * @property TblKrs[] $tblKrs
 */
class JadwalDosen extends Jadwal
{
     public $jr_id;
     public $pr_kode;
     public $kr_kode;

     public function rules()
    {
        return [
           
            [['jr_id', 'pr_kode', 'kr_kode'], 'safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'jr_id' => 'Jurusan',
            'pr_kode' => 'Program',
            'kr_kode' => 'Tahun Akademik',
        ];
    }

    public static function formatAttendance($model,$value,$Sesi,$mtk){
	    $class = ((int)$value == 1) ? "btn glyphicon glyphicon-ok-circle": 
	                                  "btn glyphicon glyphicon-remove-circle";
	    $color = ((int)$value == 1) ? "color:green": "color:red";
	    return  Html::a('', ['attendance', 
	                        'id'		=>	$model['jdwl_id'],
	                        'matakuliah'=>  $mtk,
	                        'sort'		=>  'id',
	                        'sesi'		=> $Sesi,
	                        'token'		=>  base64_encode(serialize([
					                        'id'		=>	$model['jdwl_id'],
					                        'mhs'		=>	$model['mhs_nim'],
					                        'krs_id'	=>	$model['krs_id'],
					                        'sesi'  	=> 	(int)$Sesi,
	                        				]))	
	                        ], [
	            'data-pjax' => 'pjaxAttendance',
	            'class' => $class,
	            'style'=>"cursor:pointer;text-decoration:none;$color",
	        ]);
	}

 

}