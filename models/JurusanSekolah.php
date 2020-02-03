<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jurusansekolah".
 *
 * @property integer $id
 * @property string $nama_jurusan
 */
class Jurusansekolah extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jurusansekolah';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_jurusan'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_jurusan' => 'Nama Jurusan',
        ];
    }
}
