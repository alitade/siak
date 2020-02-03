<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_matkul".
 *
 * @property string $mtk_kode
 * @property string $mtk_nama
 * @property integer $mtk_sks
 * @property string $mtk_kat
 * @property string $mtk_stat
 * @property string $jr_id
 * @property string $penanggungjawab
 * @property string $mtk_sesi
 * @property string $mtk_sub
 * @property string $mtk_semester
 * @property string $mtk_jenis
 *
 * @property TblDosen $penanggungjawab0
 * @property TblJurusan $jr
 */
class Matkul extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_matkul';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mtk_kode', 'mtk_nama', 'mtk_stat', 'jr_id', 'mtk_semester',/*'mtk_kat'*/ ], 'required'],
            [['mtk_kode', 'mtk_nama', 'mtk_kat', 'mtk_stat', 'jr_id', 'mtk_sesi', 'mtk_sub', 'mtk_jenis'], 'string'],
            [['mtk_kode', 'mtk_nama', 'mtk_kat', 'mtk_stat', 'jr_id', 'penanggungjawab', 'mtk_sesi', 'mtk_sub', 'mtk_semester', 'mtk_jenis'], 'filter','filter'=>'trim'],
			[['mtk_kode'],'unique'],
            [['mtk_sks','penanggungjawab','mtk_semester',], 'integer'],
            [['penanggungjawab'], 'exist', 'skipOnError' => false, 'targetClass' => Dosen::className(), 'targetAttribute' => ['penanggungjawab' => 'ds_id']],
            [['jr_id'], 'exist', 'skipOnError' => false, 'targetClass' => Jurusan::className(), 'targetAttribute' => ['jr_id' => 'jr_id']],
            #[['mk_kr'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mtk_kode' => 'Kode',
            'mtk_nama' => 'Matakuliah',
            'mtk_sks' => 'Sks',
            'mtk_kat' => 'Kategori',
            'mtk_stat' => 'Stat',
            'jr_id' => 'Jurusan',
            'penanggungjawab' => 'Penanggungjawab',
            'mtk_sesi' => 'Sesi',
            'mtk_sub' => 'Syarat Matkul',
            'mtk_semester' => 'Semester',
            'mtk_jenis' => 'Jenis',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosen()
    {
        return $this->hasOne(Dosen::className(), ['ds_id' => 'penanggungjawab']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJr(){
        return $this->hasOne(Jurusan::className(), ['jr_id' => 'jr_id']);
    }
    public function getSubMk(){return $this->hasOne(Matkul::className(), ['mtk_kode' => 'mtk_sub']);}
}
