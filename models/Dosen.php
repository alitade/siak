<?php

namespace app\models;

use Yii;

class Dosen extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName(){
        return 'tbl_dosen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ds_user','ds_email','ds_nm','id_tipe'], 'required'],
            [['ds_nidn', 'ds_user', 'ds_pass', 'ds_pass_kode', 'ds_nm', 'ds_kat', 'ds_email', 'RStat'], 'string'],
            [['ds_email'], 'email'],
            [['ds_tipe'], 'integer'],
            #[['ds_nidn'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ds_id' => 'ID',
            'ds_nidn' => 'Nidn',
            'ds_user' => 'Username',
            'ds_pass' => 'Password',
            'ds_pass_kode' => '??',
            'ds_nm' => 'Nama Dosen',
            'ds_tipe' => 'Tipe',
            'ds_kat' => 'Kategori',
            'ds_email' => 'Email',
            'RStat' => ' ',
        ];
    }

    public function getJr()
    {
        return $this->hasOne(Jurusan::className(), ['jr_id' => 'jr_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKr()
    {
        return $this->hasOne(Kurikulum::className(), ['kr_kode' => 'kr_kode']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPr(){return $this->hasOne(Program::className(), ['pr_kode' => 'pr_kode']);}
    public function getTipe(){return $this->hasOne(DosenTipe::className(), ['id' => 'id_tipe']);}
    public function getWali(){return $this->hasOne(DosenWali::className(), ['ds_id' => 'ds_id']);}
}
