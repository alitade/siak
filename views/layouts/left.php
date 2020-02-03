<?php
	use app\models\Akses;
    $DSN  = \app\models\Dosen::findOne(['ds_user'=>Yii::$app->user->identity->username]);
?>
<aside class="main-sidebar">
    <div id="slimScrollDiv">
        <section class="sidebar">
        <!-- Sidebar user panel -->
        <?= dmstr\widgets\Menu::widget([
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label'=>'LOGOUT','icon'=>'sign-out','url'=>['/site/logout'],'template' => '<a href="{url}" data-method="post">{icon} {label}</a>',],
                    [
                        'label'=>'ADMIN','icon'=>'gears',
                        'items'=>[
                            ['label'=>'Pengguna','icon'=>'users','url'=>['/user/index'],'template' => '<a href="{url}">{icon} {label}</a>','visible'=>$F::acc('/user/index')],
                            ['label'=>'Hak Akses','icon'=>'sign-in','url'=>['/admin'],'template' => '<a href="{url}" target="_blank">{icon} {label}</a>','visible'=>$F::acc('/admin/assignment/index')],
                        ],
                    ],
                    #PANDUAN
                    [
                        'label' => 'PANDUAN','icon' => 'question-circle',
                        'items'=>[
                            ['label' => 'Jurusan', 'icon' => 'file', 'url' => ['/panduan/jurusan'],'visible'=>$F::acc('/panduan/jurusan'),
                                'template' => '<a href="{url}" target="_blank">{icon} {label}</a>',
                            ],
                            ['label' => 'Dosen', 'icon' => 'file', 'url' => ['/panduan/dosen'],'visible'=>$F::acc('/panduan/dosen'),
                                'template' => '<a href="{url}" target="_blank">{icon} {label}</a>',
                            ],
                            [
                                'label' => 'Mahasiswa',
                                'icon' => 'file',
                                'url' => ['/panduan/mahasiswa'],'visible'=>$F::acc('/panduan/mahasiswa'),
                                'template' => '<a href="{url}" target="_blank">{icon} {label}</a>',
                            ],
                        ]
                    ],

                    #master
                    [
                        'label' => 'MASTER DATA','icon' => 'database',
                        'items'=>[
                            [
                                'label' => 'Kategori', 'icon' => 'th-list',
                                'items'=>[
                                    ['label'=>'Agama','icon'=>'list','url'=> ['/dosen-tipe/index'],'visible'=>$F::acc('/dosen-tipe/index---')],
                                    ['label'=>'Jenis Pekerjaan','icon'=>'users','url' => ['/dosen/index'],'visible'=>$F::acc('/dosen/index---')],
                                    ['label' =>'Asal Sekolah', 'icon' => 'users','url' => ['/dosen-wali/index'],'visible'=>$F::acc('/transkrip/wisuda/index---')],
                                ],
                            ],

                            ['label' => 'Gedung', 'icon' => 'home', 'url' => ['/gedung/index'],'visible'=>$F::acc('/gedung/index')],
                            ['label' => 'Ruangan', 'icon' => 'home', 'url' => ['/ruang/index'],'visible'=>$F::acc('/ruang/index') ],
                            ['label' => 'Program Perkuliahan', 'icon' => 'tasks', 'url' => ['/program/index'],'visible'=>$F::acc('/program/index') ],
                            ['label' => 'Fakultas', 'icon' => 'th-list', 'url' => ['/master/fk'],'visible'=>$F::acc('/master/fk') ],
                            ['label' => 'Jurusan', 'icon' => 'th-list', 'url' => ['/master/jr'],'visible'=>$F::acc('/master/jr')],
                            [
                                'label' => 'Dosen', 'icon' => 'users',
                                'items'=>[
                                    ['label'=>'Kategori Dosen','icon'=>'list','url'=> ['/dosen-tipe/index'],'visible'=>$F::acc('/dosen-tipe/index')],
                                    ['label'=>'Beban SKS Dosen','icon'=>'list','url'=> ['/dosen-maxsks/index'],'visible'=>$F::acc('/dosen-maxsks/index')],
                                    ['label'=>'Daftar Dosen','icon'=>'users','url' => ['/master/ds'],'visible'=>$F::acc('/master/ds')],
                                    ['label' =>'Dosen Wali', 'icon' => 'users','url' => ['/dosen-wali/index'],'visible'=>$F::acc('/dosen-wali/index')],
                                ],
                            ],
                        ]
                    ],

                    #Akademik
                    [
                        'label' => 'AKADEMIK','icon' => 'th-list',
                        'items'=>[
                            ['label' => 'Kurikulum', 'icon' => 'list','url' => ['/kurikulum/index'],'visible'=>$F::acc('/kurikulum/index')],
                            ['label' => 'Kurikulum Matakuliah','icon' => 'book','url' => ['/matkul-kr/index'],'visible'=>Akses::acc('/matkul-kr/index')],
                            ['label' => 'Mahasiswa', 'icon' => 'users', 'url' => ['/mhs/index'],'visible'=>Akses::acc('/mhs/index')],
                            ['label' => 'Kalender Akademik', 'icon' => 'calendar', 'url' => ['/kalender/index'],'visible'=>Akses::acc('/kalender/index')],
                            ['label' => 'Penjadwalan', 'icon' => 'tasks',
                                'items'=>[
                                    ['label' => 'Peserta PerMatakuliah', 'icon' => 'users', 'url' => ['/matkul-kr/info'],'visible'=>Akses::acc('/matkul-kr/info')],
                                    ['label' => 'Pengajar', 'icon' => 'users', 'url' => ['/pengajar/index'],'visible'=>Akses::acc('/pengajar/index')],
                                    ['label' => 'Jadwal Perkuliahan', 'icon' => 'list', 'url' => ['/jadwal/index'],'visible'=>Akses::acc('/jadwal/index')],
                                ]
                            ],
                            ['label' => 'Perwalian', 'icon' => 'tasks',
                                'items'=>[
                                    [
                                        'label' => 'Aktif', 'icon' => 'check',
                                        'items'=>[
                                            ['label' => 'Jadwal', 'icon' => 'list', 'url' => ['/krs/jadwal-aktif'], 'visible'=>$F::acc('/krs/jadwal-aktif')],
                                            ['label' => 'Input KRS', 'icon' => 'plus', 'url' => ['/krs/admin'],'visible'=>$F::acc('/krs/admin')],
                                            ['label' => 'Export', 'icon' => 'exchange', 'url' => ['/krs/ex-perwalian'],'visible'=>$F::acc('/krs/admin')],
                                        ],
                                    ],
                                    ['label' => 'Input KRS', 'icon' => 'plus', 'url' => ['/perwalian/krs-t'],'visible'=>$F::acc('/perwalian/krs-t')],
                                    ['label' => 'Perwalian', 'icon' => 'list', 'url' => ['/esbed/prw'],'visible'=>$F::acc('/esbed/prw')],
                                    ['label' => 'Perwalian Detail', 'icon' => 'list','url' => ['/esbed/prw-det'],'visible'=>$F::acc('/esbed/prw-det')],
                                    ['label' => 'Nilai', 'icon' => 'file-o', 'url' => ['/esbed/nil-v2'],'visible'=>$F::acc('/esbed/nil-v2')],

                                ]
                            ],
                            [
                                'label' => 'Perkuliahan','icon' => 'list',
                                'items' =>[
                                    #new
                                    ['label' => 'Tanggal Perkuliahan','icon' => 'tasks','url' => ['/dirit/tanggal-kuliah'],'visible'=>$F::acc('/dirit/tanggal-kuliah')],
                                    //['label' => 'Absen Bermasalah','icon' => 'tasks','url' => ['/dirit/tanggal-kuliah'],'visible'=>$F::acc('/absen-khusus/index')],
                                    //['label' => 'Dispensasi','icon' => 'tasks','url' => ['/absen-khusus/index'],'visible'=>$F::acc('/absen-khusus/index')],
                                    #==
                                    ['label' => 'Absensi Khusus','icon' => 'tasks','url' => ['/absen-khusus/index'],'visible'=>$F::acc('/absen-khusus/index')],
                                    #['label' => 'Hari Ini','icon' => 'tasks','url' => ['/rekap-absen/kuliah'],'visible'=>$F::acc('/rekap-absen/kuliah')],
                                    ['label' => 'Hari Ini','icon' => 'tasks','url' => ['/perkuliahan/berjalan'],'visible'=>$F::acc('/perkuliahan/berjalan')],
                                    ['label' => 'Pergantian Jadwal','icon' => 'exchange','url' => ['/dirit/jadwal-pergantian'],'visible'=>$F::acc('/dirit/jadwal-pergantian')],
                                    #['label' => 'Master Absensi','icon' => 'database','url' => ['/dirit/perkuliahan'],'visible'=>$F::acc('/dirit/perkuliahan')],
                                    ['label' => 'Master Absensi','icon' => 'database','url' => ['/perkuliahan/master-absen-dosen'],'visible'=>$F::acc('/perkuliahan/master-absen-dosen')],
                                    #['label' => 'Absensi Khusu','icon' => 'book','url' => ['/absen-khusus/index'],'visible'=>$F::acc('/absen-khusus/index')],
                                    ['label' => 'Rekap Absen Dosen','icon' => 'file-text','url' => ['/dirit/kehadiran'],'visible'=>$F::acc('/dirit/kehadiran')],
                                ],
                            ],

                            ['label' => 'Yudisium', 'icon' => 'list','url' => ['/transkrip/wisuda/index'],'visible'=>$F::acc('/transkrip/wisuda/index')],
                        ]
                    ],

                    #Keuangan

                    #Vakasi
                    ['label' => 'VAKASI', 'icon' => 'money',
                        'items'=>[
                            ['label' => 'Produk', 'icon' => 'users','url' => ['/produk/index'],'visible'=>$F::acc('/produk/index')],
                            ['label' => 'Vakasi', 'icon' => 'users','url' => ['/pengajar/vakasi'],'visible'=>$F::acc('/pengajar/vakasi')],
                            ['label' => 'Faktur', 'icon' => 'file','url' => ['/transaksi/index'],'visible'=>$F::acc('/transaksi/index')],
                        ]
                    ],
                    //['label' => 'Nilai Transkrip', 'icon' => 'list','url' => ['/esbed/nil-v2'],'visible'=>$F::acc('/esbed/nil-v2')],

                    #Jurusan
                    #JurusanEnd
                    [
                        'label' => 'Laporan','icon'=>'list',
                        'items'=>[
                            ['label'=>'Kehadiran Dosen','icon'=>'th-list','url'=>['/laporan/kehadiran-dosen'],'visible'=>$F::acc('/laporan/kehadiran-dosen')],
                            ['label'=>'Ploating Dosen','icon'=>'th-list','url'=>['/laporan/ploat-dosen'],'visible'=>$F::acc('/laporan/ploat-dosen')],
                        ]
                    ],

                    #dosen
                    [
                        'label' => 'DOSEN','icon'=>'user',
                        'items'=>[
                            ['label' => 'BOBOT NILAI', 'icon' => 'list', 'url' => ['/dosen/bobot'],'visible'=>$F::acc('/dosen/bobot')],
                            ['label' => 'JADWAL PERKULIAHAN', 'icon' => 'list', 'url' => ['/dosen/jdwl'],'visible'=>$F::acc('/dosen/jdwl')],
                            ['label' => 'ABSENSI HARI INI', 'icon' => 'list', 'url' => ['/dosen/perkuliahan'],'visible'=>$F::acc('/dosen/perkuliahan')],
                            [
                                'label' => 'MAHASISWA DIDIK', 'icon' => 'users',
                                'visible'=>($DSN->wali->ds_id!=null?:false),
                                'items'=>[
                                    ['label' => 'DAFTAR MAHASISWA', 'icon' => 'list','url' => ['/dosen/mhs'],'visible'=>$F::acc('/dosen/mhs')],
                                    ['label' => 'PERWALIAN', 'icon' => 'list','url' => ['/krs/dosen'],'visible'=>$F::acc('/krs/dosen') ],
                                ],
                            ],

                            #['label' => 'Bobot Nilai', 'icon' => 'file-o', 'url' => ['/dosen/bobot'],'visible'=>$F::acc('/dosen/bobot')],
                            #['label' => 'Jadwal', 'icon' => 'tasks', 'url' => ['/dosen/jdwl'],'visible'=>$F::acc('/dosen/jdwl')],
                            #['label' => 'Absen Hari Ini', 'icon' => 'tasks', 'url' => ['/dosen/perkuliahan'],'visible'=>$F::acc('/dosen/perkuliahan')],
                        ]

                    ],
                    #dosenEnd

                    #Mahasiswa
                    [
                        'label' => 'MAHASISWA','icon'=>'user',
                        'items'=>[
                            ['label' => 'KURIKULUM', 'icon' => 'book','url' => ['/mahasiswa/kurikulum'],'visible'=>$F::acc('/mahasiswa/kurikulum')],
                            ['label' => 'INPUT KRS', 'icon' => 'list','url' => ['/krs/mhs'],'visible'=>$F::acc('/krs/mhs')],
                            ['label' => 'KHS', 'icon' => 'list','url' => ['/mahasiswa/kartu-hasil-studi'],'visible'=>$F::acc('/mahasiswa/kartu-hasil-studi')],
                            ['label' => 'JADWAL', 'icon' => 'list','url' => ['/mahasiswa/schedule'],'visible'=>$F::acc('/mahasiswa/schedule')],
//                            ['label' => 'DAFTAR NILAI SEMENTARA', 'icon' => 'list', 'url' => ['/transkrip/cetakmhs/cetak?id='.Yii::$app->user->identity->username],'visible'=>$F::acc('/transkrip/cetakmhs/cetak')],
                            ['label' => 'TRACER STUDI', 'icon' => 'list','url' => ['/tracer/index']
                                ,'visible'=>($F::acc('/tracer/index')&& $F::lulus(Yii::$app->user->identity->username)?true:false)
                            ],
                        ]
                    ],
                    [
                        'label' => 'GRAFIK','icon'=>'bar-chart-o',
                        'items'=>[
                            ['label' => 'MAHASISWA', 'icon' => 'chart','url' => ['/chart/chart'],'visible'=>$F::acc('/chart/chart')],
                        ]
                    ],
                ],
            ]
        )
        ?>
        </section>
    </div>
</aside>
