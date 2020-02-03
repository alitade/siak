<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jenis_pekerjaan".
 *
 * @property integer $id
 * @property string $jenis_pekerjaan
 */
class KJenisPekerjaan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenis_pekerjaan';
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
            [['jenis_pekerjaan'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jenis_pekerjaan' => 'Jenis Pekerjaan',
        ];
    }
}
