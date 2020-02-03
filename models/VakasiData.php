<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vakasi_data".
 *
 * @property integer $jdwl_id
 * @property integer $tgs1
 * @property integer $tgs2
 * @property integer $tgs3
 * @property integer $quis
 * @property integer $uts
 * @property integer $uas
 * @property string $tgl
 * @property string $RStat
 * @property string $Status
 * @property integer $cuid
 * @property string $ctgl
 * @property integer $uuid
 * @property string $utgl
 * @property integer $duid
 * @property string $dtgl
 * @property string $ket
 */
class VakasiData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vakasi_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jdwl_id'], 'required'],
            [['jdwl_id', 'tgs1', 'tgs2', 'uts', 'uas', 'cuid', 'uuid', 'duid'], 'integer'],
            [['tgl', 'ctgl', 'utgl', 'dtgl'], 'safe'],
            [['RStat', 'Status', 'ket'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'jdwl_id' => 'Jdwl ID',
            'tgs1' => 'Tgs1',
            'tgs2' => 'Tgs2',
            'uts' => 'Uts',
            'uas' => 'Uas',
            'tgl' => 'Tgl',
            'RStat' => 'Rstat',
            'Status' => 'Status',
            'cuid' => 'Cuid',
            'ctgl' => 'Ctgl',
            'uuid' => 'Uuid',
            'utgl' => 'Utgl',
            'duid' => 'Duid',
            'dtgl' => 'Dtgl',
            'ket' => 'Ket',
        ];
    }
}
