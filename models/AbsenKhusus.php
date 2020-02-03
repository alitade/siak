<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "absen_khusus".
 *
 * @property integer $id
 * @property string $mhs_nim
 * @property string $kr_kode
 * @property string $tgl_exp
 * @property string $tgl_ins
 * @property string $tipe
 */
class AbsenKhusus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'absen_khusus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mhs_nim', 'kr_kode'], 'required'],
            [['mhs_nim', 'kr_kode', 'tipe'], 'string'],
            [['tgl_exp', 'tgl_ins'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mhs_nim' => 'Username',
            'kr_kode' => 'Tahun Akademik',
            'tgl_exp' => 'Tanggal Berakhir',
            'tgl_ins' => 'Tanggal Input',
            'tipe' => 'Tipe',
        ];
    }
}
