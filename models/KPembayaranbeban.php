<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pembayaranbeban".
 *
 * @property integer $id
 * @property string $nim
 * @property string $idbeban
 * @property string $sisa
 * @property string $status
 * @property string $tahun
 */
class KPembayaranbeban extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pembayaranbeban';
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
            [['nim', 'idbeban', 'sisa', 'status', 'tahun'], 'required'],
            [['nim', 'idbeban', 'status', 'tahun'], 'string'],
            [['sisa'], 'number'],
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
            'idbeban' => 'Idbeban',
            'sisa' => 'Sisa',
            'status' => 'Status',
            'tahun' => 'Tahun',
        ];
    }
}
