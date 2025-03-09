<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PermissionForm */
/* @var $existingPermissions yii\rbac\Permission[] */

$this->title = 'Create New Permission';
?>
<div class="permission-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'existingPermissions' => $existingPermissions,
        'isUpdate' => false
    ]) ?>
</div>