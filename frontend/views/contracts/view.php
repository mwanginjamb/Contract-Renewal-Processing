<?php

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Contracts $model */

$this->title = 'Contract: ' . $model->contract_number;
$this->params['breadcrumbs'][] = ['label' => 'Contracts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="contracts-view">

    <div class="card card-info">
        <div class="card-header">
            <h4 class="card-title"><?= strtoupper(Html::encode($this->title)) ?></h4>
        </div>
        <div class="card-body">
            <div class="my-3 d-flex justify-content-between">
                <div class="actions">
                    <?php Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?php Html::a('Delete', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>

                </div>
                <div class="approval">
                    <?= ($model->approval_status == 1) ? Html::a(
                        '<i class="fas fa-bookmark"></i>Track Status',
                        ['track-approval', 'id' => $model->id],
                        [
                            'class' => 'btn btn-app bg-warning',
                            'title' => 'Track contract signing status',
                        ]
                    ) : '' ?>
                    <?= ($model->ismycontract($model->id) || $model->icansign($model->id)) ? Html::a(
                        '<i class="fas fa-signature"></i>Sign Contract',
                        ['sign', 'id' => $model->id],
                        [
                            'class' => 'btn btn-app bg-success',
                            'title' => 'Digitally sign contract',
                            'data' => [
                                'confirm' => 'Are you sure you want to digitally sign this document ?',
                                'method' => 'post',
                            ],
                        ]
                    ) : '' ?>
                    <?php ($model->approval_status == NULL && $model->original_contract_path) ? Html::a('Send for Approval', ['send-for-approval', 'id' => $model->id], [
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
                            //'original_contract_path',
                            //'signed_contract_path',
                            [
                                'attribute' => 'duration_unit',
                                'value' => function ($model) {
                                    return $model->durationUnit->unit;
                                },
                            ],
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
            <?= (!$model->signed_contract_path && $model->original_contract_path) ? $this->render('_original_contract', [
                'model' => $model,
                'content' => $content
            ]) :
                $this->render('_both_contracts', [
                    'model' => $model,
                    'content' => $content,
                    'signed_content' => $signed_content
                ])
                ?>

        </div>
    </div>