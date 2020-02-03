<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cuti".
 *
 * @property integer $id
 * @property integer $idbayar
 * @property string $nim
 * @property string $tahun
 * @property integer $semester
 */
class Cuti extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cuti';
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
            [['idbayar', 'semester'], 'integer'],
            [['nim', 'tahun'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idbayar' => 'Idbayar',
            'nim' => 'Nim',
            'tahun' => 'Tahun',
            'semester' => 'Semester',
        ];
    }
}
