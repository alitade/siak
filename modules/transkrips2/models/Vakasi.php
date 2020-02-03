<?php

namespace app\modules\transkrip\models;

use Yii;

/**
 * This is the model class for table "tbl_vakasi".
 *
 * @property string $id
 * @property integer $jdwl_id
 * @property integer $tgs1
 * @property integer $tgs2
 * @property integer $tgs3
 * @property integer $quis
 * @property integer $uts
 * @property integer $uas
 * @property string $tgl
 * @property string $RStat
 */
class Vakasi extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
	public $jdwl_id;
	public $jr_id;
	public $bn_id;

	public $jdwl_kls;
	public $jdwl_hari;
	public $jdwl_masuk;
	public $jdwl_keluar;
	public $ds_nidn;
	public $mtk_kode;
	public $pr_kode;
	public $kr_kode;
	public $jr_jenjang;
	public $jr_nama;
	public $pr_nama;
	public $mtk_nama;
	public $ds_nm;
	public $jadwal;

    public static function tableName()
    {
        return 'tbl_vakasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jdwl_id', 'tgs1', 'tgs2', 'tgs3', 'quis', 'uts', 'uas'], 'integer'],
            [[
			'tgl',
			'jdwl_kls','jdwl_hari','jdwl_masuk','jdwl_keluar',"mtk_nama",
			'ds_nidn','mtk_kode',
			'jr_id','pr_kode','kr_kode','jr_id','jr_jenjang','jr_nama','pr_nama','ds_nm', 
			'jadwal',			
			], 'safe'],
			
            [['RStat'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jdwl_id' => 'IDd Jadwal',
            'tgs1' => 'Tgs1',
            'tgs2' => 'Tgs2',
            'tgs3' => 'Tgs3',
            'quis' => 'Quis',
            'uts' => 'Uts',
            'uas' => 'Uas',
            'tgl' => 'Tgl',
            'RStat' => 'Rstat',

			'jdwl_kls'=>'Kelas',
			'jdwl_hari'=>'Hari',
			'jdwl_masuk'=>'Jadwal',
			'jdwl_keluar'=>'Jadwal Keluar',
			"mtk_nama"=>'Matakuliah',
			'ds_nidn',
			'mtk_kode',
			'jr_id'=>'Jurusan',
			'pr_kode'=>'Program',
			'kr_kode'=>'Kurikulum',
			'jr_id',
			'jr_jenjang'=>'Jenjang',
			'jr_nama'=>'Jurusan',
			'pr_nama'=>'Program',
			'ds_nm'=>'dosen', 
			'jadwal',			


        ];
    }
}
