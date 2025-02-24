<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Contracts $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Contracts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="contracts-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'contract_number',
            'employee_name',
            'employee_number',
            'original_contract_path',
            [
                'attribute' => 'duration_unit',
                'value' => function ($model) {
                return $model->durationUnit->unit;
            },
            ],
            'signed_contract_path:ntext',
            'contract_duration',
            'employee_workstation',
            // 'created_at',
            'updated_at:datetime',
            // 'created_by',
            // 'updated_by',
        ],
    ]) ?>

</div>