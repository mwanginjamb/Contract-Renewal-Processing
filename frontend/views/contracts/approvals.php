<?php

use app\models\Contracts;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ContractsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Approval Management';
$this->params['breadcrumbs'][] = $this->title;
//Yii::$app->utility->printrr($approvals);
?>
<div class="contracts-index">

    <h1 class="text display-4"><?= Html::encode($this->title) ?></h1>


    <table class="table table-bordered" id="table">
        <thead>
            <tr>
                <td class="fw-bold">Contract Number</td>
                <td class="fw-bold">Contractee (Employee)</td>
                <td class="fw-bold">Approver</td>
                <td class="fw-bold">Approver Status</td>
                <td class="fw-bold">Approval Action</td>
            </tr>
        </thead>
        <tbody>
            <?php if ($approvals && is_array($approvals)): ?>
                <?php foreach ($approvals as $c):



                    $approval = ($c->approval_status == 1) ? \yii\bootstrap5\Html::a('<i class="fas fa-check mx-1"></i>', [
                        'approve',
                        'id'
                        => $c->id
                    ], [
                        'class' => 'btn btn-info btn-xs',
                        'title' => 'Approve this record.',
                        'data' => [
                            'confirm' => 'Are you sure you want to approve this contract ?',
                            'method' => 'post',
                        ]
                    ]) : '';
                    ?>



                    <tr>
                        <td><?= strtoupper($c->contract->contract_number) ?? '' ?></td>
                        <td><?= strtoupper($c->contract->employee_name) ?? '' ?></td>
                        <td><?= $c->approver->approver_name ?? '' ?></td>
                        <td><?= $c->approvalStatus->name ?? '' ?></td>
                        <td><?= $approval ?></td>

                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">
                        <div class="alert" alert-info>No approvals to process..</div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<?php

$script = <<<JS
    $('table').DataTable();
JS;

$this->registerJs($script);

