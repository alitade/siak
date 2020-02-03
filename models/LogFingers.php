<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log_finger".
 *
 * @property integer $Id
 * @property integer $fid
 * @property string $tgl
 * @property string $cat
 * @property string $ket
 */
class LogFingers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_finger';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fid'], 'integer'],
            [['tgl'], 'safe'],
            [['cat', 'ket'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'fid' => 'Fid',
            'tgl' => 'Tgl',
            'cat' => 'Cat',
            'ket' => 'Ket',
        ];
    }
}
