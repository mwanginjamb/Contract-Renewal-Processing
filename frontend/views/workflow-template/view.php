<?php

use yii\helpers\Url;
use yii\web\YiiAsset;
use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\WorkflowTemplate $model */

$this->title = 'Workflow Group : ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Workflow Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="workflow-template-view">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">

            <div class="my-3 d-flex justify-content-between">
                <div class="model-action">
                    <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?php Html::a('Delete', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>

                </div>
                <div>
                    <?php Html::button('Add Approvers', [
                        'class' => 'btn btn-primary',
                        'data-bs-toggle' => 'modal',
                        'data-bs-target' => '#templateLineModal',
                        'data-form-url' => Url::to(['workflow-template-members/create', 'workflow_template_id' => $model->id]),
                        'data-form-id' => 'workflow-template-members-form',
                        'data-model-id' => $model->id
                    ]) ?>

                    <?= Html::a('Add Workflow Member', Url::to(['workflow-template-members/create', 'workflow_template_id' => $model->id]), ['class' => 'btn btn-success']) ?>
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
        <div class="row mx-3">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Approvers</h3>
                    </div>
                    <div class="card-body">

                        <?= $this->render('_template-members', ['model' => $model]) ?>
                    </div>
                </div>

            </div>
        </div>
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