<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_absensi".
 *
 * @property integer $id
 * @property integer $krs_id
 * @property integer $jdwl_id_
 * @property string $jdwl_stat
 * @property string $jdwal_tgl
 * @property string $jdwl_sesi
 *
 * @property TblKrs $krs
 */
class Absensi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_absensi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['krs_id', 'jdwl_id_', 'jdwal_tgl', 'jdwl_sesi'], 'required'],
            [['krs_id', 'jdwl_id_'], 'integer'],
            [['jdwl_stat', 'jdwl_sesi'], 'string'],
            [['jdwal_tgl'], 'safe'],
            [['krs_id'], 'exist', 'skipOnError' => true, 'targetClass' => Krs::className(), 'targetAttribute' => ['krs_id' => 'krs_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'krs_id' => 'Krs ID',
            'jdwl_id_' => 'Jdwl ID',
            'jdwl_stat' => 'Jdwl Stat',
            'jdwal_tgl' => 'Jdwal Tgl',
            'jdwl_sesi' => 'Jdwl Sesi',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKrs()
    {
        return $this->hasOne(TblKrs::className(), ['krs_id' => 'krs_id']);
    }
}
