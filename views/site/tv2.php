<?php
    use yii\helpers\Url;
?>

<!DOCTYPE html>
<html class="no-focus">
    <head>
        <meta charset="utf-8">
        <title>Multimedia Informasi</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">
        <meta http-equiv="refresh" content="120">
        <link rel="shortcut icon" href="<?= Url::to('@web/ypkp.png'); ?>">
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">
        <link rel="stylesheet" id="css-main" href="<?= Url::to('@web/css/assets/css/oneui.css'); ?>">
        <link rel="stylesheet" id="css-main" href="<?= Url::to('@web/css/assets/css/owl.carousel.css'); ?>">
    </head>
    <body>
        <div id="page-container" class="sidebar-l side-scroll ">
            <main id="main-container">
                <div class="content">
                    <div class="row">
                        <div class="col-md-8">
                                <div class="panel panel-default" style="max-height: 300px; min-height: 250px;">
                                <div class="panel-body">
                                    
                                        <?php 
                                            $n=0;
                                            echo'<div id="owl-jadwal" class="owl-carousel"><div>';
                                            foreach($items as $jadwal){
                                            	
                                            $n++;
                                                if($n%4==0){
                                                    echo"</div><div>";
                                                }
                                                echo '<table class="table table-responsive table-condensed table-striped">
                                                        <tr>
                                                        	<td width="200px">'.$jadwal["jr_jenjang"].'-'.$jadwal["jr_nama"].'</td>
                                                            <td width="200px">'.$jadwal["mtk_nama"].'</td>
                                                            <td width="300px">'.$jadwal["ds_nm"].'</td>
                                                            <td width="120px">'.$jadwal["rg_nama"].'</td>
                                                            <td width="180px">'.$jadwal["jdwl_masuk"].'-'.$jadwal["jdwl_keluar"].'</td>
                                                        </tr>
                                                      </table>
                                                    ';
                                                }
                                            echo '</div></div>';
                                        ?> 
                                    </div>
                                </div>
                        </div>

                        <div class="col-md-4">
                            <video src="<?= Url::to('@web/video/ypkp.mp4'); ?>" muted width="100%"></video>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="block">
                                <div class="block-content bg-city text-white" style="min-height: 250px;">
                                    <div class="table-responsive">
                                        <?php 
                                            $n=0;
                                            echo"<div class='owl-carousel' id='owl-rektorat'>";
                                            foreach($rows as $rektorat){
                                            $n++;
                                              if($n%1==0){
                                                echo "<div>
                                                    <br />
                                                        <h3 class='block-title'>$rektorat[judul]</h3>
                                                        <br>
                                                        <p class='text-justify'>$rektorat[isi_berita]</p>
                                                    </div>";
                                                }
                                            }echo "</div>";
                                        ?>
                                    </div>
                                </div>
                                <div class="block-content block-content-full block-content-mini bg-city text-right text-white">
                                    <strong>--Rektorat--</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="block">
                                <div class="block-content bg-success text-white" style="min-height: 250px;">
                                    <div class="table-responsive">
                                        <?php 
                                            $n=0;
                                            echo"<div class='owl-carousel' id='owl-baa'>";
                                            foreach($rows1 as $baa){
                                            $n++;
                                              if($n%1==0){
                                                echo "<div><br />
                                                        <h3 class='block-title'>$baa[judul]</h3>
                                                        <br>
                                                        <p class='text-justify'>$baa[isi_berita]</p>
                                                    </div>";
                                                }
                                            }echo "</div>";
                                        ?>
                                    </div>
                                </div>
                                <div class="block-content block-content-full block-content-mini bg-success text-right text-white">
                                    <strong>--Biro Adm. Akademik--</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="block">
                                <div class="block-content bg-warning text-white" style="min-height: 250px;">
                                    <div class="table-responsive">
                                        <?php 
                                            $n=0;
                                            echo"<div class='owl-carousel' id='owl-keuangan'>";
                                            foreach($rows2 as $keuangan){
                                            $n++;
                                              if($n%1==0){
                                                echo "<div><br />
                                                        <h3 class='block-title'>$keuangan[judul]</h3>
                                                        <br>
                                                        <p class='text-justify'>$keuangan[isi_berita]</p>
                                                    </div>";
                                                }
                                            }echo "</div>";
                                        ?>
                                    </div>
                                </div>
                                <div class="block-content block-content-full block-content-mini bg-warning text-right text-white">
                                    <strong>--Keuangan--</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="block">
                                <div class="block-content bg-amethyst text-white" style="min-height: 250px;">
                                    <div class="table-responsive">
                                        <?php 
                                            $n=0;
                                            echo"<div class='owl-carousel' id='owl-pudi'>";
                                            foreach($rows3 as $pudi){
                                            $n++;
                                              if($n%1==0){
                                                echo "<div><br />
                                                        <h3 class='block-title'>$pudi[judul]</h3>
                                                        <br>
                                                        <p class='text-justify'>$pudi[isi_berita]</p>
                                                    </div>";
                                                }
                                            }echo "</div>";
                                        ?>
                                    </div>
                                </div>
                                <div class="block-content block-content-full block-content-mini bg-amethyst text-right text-white">
                                    <strong>--Direktorat IT--</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer id="page-footer" class="content-mini content-mini-full font-s12 bg-gray-darker text-white clearfix">
                <div class="col-md-2">
                    <span class="block-title">Media Informasi</span>
                </div>
                <div class="col-md-10">
                    <marquee>
                    <?php 
                        foreach($rows4 as $hima){
                        echo "<span class='block-title'>$hima[judul] : $hima[isi_berita]</span> | ";
                        }
                    ?>
                    </marquee>
                </div>
            </footer>
        </div>

        <script src="<?= Url::to('@web/css/assets/js/core/jquery.min.js'); ?>"></script>
        <script src="<?= Url::to('@web/css/assets/js/core/bootstrap.min.js'); ?>"></script>
        <script src="<?= Url::to('@web/css/assets/js/core/jquery.slimscroll.min.js'); ?>"></script>
        <script src="<?= Url::to('@web/css/assets/js/core/jquery.scrollLock.min.js'); ?>"></script>
        <script src="<?= Url::to('@web/css/assets/js/core/jquery.appear.min.js'); ?>"></script>
        <script src="<?= Url::to('@web/css/assets/js/core/jquery.countTo.min.js'); ?>"></script>
        <script src="<?= Url::to('@web/css/assets/js/core/jquery.placeholder.min.js'); ?>"></script>
        <script src="<?= Url::to('@web/css/assets/js/core/js.cookie.min.js'); ?>"></script>
        <script src="<?= Url::to('@web/css/assets/js/app.js'); ?>"></script>
        <script src="<?= Url::to('@web/css/assets/js/script.js'); ?>"></script>
        <script src="<?= Url::to('@web/css/assets/js/owlcarousel.min.js'); ?>"></script>
    </body>
</html>