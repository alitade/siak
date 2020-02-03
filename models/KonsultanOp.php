<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "konsultan_op".
 *
 * @property string $id_bio
 * @property string $kode
 */
class KonsultanOp extends \yii\db\ActiveRecord{
    public $sign;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'konsultan_op';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_bio', 'id_konsultan'], 'required'],
            [['id_bio', 'id_konsultan'],'unique','targetAttribute'=>['id_bio','id_konsultan']],
            [['id_bio','id_konsultan'], 'integer'],
            [['sign'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_bio' => 'Biodata',
            'id_konsultan' => 'Konsultan',
        ];
    }

    public function getBio(){return $this->hasOne(Biodata::className(), ['id_' => 'id_bio']);}
    public function getKon(){return $this->hasOne(Konsultan::className(), ['id' => 'id_konsultan']);}

}
