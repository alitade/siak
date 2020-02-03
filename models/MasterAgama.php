<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master_agama".
 *
 * @property string $agama
 * @property integer $id
 */
class MasterAgama extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'master_agama';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agama'], 'required'],
            [['agama'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'agama' => 'Agama',
            'id' => 'ID',
        ];
    }
}
