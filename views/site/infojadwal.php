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
    <link href="<?= Url::to('@web/slide/components,min.css'); ?>" rel="stylesheet" type="text/css">
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

<body style="font-size: 8px !important;">
	<?= "<!-- len ".strlen("(SA673) Sistem Perencanaan Pengendalian Manajemen (SPPM)") ."-->"?>
    <div class="page-container" >
        <div class="page-content">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-flat">
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <tbody>
                                        <tr>
                                            <td><span class="text-default text-semibold"><?php echo $hari. ", " .date('d M Y') ;?></span></td>
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
												<th><b>Ruang</b></th>
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
											<th><b>Ruang</b></th>
										</tr>
										</thead>
										<tbody>
										';}
                    					
										$Mtk	= "$jadwal[mtk_kode] : $jadwal[mtk_nama]";
										$Stat	=' <span class="bg-success text-highlight" id="ST'.$jadwal["jdwl_id"].'">'.$jadwal["status"].'</span>';
										$Stat 	= ($jadwal["status"]!='HADIR') ? $Stat='' : $Stat;
                                        echo '
										<tr id="'.$jadwal["jdwl_id"].'" class="'
										.($n%2==0? ($jadwal["status"]!='HADIR'?'info':'bg-success text-highlight'):
										($jadwal["status"]!='HADIR'?'':'bg-success text-default')
										
										)
										.'" >
											
											<td><span> '.
											( strlen($Mtk)>38 ? substr(htmlspecialchars_decode($Mtk),0,37)."...":
											htmlspecialchars_decode($Mtk))." ( $jadwal[jdwl_kls] )".
											'</span></td>
											<td><span>'.$jadwal["jdwl_masuk"].' - '.$jadwal["jdwl_keluar"].'</span></td>
											<td><span>'
											.$jadwal["ds_nm"].
											'</span></td>
											<td>'.$jadwal['rg_kode'].'</td>
										</tr>
										';
                                        $n++;
                                        }

                                    echo '</tbody></table>
									</div>
									<div style="margin:-7px 9px 8px 0px;text-align:right"><span class="bg-success text-highlight" ><b>*Hadir</b></span></div>
									';
                                ?> 
                            </div>
                        </div>

                    </div>

 
                    
                </div>

            </div>
        </div>
       
        

    </div>

<script type="text/javascript">
        function updatetime(){
	var d = new Date();
	$("#jam").html( ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2) + ":" + ("0" + d.getSeconds()).slice(-2) );
		    
		setTimeout("updatetime()",1000);
	}
	$(document).ready(function(){
		streamStats();
                updatetime();
		
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

	    var ws = new ReconnectingWebSocket('ws://192.168.11.244:4649/finger');
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
	    };
	}
	

</script>    
</body>
</html>
