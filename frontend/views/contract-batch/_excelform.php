<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Communities */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="card">
    <div class="card-header">
        <h4 class="card-title"><?= $this->title; ?></h4>
    </div>

    <div class="card-body">
        <?php $form = ActiveForm::begin([
            'action' => 'import',
            'id' => 'excel-doc-upload',
            'enableClientValidation' => true,
            'encodeErrorSummary' => false,
            'options' => [
                'enctype' => 'multipart/form-data'
            ]
        ]);

        $form->errorSummary($model);

        ?>

        <div class="row">
            <div class="col-md-8">
                <?= $form->field($model, 'excel_doc')->fileInput() ?>
            </div>

        </div>
        <div class="row">
            <div class="col-md-8">
                <?= $form->field($model, 'batch')->textInput(['readonly' => true])->label(false) ?>
            </div>

        </div>


        <div class="form-group my-3">

            <?= Html::submitButton('<i class="la la-check-square-o"></i> Save', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>