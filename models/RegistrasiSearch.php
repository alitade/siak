<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Registrasi;

/**
 * RegistrasiSearch represents the model behind the search form about `app\models\Registrasi`.
 */
class RegistrasiSearch extends Registrasi
{
    public function rules()
    {
        return [
            [['No_Registrasi', 'Nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'kewarganegaraan', 'status', 'alamat', 'kota', 'kode_pos', 'propinsi', 'negara', 'asal_sekolah', 'status_sekolah', 'alamat_sekolah', 'tahun_lulus', 'nomor_sttb', 'fakultas', 'program_studi', 'nama_ortu_wali', 'alamat_ortu_wali', 'kode_pos_ortu_wali', 'telepon_ortu_wali', 'tgl_daftar', 'status_terima', 'ket_beasiswa', 'ket_program', 'ket_pendapat', 'no_ktp', 'no_telepon', 'ibu_kandung', 'photo'], 'safe'],
            [['pekerjaan', 'jurusan_di_sekolah', 'pekerjaan_ortu', 'informasi_usb_ypkp', 'id_admin'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Registrasi::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'tanggal_lahir' => $this->tanggal_lahir,
            'pekerjaan' => $this->pekerjaan,
            'tahun_lulus' => $this->tahun_lulus,
            'jurusan_di_sekolah' => $this->jurusan_di_sekolah,
            'pekerjaan_ortu' => $this->pekerjaan_ortu,
            'informasi_usb_ypkp' => $this->informasi_usb_ypkp,
            'id_admin' => $this->id_admin,
            'tgl_daftar' => $this->tgl_daftar,
        ]);

        $query->andFilterWhere(['like', 'No_Registrasi', $this->No_Registrasi])
            ->andFilterWhere(['like', 'Nama', $this->Nama])
            ->andFilterWhere(['like', 'tempat_lahir', $this->tempat_lahir])
            ->andFilterWhere(['like', 'jenis_kelamin', $this->jenis_kelamin])
            ->andFilterWhere(['like', 'agama', $this->agama])
            ->andFilterWhere(['like', 'kewarganegaraan', $this->kewarganegaraan])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'kota', $this->kota])
            ->andFilterWhere(['like', 'kode_pos', $this->kode_pos])
            ->andFilterWhere(['like', 'propinsi', $this->propinsi])
            ->andFilterWhere(['like', 'negara', $this->negara])
            ->andFilterWhere(['like', 'asal_sekolah', $this->asal_sekolah])
            ->andFilterWhere(['like', 'status_sekolah', $this->status_sekolah])
            ->andFilterWhere(['like', 'alamat_sekolah', $this->alamat_sekolah])
            ->andFilterWhere(['like', 'nomor_sttb', $this->nomor_sttb])
            ->andFilterWhere(['like', 'fakultas', $this->fakultas])
            ->andFilterWhere(['like', 'program_studi', $this->program_studi])
            ->andFilterWhere(['like', 'nama_ortu_wali', $this->nama_ortu_wali])
            ->andFilterWhere(['like', 'alamat_ortu_wali', $this->alamat_ortu_wali])
            ->andFilterWhere(['like', 'kode_pos_ortu_wali', $this->kode_pos_ortu_wali])
            ->andFilterWhere(['like', 'telepon_ortu_wali', $this->telepon_ortu_wali])
            ->andFilterWhere(['like', 'status_terima', $this->status_terima])
            ->andFilterWhere(['like', 'ket_beasiswa', $this->ket_beasiswa])
            ->andFilterWhere(['like', 'ket_program', $this->ket_program])
            ->andFilterWhere(['like', 'ket_pendapat', $this->ket_pendapat])
            ->andFilterWhere(['like', 'no_ktp', $this->no_ktp])
            ->andFilterWhere(['like', 'no_telepon', $this->no_telepon])
            ->andFilterWhere(['like', 'ibu_kandung', $this->ibu_kandung])
            ->andFilterWhere(['like', 'photo', $this->photo]);

        return $dataProvider;
    }
}
