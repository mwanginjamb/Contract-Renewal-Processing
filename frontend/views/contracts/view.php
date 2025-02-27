<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Contracts $model */

$this->title = $model->contract_number;
$this->params['breadcrumbs'][] = ['label' => 'Contracts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="contracts-view">

    <h1><?= strtoupper(Html::encode($this->title)) ?></h1>

    <div class="my-3 d-flex justify-content-between">
        <div class="actions">
            <?= ($model->approval_status == NULL) ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : '' ?>
            <?= ($model->approval_status == NULL) ? Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) : '' ?>

        </div>
        <div class="approval">
            <?= ($model->approval_status == NULL) ? Html::a('Send for Approval', ['send-for-approval', 'id' => $model->id], [
                'class' => 'btn btn-warning',
                'data' => [
                    'confirm' => 'Are you sure you want to send this contract for approval ?',
                    'method' => 'post',
                ],
            ]) : '' ?>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    //'id',
                    'contract_number',
                    'employee_name',
                    'employee_number',
                    // 'original_contract_path',
                    [
                        'attribute' => 'duration_unit',
                        'value' => function ($model) {
                            return $model->durationUnit->unit;
                        },
                    ],
                    // 'signed_contract_path:ntext',
                    'contract_duration',
                    'employee_workstation',
                    // 'created_at',
                    // 'updated_at:datetime',
                    // 'created_by',
                    // 'updated_by',
                    [
                        'attribute' => 'approval_status',
                        'value' => function ($model) {
                            return $model->approvalStatus->name ?? '';
                        }
                    ]
                ],
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="m-3">
                <?php if (!$model->original_contract_path)
                    echo 'Original Contract not uploaded yet.';
                else
                    print '<iframe src="data:application/pdf;base64,' . $content . '" height="950px" width="100%"></iframe>';
                ?>
            </div>
        </div>
    </div>