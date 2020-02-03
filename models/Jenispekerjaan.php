<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jenispekerjaan".
 *
 * @property integer $id
 * @property string $jenis_pekerjaan
 */
class Jenispekerjaan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenispekerjaan';
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
