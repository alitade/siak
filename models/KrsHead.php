<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "krs_head".
 *
 * @property integer $id
 * @property string $nim
 * @property integer $ds_id
 * @property string $kr_kode
 * @property string $app
 * @property string $app_date
 * @property integer $cuid
 * @property string $ctgl
 * @property integer $uuid
 * @property string $utgl
 * @property integer $duid
 * @property string $dtgl
 * @property string $Rstat
 */
class KrsHead extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'krs_head';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nim', 'kr_kode'], 'required'],
            [['nim', 'kr_kode', 'app', 'Rstat'], 'string'],
            [['ds_id', 'cuid', 'uuid', 'duid'], 'integer'],
            [['app_date', 'ctgl', 'utgl', 'dtgl'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nim' => 'Nim',
            'ds_id' => 'Ds ID',
            'kr_kode' => 'Kr Kode',
            'app' => 'App',
            'app_date' => 'App Date',
            'cuid' => 'Cuid',
            'ctgl' => 'Ctgl',
            'uuid' => 'Uuid',
            'utgl' => 'Utgl',
            'duid' => 'Duid',
            'dtgl' => 'Dtgl',
            'Rstat' => 'Rstat',
        ];
    }
}
