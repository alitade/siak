<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_jurusan".
 *
 * @property string $jr_id
 * @property string $fk_id
 * @property string $jr_kode_nim
 * @property string $jr_nama
 * @property string $jr_jenjang
 * @property string $jr_stat
 *
 * @property TblFakultas $fk
 * @property TblKalender[] $tblKalenders
 * @property TblMatkul[] $tblMatkuls
 */
class Jurusan extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_jurusan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jr_id','jr_stat','jr_head',], 'required'],
			['jr_id','unique','targetAttribute' =>'jr_id'],
            [['jr_id', 'fk_id', 'jr_kode_nim', 'jr_nama', 'jr_jenjang', 'jr_stat','jr_head'], 'string'],
            //[['jr_id', 'fk_id', 'jr_kode_nim', 'jr_nama', 'jr_jenjang', 'jr_stat'], 'save'],
            //[['fk_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fakultas::className(), 'targetAttribute' => ['fk_id' => 'fk_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'jr_id' => 'Kode',
            'fk_id' => 'Fakultas',
            'jr_kode_nim' => 'Kode Nim',
            'jr_nama' => 'Jurusan',
            'jr_head' => 'Kajur.',
            'jr_jenjang' => 'Jenjang',
            'jr_stat' => 'Stat',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFk()
    {
        return $this->hasOne(Fakultas::className(), ['fk_id' => 'fk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKln(){
        return $this->hasMany(Kalender::className(), ['jr_id' => 'jr_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMtk(){
        return $this->hasMany(Matkul::className(), ['jr_id' => 'jr_id']);
    }

    public function getMhs(){
        return $this->hasMany(Mahasiswa::className(), ['jr_id' => 'jr_id']);
    }
}
