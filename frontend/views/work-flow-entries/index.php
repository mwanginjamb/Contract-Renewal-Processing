<?php

use app\models\WorkFlowEntries;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\WorkFlowEntriesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Work Flow Entries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-flow-entries-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Work Flow Entries', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'template_id',
            'approver_id',
            'approval_status',
            'actioned_date',
            //'created_at',
            //'updated_at',
            //'created_by',
            //'updated_by',
            //'contract_id',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, WorkFlowEntries $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
