<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "informasipendaftaran".
 *
 * @property integer $id
 * @property string $jenis_informasi
 */
class Informasipendaftaran extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'informasipendaftaran';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jenis_informasi'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jenis_informasi' => 'Jenis Informasi',
        ];
    }
}
