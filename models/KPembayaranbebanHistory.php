<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pembayaranbeban_history".
 *
 * @property integer $id
 * @property integer $idbayar
 * @property string $tanggal
 * @property string $jtu
 * @property string $jumlah
 * @property string $keterangan
 */
class KPembayaranbebanHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pembayaranbeban_history';
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
            [['idbayar', 'tanggal', 'jumlah'], 'required'],
            [['idbayar'], 'integer'],
            [['tanggal', 'jtu'], 'safe'],
            [['jumlah'], 'number'],
            [['keterangan'], 'string'],
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
            'tanggal' => 'Tanggal',
            'jtu' => 'Jtu',
            'jumlah' => 'Jumlah',
            'keterangan' => 'Keterangan',
        ];
    }
}
