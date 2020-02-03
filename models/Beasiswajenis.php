<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "beasiswajenis".
 *
 * @property integer $id
 * @property string $namabeasiswa
 * @property string $jenispotongan
 * @property string $jumlah
 */
class Beasiswajenis extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'beasiswajenis';
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
            [['namabeasiswa', 'jenispotongan'], 'string'],
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
            'namabeasiswa' => 'Namabeasiswa',
            'jenispotongan' => 'Jenispotongan',
            'jumlah' => 'Jumlah',
        ];
    }
}
