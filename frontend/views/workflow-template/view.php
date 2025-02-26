<?php

use yii\helpers\Url;
use yii\web\YiiAsset;
use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\WorkflowTemplate $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Workflow Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="workflow-template-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="my-3 d-flex justify-content-between">
        <div class="model-action">
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>

        </div>
        <div>
            <?= Html::button('Add Approvers', [
                'class' => 'btn btn-primary',
                'data-bs-toggle' => 'modal',
                'data-bs-target' => '#templateLineModal',
                'data-form-url' => Url::to(['workflow-template-members/create', 'workflow_template_id' => $model->id]),
                'data-form-id' => 'workflow-template-members-form',
                'data-model-id' => $model->id
            ]) ?>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //    'id',
            //    'sequence',
            //   'user_id',
            'workflow_name',
            // 'workflow_role',
            //   'created_at',
            //  'updated_at',
            //  'created_by',
            // 'updated_by',
        ],
    ]) ?>

</div>
<div class="row">
    <div class="col">
        <h2>Approvers</h2>
        <?= $this->render('_template-members', ['model' => $model]) ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="templateLineModal" tabindex="-1" aria-labelledby="templateLineModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="templateLineModalLabel">Add Approver Line</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="templateLineFormContainer">
                    <!-- Form will be loaded here via AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>