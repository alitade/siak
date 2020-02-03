<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "matkul_kr_det".
 *
 * @property string $kode_kr
 * @property int $id
 * @property string $kode
 * @property string $matkul
 * @property int $sks
 * @property string $matkul_en
 * @property string $cuid
 * @property string $ctgl
 * @property string $uuid
 * @property string $utgl
 * @property string $duid
 * @property string $dtgl
 * @property string $Rstat
 */
class MatkulKrDet extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'matkul_kr_det';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode_kr', 'kode', 'matkul', 'matkul_en', 'Rstat'], 'string'],
            [['kode', 'matkul', 'cuid'], 'required'],
            [['sks', 'cuid', 'uuid', 'duid'], 'integer'],
            [['ctgl', 'utgl', 'dtgl'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kode_kr' => 'Kode Kr',
            'id' => 'ID',
            'kode' => 'Kode',
            'matkul' => 'Matkul',
            'sks' => 'Sks',
            'matkul_en' => 'Matkul En',
            'cuid' => 'Cuid',
            'ctgl' => 'Ctgl',
            'uuid' => 'Uuid',
            'utgl' => 'Utgl',
            'duid' => 'Duid',
            'dtgl' => 'Dtgl',
            'Rstat' => 'Rstat',
        ];
    }
}
