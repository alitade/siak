<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "logs".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $created_at
 * @property string $source_ip
 * @property string $user_agent
 * @property string $activity
 * @property string $link
 */
class Logs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['source_ip', 'user_agent', 'activity', 'link','kode'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'source_ip' => 'Source Ip',
            'user_agent' => 'User Agent',
            'activity' => 'Activity',
            'link' => 'Link',
            'data' => 'Data',
        ];
    }



}
