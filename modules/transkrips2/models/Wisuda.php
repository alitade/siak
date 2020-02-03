<?php

namespace app\modules\transkrip\models;

use Yii;

/**
 * This is the model class for table "t_head".
 *
 * @property integer $id
 * @property string $jr_id
 * @property string $npm
 * @property string $nama
 * @property string $mtk_kode
 * @property string $pembimbing
 * @property string $ds_id_
 * @property string $skripsi_indo
 * @property string $skripsi_end
 * @property integer $no_urut
 * @property string $kode
 * @property string $tgl_lulus
 * @property string $predikat
 * @property string $nilai
 * @property string $pejabat1
 * @property string $pejabat2
 * @property string $tgl_cetak
 * @property string $tgl
 */
class Wisuda extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_head';
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
            [['jr_id', 'npm', 'nama', 'mtk_kode', 'pembimbing', 'skripsi_indo', 'skripsi_end', 'kode', 'predikat', 'nilai', 'pejabat1', 'pejabat2'], 'string'],
            [[
				'npm', 'nama', 
				'pembimbing', 'skripsi_indo', 'kode',
				'nilai', 'pejabat1',
				'tgl_lulus'], 'required'],
            [['ds_id_', 'no_urut'], 'integer'],
            [['tgl_lulus', 'tgl_cetak', 'tgl'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jr_id' => 'Jurusan',
            'npm' => 'NPM',
            'nama' => 'Nama',
            'mtk_kode' => 'Kode Matakuliah',
            'pembimbing' => 'Pembimbing',
            'ds_id_' => 'Id Dosen',
            'skripsi_indo' => 'Skripsi Indo',
            'skripsi_end' => 'Skripsi Eng',
            'no_urut' => 'No Urut',
            'kode' => 'Kode',
            'tgl_lulus' => 'Tanggal Lulus',
            'predikat' => 'Predikat',
            'nilai' => 'Nilai',
            'pejabat1' => 'Pejabat1',
            'pejabat2' => 'Pejabat2',
            'tgl_cetak' => 'Tgl Cetak',
            'tgl' => 'Tgl',
        ];
    }

	public function Urut($kode){
		$mod = Wisuda::find()->where(['upper(kode)'=>$kode])->orderBy(['no_urut'=>SORT_DESC])->One();
		return $mod->no_urut;
	}

    public function getMhs(){
        return $this->hasOne(\app\models\Mahasiswa::className(), ['mhs_nim' => 'npm']);
    }


}
