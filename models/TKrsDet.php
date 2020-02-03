<?php

namespace app\models;

use Yii;

class TKrsDet extends \yii\db\ActiveRecord{

    public static function tableName(){return 't_krs_det';}
    public static  $ID=152;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','id_head', 'jdwl_id', 'mtk_sks', 'cuid', 'uuid', 'duid'], 'integer'],
            [['mtk_kode', 'mtk_nama', 'mhs_nim', 'kr_kode', 'krs_stat', 'ket', 'krs_ulang', 'RStat'], 'string'],
            [['mhs_nim','id_head', 'cuid'], 'required'],
            [['tgl','kode', 'tgl_jdwl', 'ctgl', 'utgl', 'dtgl'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jdwl_id' => 'Jdwl ID',
            'mtk_kode' => 'Mtk Kode',
            'mtk_sks' => 'Mtk Sks',
            'mtk_nama' => 'Mtk Nama',
            'mhs_nim' => 'Mhs Nim',
            'kr_kode' => 'Kr Kode',
            'tgl' => 'Tgl',
            'tgl_jdwl' => 'Tgl Jdwl',
            'krs_stat' => 'Krs Stat',
            'ket' => 'Ket',
            'krs_ulang' => 'Krs Ulang',
            'RStat' => 'Rstat',
            'cuid' => 'Cuid',
            'ctgl' => 'Ctgl',
            'uuid' => 'Uuid',
            'utgl' => 'Utgl',
            'duid' => 'Duid',
            'dtgl' => 'Dtgl',
        ];
    }

    public function getJdwl(){return $this->hasOne(Jadwal::className(), ['jdwl_id' => 'jdwl_id']);}
    public function getHead(){return $this->hasOne(TKrsHead::className(), ['id' => 'id_head']);}


}
