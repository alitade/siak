<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "konsultan_program".
 *
 * @property integer $konsultan_id
 * @property integer $program_id
 * @property integer $jurusan_id
 */
class KonsultanProgram extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'konsultan_program';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['konsultan_id', 'program_id', 'jurusan_id'], 'required'],
            [['konsultan_id', 'program_id', 'jurusan_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'konsultan_id' => 'Konsultan ID',
            'program_id' => 'Program ID',
            'jurusan_id' => 'Jurusan ID',
        ];
    }

    public function getJr(){return $this->hasOne(Jurusan::className(),['id' => 'jurusan_id']);}
    public function getPr(){return $this->hasOne(Program::className(),['id' => 'program_id']);}
    public function getKon(){return $this->hasOne(Konsultan::className(),['id' => 'konsultan_id']);}


}
