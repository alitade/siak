<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_program".
 *
 * @property string $pr_kode
 * @property string $pr_nama
 * @property string $pr_nim
 * @property string $pr_stat
 *
 * @property TblKalender[] $tblKalenders
 */
class Program extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_program';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pr_kode', 'pr_stat'], 'required'],
            [['pr_kode', 'pr_nama', 'pr_nim', 'pr_stat'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pr_kode' => 'Kode',
            'pr_nama' => 'Program',
            'pr_nim' => 'Kode Nim',
            'pr_stat' => 'Stat',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblKalenders()
    {
        return $this->hasMany(TblKalender::className(), ['pr_kode' => 'pr_kode']);
    }
}
