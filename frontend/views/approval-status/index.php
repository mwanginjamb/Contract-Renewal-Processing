<?php

use app\models\ApprovalStatus;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ApprovalStatusSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Approval Statuses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="approval-status-index">


    <div class="card card-info">
        <div class="card-header">
            <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
            <div class="card-tools">
                <?= Html::a('Create Approval Status', ['create'], ['class' => 'btn btn-success']) ?>

            </div>
            <div class="card-body">

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        'name',
                        'created_at:datetime',
                        'updated_at:datetime',
                        'created_by',
                        //'updated_by',
                        [
                            'class' => ActionColumn::className(),
                            'urlCreator' => function ($action, ApprovalStatus $model, $key, $index, $column) {
                                                return Url::toRoute([$action, 'id' => $model->id]);
                                            }
                        ],
                    ],
                ]); ?>
            </div>
        </div>

    </div>




</div>