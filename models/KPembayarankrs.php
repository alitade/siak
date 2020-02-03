<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pembayarankrs".
 *
 * @property integer $id
 * @property string $nim
 * @property integer $semester
 * @property string $tahun
 * @property string $dpp
 * @property string $sks
 * @property string $praktek
 * @property string $sisa
 * @property string $status
 */
class KPembayarankrs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pembayarankrs';
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
            [['nim', 'tahun', 'status'], 'string'],
            [['semester'], 'integer'],
            [['dpp', 'sks', 'praktek', 'sisa'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nim' => 'Nim',
            'semester' => 'Semester',
            'tahun' => 'Tahun',
            'dpp' => 'Dpp',
            'sks' => 'Sks',
            'praktek' => 'Praktek',
            'sisa' => 'Sisa',
            'status' => 'Status',
        ];
    }
}
