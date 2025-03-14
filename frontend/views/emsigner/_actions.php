<?php

use yii\bootstrap5\Html;

$contract = Yii::$app->session->get('metadata')['Application'];

if (Yii::$app->utility->ismycontract($contract)) {
    echo Html::a(
        '<i class="fas fa-paper-plane"></i>Next Reviewers',
        [
            'generate-approval-entries',
            'contractNumber' => $contract,
        ],
        [
            'class' => 'btn btn-app bg-primary mx-3',
            'title' => 'Send contract to next reviewer for consideration and signing'
        ]
    );
} else {
    echo Html::a(
        '<i class="fas fa-fast-forward"></i>Next Reviewer',
        [
            'approve-request',
            'contractNumber' => $contract,
        ],
        [
            'class' => 'btn btn-app bg-success mx-3',
            'title' => 'Send contract to next reviewer for consideration and signing'
        ]
    );
}



