<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */


?>

<div class="envelo" style="background-color:#ddd; padding:1rem; width: 100%; max-width: 700px;">



    <table style="width: 100%; margin: 1rem 0; padding: 0.5rem;">
        <tr>
            <td>Dear <b><?= ucwords($user->username) ?></b>, <br><br>
                This is to notify you that some / all of the data fields below could have changed on the system:<br>
                <br>
                <ul>
                    <li>Username: <b><?= $user->username ?></b></li>
                    <li>Email: <b><?= $user->email ?></b></li>
                    <li>Staff ID Number: <b><?= $user->staff_id_number ?></b></li>
                </ul>
                <br><br>
                <b>NB:</b> If you did not initiate this changes on the system, please notify the system administrators
                for quick intervention.
                <br>
                Thank you.
            </td>
        </tr>
    </table>


</div>




<footer
    style="margin:1.5rem 0;text-align: center;font-size: 12px;border-top: 1px solid #dddddd;display:flex;justify-content:space-between;padding:0 10px;width: 100%; max-width: 700px;">
    <div style="color: #ffffff;padding: 10px;background-color:#5a5757;margin: 0;line-height: 1.5;">&copy;
        <?= date('Y') ?>
        <?= env('CUSTOMER', 'KEMRI ICT') ?>
    </div>
    <div style="color: #ffffff;padding: 10px;background-color:#5a5757;margin: 0;line-height: 1.5; ">
        Powered by KEMRI
        ICT | Engineered By
        <?= Html::a(env('DEVELOPER', 'Francis Njambi'), 'https://www.linkedin.com/in/francisnjambi/', ['target' => '_blank', 'title' => 'Systems Developer', 'style' => 'color: #ffffff; text-decoration: none;']) ?>
    </div>
</footer>