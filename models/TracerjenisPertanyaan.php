<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tracerjenis_pertanyaan".
 *
 * @property integer $id
 * @property string $kode
 * @property string $pertanyaan
 * @property string $jenis
 * @property string $status
 */
class TracerjenisPertanyaan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tracerjenis_pertanyaan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode', 'pertanyaan', 'jenis'], 'required'],
            [['kode', 'pertanyaan', 'jenis', 'status'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode' => 'Kode',
            'pertanyaan' => 'Pertanyaan',
            'jenis' => 'Jenis',
            'status' => 'Status',
        ];
    }
}
