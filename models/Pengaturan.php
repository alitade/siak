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
class Pengaturan extends \yii\db\ActiveRecord{

    public static function tableName(){
        return 'aturan';
    }


    public function rules(){
        return [
            [['id','kd','ket','nil','aktif',], 'required'],
            [['parent','lock','def','input','satuan','cuid','ctgl','uuid','utgl','duid','dtgl',],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */

}
