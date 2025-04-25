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
                ['html' => 'fullySignedNotification-html'],
                ['contract' => $contract]
            )
            ->setFrom(env('SMTP_USERNAME'))
            ->setTo($to) // Ensure `user` relation exists
            ->setSubject("STAFF CONTRACT #{$contract->contract_number} HAS BEEN FULLY SIGNED.")
            ->send();
    }



}

