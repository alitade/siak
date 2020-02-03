<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pendaftaran;

/**
 * PendaftaranSearch represents the model behind the search form about `app\models\Pendaftaran`.
 */
class PendaftaranSearch extends Pendaftaran
{
    public function rules()
    {
        return [
            [[
                'id', 'No_Registrasi', 'asal_sekolah', 'status_sekolah', 'alamat_sekolah', 'tahun_lulus', 'nomor_sttb', 'program_studi',
                'status_terima', 'ket_beasiswa', 'ket_program',
                'kd_daftar',
            ],
            'safe'],
            [['jurusan_di_sekolah','informasi_usb_ypkp',], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params){
        $query = Pendaftaran::find();
        $dataProvider = new ActiveDataProvider(['query' => $query,]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'tahun_lulus' => $this->tahun_lulus,
            'jurusan_di_sekolah' => $this->jurusan_di_sekolah,
            'informasi_usb_ypkp' => $this->informasi_usb_ypkp,
            'tgl_daftar' => $this->tgl_daftar,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'No_Registrasi', $this->No_Registrasi])
            ->andFilterWhere(['like', 'kd_daftar', $this->kd_daftar])
            ->andFilterWhere(['like', 'asal_sekolah', $this->asal_sekolah])
            ->andFilterWhere(['like', 'status_sekolah', $this->status_sekolah])
            ->andFilterWhere(['like', 'alamat_sekolah', $this->alamat_sekolah])
            ->andFilterWhere(['like', 'nomor_sttb', $this->nomor_sttb])
            ->andFilterWhere(['like', 'program_studi', $this->program_studi])
            ->andFilterWhere(['like', 'status_terima', $this->status_terima])
            ->andFilterWhere(['like', 'ket_beasiswa', $this->ket_beasiswa])
            ->andFilterWhere(['like', 'ket_program', $this->ket_program])
            ->andFilterWhere(['like', 'ket_pendapat', $this->ket_pendapat]);

        return $dataProvider;
    }

    public function pmb($params,$kon=""){
        $query = Pendaftaran::find()
            ->select([
                'pendaftaran.*',
                'nama'=>'b.nama',
                'nok_ktp'=>'b.no_ktp',
            ])
            ->innerJoin('biodata b',"(b.id=pendaftaran.id)")
            ->where("pendaftaran.No_Registrasi is not null");
        $dataProvider = new ActiveDataProvider(['query' => $query,]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'tahun_lulus' => $this->tahun_lulus,
            'jurusan_di_sekolah' => $this->jurusan_di_sekolah,
            'informasi_usb_ypkp' => $this->informasi_usb_ypkp,
            'tgl_daftar' => $this->tgl_daftar,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'No_Registrasi', $this->No_Registrasi])
            ->andFilterWhere(['like', 'kd_daftar', $this->kd_daftar])
            ->andFilterWhere(['like', 'asal_sekolah', $this->asal_sekolah])
            ->andFilterWhere(['like', 'status_sekolah', $this->status_sekolah])
            ->andFilterWhere(['like', 'alamat_sekolah', $this->alamat_sekolah])
            ->andFilterWhere(['like', 'nomor_sttb', $this->nomor_sttb])
            ->andFilterWhere(['like', 'program_studi', $this->program_studi])
            ->andFilterWhere(['like', 'status_terima', $this->status_terima])
            ->andFilterWhere(['like', 'ket_beasiswa', $this->ket_beasiswa])
            ->andFilterWhere(['like', 'ket_program', $this->ket_program])
            ->andFilterWhere(['like', 'ket_pendapat', $this->ket_pendapat]);

        return $dataProvider;
    }



}
