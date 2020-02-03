<?php

namespace app\models;

use Yii;

class MatkulKr extends \yii\db\ActiveRecord{
    public static function tableName(){
        return 'matkul_kr';
    }

    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            [['kode', 'jr_id','cuid','ket'], 'required'],
            [['kode',], 'uniqueKode'],
            [['kode', 'ket', 'Rstat', 'lock', 'aktif'], 'string'],
            [['totSks', 'cuid', 'uuid', 'duid'], 'integer'],
            [['ctgl', 'utgl', 'dtgl'], 'safe'],
        ];
    }

    #Innline validator
    public function uniqueKode($attribute, $params, $validator){
        $model = MatkulKr::find()->where(['kode'=>$this->kode,'jr_id'=>$this->jr_id,'isnull(Rstat,0)'=>0])->count();
        if($model > 0){
            $this->addError($attribute, 'Kode '.$this->kode." untuk jurusan ".Funct::JURUSAN()[$this->jr_id]." Sudah Ada");
        }

    }
    #end validator

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kode' => 'Kode',
            'ket' => 'Keterangan',
            'totSks' => 'Sks',
            'cuid' => 'Cuid',
            'ctgl' => 'Ctgl',
            'jr_id'=>'Jurusan',
            'uuid' => 'Uuid',
            'utgl' => 'Utgl',
            'duid' => 'Duid',
            'dtgl' => 'Dtgl',
            'Rstat' => 'Rstat',
            'lock' => 'Lock',
            'aktif' => 'Aktif',
        ];
    }


    public function getMkDet(){
        return $this->hasMany(MatkulKrDet::className(),['id_kr'=>'id'])
            ->andOnCondition(["isnull(matkul_kr_det.Rstat,0)"=>0]);
    }

    public function getMhsAll(){
        return $this->hasMany(Mahasiswa::className(),['mk_kr'=>'id'])
            ->andOnCondition(["isnull(tbl_mahasiswa.RStat,0)"=>0]);
    }

    public function getSubKr(){
        return $this->hasMany(MatkulKr::className(),['parent'=>'id'])
            ->andOnCondition(["isnull(matkul_kr.Rstat,0)"=>0]);
    }

    public function getPr(){return $this->hasOne(Program::className(),['pr_kode'=>'pr_id']);}

    public function getJr(){return $this->hasOne(Jurusan::className(), ['jr_id' => 'jr_id']);}

}
