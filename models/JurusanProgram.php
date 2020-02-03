<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jurusan_program".
 *
 * @property string $jr_id
 * @property string $program_id
 */
class JurusanProgram extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jurusan_program';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jr_id', 'program_id'], 'required'],
            [['jr_id', 'program_id'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'jr_id' => 'Jr ID',
            'program_id' => 'Program ID',
        ];
    }

    public function getJr(){return $this->hasOne(Jurusan::className(),['jr_id' => 'jr_id']);}
    public function getPr(){return $this->hasOne(Jurusan::className(),['program_id' => 'pr_kode']);}

}
