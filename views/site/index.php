<?php
use yii\bootstrap\Carousel;
use yii\bootstrap\Tabs;
/* @var $this yii\web\View */

$this->title = 'Sistem Informasi Akademik';
$url= "<img src=".Yii::getAlias('@web');
$url.="/images/wowslider/"; 

echo Carousel::widget([
    'options' => [
        'id'    => 'slideshow',
        'interval' => 100,
        'style' => [
            'width: 100%; height: auto;'
        ],
        ['class'=>'fade slideshow'],
    ],
    'items' => [
        $url."01.jpg".">",
        $url."02.jpg".">",
        $url."03.jpg".">",
    ],
    
]);
?>
<?php
//session_destroy();

$User = Yii::$app->user;
switch (@$User->identity->tipe) {
	case  5:echo $this->render('mhs',['User'=>$User]);break;
	case  3:echo $this->render('dsn',['User'=>$User]);break;
	case  4:echo $this->render('prodi',['User'=>$User]);break;
	default:
?>
<hr />

<ul class="nav nav-tabs nav-justified" role="tablist">
  <li class="active"><a href="#home" data-toggle="tab"><b>Tentang Aplikasi Sistem Informasi Akademik</b></a></li>
  <li><a href="#profile" data-toggle="tab"><b>Tentang Direktorat Sistem Informasi & Multimedia</b></a></li>
</ul>
<!-- Tab panes, ini content dari tab di atas -->
<div class="tab-content">
  <div class="tab-pane active" id="home">
    <div class="container-fluid">
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
    </div>
  </div>
	<div class="tab-pane" id="profile">
    <div class="container-fluid">
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
                            <li>Kepala Bagian Data</li>
                            <i>
                                <ul>
                                <li>Dadang Kusdiana</li>
                                    <ul>
                                        <!-- li>Pratomo Bowo Leksono, Amd.</li -->
                                        <!-- li>Rizal Gunawan, Amd.</li -->
                                        <li>Erna Kurniawati Hernsawan, ST.</li>
                                    </ul>
                                </ul>
                            </i>
                        </ul>
                    </div>

                    <div class="span6">
                        <ul>
                            <li>Kepala Bagian Sistem Internal</li>
                            <i>
                            <ul>
                                <li>Deden Rizal Riadi, ME.</li>
                                    <ul>
                                        <li>Galih Indra Rukmana, ST.</li>
                                        <!-- li>Rahman Hardianto, ST.</li -->
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
                                    <li>Suhendra Saputra, ST., MTCNA.</li>
                                    <li>Linda Setianingsih, ST., MOS., CCENT.</li>
                                    <!-- li>Tata Sutiadi, ST., MOS., MTCNA., CCENT.</li -->
                                    </ul>
                                </i>
                        </ul>
                    </div>
                </div>
            </p>
        </h5>
    </div>
  </div>
</div>


<?php	
	break;
  }

?>


<script type="text/javascript">
    
var checkitem = function() {
  var $this;
  $this = $("#slideshow");
  if ($("#slideshow .carousel-inner .item:first").hasClass("active")) {
    $this.children(".left").hide();
    $this.children(".right").show();
  } else if ($("#slideshow .carousel-inner .item:last").hasClass("active")) {
    $this.children(".right").hide();
    $this.children(".left").show();
  } else {
    $this.children(".carousel-control").show();
  }
};

checkitem();

$("#slideshow").on("slid.bs.carousel", "", checkitem);

</script>