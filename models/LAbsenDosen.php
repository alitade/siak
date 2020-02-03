<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "l_absen_dosen".
 *
 * @property string $id
 * @property string $kode
 * @property string $kr_kode
 * @property string $tipe
 * @property string $tgl_awal
 * @property string $tgl_akhir
 * @property string $cuid
 * @property string $ctgl
 * @property string $uuid
 * @property string $utgl
 * @property string $duid
 * @property string $dtgl
 * @property string $RStat
 */
class LAbsenDosen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_absen_dosen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cuid', 'uuid', 'duid','rf_count','parent'], 'integer'],
            [['kode', 'kr_kode', 'tipe', 'RStat','rf_stat'], 'string'],
            [['kr_kode', 'tgl_awal', 'tgl_akhir'], 'required'],
            [['tgl_awal', 'tgl_akhir', 'ctgl', 'utgl', 'dtgl'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode' => 'Kode',
            'kr_kode' => 'Kr Kode',
            'tipe' => 'Tipe',
            'tgl_awal' => 'Tgl Awal',
            'tgl_akhir' => 'Tgl Akhir',
            'cuid' => 'Cuid',
            'ctgl' => 'Ctgl',
            'uuid' => 'Uuid',
            'utgl' => 'Utgl',
            'duid' => 'Duid',
            'dtgl' => 'Dtgl',
            'RStat' => 'Rstat',
        ];
    }

    public function getKr(){return $this->hasOne(Kurikulum::className(), ['kr_kode' => 'kr_kode']);}
    public function getUsr(){return $this->hasOne(User::className(),['id' =>'cuid']);}
    public function getTotal(){return $this->hasMany(LAbsenDosen::className(),['parent' =>'id']);}


}
