<?php
namespace common\Library;

use app\models\Contracts;
use app\models\WorkflowEntries;
use Yii;
use yii\base\Component;
use yii\base\Event;

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

}

