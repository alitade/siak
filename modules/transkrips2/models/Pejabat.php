<?php

namespace app\modules\transkrip\models;

use Yii;

/**
 * This is the model class for table "pejabat".
 *
 * @property integer $id
 * @property string $nama
 * @property string $jabatan
 * @property string $kode_fakultas
 * @property string $kode_jurusan
 * @property integer $ds_id
 * @property string $status
 * @property string $thn_jabatan
 */
class Pejabat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pejabat';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama', 'jabatan'], 'required'],
            [['nama', 'jabatan', 'kode_fakultas', 'kode_jurusan', 'status', 'thn_jabatan'], 'string'],
            [['ds_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
            'jabatan' => 'Jabatan',
            'kode_fakultas' => 'Kode Fakultas',
            'kode_jurusan' => 'Kode Jurusan',
            'ds_id' => 'Ds ID',
            'status' => 'Status',
            'thn_jabatan' => 'Thn Jabatan',
        ];
    }
}
