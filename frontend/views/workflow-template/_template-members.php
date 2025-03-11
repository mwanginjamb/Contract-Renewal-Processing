<?php

use yii\bootstrap5\Html;
$templateMembers = $model->getWorkflowMembers()->all();

?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Approver Name</th>
            <th>Approver Email</th>
            <th>Sequence</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($templateMembers as $templateMember):

            $delete = Html::a('<i class="fas fa-trash"></i>', ['workflow-template-members/delete', 'id' => $templateMember->id, 'templateID' => $model->id], [
                'class' => 'btn btn-danger',
                'title' => 'remove approver record.',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]);

            $update = Html::a('<i class="fas fa-edit"></i>', ['workflow-template-members/update', 'id' => $templateMember->id, 'templateID' => $model->id], [
                'class' => 'mx-1 btn btn-primary',
                'title' => 'Update Approver',
            ]);

            ?>
            <tr>
                <td><?= $templateMember->approver_name ?></td>
                <td><?= $templateMember->approver_email ?></td>
                <td><?= $templateMember->sequence ?></td>
                <td><?= $update . $delete ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>