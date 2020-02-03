<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaksi_detail".
 *
 * @property integer $id
 * @property string $kd_trans
 * @property string $kd_prod
 * @property integer $qty
 * @property integer $harga
 * @property integer $cuid
 * @property integer $uuid
 * @property string $utgl
 * @property integer $duid
 * @property string $dtgl
 * @property string $Rstat
 */
class TransaksiDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaksi_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kd_trans', 'kd_prod', 'qty', 'harga', 'cuid'], 'required'],
            [['kd_trans', 'kd_prod', 'Rstat'], 'string'],
            [['qty', 'harga', 'cuid', 'uuid', 'duid'], 'integer'],
            [['utgl', 'dtgl'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kd_trans' => 'Kd Trans',
            'kd_prod' => 'Kd Prod',
            'qty' => 'Qty',
            'harga' => 'Harga',
            'cuid' => 'Cuid',
            'uuid' => 'Uuid',
            'utgl' => 'Utgl',
            'duid' => 'Duid',
            'dtgl' => 'Dtgl',
            'Rstat' => 'Rstat',
        ];
    }

    public function getProduk(){
		return $this->hasOne(Produk::className(), ['kode' => 'kd_prod'])
		//->andOnCondition(["isnull(produk_harga.RStat,0)"=>0])
		//->orderBy(['produk_harga.aktif'=>SORT_DESC])
		;
	}


}
