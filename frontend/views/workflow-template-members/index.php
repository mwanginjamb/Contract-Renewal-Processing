<?php

use app\models\WorkflowTemplateMembers;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\WorkflowTemplateMembersSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Workflow Template Members');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workflow-template-members-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Workflow Template Members'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'approver_name',
            'approver_email:email',
            'approver_phone_number',
            'sequence',
            //'user_id',
            //'workflow_id',
            //'created_at',
            //'updated_at',
            //'updated_by',
            //'created_by',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, WorkflowTemplateMembers $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>