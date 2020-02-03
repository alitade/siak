<?php

namespace app\modules\test\models;

use Yii;

/**
 * This is the model class for table "t_head".
 *
 * @property integer $id
 * @property integer $no_urut
 * @property string $kode
 * @property string $npm
 * @property string $tgl_cetak
 * @property string $tgl_lulus
 * @property string $predikat
 * @property string $pejabat1
 * @property string $pejabat2
 * @property string $skripsi_indo
 * @property string $skripsi_end
 */
class THead extends \yii\db\ActiveRecord
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
            [['no_urut'], 'integer'],
            [['kode', 'npm', 'tgl_cetak', 'tgl_lulus'], 'required'],
            [['kode', 'npm', 'predikat', 'pejabat1', 'pejabat2', 'skripsi_indo', 'skripsi_end'], 'string'],
            [['tgl_cetak', 'tgl_lulus'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no_urut' => 'No Urut',
            'kode' => 'Kode',
            'npm' => 'Npm',
            'tgl_cetak' => 'Tgl Cetak',
            'tgl_lulus' => 'Tgl Lulus',
            'predikat' => 'Predikat',
            'pejabat1' => 'Pejabat1',
            'pejabat2' => 'Pejabat2',
            'skripsi_indo' => 'Skripsi Indo',
            'skripsi_end' => 'Skripsi End',
        ];
    }
}
