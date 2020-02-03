<?php
    use yii\helpers\Url;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="600">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Universitas Sangga Buana</title>

    <!-- Global stylesheets -->
    <link href="<?= Url::to('@web/slide/styles.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?= Url::to('@web/slide/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?= Url::to('@web/slide/core.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?= Url::to('@web/slide/components.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?= Url::to('@web/slide/colors.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?= Url::to('@web/slide/owl.carousel.css'); ?>" rel="stylesheet">
    <link href="<?= Url::to('@web/slide/owl.theme.css'); ?>" rel="stylesheet">
    <link href="<?= Url::to('@web/slide/pnotify.custom.min.css'); ?>" rel="stylesheet">
    <script src="<?= Url::to('@web/slide/jquery-1.9.1.min.js'); ?>"></script> 
    <script src="<?= Url::to('@web/slide/owl.carousel.js'); ?>"></script>
    <script src="<?= Url::to('@web/slide/app.js'); ?>"></script>
    <script src="<?= Url::to('@web/slide/pnotify.custom.min.js'); ?>"></script>
    <script src="<?= Url::to('@web/slide/reconnecting-websocket.js'); ?>"></script>
    <style>
    #owl-demo .item img{
      display: block;
      width: 100%;
      height: auto;
    }
    #bar{
      width: 0%;
      max-width: 100%;
      height: 4px;
      background: #7fc242;
    }
    #progressBar{
      width: 100%;
      background: #EDEDED;
    }
    </style>

</head>

<body style="font-size: 20px !important;">
	<?= "<!-- len ".strlen("(SA673) Sistem Perencanaan Pengendalian Manajemen (SPPM)") ."-->"?>
    <div class="page-container" style="padding: 20px 20px;">
        <div class="page-content">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-flat">
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <tbody>
                                        <tr>
                                            <td><span class="text-default text-semibold"><?php echo $hari. ", " .date('d M Y') ;?></span>
                                            	<span class="bg-success text-highlight" ><b>*Hadir</b></span>
                                                <span class="bg-info text-highlight" ><b>*Sedang Berlangsung</b></span>
                                                <span class="bg-danger text-highlight" ><b>*Selesai</b></span>
                                            </td>
                                            <td align="left"> </td>
                                            <td><span class="text-default text-semibold pull-right" id="jam"></span></td>
                                        </tr>
                                    </tbody>
                                </table>
								<?php 
                 
                                    $n=0;
                                    echo'<div id="owl-demo" class="owl-carousel owl-theme" style="font-weight:bold">
                                            <table class="table text-nowrap" style="font-weight:bold">
											<thead>
											<tr style="background:black;color:white">
												<th><b>Kode : Matakuliah (Kelas)</b></th>
												<th><b>Jam</b></th>
												<th><b>Dosen</b></th>
												<!-- <th><b>Ruang</b></th> -->
											</tr>
											</thead>
											<tbody>';
                                    foreach($items as $jadwal){                                       
                                        if($n%16==0 && $n>0){echo'</tbody></table><table class="table text-nowrap" style="font-weight:bold">
										<thead>
										<tr style="background:black;color:white">
											<th><b>Kode : Matakuliah (Kelas)</b></th>
											<th><b>Jam</b></th>
											<th><b>Dosen</b></th>
											<!-- <th><b>Ruang</b></th> -->
										</tr>
										</thead>
										<tbody>
										';}
                    					
										$Stat	=' <span class="bg-success text-highlight" id="ST'.$jadwal["jdwl_id"].'">'.$jadwal["Kehadiran"].'</span>';
										$Stat 	= (strtoupper($jadwal["Kehadiran"])!='HADIR') ? $Stat='' : $Stat;
                                        echo '
										<tr id="'.$jadwal["jdwl_id"].'" class="'
										.($n%2==0
											? 
											(strtoupper($jadwal["Kehadiran"])!='HADIR'?
											(strtoupper($jadwal["Kehadiran"])=='BERLANGSUNG'?'bg-info text-highlight':
													(strtoupper($jadwal["Kehadiran"])=='SELESAI'?'bg-warning text-highlight':'')
											):'bg-success text-highlight')
											:
											(strtoupper($jadwal["Kehadiran"])!='HADIR'?
											(strtoupper($jadwal["Kehadiran"])=='BERLANGSUNG'?'bg-info text-highlight':
													(strtoupper($jadwal["Kehadiran"])=='SELESAI'?'bg-warning text-highlight':'')
												)
											:'bg-success text-highlight'
											)										
										)
										.'" >
											
											<td><span> '.
											( strlen($Mtk)>38 ? substr(htmlspecialchars_decode($Mtk),0,37)."...":
											htmlspecialchars_decode($jadwal['Matakuliah'])).
											'</span></td>
											<td><span>'.$jadwal["jam"].'</span></td>
											<td><span>'.$jadwal["dosen"].'</span></td>
											<!-- <td><span>'.$jadwal["ruangan"].'</span></td>-->
										</tr>
										';
                                        $n++;
                                        }

                                    echo '</tbody></table>
									</div>
									
									';
                                ?> 
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-4">
                        <div class="panel panel-flat">

                            <div class="container-fluid">
                                <div class="row text-center">
                                    <video src="<?= Url::to('@web/video/ypkp.mp4'); ?>" width="100%" autoplay loop>
                                </div>
                            </div>
                            <ul class="nav nav-lg nav-tabs nav-justified no-margin no-border-radius bg-indigo-400 border-top border-top-indigo-300">
                                <li class="active">
                                    <a href="#messages-tue" class="text-size-small text-uppercase" data-toggle="tab">
                                        <span style="font-size: 20px">INFORMASI</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active fade in" id="messages-tue">
                                    <?php 
                                        $n=0;

                                        echo'<div id="owl-berita" class="owl-carousel owl-theme">';
                                         foreach($rows as $berita){
                                            
                                              if($n%1==0){

                                            echo '
                                            <div class="panel panel-white">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title"><strong>'.$berita["judul"].'</strong></h3>
                                                </div>
                                                
                                                <div class="panel-body" style="text-align: justify;">
                                                    '.$berita["isi_berita"].'
                                                </div>

                                                <div class="panel-footer">
                                                    <span class="pull-right">--<i>'.$berita["kategori"].'</i>--</span>
                                                </div>
                                            </div>
                                                ';
                                                $n++;
                                            }

                                            }


                                        echo '</div>';

                                    ?> 

                                </div>
                            </div>

                        </div>

                            <div class="panel panel-flat">
                                <div class="panel-body">
                                    <span style="text-align: center;">
                                        <center><img src="<?= Url::to('@web/ypkp.png')?>" width="20%"></center>
                                        <h1>SPACE AVAILABLE</h1>
                                        <p><h1><strong>call : info@usbypkp.ac.id</strong></h1></p>
                                    </span>
                                </div>
                               
                            </div>
    
                        </div>
                    
                </div>

            </div>
        </div>
       
        <div class="navbar navbar-default navbar-sm navbar-fixed-bottom">
	        <h2 class="text-default text-semibold">
	            <div class="col-md-2">
                <span>Universitas Sangga Buana</span>
            </div>
            <div class="col-md-10">
                <marquee>
                Website Universitas Sangga Buana : <strong><i>usbypkp.ac.id</i></strong> | Website Sistem Informasi Akademik : <strong><i>sia.usbypkp.ac.id</i></strong>
                | Email Biro Administrasi Akademik : <strong><i>baak@usbypkp.ac.id</i></strong> | Email Keuangan : <strong><i>keuangan@usbypkp.ac.id</i></strong>
                </marquee>
            </div>
	        </h2>
        </div>
        <!-- /footer -->

    </div>

<script type="text/javascript">


    function updatetime(){
		var d = new Date();
		$("#jam").html( ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2) + ":" + ("0" + d.getSeconds()).slice(-2) );
		    
		setTimeout("updatetime()",1000);
	}

	function reloadPage(){
			setTimeout(function(){
		  			location.reload();
            },50000);
	}

	$(document).ready(function(){
		streamStats();
                updatetime();
                reloadPage();
		
	});
	var stack_bar_top = {"dir1": "down", "dir2": "left", "push": "top", "spacing1": 0, "spacing2": 0,"context": $("body")};
	
	function popup(data) {
	    var m = JSON.parse(data);
	    var msg = '<table style="undefined;table-layout: fixed; width: 100%">'+
		  '<colgroup><col style="width: 150px"><col style="width: auto"></colgroup><tr>' +
		    '<th>Dosen</th>' +
		    '<th>'+m.dosen+'<br></th>' +
		  '</tr><tr>' +
		    '<td>Matakuliah</td>' +
		    '<td>'+m.matkul+'</td>' +
		  '</tr><tr>' +
		    '<td>Jam</td>' +
		    '<td>'+m.jam+'</td>' +
		  '</tr><tr>' +
		    '<td>Status</td>' +
		    '<td>HADIR</td>' +
		 ' </tr>' +
		'</table>';
	    var opts = {
		title: "Selamat Datang, Dosen Pengajar.",
		text: "",
		addclass: "stack-bar-top",
		cornerclass: "",
		width: "96%",
		stack: stack_bar_top
	    };
	     
		opts.title = "Informasi";
		opts.text = msg;
		opts.type = "info";
	    var jd;
		jd = m.jdwl_id.split(",");
                for (i = 0; i < jd.length; i++) {
		     $("#ST" + jd[i] ).toggleClass("bg-success");
	             $("#ST" + jd[i] ).html('HADIR');
		} 
	   
	    new PNotify(opts);
	}
	

	function streamStats() {

	   /* var ws = new ReconnectingWebSocket('ws://192.168.11.244:4649/finger');
	    var lineCount;
	    var colHeadings;

	    ws.onopen = function() {
		console.log('connect');
	    };

	    ws.onclose = function() {
		console.log('disconnect');
	    };

	    ws.onmessage = function(e) {	    	 
		 popup(e.data);
	         setTimeout(function(){
		  location.reload();
                 },5000);
	    };*/
	}
	
/*$("#owl-demo").owlCarousel({
	afterAction:setTimeout(function(){location.reload();},1000);
)}*/
</script>    
</body>
</html>
