<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_tipe".
 *
 * @property integer $tp_id
 * @property string $tp_nama
 * @property string $target
 */
class TblTipe extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_tipe';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tp_nama', 'target'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tp_id' => 'Tp ID',
            'tp_nama' => 'Tp Nama',
            'target' => 'Target',
        ];
    }
}
