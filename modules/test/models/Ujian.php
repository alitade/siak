<?php

namespace app\modules\test\models;

use Yii;

/**
 * This is the model class for table "ujian".
 *
 * @property integer $Id
 * @property integer $IdJadwal
 * @property string $Kat
 * @property string $Tgl
 * @property string $Masuk
 * @property string $Keluar
 * @property string $RgKode
 */
class Ujian extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ujian';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdJadwal'], 'integer'],
            [['Kat', 'Masuk', 'Keluar', 'RgKode'], 'string'],
            [['Tgl'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'IdJadwal' => 'Id Jadwal',
            'Kat' => 'Kat',
            'Tgl' => 'Tgl',
            'Masuk' => 'Masuk',
            'Keluar' => 'Keluar',
            'RgKode' => 'Rg Kode',
        ];
    }
}
