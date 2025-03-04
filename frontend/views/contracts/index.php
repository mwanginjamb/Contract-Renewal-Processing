<?php

use app\models\Contracts;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ContractsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'List of Contracts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contracts-index">


    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title"><?= $this->title ?></h3>
            <div class="card-tools">
                <?= Html::a('Add a Contract', ['create'], ['class' => 'btn btn-sm btn-outline-warning']) ?>
            </div>
        </div>
        <div class="card-body">

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


                            $view = \yii\bootstrap5\Html::a('<i class="fas fa-eye mx-1"></i>', ['view', 'id' => $c->id], ['title' => 'View Contract details', 'class' => 'btn btn-success btn-xs']);
                            $track = \yii\bootstrap5\Html::a('<i class="fas fa-bookmark mx-1"></i>', ['track-approval', 'id' => $c->id], ['class' => 'btn btn-warning btn-xs', 'title' => 'Track Approval']);
                            $approval = (!$c->approval_status) ? \yii\bootstrap5\Html::a('<i class="fas fa-paper-plane mx-1"></i>', [
                                'send-for-approval',
                                'id'
                                => $c->id
                            ], [
                                'class' => 'btn btn-info btn-xs',
                                'title' => 'send for Approval',
                                'data' => [
                                    'confirm' => 'Are you sure you want to send this document for approval ?',
                                    'method' => 'post',
                                ]
                            ]) : '';

                            $cancelApproval = ($c->approval_status == 1) ? \yii\bootstrap5\Html::a('<i class="fas fa-times mx-1"></i>', [
                                'cancel-approval',
                                'id'
                                => $c->id
                            ], [
                                'class' => 'btn btn-danger btn-xs',
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
                                    <?= $view ?>
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
    $('table').DataTable();
JS;

$this->registerJs($script);

