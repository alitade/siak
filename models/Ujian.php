<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ujian".
 *
 * @property integer $Id
 * @property integer $IdJadwal
 * @property string $Kat
 * @property string $Tgl
 * @property string $Masuk
 * @property string $Keluar
 * @property string $RgKode
 */
class Ujian extends \yii\db\ActiveRecord
{
	public $Mtk;
	public $Dsn;
	public $Jrs;
	public $Prg;
	public $Kls;
	public $Jml;
	public $Sisa;
	public $Jadwal;
	

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ujian';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdJadwal'], 'integer'],
            [['Kat','GKode', 'Masuk', 'Keluar', 'RgKode'], 'string'],
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
            'IdJadwal' => 'Id Jadwal',
            'Kat' => 'Kat',
            'Tgl' => 'Tanggal',
            'Masuk' => 'Masuk',
            'Keluar' => 'Keluar',
            'RgKode' => 'Ruangan',
			'Mtk'=>'Matakuliah',
			'Dsn'=>'Dosen',
			'Jrs'=>'Jurusan',
			'Prg'=>'Program',
			'Jml'=>'Jumlah',
			'Sisa'=>'Sisa',


        ];
    }

    public function getJadwal()
    {
        return $this->hasOne(Jadwal::className(), ['jdwl_id' => 'IdJadwal']);
    }

    public function getRuang(){
        return $this->hasOne(Ruang::className(), ['rg_kode' => 'RgKode']);
    }


}
