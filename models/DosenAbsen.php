<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dosen_absen".
 *
 * @property string $id
 * @property integer $jdwl_id
 * @property string $ds_id
 * @property string $sesi
 * @property string $tgl_absen
 * @property string $masuk
 * @property string $keluar
 * @property string $RStat
 * @property string $status
 */
class DosenAbsen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dosen_absen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jdwl_id', 'ds_id', 'sesi'], 'required'],
            [['jdwl_id', 'ds_id'], 'integer'],
            [['sesi', 'masuk', 'keluar', 'RStat', 'status'], 'string'],
            [['tgl_absen'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jdwl_id' => 'Jdwl ID',
            'ds_id' => 'Ds ID',
            'sesi' => 'Sesi',
            'tgl_absen' => 'Tgl Absen',
            'masuk' => 'Masuk',
            'keluar' => 'Keluar',
            'RStat' => 'Rstat',
            'status' => 'Status',
        ];
    }
}
