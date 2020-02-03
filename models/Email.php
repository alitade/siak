<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "email".
 *
 * @property integer $Id
 * @property string $mhs_nim
 * @property string $email
 * @property string $aktif
 */
class Email extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mhs_nim', 'email'], 'required'],
            [['mhs_nim', 'email', 'aktif'], 'string'],
            [['email'], 'unique'],
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
            'email' => 'Email',
            'aktif' => 'Aktif',
        ];
    }
}
