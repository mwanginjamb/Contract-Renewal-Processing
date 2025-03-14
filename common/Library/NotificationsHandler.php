<?php
namespace common\Library;

use Yii;
use yii\base\Event;
use yii\base\Component;
use app\models\Contracts;
use app\models\WorkflowEntries;
use app\models\WorkflowTemplate;

class NotificationsHandler extends Component
{
    /**
     * Attaches an event handler during initiation
     */

    public function init()
    {
        parent::init();

        // Listen to custom event "EVENT_STATUS_PENDING" from WorkflowEntries model
        Event::on(
            WorkflowEntries::class,
            WorkflowEntries::EVENT_STATUS_PENDING,
            [$this, 'handleStatusPending']
        );

        // Listen to custom event "EVENT_CONTRACT_ATTACHED" from Contracts model

        Event::on(
            Contracts::class,
            Contracts::EVENT_CONTRACT_ATTACHED,
            [$this, 'handleContractAttached']
        );

        // Listen to custom event "EVENT_CONTRACT_FULLY_SIGNED" from Contracts model

        Event::on(
            Contracts::class,
            Contracts::EVENT_CONTRACT_FULLY_SIGNED,
            [$this, 'handleContractFullySigned']
        );
    }

    public function handleStatusPending(Event $event)
    {
        $workflowEntry = $event->sender; // workflowEntries model instance

        // Send notification
        Yii::$app
            ->mailer
            ->compose(
                ['html' => 'workflowNotification-html'],
                ['workflowEntry' => $workflowEntry]
            )
            ->setFrom(env('SMTP_USERNAME'))
            ->setTo($workflowEntry->approver->approver_email) // Ensure `approver` relation exists
            ->setSubject("APPROVAL REQUIRED FOR STAFF CONTRACT: #{$workflowEntry->contract->contract_number}")
            // ->setTextBody("Contract #{$workflowEntry->contract->contract_number} is pending your approval.")
            ->send();
    }

    public function handleContractAttached(Event $event)
    {
        $contract = $event->sender; // Contracts model instance
        // Generate Approval entries
        // $this->generateApprovalEntries($contract->id);
        // Send notification
        Yii::$app
            ->mailer
            ->compose(
                ['html' => 'officerSigningNotification-html'],
                ['contract' => $contract]
            )
            ->setFrom(env('SMTP_USERNAME'))
            ->setTo($contract->user->email) // Ensure `user` relation exists
            ->setSubject("YOUR STAFF CONTRACT #{$contract->contract_number} IS READY FOR SIGNING")
            // ->setTextBody("Contract #{$contract->contract_number} is ready for review.")
            ->send();
    }

    public function handleContractFullySigned(Event $event)
    {
        $contract = $event->sender; // Contracts model instance
        $hrEmails = Yii::$app->utility->getHremails();
        $officerEmail = [$contract->user->email];

        $to = array_merge($hrEmails, $officerEmail);
        // Send notification
        Yii::$app
            ->mailer
            ->compose(
                ['html' => 'officerSigningNotification-html'],
                ['contract' => $contract]
            )
            ->setFrom(env('SMTP_USERNAME'))
            ->setTo($to) // Ensure `user` relation exists
            ->setSubject("STAFF CONTRACT #{$contract->contract_number} HAS BEEN FULLY SIGNED.")
            // ->setTextBody("Contract #{$contract->contract_number} is ready for review.")
            ->send();
    }

    public function generateApprovalEntries($id)
    {
        $contractId = $id;
        $contractModel = Contracts::findOne(['id' => $id]);
        // Use the default/first Workflow Template where workflow_name is not null
        $approvalTemplate = WorkflowTemplate::find()->where(['IS NOT', 'workflow_name', NULL])->orderBy(['id' => SORT_DESC])->one();
        if ($approvalTemplate) {
            // Get WorkflowTemplateMembers for this template
            $members = $approvalTemplate->workflowMembers;
            // if members exist create workflow entries for the subject contract
            if ($members) {
                foreach ($members as $member) {
                    $workflowEntry = new WorkflowEntries();
                    $workflowEntry->template_id = $approvalTemplate->id;
                    $workflowEntry->approver_id = $member->user_id;
                    $workflowEntry->sequence = $member->sequence;
                    $workflowEntry->contract_id = $contractId;
                    if ($member->sequence === 1) {
                        $workflowEntry->approval_status = 1; // Pending
                    } else {
                        $workflowEntry->approval_status = 4; // created
                    }


                    //  Yii::$app->utility->printrr($workflowEntry);
                    if ($workflowEntry->save()) {
                        // Update approval status of the contract model to pending status
                        $contractModel->approval_status = 1;
                        $contractModel->save();
                        Yii::$app->session->setFlash('success', 'Approval entries created successfully.');
                    } else {
                        //  Yii::$app->utility->printrr($workflowEntry);
                        Yii::$app->session->setFlash('error', 'Error creating approval entries.');
                    }

                }
            }

        }
    }

}

