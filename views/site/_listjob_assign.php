<?php
// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="card card-outline card-info">
    <!-- <div class="card-header">
        <div class="card-tools">
            <?php //if ($model->tanggal_kerja == date("Y-m-d")) { 
            ?>
                <span class="badge badge-primary">Today</span>
            <?php //} 
            ?>
        </div>
    </div> -->
    <div class="card-body">
        <?php
        // foreach ($notifikasi as $row) {
        //     echo $row->owner;
        //     echo "<br/>";
        // }
        ?>
        <p>Anda mendapatkan tugas
            <span class="text-info"><?php echo $model->rincian_report ?> </span>
            dari
            <span class="text-info"><?php echo $model->ownere->nama ?>,</span>
            untuk diselesaikan pada
            <span class="text-info"><?php echo date("d F Y", strtotime($model->tanggal_kerja)) ?>.</span>
        </p>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <small>Diupdate pada <?php echo date("d F Y \p\u\k\u\l H:i", strtotime($model->timestamp_lastupdated)) ?> WIB</small>
    </div>
    <!-- /.card-footer -->
</div>