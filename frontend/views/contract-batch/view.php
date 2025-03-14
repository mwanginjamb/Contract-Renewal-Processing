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
                <?= Html::a(Yii::t('app', '<i class="fas fa-download"></i> Download Template'), ['download', 'templateName' => 'contracts_template.xlsx'], ['class' => 'btn btn-warning', 'title' => 'Download Import Excel Template']) ?>
            </div>
        </div>
        <div class="card-body">
            <p>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?php Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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

            <!-- Line contracts for this batch -->

            <table class="table table-bordered" id="table">
                <thead>
                    <tr>
                        <td class="fw-bold">Contract Number</td>
                        <td class="fw-bold">Employee Name</td>
                        <td class="fw-bold">Employee Number</td>
                        <td class="fw-bold">Duration Unit</td>
                        <td class="fw-bold">Contract Duration</td>
                        <td class="fw-bold">Employee Station</td>
                        <td class="fw-bold">Approval Status</td>
                        <td class="fw-bold">Send Approval Req.</td>
                        <td class="fw-bold">Track Approval</td>
                        <td class="fw-bold">Contract Details</td>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($contracts && is_array($contracts)): ?>
                        <?php foreach ($contracts as $c):


                            $view = \yii\bootstrap5\Html::a('<i class="fas fa-eye mx-1"></i>', ['../contracts/view', 'id' => $c->id], ['title' => 'View Contract details', 'class' => 'btn btn-success btn-xs', 'target' => '_blank']);
                            $update = \yii\bootstrap5\Html::a('<i class="fas fa-edit mx-1"></i>', ['../contracts/update', 'id' => $c->id], ['title' => 'Update Contract details', 'class' => 'btn btn-success btn-xs', 'target' => '_blank']);

                            $accessLink = ($c->original_contract_path && Yii::$app->utility->isValidSharepointLink($c->original_contract_path)) ? $view : $update;
                            $track = \yii\bootstrap5\Html::a('<i class="fas fa-bookmark mx-1"></i>', ['../contracts/track-approval', 'id' => $c->id], ['class' => 'btn btn-warning btn-xs', 'title' => 'Track Approval', 'target' => '_blank']);
                            $approval = (!$c->approval_status && (Yii::$app->utility->isValidSharepointLink($c->original_contract_path) && $c->original_contract_path)) ? \yii\bootstrap5\Html::a('<i class="fas fa-paper-plane mx-1"></i>Ready for Signing', ['../contracts/send-for-approval', 'id' => $c->id], [
                                'class' => 'btn btn-info btn-xs',
                                'title' => 'Contract is ready for signing process',
                                'data' => [
                                    'confirm' => 'Are you sure you want to send this document for approval ?',
                                    'method' => 'post',
                                ]
                            ]) : '';

                            $cancelApproval = ($c->approval_status == 1 && Yii::$app->utility->isValidSharepointLink($c->original_contract_path)) ? \yii\bootstrap5\Html::a('<i class="fas fa-times mx-1"></i> in progress ..', [
                                '../contracts/cancel-approval',
                                'id'
                                => $c->id
                            ], [
                                'class' => 'btn btn-success btn-xs',
                                'title' => 'send for Approval',
                                'data' => [
                                    'confirm' => 'Are you sure you want to cancel approval request for this record ?',
                                    'method' => 'post',
                                ]
                            ]) : '';

                            ?>



                            <tr>
                                <td><?= strtoupper($c->contract_number) ?? '' ?></td>
                                <td><?= $c->employee_name ?? '' ?></td>
                                <td><?= strtoupper($c->employee_number) ?? '' ?></td>
                                <td><?= $c->durationUnit->unit ?? '' ?></td>
                                <td><?= $c->contract_duration ?? '' ?></td>
                                <td><?= $c->employee_workstation ?? '' ?></td>
                                <td><?= $c->approvalStatus->name ?? 'Open' ?></td>
                                <td><?= $approval . $cancelApproval ?></td>
                                <td><?= $track ?></td>
                                <td>
                                    <?= $accessLink ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">
                                <div class="alert" alert-info>No contracts to process..</div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

<?php

$script = <<<JS
    $('#table').DataTable();
JS;

$this->registerJs($script);