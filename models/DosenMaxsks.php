<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dosen_maxsks".
 *
 * @property integer $id
 * @property string $tahun
 * @property integer $id_tipe
 * @property integer $maxsks
 * @property integer $cuid
 * @property string $ctgl
 * @property integer $uuid
 * @property string $utgl
 * @property integer $duid
 * @property string $dtgl
 */
class DosenMaxsks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dosen_maxsks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tahun'], 'string'],
            [['id_tipe', 'maxsks', 'cuid', 'uuid', 'duid'], 'integer'],
            [['ctgl', 'utgl', 'dtgl'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tahun' => 'Tahun',
            'id_tipe' => 'Id Tipe',
            'maxsks' => 'Maxsks',
            'cuid' => 'Cuid',
            'ctgl' => 'Ctgl',
            'uuid' => 'Uuid',
            'utgl' => 'Utgl',
            'duid' => 'Duid',
            'dtgl' => 'Dtgl',
        ];
    }

    public function getTipe(){return $this->hasOne(DosenTipe::className(), ['id' => 'id_tipe']);}


}
