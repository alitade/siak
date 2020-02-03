<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jenjang".
 *
 * @property integer $id
 * @property string $jenjang
 */
class Jenjang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenjang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['jenjang'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jenjang' => 'Jenjang',
        ];
    }
}
