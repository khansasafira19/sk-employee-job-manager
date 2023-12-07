<?php
// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<style>
    .ceklis {
        padding-left: 29px !important;
        min-height: 22px;
        line-height: 22px;
        display: inline-block;
        position: relative;
        vertical-align: top;
        margin-bottom: 0;
        font-weight: 400;
        cursor: pointer;
    }

    .direct-chat-messages {
        -webkit-transform: translate(0, 0);
        transform: translate(0, 0);
        height: auto !important;
        overflow: auto;
        padding: 10px;
    }

    .btn-bundar-site-index {
        border-radius: 30px !important;
        width: 28px !important;
        padding: 6px 0px !important;
    }
</style>
<div class="card direct-chat direct-chat-success shadow-sm">
    <div class="card-header">
        <h3 class="card-title"><?php echo $model->rincian_report ?></h3>
        <div class="card-tools">
            <?php if ($model->tanggal_kerja == date("Y-m-d")) { ?>
                <button type="button" class="btn btn-primary btn-xs" title="Selesaikan Hari Ini">
                    <i class="fas fa-clock"></i> Today
                </button>
            <?php } ?>
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="info-box bg-light">
            <span class="info-box-icon">
                <?php if ($model->status_selesai == 0) { ?>
                    <input type="checkbox" value="<?php echo $model->id_keg ?>" class="tandaselesai" name="selesai">
                <?php } else { ?>
                    <!-- <button type="button" class="btn btn-success btn-sm btn-bundar-site-index disabled" title="Pekerjaan Telah Selesai">
                        <i class="fas fa-check"></i>
                    </button> -->
                    <?= Html::a('<i class="fas fa-check"></i>', '', ['class' => 'btn btn-success btn-sm btn-bundar-site-index', 'title'=>'Pekerjaan Telah Selesai']) ?>
                <?php } ?>

                <!-- <label for="tandaselesai"></label> -->
            </span>
            <div class="info-box-content">
                <?php if ($model->assigned_to == Yii::$app->user->identity->username) { ?>
                    <span class="info-box-text"><i class="fas fa-exclamation-circle"></i> Dari: <span class="text-white text-bold alert-primary"><?php echo $model->ownere->nama ?></span></span>
                <?php } else { ?>
                    <span class="info-box-text"><i class="fas fa-bullseye"></i> Target Pribadi</span></span>
                <?php } ?>
                <span class="info-box-number"><small><?php echo date("d F Y \p\u\k\u\l H:i", strtotime($model->timestamp)) ?> WIB</small></span>
                <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                    <div class="row">
                        <div class="col-sm-6">
                            <?php if ($model->timkerjaproject != NULL) { ?>
                                <?= Html::a('#' . $model['timkerjaprojecte']['project_name'], '', ['class' => 'btn btn-warning btn-xs']) ?>
                                <?= Html::a($model['timkerjae']['nama_timkerja'], '', ['class' => 'btn btn-outline-warning btn-xs']) ?>
                            <?php } ?>
                        </div>
                        <div class="col-sm-6 text-right">
                            <i class="fas fa-calendar"></i> <?php echo date("d F Y", strtotime($model->tanggal_kerja)) ?>
                        </div>
                    </div>

                </span>
            </div>
            <div class="timeline-footer text-right">
                <?php if ($model->priority == 1) { ?>
                    <?= Html::a('<i class="fas fa-star"></i>', ['dp_markpriority?id=' . $model->id_keg . '&value=0'], ['class' => 'btn btn-warning btn-sm text-danger btn-bundar-site-index', 'title' => 'Pekerjaan Prioritas']) ?>
                <?php } else { ?>
                    <?= Html::a('<i class="fas fa-star"></i>', ['dp_markpriority?id=' . $model->id_keg . '&value=1'], ['class' => 'btn btn-default btn-sm text-gray btn-bundar-site-index', 'title' => 'Pekerjaan Non Prioritas']) ?>
                <?php } ?>
                <?= Html::a('<i class="fas fa-eye"></i>', ['dailyreport/view?id=' . $model->id_keg], ['class' => 'btn btn-info btn-bundar-site-index modalButton', 'data-pjax' => '0']) ?>
            </div>
        </div>
    </div>
</div>