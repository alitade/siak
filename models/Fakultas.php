<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_fakultas".
 *
 * @property string $fk_id
 * @property string $fk_nama
 *
 * @property TblJurusan[] $tblJurusans
 */
class Fakultas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_fakultas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fk_id','fk_nama'], 'required'],
            [['fk_nama','fk_head'], 'string'],
			[['fk_id', ], 'string','max'=>2],
			
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fk_id' => 'Kode',
            'fk_nama' => 'Fakultas',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblJurusans()
    {
        return $this->hasMany(TblJurusan::className(), ['fk_id' => 'fk_id']);
    }
}
