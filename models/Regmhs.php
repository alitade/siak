<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "regmhs".
 *
 * @property integer $id
 * @property string $nim
 * @property string $tahun
 * @property string $tanggal
 */
class Regmhs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(){return 'regmhs';}

    #DB KEUANGAN
    public static function getDb(){return Yii::$app->get('db1');}


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nim', 'tahun'], 'string'],
            [['tanggal'], 'safe'],
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
            'tanggal' => 'Tanggal',
        ];
    }
}
