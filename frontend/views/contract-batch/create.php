<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ContractBatch $model */

$this->title = Yii::t('app', 'Create a Contract Batch');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Contract Batches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contract-batch-create">

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>



</div>