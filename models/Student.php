<?php

namespace app\models;

use Yii;

class Student extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb(){
        return Yii::$app->get('db1');
    }

    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            [['identitas_id', 'no_registrasi', 'nim', 'angkatan', 'kurikulum', 'jurusan', 'program_id', 'status_mhs'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'identitas_id' => 'Identitas ID',
            'no_registrasi' => 'No Registrasi',
            'nim' => 'Nim',
            'angkatan' => 'Angkatan',
            'kurikulum' => 'Kurikulum',
            'jurusan' => 'Jurusan',
            'program_id' => 'Program ID',
            'status_mhs' => 'Status Mhs',
        ];
    }

    public function getPeople(){
        return $this->hasOne(People::className(), ['No_Registrasi' => 'no_registrasi']);
    }

    public function getBio(){
        return $this->hasOne(People::className(), ['No_Registrasi' => 'no_registrasi']);
    }

}
