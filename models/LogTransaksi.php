<?php

namespace app\models;

use Yii;


class LogTransaksi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_transaksi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','tb', 'user_id'], 'integer'],
            [['ip4', 'ip6', 'user_agent', 'ket', 'kode', 'pk'], 'string'],
            [['tgl'], 'safe'],
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
            'ip4' => 'Ip4',
            'ip6' => 'Ip6',
            'user_agent' => 'User Agent',
            'tgl' => 'Tgl',
            'ket' => 'Ket',
            'kode' => 'Kode',
            'tb' => 'Tb',
            'pk' => 'Pk',
        ];
    }

    public function getUsr(){
        return $this->hasOne(User::className(), ['id' =>'user_id']);
    }

}
