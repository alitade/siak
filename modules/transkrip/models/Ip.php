<?php

namespace app\modules\transkrip\models;

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
class Ip extends \yii\db\ActiveRecord{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'IP';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IP', 'Akses'], 'required'],
        ];
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
        ];
    }
}
