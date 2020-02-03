<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_jenis_kurikulum".
 *
 * @property integer $Id
 * @property string $Nama
 * @property string $Rstat
 */
class JenisKurikulum extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_jenis_kurikulum';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Nama', 'Rstat'], 'string'],
            [['Nama'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Nama' => 'Nama',
            'Rstat' => 'Rstat',
        ];
    }
}
