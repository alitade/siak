<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_ruang".
 *
 * @property string $rg_kode
 * @property string $rg_nama
 * @property integer $kapasitas
 * @property integer $IdGedung
 *
 * @property TblJadwal[] $tblJadwals
 */
class M113 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_ruang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rg_kode','rg_nama','IdGedung'], 'required'],
            [['rg_nama',], 'string', 'max'=>20],
            [['rg_kode',], 'string', 'max'=>8],
            [['kapasitas', 'IdGedung'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rg_kode' => 'Kode',
            'rg_nama' => 'Nama Ruangan',
            'kapasitas' => 'Kapasitas',
            'IdGedung' => 'Gedung',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJadwal()
    {
        return $this->hasMany(Jadwal::className(), ['rg_kode' => 'rg_kode']);
    }

    public function getGedung()
    {
        return $this->hasOne(Gedung::className(), ['Id' => 'IdGedung']);
    }
}
