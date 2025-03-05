<?php

use app\models\Contracts;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ContractsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Contract Approval Entries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contracts-index">

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">
                <?= Html::encode($this->title) ?>
            </h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="table">
                <thead>
                    <tr>
                        <td class="fw-bold">Contract</td>
                        <td class="fw-bold">Approver</td>
                        <td class="fw-bold">Approval Status</td>
                        <td class="fw-bold">Sequence</td>

                    </tr>
                </thead>
                <tbody>
                    <?php if ($entries && is_array($entries)): ?>
                        <?php foreach ($entries as $c): ?>


                            <tr>
                                <td><?= $c->contract->contract_number ?? '' ?></td>
                                <td><?= $c->approver->approver_name ?? '' ?></td>
                                <td><?= $c->approvalStatus->name ?? '' ?></td>
                                <td><?= $c->approver->sequence ?? '' ?></td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">
                                <div class="alert" alert-info>No Approval Entries for this Record</div>
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

