<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */

//Yii::$app->urlManager->hostInfo = env('APP_BASE_URL', 'http://utility.com');
$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['contracts/approvals']);

?>

<div class="envelo" style="background-color:#ddd; padding:1rem; width: 100%; max-width: 600px;">



    <table style="width: 100%; margin: 1rem 0; padding: 0.5rem;">
        <tr>
            <td>Dear <b><?= $workflowEntry->approver->approver_name ?></b>, <br><br>
                You are notified that a staff contract <?= $workflowEntry->contract->contract_number ?> is due for your
                consideration and <b>signing</b>.<br>
                <br>Click button below to sign and move it to the next approver upon successful signing.
                <br><br>
                <b>NB:</b> You have to see a preview of the signed contract file after signing successfully in order to
                click the action to
                send it to next signer.
            </td>
        </tr>
    </table>


</div>



<div class="button-container" style="display: flex;justify-content: center;margin: 20px 0;">
    <?= Html::a('View Signing Entries', $verifyLink, ['style' => 'text-decoration:none;background-color: #007bff;color: #ffffff;border: none;padding: 10px 20px;font-size: 14px;font-weight: bold;cursor: pointer;border-radius: 5px;transition: background-color 0.3s;']) ?>
</div>
<footer
    style="margin:1.5rem 0;text-align: center;font-size: 12px;border-top: 1px solid #dddddd;display:flex;justify-content:space-between;padding:0 10px;">
    <div style="color: #ffffff;padding: 10px;background-color:#5a5757;margin: 0;line-height: 1.5;">&copy;
        <?= date('Y') ?>
        <?= env('CUSTOMER', 'KEMRI ICT') ?>
    </div>
    <div style="color: #ffffff;padding: 10px;background-color:#5a5757;margin: 0;line-height: 1.5;">
        Powered by KEMRI
        ICT | Engineered By
        <?= Html::a(env('DEVELOPER', 'Francis Njambi'), 'https://www.linkedin.com/in/francisnjambi/', ['target' => '_blank', 'title' => 'Systems Developer', 'style' => 'color: #ffffff; text-decoration: none;']) ?>
    </div>
</footer>