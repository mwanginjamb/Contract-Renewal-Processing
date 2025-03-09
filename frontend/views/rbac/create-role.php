<?php
$this->title = 'Create Role';
use yii\helpers\Html;

/* @var $model backend\models\RoleForm */
/* @var $permissions yii\rbac\Permission[] */
?>

<div class="role-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form_role', [
        'model' => $model,
        'permissions' => $permissions,
        'isUpdate' => false
    ]) ?>
</div>