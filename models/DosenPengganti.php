<?php

namespace app\models;

use Yii;
/**
 * This is the model class for table "dosen_pengganti".
 *
 * @property integer $Id
 * @property string $Tgl
 * @property integer $ds_id
 * @property integer $id_absen
 */
class DosenPengganti extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dosen_pengganti';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Id', 'Tgl'], 'required'],
            [['Id', 'ds_id', 'id_absen'], 'integer'],
            [['Tgl'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Tgl' => 'Tgl',
            'ds_id' => 'Ds ID',
            'id_absen' => 'Id Absen',
        ];
    }

    public function getJdw()
    {
        return $this->hasOne(Jadwal::className(), ['jdwl_id' => 'Id']);
    }
    
}
