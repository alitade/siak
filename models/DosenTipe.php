<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dosen_tipe".
 *
 * @property integer $id
 * @property string $tipe
 * @property integer $maxsks
 * @property integer $cuid
 * @property string $ctgl
 * @property integer $uuid
 * @property string $utgl
 * @property integer $duid
 * @property string $dtgl
 */
class DosenTipe extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dosen_tipe';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tipe'], 'string'],
            [['maxsks', 'cuid', 'uuid', 'duid'], 'integer'],
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
            'tipe' => 'Tipe',
            'maxsks' => 'Maxsks',
            'cuid' => 'Cuid',
            'ctgl' => 'Ctgl',
            'uuid' => 'Uuid',
            'utgl' => 'Utgl',
            'duid' => 'Duid',
            'dtgl' => 'Dtgl',
        ];
    }

    public function getDosen(){return $this->hasMany(Dosen::className(), ['id_tipe' => 'id']);}


}
