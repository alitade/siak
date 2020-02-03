<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "konsultan".
 *
 * @property string $kode
 * @property string $vendor
 * @property string $email
 * @property string $tlp
 * @property string $pic
 */
class Konsultan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'konsultan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode'], 'required'],
            [['kode', 'vendor', 'email', 'tlp', 'pic'], 'string'],
            [['vendor'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kode' => 'Kode',
            'vendor' => 'Vendor',
            'email' => 'Email',
            'tlp' => 'Tlp',
            'pic' => 'Pic',
        ];
    }
}
