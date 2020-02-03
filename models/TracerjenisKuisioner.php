<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tracerjenis_kuisioner".
 *
 * @property integer $id
 * @property string $kode_kuisioner
 * @property string $kode_pertanyaan
 * @property string $pertanyaan
 * @property string $status
 * @property string $pemodelan
 */
class TracerjenisKuisioner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tracerjenis_kuisioner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode_kuisioner', 'kode_pertanyaan', 'pertanyaan'], 'required'],
            [['kode_kuisioner', 'kode_pertanyaan', 'pertanyaan', 'status','pemodelan'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_kuisioner' => 'Kode Kuisioner',
            'kode_pertanyaan' => 'Kode Pertanyaan',
            'pertanyaan' => 'Pertanyaan',
            'status' => 'Status',
            'pemodelan' => 'Model',
        ];
    }

    public static function getPertanyaan($id){
        return TracerjenisKuisioner::find()
                ->select('pemodelan,pertanyaan')
                ->where(['kode_kuisioner'=>$id])->all();
    }
}
