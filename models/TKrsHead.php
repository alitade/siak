<?php

namespace app\models;

use Yii;

class TKrsHead extends \yii\db\ActiveRecord{

    public static function tableName(){return 't_krs_head';}
    public static $ID=151;

    public function rules(){
        return [
            [['kode', 'nim', 'kr_kode'], 'required'],
            [['kode', 'nim', 'kr_kode', 'status', 'app', 'Rstat'], 'string'],
            [['id', 'ds_id', 'cuid', 'uuid', 'duid'], 'integer'],
            [['app_date', 'ctgl', 'utgl', 'dtgl','tf'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'kode' => 'Kode',
            'id' => 'ID',
            'nim' => 'Nim',
            'ds_id' => 'Ds ID',
            'kr_kode' => 'Kr Kode',
            'status' => 'Status',
            'app' => 'App',
            'app_date' => 'App Date',
            'cuid' => 'Cuid',
            'ctgl' => 'Ctgl',
            'uuid' => 'Uuid',
            'utgl' => 'Utgl',
            'duid' => 'Duid',
            'dtgl' => 'Dtgl',
            'Rstat' => 'Rstat',
        ];
    }

    public function getMhs(){return $this->hasOne(Mahasiswa::className(), ['mhs_nim' => 'nim']);}
    public function getDs(){return $this->hasOne(Dosen::className(), ['ds_id' => 'ds_id']);}
    public function getKr(){return $this->hasOne(Kurikulum::className(), ['kr_kode' => 'kr_kode']);}
    public function getKrsdet(){return $this->hasMany(TKrsDet::className(), ['id_head' => 'id']);}

    }
