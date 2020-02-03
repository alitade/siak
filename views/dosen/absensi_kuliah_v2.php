<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\web\View;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use app\models\Funct;
use kartik\select2\Select2;
use kartik\editable\Editable;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\BobotNilaiSearch $searchModel
 */

$this->title = 'Absensi Perkuliahan Hari Ini';
$this->params['breadcrumbs'][] = $this->title;
?>    
<style type="text/css"> 

.container{
  width:100%;
  margin:auto;
}
table{
  border-collapse:collapse;
  width:100%;
}
thead{
color: white;
background: #337AB7;
}
tbody tr:nth-child(even){
  background:#ECF0F1;
} 
.fixed{
  top:0;
  position:fixed;
  width:auto;
  display:none;
  border:none;
}
.scrollMore{
  margin-top:600px;
}
.up{
  cursor:pointer;
}

.summary{
  background: rgb(51, 122, 183) none repeat scroll 0% 0%;
  margin-left: 16px;
  margin-right: 16px;
  color: white;
  padding-top: 3px;
  padding-right: 10px;
  text-align: right;
  height: 70px;
}

.info{
  float: left;
  font-weight: bold;
  padding-left: 10px;
  text-align: left; 
}

</style>

<div id="TGL"></div>
<?= Url::to('@web/dosen/tanggal') ?><br>
<?= Yii::$app->urlManager->createAbsoluteUrl('dosen/tanggal')?>

<?=$table?>
<p id="demo">00</p>

<script>
    var d = new Date("2018-09-05 15:37:25");
    var jam = d.getHours();
    var mnt = d.getMinutes();
    var dtk = d.getSeconds();
    var mm=dtk;
    document.write(
        d.getHours()+':'+ d.getMinutes()+':'+d.getSeconds()
    );


    // Set the date we're counting down to
    var countDownDate = new Date("Sep 5, 2018 15:37:25").getTime();



    // Update the count down every 1 second
    var x = setInterval(function() {
        ++mm;
        var detik = mm % 60;
        //var detik = pad(mm % 60);
        // Get todays date and time
        var now = new Date().getTime();

        // Find the distance between now an the count down date
        var distance = countDownDate + now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="demo"
        //document.getElementById("demo").innerHTML = hours+ ":"+ minutes + ":" + seconds + "";
        document.getElementById("demo").innerHTML = 's '+ detik;
        // If the count down is finished, write some text
        //clearInterval(x);
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("demo").innerHTML = "EXPIRED";
        }
    }, 1000);

    function pad(val) {
        var valString = val + "";
        if (valString.length < 2) {
            return "0" + valString;
        } else {
            return valString;
        }
    }
</script>

<!-- script>
    function autoRefresh_div(){
        $("#TGL").load("<?= Yii::$app->urlManager->createAbsoluteUrl('dosen/tanggal')?>");// a function which will load data from other file after x seconds
    }

    setInterval('autoRefresh_div()', 1000); // refresh div after 5 secs
</script -->


