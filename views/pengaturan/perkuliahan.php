<?php

$this->title='Pengaturan Perwalian';
$this->params['breadcrumbs'][] = ['label' => 'Pengaturan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>



<div class="panel panel-default">
    <div class="panel-heading">
        <span class="panel-title"> <b>PENGATURAN PERKULIAHAN</b> </span>
    </div>
    <div class="panel-body">

        <b> Pengelompokan Kelas Berdasarkan Waktu </b>
        <table class="table teble-bordered">
            <tr>
                <th> Kls. Pagi</th>
                <td>Senin (07:00 - 17:00) | Selasa (07:00 - 17:00) | Rabu(07:00 - 17:00) | Kamis (07:00 - 17:00) | Jum`at (07:00 - 17:00) | Sabtu (07:00 - 12:00)</td>
            </tr>
            <tr>
                <th> Kls. Sore</th>
                <td>Senin (17:01 - 21:00) | Selasa (17:01 - 21:00) | Rabu(17:01 - 21:00) | Kamis (17:01 - 21:00) | Jum`at (17:01 - 21:00) | Sabtu (12:01 - 21:00)</td>
            </tr>
        </table>

        <p></p>
        <div class="col-sm-12" style="border-bottom: solid 1px rgba(0,0,0,0.5)"></div>
        <div class="clearfix"></div>

        <b> Validasi minimal jumlah pertemuan sebelum UTS & UAS </b>
        <table class="table table-bordered">
            <tr>
                <th> Minimal jumlah pertemuan sebelum UTS </th>
                <td> </td>
            </tr>
            <tr>
                <th> Minimal jumlah pertemuan sebelum UAS </th>
                <td> </td>
            </tr>
        </table>
        <p></p>
        <div class="col-sm-12" style="border-bottom: solid 1px rgba(0,0,0,0.5)"></div>
        <div class="clearfix"></div>


        <p> </p>
        <b>Persensi Perkuliahan </b>
        <div class="row">
            <div class="col-sm-6">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ket </th>
                            <th>Nilai</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            Minimal kehadiran dosen sebelum perkuliahan
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            Maksimal kehadiran dosen sebelum perkuliahan
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>

            </div>

            <div class="col-sm-6">

            </div>

        </div>
        <table class="table table-bordered">
            <thead><tr style="text-align: center"><th>PENGATURAN</th><th>AKTIF</th></tr></thead>
            <tbody>
            <tr><th> Hanya Mahasiswa Yang Telah Melakukan Registrasi Keuangan Yang Bisa Menambah Data KRS </th><td> </td></tr>
            <tr><th> Tampilkan Nama Dosen Di KRS MAHASISWA </th><td> </td></tr>
            <tr><th> Kuota mahasiswa per jadwal (termasuk kelas gabungan) </th><td> </td></tr>
            <tr><th> Pemilihan Matakuliah Sesuai Dengan Kurikulum Mahasiswa </th><td> </td></tr>
            <tr><th> Kelas Anvulen (Kelas Private) </th><td> </td></tr>
            </tbody>
        </table>
    </div>

</div>




