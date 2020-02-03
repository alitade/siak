<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user2".
 *
 * @property integer $id
 * @property string $username
 * @property string $pass
 * @property string $pass2
 * @property string $tglLog
 * @property string $tipe
 */
class User2 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user2';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'pass', 'pass2', 'tipe'], 'string'],
            [['tglLog'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'pass' => 'Pass',
            'pass2' => 'Pass2',
            'tglLog' => 'Tgl Log',
            'tipe' => 'Tipe',
        ];
    }
}
