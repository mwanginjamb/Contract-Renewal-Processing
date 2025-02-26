<?php
$templateMembers = $model->getWorkflowMembers()->all();

?>

<table class="table">
    <thead>
        <tr>
            <th>Approver Name</th>
            <th>Approver Email</th>
            <th>Approver Phone Number</th>
            <th>Sequence</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($templateMembers as $templateMember): ?>
            <tr>
                <td><?= $templateMember->approver_name ?></td>
                <td><?= $templateMember->approver_email ?></td>
                <td><?= $templateMember->approver_phone_number ?></td>
                <td><?= $templateMember->sequence ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tbody>
</table>