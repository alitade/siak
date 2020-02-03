<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pkrs".
 *
 * @property string $kr_kode
 * @property string $mhs_nim
 * @property string $tgl_awal
 * @property string $tgl_akhir
 */
class Pkrs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pkrs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kr_kode', 'mhs_nim'], 'required'],
            [['kr_kode', 'mhs_nim'], 'string'],
            [['tgl_awal', 'tgl_akhir'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kr_kode' => 'Kr Kode',
            'mhs_nim' => 'Mhs Nim',
            'tgl_awal' => 'Tgl Awal',
            'tgl_akhir' => 'Tgl Akhir',
        ];
    }
}
