<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master_kota".
 *
 * @property int $id
 * @property string $kota
 * @property int $provinsi_id
 */
class MasterKota extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'master_kota';
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
            [['kota', 'provinsi_id'], 'required'],
            [['kota'], 'string'],
            [['provinsi_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kota' => 'Kota',
            'provinsi_id' => 'Provinsi ID',
        ];
    }
}
