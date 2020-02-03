<?php

$this->title='Pengaturan Perwalian';
$this->params['breadcrumbs'][] = ['label' => 'Pengaturan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <span class="panel-title"> <b>PENGATURAN PERWALIAN</b> </span>
    </div>
    <div class="panel-body">

        <b>ALUR PERWALIAN</b>
        <table class="table table-bordered">
            <tr>
                <th> 1 </th>
                <td> Penyiapan Jadwal Perkuliahan <i class="fa fa-arrow-right"></i> Mahasiswa Memilih Jadwal Matakuliah <i class="fa fa-arrow-right"></i> Proses Approval Dosen Wali  </td>
            </tr>
            <tr>
                <th> 2 </th>
                <td> Mahasiswa Memilih Matakuliah  <i class="fa fa-arrow-right"></i> Penyiapan Jadwal Perkuliahan <i class="fa fa-arrow-right"></i> Mahasiswa Memilih Jadwal <i class="fa fa-arrow-right"></i> Proses Approval Dosen Wali  </td>
            </tr>
        </table>
        <p></p>
        <div class="col-sm-12" style="border-bottom: solid 1px rgba(0,0,0,0.5)"></div>
        <div class="clearfix"></div>
        <p> </p>
        <table class="table table-bordered">
            <thead><tr style="text-align: center"><th>PENGATURAN</th><th>AKTIF</th></tr></thead>
            <tbody>
            <tr><th> Hanya mahasiswa yang telah melakukan registrasi keuangan yang bisa menambah data KRS </th><td> </td></tr>
            <tr><th> Tampilkan nama dosen Di KRS MAHASISWA </th><td> </td></tr>
            <tr><th> Kuota mahasiswa per jadwal (termasuk kelas gabungan) </th><td> </td></tr>
            <tr><th> Pemilihan Matakuliah Sesuai Dengan Kurikulum Mahasiswa </th><td> </td></tr>
            <tr><th> Jumlah maksimal SKS yang bisa diambil mahasiswa</th><td> </td></tr>
            <tr><th> Kelas Anvulen (Kelas Private) </th><td> </td></tr>
            </tbody>
        </table>
    </div>

</div>




