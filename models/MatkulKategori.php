<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "matkul_kategori".
 *
 * @property integer $id
 * @property string $kode
 * @property string $kategori
 */
class MatkulKategori extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'matkul_kategori';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['kode', 'kategori'], 'string'],
            [['kategori'], 'unique'],
            [['kode'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode' => 'Kode',
            'kategori' => 'Kategori',
        ];
    }
}
