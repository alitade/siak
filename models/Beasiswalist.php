<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "beasiswalist".
 *
 * @property integer $id
 * @property string $nim
 * @property string $tahun
 * @property integer $jenis
 */
class Beasiswalist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'beasiswalist';
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
            [['nim', 'tahun'], 'string'],
            [['jenis'], 'integer'],
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
            'tahun' => 'Tahun',
            'jenis' => 'Jenis',
        ];
    }
}
