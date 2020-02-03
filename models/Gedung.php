<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_gedung".
 *
 * @property integer $Id
 * @property string $Name
 * @property integer $Lantai
 */
class Gedung extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_gedung';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name'], 'string'],
			[['Name'], 'filter','filter'=>'trim'],
            [['Lantai'], 'integer'],
            [['Name'], 'unique'],
			
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Name' => 'Nama Gedung',
            'Lantai' => 'Jumlah Lantai',
        ];
    }

}
