<?php

namespace app\models;

use Yii;

class Transaksi extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName(){return 'transaksi';}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode_transaksi', 'pph', 'cuid'], 'required'],
            [['kode_transaksi', 'cetak', 'Rstat', 'lock','kat','anv'], 'string'],
            [['ds_id', 'cuid', 'uuid', 'duid'], 'integer'],
			[['pph'],'number'],

            [['tgl', 'tgl_cetak', 'utgl', 'dtgl'
				,'jdwl_hari_','jdwl_masuk_','jdwl_keluar_'
			], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kode_transaksi' => 'Kode Transaksi',
            'ds_id' => 'Ds ID',
            'tgl' => 'Tgl',
            'pph' => 'Pph',
            'cetak' => 'Cetak',
            'tgl_cetak' => 'Tgl Cetak',
            'cuid' => 'Cuid',
            'uuid' => 'Uuid',
            'utgl' => 'Utgl',
            'duid' => 'Duid',
            'dtgl' => 'Dtgl',
            'Rstat' => 'Rstat',
            'lock' => 'Lock',
        ];
    }

    public function getDsn(){
		return $this->hasOne(Dosen::className(), ['ds_id' => 'ds_id'])
		->andOnCondition(["isnull(tbl_dosen.RStat,0)"=>0])
		//->orderBy(['produk_harga.aktif'=>SORT_DESC])
		;
	}

    public function getC(){return $this->hasOne(User::className(), ['id' => 'cuid']);}
    public function getD(){return $this->hasOne(User::className(), ['id' => 'duid']);}
    public function getU(){return $this->hasOne(User::className(), ['id' => 'uuid']);}

    public function getKr(){return $this->hasOne(Kurikulum::className(), ['kr_kode' => 'kr_kode_']);}
	
	public function getDet(){
		return $this->hasMany(TransaksiDetail::className(), ['kd_trans' => 'kode_transaksi'])
		->andOnCondition(["isnull(transaksi_detail.RStat,0)"=>0])
		;
	}

	public function getDat(){
		return $this->hasMany(VakasiData::className(), ['kode' => 'kode_transaksi'])
		->andOnCondition(["isnull(vakasi_data.RStat,0)"=>0])
		;
	}



}
