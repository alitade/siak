<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "student".
 *
 * @property integer $id
 * @property string $identitas_id
 * @property string $no_registrasi
 * @property string $nim
 * @property string $angkatan
 * @property string $kurikulum
 * @property string $jurusan
 * @property string $program_id
 * @property string $status_mhs
 */
class KStudent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db1');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identitas_id', 'no_registrasi', 'nim', 'angkatan', 'kurikulum', 'jurusan', 'program_id', 'status_mhs'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'identitas_id' => 'Identitas ID',
            'no_registrasi' => 'No Registrasi',
            'nim' => 'Nim',
            'angkatan' => 'Angkatan',
            'kurikulum' => 'Kurikulum',
            'jurusan' => 'Jurusan',
            'program_id' => 'Program ID',
            'status_mhs' => 'Status Mhs',
        ];
    }
}
