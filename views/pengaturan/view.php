    <?php
use yii\helpers\Html;

$this->title = $sql['ket'];
$this->params['breadcrumbs'][] = ['label' => 'Pengaturan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<style>
    :target {
        color: #00C !important;
        #background:#000 !important;
        font-weight:bold;
    }
</style>
<div class="panel panel-primary">
    <div class="panel-heading"><span class="panel-title">Nilai Default</span></div>
    <div class="panel-body">
        <table class="table table-bordered table-hover ">
            <thead>
            <tr>
                <th>Ket.</th>
                <th>Nilai</th>
                <th> </th>
            </tr>
            </thead>
            <tbody>
            <?php
                echo'<tr>
                <td>'.$sql['ket'].'</td>
                <td>'.$sql['nil'].' '.$sql['satuan'].'</td>
                <td>'.Html::a('<i class="fa fa-pencil"></i>',['/pengaturan/update','id'=>$sql['id']]).'</td>
                </tr>';
            ?>

            </tbody>

        </table>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><span class="panel-title">Sub Pengaturan</span></div>
    <div class="panel-body">
        <table class="table">
            <thead>
            <tr>
                <th>Ket.</th>
                <th>Nilai</th>
                <th>Aktif</th>
                <th>Default</th>
                <th><i class="fa fa-gears"></i></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $n=0;
            foreach($sql1 as $d){
                $aktif=Html::a('<i class="fa fa-'.($d['aktif']==1?'check-circle':'remove').'"></i>',['/pengaturan/aktif','id'=>$d['id']],['class'=>'btn btn-sm','style'=>  'color:'.($d['aktif']==1?'green':'red')]);

                $nil=$d['nil'].' '.$d['satuan'] ;
                if($d['def']==1){$nil='<span class="label label-info">default</span>';}
                echo'<tr class="'.($d['aktif']!=1?'danger':'').'" id="'.$d['kd'].'">
                <td>'.$d['ket'].'</td>
                <td>'.$nil.'</td>
                <td>'.$aktif.'</td>
                <td>'.Html::a('<i class="fa fa-'.($d['def']==1?'check-circle':'remove').'"></i>',['/pengaturan/default','id'=>$d['id']],['class'=>'btn btn-sm','style'=>  'color:'.($d['def']==1?'green':'red')]).'</td>
                <td>'.Html::a('<i class="fa fa-pencil"></i>',['/pengaturan/update','id'=>$d['id']]).'</td>
                </tr>';
            }
            ?>

            </tbody>

        </table>

    </div>
</div>
