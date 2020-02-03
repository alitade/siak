<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_absensi".
 *
 * @property integer $id
 * @property integer $krs_id
 * @property integer $jdwl_id_
 * @property string $jdwl_stat
 * @property string $jdwal_tgl
 * @property string $jdwl_sesi
 *
 * @property TblKrs $krs
 */
class Aturan extends \yii\db\ActiveRecord{

    public $nil,$anv;
    public $sesiUts,$sesiUas,$t1,$uts,$t2,$uas,$gB,$gC,$gD,$gE,
    $mdMin,$mdMax,$kdMin,$kdMax,$mmMin,$mmMax,$kmMin,$kmMax,
    $vAbsM,
    $sesiUts1,$sesiUts2,$sesiUts4,$sesiUts5,$sesiUas1,$sesiUas2,$sesiUas4,$sesiUas5,
    $vdSesi,$vdSks,$vdGrade,
    $t1_1,$uts_1,$t2_1,$uas_1,$gB_1,$gC_1,$gD_1,$gE_1,
    $t1_2,$uts_2,$t2_2,$uas_2,$gB_2,$gC_2,$gD_2,$gE_2,
    $mdMin1,$mdMax1,$kdMin1,$kdMax1,$mmMin1,$mmMax1,$kmMin1,$kmMax1,
    $mdMin2,$mdMax2,$kdMin2,$kdMax2,$mmMin2,$mmMax2,$kmMin2,$kmMax2;


    public function rules(){
        return [
            [[
                'sesiUas','t1','uts','t2','uas','gB','gC','gD','gE','mdMin','mdMax','kdMin','kdMax','mmMin','mmMax','kmMin','kmMax','vAbsM','anv','sesiUts1','sesiUts2','sesiUts4','sesiUts5','sesiUas1','sesiUas2','sesiUas4','sesiUas5','vdSesi','vdSks','vdGrade','t1_1','uts_1','t2_1','uas_1','gB_1','gC_1','gD_1','gE_1','t1_2','uts_2','t2_2','uas_2','gB_2','gC_2','gD_2','gE_2','mdMin1','mdMax1','kdMin1','kdMax1','mmMin1','mmMax1','kmMin1','kmMax1','mdMin2','mdMax2','kdMin2','kdMax2','mmMin2','mmMax2','kmMin2','kmMax2',

            ], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sesiUts' => 'Min. Sesi UTS',
            'sesiUts1' => 'Min. Sesi UTS',
            'anv' => 'Kelas Anvulen',
        ];
    }

}
