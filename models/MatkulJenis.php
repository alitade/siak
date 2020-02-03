<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "matkul_jenis".
 *
 * @property integer $id
 * @property string $kode
 * @property string $jenis
 */
class MatkulJenis extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'matkul_jenis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['kode', 'jenis'], 'string'],
            [['jenis'], 'unique'],
            [['kode'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode' => 'Kode',
            'jenis' => 'Jenis',
        ];
    }
}
