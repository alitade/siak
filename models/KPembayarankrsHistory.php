<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pembayarankrs_history".
 *
 * @property integer $id
 * @property integer $idbayar
 * @property string $tanggal
 * @property string $jtu
 * @property string $keterangan
 * @property string $jumlah
 * @property string $janjibayar
 */
class KPembayarankrsHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pembayarankrs_history';
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
            [['tanggal', 'jtu', 'janjibayar'], 'safe'],
            [['keterangan'], 'string'],
            [['jumlah'], 'number'],
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
            'keterangan' => 'Keterangan',
            'jumlah' => 'Jumlah',
            'janjibayar' => 'Janjibayar',
        ];
    }
}
