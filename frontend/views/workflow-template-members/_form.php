<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\WorkflowTemplateMembers $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="workflow-template-members-form">

    <?php $form = ActiveForm::begin(['id' => 'workflow-template-form']); ?>

    <?= $form->field($model, 'approver_name')->dropDownList($users, ['prompt' => 'Select ..']) ?>

    <?php $form->field($model, 'approver_email')->textInput(['maxlength' => true]) ?>

    <?php $form->field($model, 'approver_phone_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sequence')->textInput(['type' => 'number']) ?>

    <?php $form->field($model, 'user_id')->textInput() ?>

    <?php $form->field($model, 'workflow_id')->textInput() ?>

    <?php $form->field($model, 'created_at')->textInput() ?>

    <?php $form->field($model, 'updated_at')->textInput() ?>

    <?php $form->field($model, 'updated_by')->textInput() ?>

    <?php $form->field($model, 'created_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$script = <<<JS
// Handle form submission via AJAX
    $('#templateLineFormContainer form').on('submit', function (e) {

        // Get the current active modal
        var modal = $('#templateLineModal');
        // Get the form ID from the modal
        var formId = modal.data('form-id');
        var modelID = modal.data('model-id')

       
        // Get the form data and append the parent ID
        var formData = $(this).serialize();
        formData += '&model_id=' + modelID;

        console.log(formData);
        if (e.target.id === formId) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        // Close modal
                        $('#templateLineModal').modal('hide');

                        // You might want to refresh your main view here
                        // or update it with the newly created item

                        // Show success message
                        alert('Template line created successfully!');
                    } else {
                        // If there are validation errors, they will be rendered in the form
                        $('#templateLineFormContainer').html(response);
                    }
                }
            });

        }
    });
JS;