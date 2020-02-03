<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_berita_2".
 *
 * @property integer $id_berita
 * @property integer $id_user
 * @property string $kategori
 * @property string $judul
 * @property string $isi_berita
 * @property string $status
 * @property string $tanggal
 */
class Berita extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_berita_2';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user'], 'integer'],
            [['kategori', 'judul', 'isi_berita', 'status'], 'string'],
            [['tanggal'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_berita' => 'Id Berita',
            'id_user' => 'Penerbit',
            'kategori' => 'Kategori',
            'judul' => 'Judul',
            'isi_berita' => 'Isi Berita',
            'status' => 'Status',
            'tanggal' => 'Tanggal',
        ];
    }

    public function getIdUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}
