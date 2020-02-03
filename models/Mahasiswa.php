<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_mahasiswa".
 *
 * @property string $mhs_nim
 * @property string $mhs_pass
 * @property string $mhs_pass_kode
 * @property string $mhs_angkatan
 * @property string $jr_id
 * @property string $pr_kode
 * @property string $mhs_stat
 * @property string $ds_wali
 * @property integer $mhs_tipe
 *
 * @property TblKrs[] $tblKrs
 */
class Mahasiswa extends \yii\db\ActiveRecord{
    public $fullName;
    public $Nama;
    public $ws;
    public $thn,$reg,$regis,$tarif,$kr_kode,$krs,$krs_head,$krsHeadApp,$klnId;
    public $tipe;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_mahasiswa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mhs_nim', 'mhs_stat', 'mhs_tipe'], 'required'],
            [['mhs_nim', 'mhs_pass', 'mhs_pass_kode', 'mhs_angkatan', 'jr_id', 'pr_kode', 'mhs_stat', 'ds_wali','fullName','thn'], 'string'],
            [['mhs_tipe'], 'integer'],
            [['tipe',], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mhs_nim' => 'NPM',
            'mhs_pass' => 'Password',
            'mhs_angkatan' => 'Angkatan',
            'jr_id' => 'Jurusan',
            'pr_kode' => 'Program',
            'mhs_stat' => 'Status',
            'ds_wali' => 'Dosen Wali',
            'ws' => 'Lulus',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKrs()
    {
        return $this->hasMany(Krs::className(), ['mhs_nim' => 'mhs_nim']);
    }

    public function getMhs(){return $this->hasOne(Student::className(), ['nim' => 'mhs_nim']);}

    public function getDft(){return $this->hasOne(Student::className(), ['nim' => 'mhs_nim']);}

    public function Mhs($nim)
    {
        $q="
			select m.mhs_nim,p.* 
			from tbl_mahasiswa m
			inner join keuanganfix.dbo.student s on(s.nim COLLATE Latin1_General_CI_AS = m.mhs_nim )
			inner join keuanganfix.dbo.people p on(p.No_Registrasi = s.no_registrasi)
			where m.mhs_nim='$nim'
		 ";
		 $q=Yii::$app->db->createCommand($q)->queryOne();		 
		return $q;
    }



    public function getWali(){return $this->hasOne(Dosen::className(), ['ds_id'=>'ds_wali']);}

    public function getJr(){return $this->hasOne(Jurusan::className(), ['jr_id' => 'jr_id']);}

    public function getPr(){return $this->hasOne(Program::className(), ['pr_kode' => 'pr_kode']);}

    public function getWisuda(){return $this->hasOne(\app\modules\transkrip\models\Wisuda::className(), ['npm' => 'mhs_nim']);}


    public function getKr(){return $this->hasOne(MatkulKr::className(), ['id' => 'mk_kr']);}
}
