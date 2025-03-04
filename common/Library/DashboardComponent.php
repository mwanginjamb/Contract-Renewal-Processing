<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/10/2020
 * Time: 2:27 PM
 */

namespace common\Library;

use yii;
use yii\base\Component;
use common\models\Property;
use yii\helpers\ArrayHelper;
use app\models\WorkflowEntries;


class DashboardComponent extends Component
{

    // count properties

    public function countApprovals()
    {
        $approvals = WorkflowEntries::find()->where(['approver_id' => Yii::$app->user->id, 'approval_status' => 1])->asArray()->all();
        if ($approvals) {
            return count($approvals);
        }

        return 0;
    }

}
