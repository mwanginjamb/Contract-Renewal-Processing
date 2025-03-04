<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ContractBatch $model */

$this->title = 'Contract Batch: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Contract Batches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="contract-batch-view">

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            <div class="card-tools">
                <?= Html::a(Yii::t('app', '<i class="fas fa-file-excel"></i> Import Contracts'), ['excel-import', 'batch' => $model->id], ['class' => 'mx-2 btn btn-success', 'title' => 'Import contracts into this batch.']) ?>
                <?= Html::a(Yii::t('app', '<i class="fas fa-download"></i> Download Template'), ['download', 'templateName' => 'estate_entity_import_template.xlsx'], ['class' => 'btn btn-warning', 'title' => 'Download Import Excel Template']) ?>
            </div>
        </div>
        <div class="card-body">
            <p>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    //'id',
                    'batch_description:ntext',
                    'created_at:datetime',
                    //'updated_at',
                    //'created_by',
                    //'updated_by',
                ],
            ]) ?>

            <div class="text fw-bold my-3">Related Contract Lines</div>

        </div>
    </div>




</div>