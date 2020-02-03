<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_vakasi".
 *
 * @property string $id
 * @property integer $jdwl_id
 * @property integer $tgs1
 * @property integer $tgs2
 * @property integer $tgs3
 * @property integer $quis
 * @property integer $uts
 * @property integer $uas
 * @property string $tgl
 * @property string $RStat
 */
class Vakasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_vakasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jdwl_id', 'tgs1', 'tgs2', 'tgs3', 'quis', 'uts', 'uas'], 'integer'],
            [['tgl'], 'safe'],
            [['RStat'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jdwl_id' => 'Jdwl ID',
            'tgs1' => 'Tgs1',
            'tgs2' => 'Tgs2',
            'tgs3' => 'Tgs3',
            'quis' => 'Quis',
            'uts' => 'Uts',
            'uas' => 'Uas',
            'tgl' => 'Tgl',
            'RStat' => 'Rstat',
        ];
    }
}
