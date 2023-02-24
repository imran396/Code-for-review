<?php

namespace Sam\Email\Queue;

use Sam\Core\Service\CustomizableClass;
use Sam\ActionQueue\Base\ActionQueueHandlerInterface;
use Sam\AuditTrail\AuditTrailLoggerAwareTrait;
use Sam\Email\Queue\Convert\ActionQueueEmailDataConverter;
use Sam\Email\Transport\Smpt;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Email action handler for action queue
 *
 * @author tom
 * @package com.swb.sam2
 * @subpackage Component
 */
class EmailActionHandler extends CustomizableClass implements ActionQueueHandlerInterface
{
    use AuditTrailLoggerAwareTrait;
    use ConfigRepositoryAwareTrait;

    /**
     * Returns instance of Email_ActionHandler
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Send email
     * @param \ActionQueue $actionQueue
     * @return bool
     * @see ActionQueueHandlerInterface::process()
     */
    public function process(\ActionQueue $actionQueue): bool
    {
        $actionQueueDto = $this->getDtoFromData($actionQueue);
        $isEmailSent = $this->sendEmail($actionQueueDto);
        $this->logToAuditTrail($actionQueueDto, $actionQueue->CreatedBy, $actionQueue->Attempts + 1, $isEmailSent);
        return $isEmailSent;
    }

    /**
     * Unserialize data from $action Queue
     * @param \ActionQueue $actionQueue
     * @return ActionQueueDto
     */
    private function getDtoFromData(\ActionQueue $actionQueue): ActionQueueDto
    {
        $converter = ActionQueueEmailDataConverter::new();
        $actionQueueDto = $converter->fromFormat($actionQueue->Data);
        return $actionQueueDto;
    }

    /**
     * @param ActionQueueDto $actionQueueDto
     * @return bool
     */
    private function sendEmail(ActionQueueDto $actionQueueDto): bool
    {
        $accountId = $actionQueueDto->getAccountId() ?? $this->cfg()->get('core->portal->mainAccountId');
        $isEmailSent = Smpt::new()
            ->setAccountId($accountId)
            ->send($actionQueueDto->getEmail());
        return $isEmailSent;
    }

    /**
     * Message to Audit Trail
     * @param ActionQueueDto $actionQueueDto
     * @param int $editorUserId
     * @param int $attempt
     * @param bool $isEmailSent
     */
    private function logToAuditTrail(ActionQueueDto $actionQueueDto, int $editorUserId, int $attempt, bool $isEmailSent): void
    {
        $section = 'mail';
        if ($actionQueueDto->getEmailTemplate()) {
            $section .= '/' . $actionQueueDto->getEmailTemplate()->Name;
        }
        $email = $actionQueueDto->getEmail();
        $accountId = $actionQueueDto->getAccountId();
        $event = $isEmailSent ? "Email sent to {$email->getTo()} subject {$email->getSubject()}"
            : "Attempt {$attempt} failed to send email to {$email->getTo()} subject {$email->getSubject()}";
        $this->getAuditTrailLogger()
            ->setAccountId($accountId)
            ->setEditorUserId($editorUserId)
            ->setEvent($event)
            ->setSection($section)
            ->setUserId($editorUserId)
            ->log();
    }
}
