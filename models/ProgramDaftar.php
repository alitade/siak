<?php

namespace app\models;

use Yii;

class ProgramDaftar extends \yii\db\ActiveRecord{

    public static function tableName(){
        return 'program';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['program_id', 'nama_program', 'identitas_id', 'aktif', 'kode_nim', 'group', 'party', 'kode'], 'string'],
            [['aktif'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'program_id' => 'Program ID',
            'nama_program' => 'Nama Program',
            'identitas_id' => 'Identitas ID',
            'aktif' => 'Aktif',
            'kode_nim' => 'Kode Nim',
            'group' => 'Group',
            'party' => 'Party',
            'kode' => 'Kode',
        ];
    }


    public function getKonsultan(){return $this->hasOne(Konsultan::className(), ['kode' => 'party']);}


}
