<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "produk".
 *
 * @property string $kode
 * @property string $produk
 * @property integer $cuid
 * @property integer $uuid
 * @property string $utgl
 * @property integer $duid
 * @property string $dtgl
 * @property string $Rstat
 */
class Produk extends \yii\db\ActiveRecord{
	
	public $harga;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'produk';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode', 'cuid'], 'required'],
			[['kode'],'unique'],		
            [['kode', 'produk', 'Rstat'], 'string'],
            [['cuid', 'uuid', 'duid'], 'integer'],
            [['utgl','harga', 'dtgl'], 'safe'],
            [['produk'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kode' => 'Kode',
            'produk' => 'Produk',
            'cuid' => 'Cuid',
            'uuid' => 'Uuid',
            'utgl' => 'Utgl',
            'duid' => 'Duid',
            'dtgl' => 'Dtgl',
            'Rstat' => 'Rstat',
        ];
    }


    public function getHrg(){
		return $this->hasOne(ProdukHarga::className(), ['kode_produk' => 'kode'])
		->andOnCondition(["isnull(produk_harga.RStat,0)"=>0])
		->orderBy(['produk_harga.aktif'=>SORT_DESC])
		;
	}


}
