<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_jadwal".
 *
 * @property integer $jdwl_id
 * @property integer $bn_id
 * @property string $rg_kode
 * @property string $semester
 * @property string $jdwl_hari
 * @property string $jdwl_masuk
 * @property string $jdwl_keluar
 * @property string $jdwl_kls
 * @property string $jdwl_uts
 * @property string $jdwl_uas
 * @property string $jdwl_uts_out
 * @property string $jdwl_uas_out
 * @property string $rg_uts
 * @property string $rg_uas
 *
 * @property TblBobotNilai $bn
 * @property TblRuang $rgKode
 * @property TblJadwalTmp[] $tblJadwalTmps
 * @property TblKrs[] $tblKrs
 */
class Jadwal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $x;

	public $mtk_kode;
	public $matkul;
	public $id;

	public $mtk_nama;
	public $mtk_sks;
	public $mtk_semester;
	
	public $jr_id;
	public $jr_nama;
	public $jr_jenjang;
	
	public $pr_kode;
	public $pr_nama;
	
	public $ds_nm;
	public $jum;
	public $jumjdw;
	public $jumabs;

	public $jadwal;
	public $ujian;
	public $penanggungjawab;

    public $jenis,$kr_kode;
	#Variable PErkuliahan
    public $sesi,$pMasuk,$pKeluar,$jAwal;
	public $peserta,$pelaksana,$tgl,$hadir,$dMasuk,$dKeluar;
	public $xx;

	#

    public static function tableName()
    {
        return 'tbl_jadwal';
    }


    public function Uinput($attribute, $params){

        $strStart = "2000-01-01 $this->jdwl_masuk"; 
        $strEnd = "2000-01-01 $this->jdwl_keluar";

        $dteStart = new \DateTime($strStart);
        $dteEnd   = new \DateTime($strEnd); 
        $dteDiff  = $dteStart->diff($dteEnd)->i;

        if ($dteDiff < 50) {
            $this->addError('jdwl_masuk', 'Durasi Jam tidak sesuai SKS');
            $this->addError('jdwl_keluar', 'Durasi Jam tidak sesuai SKS');
        }

        $jadwal = Jadwal::find()->where(['bn_id'=>$this->bn_id])->All();
        $compare = [];
        $kelas = [];
        $jam_masuk = [];
        foreach ($jadwal as $j=>$jad) {
            $compare[$j] = $jad->jdwl_hari;
            $kelas[$j] = $jad->jdwl_kls;
            $jam_masuk[$j] = $jad->jdwl_masuk;
        }

         if (in_array($this->jdwl_hari, $compare)) {
            if (in_array($this->jdwl_kls, $kelas)) {
                if (in_array($this->jdwl_masuk, $jam_masuk)) {
                $this->addError($attribute, 'Jadwal Dengan nilai yang sama telah ada');
                }
            }
        }
    }

    public function validateSchedule($attribute, $params){

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bn_id', 'jdwl_hari', 'jdwl_masuk', 'jdwl_keluar','rg_kode','jdwl_kls'], 'required'],
            [['bn_id'], 'integer'],
            [['rg_kode', 'semester', 'jdwl_hari', 'jdwl_masuk', 'jdwl_keluar', 'jdwl_kls', 'rg_uts', 'rg_uas'], 'string'],
            //[['jdwl_hari','jdwl_kls', 'jdwl_masuk'],'Uinput'],
            [['jdwl_uts', 'jdwl_uas', 'jdwl_uts_out', 'jdwl_uas_out'], 'safe'],
            ['bn_id','validateSchedule'],
            //[['bn_id'], 'exist', 'skipOnError' => true, 'targetClass' => BobotNilai::className(), 'targetAttribute' => ['bn_id' => 'id']],
            //[['rg_kode'], 'exist', 'skipOnError' => true, 'targetClass' => Ruang::className(), 'targetAttribute' => ['rg_kode' => 'rg_kode']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'jdwl_id' => 'Jdwl ID',
            'bn_id' => 'Bn ID',
            'rg_kode' => 'Ruang',
            'semester' => 'Semester',
            'jdwl_hari' => 'Hari',
            'jdwl_masuk' => 'Jam Masuk',
            'jdwl_keluar' => 'Jam Keluar',
            'jdwl_kls' => 'Kelas',
            'jdwl_uts' => 'Uts',
            'jdwl_uas' => 'Uas',
            'jdwl_uts_out' => 'Akhir UTS',
            'jdwl_uas_out' => 'Akhir UAS',
            'rg_uts' 		=> 'Ruang Uts',
            'rg_uas' 	=> 'Ruang Uas',
            'jenis'		=>'Jenis',
            'kr_kode'=>'Kurikulum'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBn()
    {
        return $this->hasOne(BobotNilai::className(), ['id' => 'bn_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRg()
    {
        return $this->hasOne(Ruang::className(), ['rg_kode' => 'rg_kode']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJadwalTmps()
    {
        return $this->hasMany(JadwalTmp::className(), ['jdwl_id' => 'jdwl_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKrs()
    {
        return $this->hasMany(Krs::className(), ['jdwl_id' => 'jdwl_id']);
    }
}
