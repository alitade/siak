<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tracerjenis_jawaban".
 *
 * @property integer $id
 * @property string $pertanyaan_kode
 * @property string $jawaban
 * @property string $status
 */
class TracerjenisJawaban extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tracerjenis_jawaban';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pertanyaan_kode', 'jawaban', 'status'], 'string'],
            [['jawaban'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pertanyaan_kode' => 'Pertanyaan Kode',
            'jawaban' => 'Jawaban',
            'status' => 'Status',
        ];
    }

    public static function listJawaban($id){
        return ArrayHelper::map(TracerjenisJawaban::find()->where(['pertanyaan_kode'=>$id])->all(),'id','jawaban');
    }
}
