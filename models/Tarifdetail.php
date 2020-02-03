<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tarifdetail".
 *
 * @property integer $id
 * @property string $idtarif
 * @property integer $dpp
 * @property integer $sks
 * @property integer $praktek
 * @property integer $urutan
 * @property string $tipe
 * @property string $cc
 */
class Tarifdetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tarifdetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dpp','urutan'],'required'],
            [['idtarif', 'tipe', 'cc'], 'string'],
            [['sks', 'praktek', 'urutan'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idtarif' => 'Idtarif',
            'dpp' => 'Dpp',
            'sks' => 'Sks',
            'praktek' => 'Praktek',
            'urutan' => 'Urutan',
            'tipe' => 'Tipe',
            'cc' => 'Cc',
        ];
    }
}
