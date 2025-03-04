<?php

use app\models\ContractBatch;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ContractBatchSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Contract Batches');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contract-batch-index">


    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title"><?= $this->title ?></h3>
            <div class="card-tools">
                <?= Html::a('Add Batch', ['create'], ['class' => 'btn btn-sm btn-outline-warning', 'title' => 'Add a contracts batch.']) ?>
            </div>
        </div>
        <div class="card-body">

            <table class="table table-bordered" id="table">
                <thead>
                    <tr>
                        <td class="fw-bold">Contract Batch</td>
                        <td class="fw-bold">Created Date</td>
                        <td class="fw-bold">Contract Details</td>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($batches && is_array($batches)): ?>
                        <?php foreach ($batches as $c):
                            $view = \yii\bootstrap5\Html::a('<i class="fas fa-eye mx-1"></i> View Batch', ['view', 'id' => $c->id], ['title' => 'View Contract Batch details', 'class' => 'btn btn-success btn-sm']);
                            ?>
                            <tr>
                                <td><?= strtoupper($c->batch_description) ?? '' ?></td>
                                <td><?= Yii::$app->formatter->asDatetime($c->created_at) ?? '' ?></td>
                                <td>
                                    <?= $view ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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



