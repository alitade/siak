<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "absen_awal".
 *
 * @property string $id
 * @property resource $GKode
 * @property string $jdwl_masuk
 * @property string $jdwl_keluar
 * @property string $tgl
 * @property string $ds_masuk
 * @property string $ds_keluar
 * @property string $tipe
 * @property integer $ds_fid
 */
class AbsenAwal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'absen_awal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'GKode', 'tipe'], 'string'],
            [['GKode','jdwl_keluar', 'ket'], 'required'],

            [['jdwl_masuk', 'jdwl_keluar', 'tgl', 'ds_masuk', 'ds_keluar'], 'safe'],
            [['ds_fid'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'GKode' => 'Gkode',
            'jdwl_masuk' => 'Jdwl Masuk',
            'jdwl_keluar' => 'Jdwl Keluar',
            'tgl' => 'Tgl',
            'ds_masuk' => 'Ds Masuk',
            'ds_keluar' => 'Ds Keluar',
            'tipe' => 'Tipe',
            'ds_fid' => 'Ds Fid',
        ];
    }
}
