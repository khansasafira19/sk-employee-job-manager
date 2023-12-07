<?php
// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php if ($model->tanggal_kerja == date("Y-m-d")) { ?>
    <div class="carikalender" id="proyek-<?php echo $model->tanggal_kerja?>">
    <?php } else { ?>
        <div class="carikalender" id="proyek-<?php echo $model->tanggal_kerja ?>" style="display:none">
        <?php } ?>

        <div class="row">
            <div class="col-2" style="vertical-align: middle!important;">
                <div class="bg-info alert" style="height: 90%!important; line-height: 90%; text-align: center; display: flex; justify-content: center; align-items: center;"><h2><i class="far fa-calendar"></i></h2></div>
            </div>

            <div class="col-10">
                <div class="callout callout-info">
                    <?php if ($model->timkerjaproject != NULL) { ?>
                        <span class="info-box-number"><i class="fas fa-users"></i> PROJECT | <?php echo $model->timkerjaprojecte->project_name ?></span>
                    <?php } else { ?>
                        <span class="info-box-number"><i class="fas fa-user"></i> MANDIRI </span>
                    <?php } ?>
                    <br />
                    <span class="info-box-text"><i class="fas fa-asterisk"></i> <?php echo $model->rincian_report ?></span>
                    <br />
                    <span class="info-box-text"><i class="far fa-clock"></i><?php echo ' Due On ' . date("d F Y", strtotime($model->tanggal_kerja)) ?></span>
                </div>
            </div>
        </div>
        </div>