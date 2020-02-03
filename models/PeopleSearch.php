<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\People;

/**
 * PeopleSearch represents the model behind the search form about `app\models\People`.
 */
class PeopleSearch extends People
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['No_Registrasi', 'Nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'kewarganegaraan', 'status', 'alamat', 'kota', 'kode_pos', 'propinsi', 'negara', 'asal_sekolah', 'status_sekolah', 'alamat_sekolah', 'kode_pos_sekolah', 'telepon_sekolah', 'tahun_lulus', 'nomor_sttb', 'fakultas', 'program_studi', 'nama_ortu_wali', 'alamat_ortu_wali', 'kode_pos_ortu_wali', 'telepon_ortu_wali', 'tgl_daftar', 'status_terima', 'ket_beasiswa', 'ket_program', 'ket_pendapat', 'Foto'], 'safe'],
            [['pekerjaan', 'jurusan_di_sekolah', 'pekerjaan_ortu', 'informasi_usb_ypkp', 'id_admin'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = People::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
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
            ->andFilterWhere(['like', 'kode_pos_sekolah', $this->kode_pos_sekolah])
            ->andFilterWhere(['like', 'telepon_sekolah', $this->telepon_sekolah])
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
            ->andFilterWhere(['like', 'Foto', $this->Foto]);

        return $dataProvider;
    }
}
