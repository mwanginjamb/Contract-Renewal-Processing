<?php
use yii\bootstrap5\Html;
?>
<?= yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'name',
        'description',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a('Update', ['update-role', 'name' => $model->name], [
                        'class' => 'btn btn-xs btn-primary'
                    ]);
                }
            ]
        ],
    ],
]) ?>

<?= yii\helpers\Html::a('Create New Role', ['create-role'], ['class' => 'btn btn-success']) ?>