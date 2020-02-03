<?php

namespace app\modules\transkrip\models;

use Yii;

/**
 * This is the model class for table "t_nilai".
 *
 * @property integer $id
 * @property string $npm
 * @property string $kode_mk
 * @property string $nama_mk
 * @property integer $semester
 * @property integer $sks
 * @property string $huruf
 * @property string $nilai
 * @property string $tahun
 * @property string $tgl_input
 * @property string $stat
 * @property string $kat
 */
class Nilai extends \yii\db\ActiveRecord{
	public $NAMA,$ANGKATAN,$MATKUL,$jr_id;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_nilai';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['npm', 'kode_mk', 'nama_mk', 'huruf', 'tahun', 'stat', 'kat'], 'string'],
            [['npm', 'kode_mk', 'nama_mk', 'huruf','tahun',], 'required'],
            [['npm', 'kode_mk', 'tahun','stat',], 'unique'],
            [['semester', 'sks'], 'integer'],
            [['nilai'], 'number'],
            [['tgl_input'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'npm' => 'Npm',
            'kode_mk' => 'Kode Mk',
            'nama_mk' => 'Nama Mk',
            'semester' => 'Semester',
            'sks' => 'Sks',
            'huruf' => 'Huruf',
            'nilai' => 'Nilai',
            'tahun' => 'Tahun',
            'tgl_input' => 'Tgl Input',
            'stat' => 'Stat',
            'kat' => 'Kat',
        ];
    }
}
