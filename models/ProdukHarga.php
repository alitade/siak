<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "produk_harga".
 *
 * @property string $kode_produk
 * @property integer $harga
 * @property string $aktif
 * @property integer $cuid
 * @property integer $uuid
 * @property string $utgl
 * @property integer $duid
 * @property string $dtgl
 * @property string $Rstat
 */
class ProdukHarga extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'produk_harga';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode_produk', 'aktif', 'Rstat'], 'string'],
            [['kode_produk', 'harga',], 'unique','targetAttribute'=>['kode_produk', 'harga',]],
            [['harga', 'cuid', 'uuid', 'duid'], 'integer'],
            [['cuid'], 'required'],
            [['utgl', 'dtgl'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kode_produk' => 'Kode Produk',
            'harga' => 'Harga',
            'aktif' => 'Aktif',
            'cuid' => 'Cuid',
            'uuid' => 'Uuid',
            'utgl' => 'Utgl',
            'duid' => 'Duid',
            'dtgl' => 'Dtgl',
            'Rstat' => 'Rstat',
        ];
    }
}
