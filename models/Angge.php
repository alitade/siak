<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "angge".
 *
 * @property integer $Id
 * @property string $Fk
 * @property string $Username
 * @property string $Pass
 * @property string $PassKode
 * @property string $Tipe
 */
class Angge extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'angge';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Fk', 'Username', 'Pass', 'PassKode', 'Tipe'], 'string'],
            [['Fk'], 'unique'],
            [['Username'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Fk' => 'Fk',
            'Username' => 'Username',
            'Pass' => 'Pass',
            'PassKode' => 'Pass Kode',
            'Tipe' => 'Tipe',
        ];
    }
}
