<aside class="main-sidebar">
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    [
                        'label' => 'ADMIN',
                        'options' => ['class' => 'header',],
                        'visible'=>true
                    ],

                    [
                        'label' => 'Master',
                        'icon' => 'list',
                        'url' => ['#'],
                        'items'=>[
                            ['label' => 'Gedung','icon' => 'list', 'url' => ['/master/gedung'],],
                            ['label' => 'Ruangan','icon' => 'list', 'url' => ['/master/rg/'],],
                            ['label' => 'Fakultas', 'icon' => 'list', 'url' => ['/master/fk/'],],
                            ['label' => 'Jurusan', 'icon' => 'list', 'url' => ['/master/jr/'],],
                            ['label' => 'Program', 'icon' => 'list', 'url' => ['/master/pr/'],],
                            ['label' => 'Dosen', 'icon' => 'list', 'url' => ['/master/ds/'],],
                            ['label' => 'Kurikulum', 'icon' => 'list', 'url' => ['/master/kr/'],],
                        ]
                    ],

                    #Master
                    ['label' => 'MASTER','options' => ['class' => 'header']],
                    ['label' => 'Fakultas', 'icon' => 'fa fa-file-code-o', 'url' => ['/akdemik/kr']],
                    ['label' => 'Jurusan', 'icon' => 'fa fa-file-code-o', 'url' => ['/akdemik/kln']],
                    ['label' => 'Program', 'icon' => 'fa fa-file-code-o', 'url' => ['/akdemik/mtk']],
                    ['label' => 'Gedung', 'icon' => 'fa fa-file-code-o', 'url' => ['/akdemik/mtk']],
                    ['label' => 'Ruangan', 'icon' => 'fa fa-file-code-o', 'url' => ['/akdemik/mtk']],
                    ['label' => 'Dosen', 'icon' => 'fa fa-file-code-o', 'url' => ['/akdemik/mtk']],
                    ['label' => 'Mahasiwa', 'icon' => 'fa fa-file-code-o', 'url' => ['/akdemik/mhs']],
                    #MasterEnd

                    #akademik
                    ['label' => 'AKADEMIK','options' => ['class' => 'header']],
                    ['label' => 'Kurikulum', 'icon' => 'fa fa-file-code-o', 'url' => ['/akdemik/kr']],
                    ['label' => 'Kalender Akadmemik', 'icon' => 'fa fa-file-code-o', 'url' => ['/akdemik/kln']],
                    ['label' => 'Matakuliah', 'icon' => 'fa fa-file-code-o', 'url' => ['/akdemik/mtk']],
                    ['label' => 'Jadwal', 'icon' => 'fa fa-file-code-o',
                        'icon' => 'list',
                        'url' => ['#'],
                        'items'=>[
                            ['label' => 'Pengajar', 'icon' => 'fa fa-file-code-o', 'url' => ['/akdemik/ajr']],
                            ['label' => 'Perkuliahan', 'icon' => 'fa fa-file-code-o', 'url' => ['/akdemik/jdw']],
                        ]
                    ],
                    ['label' => 'mahasiswa', 'icon' => 'fa fa-file-code-o', 'url' => ['/akdemik/mhs']],
                    #AkademikEnd

                    #dosen
                    ['label' => 'DOSEN','options' => ['class' => 'header']],
                    ['label' => 'MAHASISWA','options' => ['class' => 'header']],
                    ['label' => 'JURUSAN','options' => ['class' => 'header']],
                    ['label' => 'FAKULTAS','options' => ['class' => 'header']],
                    ['label' => 'PASCA','options' => ['class' => 'header']],
                    ['label' => 'PIKET','options' => ['class' => 'header']],
                    ['label' => 'PIKET IT','options' => ['class' => 'header']],
					#dosenEnd

					/**/
                    [
						'label' => 'Master',
						'icon' => 'list', 
						'url' => ['#'],
						'items'=>[
							['label' => 'Gedung','icon' => 'list', 'url' => ['/master/gedung'],],
							['label' => 'Ruangan','icon' => 'list', 'url' => ['/master/rg/'],],
							['label' => 'Fakultas', 'icon' => 'list', 'url' => ['/master/fk/'],],
							['label' => 'Jurusan', 'icon' => 'list', 'url' => ['/master/jr/'],],
							['label' => 'Program', 'icon' => 'list', 'url' => ['/master/pr/'],],
							['label' => 'Dosen', 'icon' => 'list', 'url' => ['/master/ds/'],],
							['label' => 'Kurikulum', 'icon' => 'list', 'url' => ['/master/kr/'],],
						]
					],
					


					/**/
                    ['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => 'Same tools',
                        'icon' => 'fa fa-share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug'],],
                            [
                                'label' => 'Level One',
                                'icon' => 'fa fa-circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'fa fa-circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
