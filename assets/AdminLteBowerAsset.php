<?php
namespace app\assets;
use yii\web\AssetBundle;

class AdminLteBowerAsset extends AssetBundle{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/bower_components';
    public $css = [
        'Ionicons/css/ionicons.min.css',
        'morris.js/morris.css',
        'jvectormap/jquery-jvectormap.css',
    ];
    public $js = [
        'jquery-slimscroll/jquery.slimscroll.min.js',
        'chart.js/chart.js',
        'fastclick/lib/fastclick.js',
        'jquery-sparkline/dist/jquery.sparkline.min.js',
        'jquery-ui/jquery-ui.min.js',
        'raphael/raphael.min.js',
        'morris.js/morris.min.js',
        'jvectormap/jquery-jvectormap.js',
        'jquery-knob/dist/jquery.knob.min.js',
        'moment/min/moment.min.js',
    ];
    public $depends = [

    ];
}
