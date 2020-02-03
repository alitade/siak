<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asal_sekolah".
 *
 * @property integer $id
 * @property string $sekolah
 */
class AsalSekolah extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'asal_sekolah';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['sekolah'], 'string'],
            [['sekolah'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sekolah' => 'Sekolah',
        ];
    }
}
