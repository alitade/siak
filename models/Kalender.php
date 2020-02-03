<?php

namespace app\models;

use Yii;

class Kalender extends \yii\db\ActiveRecord{
    public static function tableName(){return 'tbl_kalender';}

    public function rules(){
        return [
			[['kr_kode','jr_id','pr_kode'],'unique','targetAttribute'=>['kr_kode','jr_id','pr_kode']],
            [[
				'kr_kode', 'jr_id', 'pr_kode', 'kln_stat', 'kln_sesi',
				'kln_krs', 'kln_masuk', 'kln_uts', 'kln_uas',
                'krs_akhir', 'uts_akhir', 'uas_akhir',
				], 'string'],

            [['kln_krs', 'kln_masuk', 'kln_uts', 'kln_uas'], 'safe'],

            [[
                'kr_kode', 'jr_id', 'pr_kode', 'kln_stat', 'kln_sesi'
				,'kln_krs', 'kln_masuk', 'kln_uts', 'kln_uas',
				'krs_akhir', 'uts_akhir', 'uas_akhir',
            ],'required'],
            [['jr_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jurusan::className(), 'targetAttribute' => ['jr_id' => 'jr_id']],
            [['kr_kode'], 'exist', 'skipOnError' => true, 'targetClass' => Kurikulum::className(), 'targetAttribute' => ['kr_kode' => 'kr_kode']],
            [['pr_kode'], 'exist', 'skipOnError' => true, 'targetClass' => Program::className(), 'targetAttribute' => ['pr_kode' => 'pr_kode']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kln_id' => 'ID',
            'kr_kode' => 'Kode Tahun',
            'jr_id' => 'Jurusan',
            'pr_kode' => 'Program',
            'kln_krs' => 'Krs Mulai',
            'kln_masuk' => 'Perkuliahan',
            'kln_uts' => 'Uts Mulai',
            'kln_uas' => 'Uas Mulai',
            'kln_krs_lama' => 'Krs Selesai',
            'kln_uts_lama' => 'Uts Selesai',
            'kln_uas_lama' => 'Uas Selesai',
            'kln_stat' => 'Aktif',
            'kln_sesi' => 'Jumlah Sesi per minggu',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBobotNilais()
    {
        return $this->hasMany(BobotNilai::className(), ['kln_id' => 'kln_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJr()
    {
        return $this->hasOne(Jurusan::className(), ['jr_id' => 'jr_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKr()
    {
        return $this->hasOne(Kurikulum::className(), ['kr_kode' => 'kr_kode']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPr()
    {
        return $this->hasOne(Program::className(), ['pr_kode' => 'pr_kode']);
    }
}
