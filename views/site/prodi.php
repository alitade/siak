<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use yii\helpers\Url;

use kartik\tabs\TabsX;

$items = [
    [
        'label'=>'<i class="glyphicon glyphicon-home"></i> Info',
        'content'=>" ",
		'active'=>true
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-user"></i> Perkuliahan',
        'content'=>"",
		'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['/prodi/perkuliahan'])],

    ],
    [
        'label'=>'<i class="glyphicon glyphicon-user"></i> Kehadiran',
        'content'=>"",
		'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['/prodi/kehadiran'])],
    ],
	[
        'label'=>'<i class="glyphicon glyphicon-list-alt"></i> Tentang',
        'items'=>[
             [
                 'label'=>'Aplikasi SIA',
                 'encode'=>false,
                 'content'=>'
					<center><b>Tentang Aplikasi Sistem Informasi Akademik</b></center>
					<h5>
						<p class="text-justify" style="text-align: justify;">
							Sistem Informasi Akademik adalah aplikasi yang dirancang dan dibuat untuk mengolah data-data yang
							berhubungan dengan informasi akademik, meliputi data mahasiswa, nilai, KRS, kurikulum, dan jadwal perkuliahan.
						</p>

						<p style="text-align: justify;">
							Untuk pertanyaan dan kendala, silahkan email ke <a href="mailto:sim@usbypkp.ac.id"><i style="text-decoration: underline;">sim@usbypkp.ac.id</i></a>
						</p>

						<p style="text-align: justify;">
							<i style="color:red">-Aplikasi ini dilindungi oleh Undang-Undang, segala bentuk pelanggaran akan ditindak secara HUKUM-</i>
						</p>
					<h5>				 
				 ',
             ],
             [
                 'label'=>'Dir SIM',
                 'encode'=>false,
                 'content'=>'
					<center><b>Tentang Direktorat Sistem Informasi & Multimedia</b></center>
				 <h5>
						<p class="text-justified" style="text-align: justify;">
							Direktorat Sistem Informasi & Multimedia Universitas Sangga Buana YPKP merupakan sebuah unit yang memberikan
							layanan infrastruktur teknologi informasi, layanan interkoneksi (intranet dan Internet), layanan data dan
							sistem informasi (aplikasi sistem informasi akademik, non-akademik maupun pendukung) dan layanan
							komputasi sebagai strategic tools untuk berjalannya proses bisnis di Universitas Sangga Buana YPKP.
						</p>

						<p>
						<br />
							<div class="col-md-12">
									<ul>
										<li>
											Direktur Sistem Informasi & Multimedia
										</li>
											<ul>
												<li>
													<i>Renol Burjulius, ST., M.Kom., App., CEH., CCNA., CHFI., LIMS.</i>
												</li>
											</ul>
									</ul>
							</div>
							<div class="col-md-12">
								<div class="span6">
									<ul>
										<li>
											Kepala Bagian Data
										</li>
											<i>
												<ul>
												<li>
													Dadang Kusdiana
												</li>
													<ul>
														<li>
															Pratomo Bowo Leksono, Amd.
														</li>
														<li>
															Rizal Gunawan, Amd.
														</li>
														<li>
															Erna Kurniawati Herawan
														</li>
													</ul>
												</ul>
											</i>
									</ul>
								</div>
								<div class="span6">
									<ul>
										<li>
											Kepala Bagian Sistem Internal
										</li>
											<i>
											<ul>
												<li>
													Deden Rizal Riadi, ME.
												</li>
													<ul>
														<li>
															Galih Indra Rukmana, Amd.
														</li>
														<li>
															Rahman Hardianto, Amd.
														</li>
													</ul>
											</ul> 
											</i>  
									</ul>
								</div>
							</div>
							<div class="col-md-12">
								<div class="span6">
									<ul>
										<li>
											Supporting Staff
										</li>
											<i>
												<ul>
												<li>
													Adji Kristiawan, ST., CCNA R&S., MTCNA.
												</li>
												<li>
													Linda Setianingsih, ST., MOS., CCENT.
												</li>
												<li>
													Tata Sutiadi, ST., MOS., MTCNA., CCENT.
												</li>
												
												</ul>
											</i>
									</ul>
								</div>
							</div>
						</p>
					</h5>
				 
				 ',
             ],
        ],
    ],
];
?>
<br>
<div>
<?php
echo TabsX::widget([
    'items'=>$items,
    'position'=>TabsX::POS_LEFT,
    'encodeLabels'=>false,
	//'sideways'=>TabsX::POS_LEFT,
	
]);
?>
</div>
<div style="clear:both"></div>
