<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_jadwal_tmp".
 *
 * @property integer $id
 * @property integer $jdwl_id
 * @property string $ds_nidn
 * @property string $rg_kode
 * @property string $jdwl_hari
 * @property string $jdwl_keluar
 * @property string $jdwl_masuk
 *
 * @property TblJadwal $jdwl
 */
class JadwalTmp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_jadwal_tmp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jdwl_id'], 'required'],
            [['jdwl_id'], 'integer'],
            [['ds_nidn', 'rg_kode', 'jdwl_hari', 'jdwl_keluar', 'jdwl_masuk'], 'string'],
            [['jdwl_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jadwal::className(), 'targetAttribute' => ['jdwl_id' => 'jdwl_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jdwl_id' => 'Jdwl ID',
            'ds_nidn' => 'Ds Nidn',
            'rg_kode' => 'Rg Kode',
            'jdwl_hari' => 'Jdwl Hari',
            'jdwl_keluar' => 'Jdwl Keluar',
            'jdwl_masuk' => 'Jdwl Masuk',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJdwl()
    {
        return $this->hasOne(TblJadwal::className(), ['jdwl_id' => 'jdwl_id']);
    }
}
