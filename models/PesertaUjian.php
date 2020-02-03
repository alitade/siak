<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "peserta_ujian".
 *
 * @property integer $Id
 * @property integer $IdUjian
 * @property integer $Krs_id
 * @property integer $jdwl_id_
 * @property string $RStat
 */
class PesertaUjian extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'peserta_ujian';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdUjian', 'Krs_id', 'jdwl_id_'], 'integer'],
            [['RStat'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'IdUjian' => 'Id Ujian',
            'Krs_id' => 'Krs ID',
            'jdwl_id_' => 'Jdwl ID',
            'RStat' => 'Rstat',
        ];
    }
}
