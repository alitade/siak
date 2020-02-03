<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "phone".
 *
 * @property integer $Id
 * @property string $mhs_nim
 * @property string $phone
 * @property string $aktif
 */
class Phone extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'phone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mhs_nim', 'phone'], 'required'],
            [['mhs_nim', 'phone', 'aktif'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'mhs_nim' => 'Mhs Nim',
            'phone' => 'Phone',
            'aktif' => 'Aktif',
        ];
    }
}
