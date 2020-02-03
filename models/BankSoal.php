<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bank_soal".
 *
 * @property integer $Id
 * @property string $mtk_kode
 * @property string $jenis
 * @property integer $jml_soal
 * @property string $tgl_upload
 */
class BankSoal extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
	 
	public $Jurusan; 
	public $jr_id;
	 
    public static function tableName()
    {
        return 'bank_soal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file','jml_soal','mtk_kode'],'required'],
            [['mtk_kode', 'jenis'], 'string'],
            [['jml_soal'], 'integer'],
            [['tgl_upload','RStat',], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'mtk_kode' => 'Matakuliah',
            'jenis' => 'Jenis',
            'jml_soal' => 'Jumlah Soal',
            'tgl_upload' => 'Tanggal',
        ];
    }

    public function getMk(){
        return $this->hasOne(Matkul::className(), ['mtk_kode' => 'mtk_kode']);
    }

}
