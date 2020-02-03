<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_wali".
 *
 * @property integer $Id
 * @property string $JrId
 * @property integer $DsId
 * @property string $KrKd
 * @property string $Status
 * @property string $RStat
 */
class Wali extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_wali';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Id', 'DsId'], 'integer'],
            [['JrId', 'KrKd', 'Status', 'RStat'], 'string'],
            [['DsId'],'unique','targetAttribute'=>['JrId','DsId','KrKd']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'JrId' => 'Jurusan',
            'DsId' => 'Dosen',
            'KrKd' => 'Kurikulum',
            'Status' => 'Status',
            'RStat' => 'Rstat',
        ];
    }
}
