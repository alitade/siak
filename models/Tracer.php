<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tracer".
 *
 * @property string $npm
 * @property string $no_telepon
 * @property string $email
 * @property string $tanggal
 * @property integer $id
 */
class Tracer extends \yii\db\ActiveRecord
{
    public $f3, $f4, $f5, $f6, $f7, $f7a, $f8, $f9, $f10, $f11, $f12, $f13, $f14, $f15, $f16;
    public $ketf3, $ketf5;
    public $a1, $a2, $a3, $a4, $a5, $a6, $a7, $a8, $a9, $a10, $a11, $a12, $a13, $a14, $a15, $a16, $a17, $a18, $a19, $a20, $a21, $a22, $a23, $a24, $a25, $a26, $a27, $a28, $a29;
    public $b1, $b2, $b3, $b4, $b5, $b6, $b7, $b8, $b9, $b10, $b11, $b12, $b13, $b14, $b15, $b16, $b17, $b18, $b19, $b20, $b21, $b22, $b23, $b24, $b25, $b26, $b27, $b28, $b29;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tracer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['npm', 'no_telepon', 'email', 'tanggal'], 'required'],
            [['npm', 'no_telepon', 'email'], 'string'],
            [['tanggal'], 'safe'],
            [['npm'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'npm' => 'NIM',
            'no_telepon' => 'No Telepon',
            'email' => 'Email',
            'tanggal' => 'Tanggal',
            'id' => 'ID',
            'ketf3'=>'',
            'ketf5'=>'',
            'f3' => '1. Kapan anda mulai mencari pekerjaan? Mohon pekerjaan sambilan tidak dimasukkan',
            'f6' => '4. Berapa jumlah perusahaan/instansi/institusi yang sudah anda lamar (lewat surat atau e-mail) sebelum anda memeroleh pekerjaan pertama?',
            'f7' => '5. Berapa banyak perusahaan/instansi/institusi yang merespons lamaran anda?',
            'f7a' => '6. Berapa banyak perusahaan/instansi/institusi yang mengundang anda untuk wawancara?',
            'f5' => '3. Berapa bulan waktu yang dihabiskan untuk memeroleh pekerjaan pertama?',
            'f8' => '7. Apakah anda bekerja saat ini (termasuk kerja sambilan dan wirausaha)?',
        ];
    }
}
