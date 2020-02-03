<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_kurikulum".
 *
 * @property string $kr_kode
 * @property string $kr_nama
 *
 * @property TblKalender[] $tblKalenders
 */
class Kurikulum extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_kurikulum';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kr_kode'], 'required'],
            [['kr_kode'], 'unique'],
            [['kr_kode', 'kr_nama'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kr_kode' => 'Kode',
            'kr_nama' => 'Kurikulum',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblKalenders()
    {
        return $this->hasMany(TblKalender::className(), ['kr_kode' => 'kr_kode']);
    }
}
