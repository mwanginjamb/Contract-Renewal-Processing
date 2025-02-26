<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Contracts $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="contracts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'contract_number')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'employee_name')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">

                    <?= $form->field($model, 'employee_number')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= (!$model->isNewRecord) ? $form->field($model, 'attachment')->fileInput(['maxlength' => true]) : '' ?>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <?= $form->field($model, 'duration_unit')->dropDownList($durationUnits, ['prompt' => 'Select ...']) ?>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'contract_duration')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'employee_workstation')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-md-6">
            <p class="text alert alert-info">Contract File Preview</p>
            <?php if (!$model->original_contract_path)
                echo 'No file uploaded';
            else
                print '<iframe src="data:application/pdf;base64,' . $content . '" height="950px" width="100%"></iframe>';
            ?>
        </div>
    </div>







</div>

<?php

$script = <<<JS

        $('#contracts-attachment').change(function(e){
            globalUpload('_','Contracts','attachment','_');
        });

JS;

$this->registerJs($script);