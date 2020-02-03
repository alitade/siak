<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_user".
 *
 * @property integer $id
 * @property string $username
 * @property string $name
 * @property string $posisi
 * @property string $password
 * @property string $pass_kode
 * @property integer $tipe
 * @property string $stat
 *
 * @property TblEkstrakulikuler[] $tblEkstrakulikulers
 * @property TblEvent[] $tblEvents
 */
class TUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'name', 'posisi', 'password', 'pass_kode', 'stat'], 'required'],
            [['username', 'name', 'posisi', 'password', 'pass_kode', 'stat'], 'string'],
            [['tipe'], 'integer'],
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
            'name' => 'Name',
            'posisi' => 'Posisi',
            'password' => 'Password',
            'pass_kode' => 'Pass Kode',
            'tipe' => 'Tipe',
            'stat' => 'Stat',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblEkstrakulikulers()
    {
        return $this->hasMany(TblEkstrakulikuler::className(), ['id_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblEvents()
    {
        return $this->hasMany(TblEvent::className(), ['id_user' => 'id']);
    }
}
