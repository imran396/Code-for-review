<?php
/**
 * SAM-5018 : Refactor Email_Template to sub classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Apr 1, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Queue;

use AuctionEmailTemplate;
use EmailTemplate;
use Sam\ActionQueue\ActionQueueManagerAwareTrait;
use Sam\AuditTrail\AuditTrailLoggerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Email\Queue\Convert\ActionQueueEmailDataConverter;

/**
 * Class ActionQueue
 * @package Sam\Email
 */
class ActionQueue extends CustomizableClass
{
    use ActionQueueManagerAwareTrait;
    use AuditTrailLoggerAwareTrait;

    protected AuctionEmailTemplate|EmailTemplate|null $emailTemplate = null;

    /**
     * Optional unique identifier for email (by default TO~SUBJECT)
     */
    protected ?string $identifier = null;

    /**
     * Optional group identifier
     */
    protected ?int $group = null;
    protected int $maxAttempts = 1;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Add email to ActionQueue
     *
     * @param ActionQueueDto $dto
     * @param int $priority Default ActionQueue::LOW. Can be ActionQueue::MEDIUM or ActionQueue::HIGH
     * @param int $editorUserId
     * @return bool
     */
    public function add(ActionQueueDto $dto, int $priority, int $editorUserId): bool
    {
        if (
            $this->getEmailTemplate()
            && $this->getEmailTemplate()->Disabled
        ) {
            log_info($this->getLogMessageForDisabledEmailTemplate($dto));
            return false;
        }

        if (!$dto->getEmail()->getTo()) {
            $errorMessage = "Failed to queue e-mail: 'TO' is empty";
            log_error($errorMessage . composeSuffix(['editor u' => $editorUserId]));
            $this->logMessageToAuditTrail($dto, $editorUserId, $errorMessage);
            return false;
        }

        $identifier = $this->getIdentifier() ?: $dto->getEmail()->getIdentifier();
        $converter = ActionQueueEmailDataConverter::new();
        $data = $converter->toFormat($dto);
        if (!$data) {
            $errorMessage = "Failed to queue e-mail: Data is empty";
            log_errorBackTrace($errorMessage . composeSuffix(['editor u' => $editorUserId]));
            $this->logMessageToAuditTrail($dto, $editorUserId, $errorMessage);
            return false;
        }

        $this->getActionQueueManager()->addToQueue(
            EmailActionHandler::class,
            $data,
            $editorUserId,
            $identifier,
            $this->getGroup(),
            $priority,
            $this->getMaxAttempts()
        );
        return true;
    }

    /**
     * @param $dto
     * @return string
     */
    private function getLogMessageForDisabledEmailTemplate(ActionQueueDto $dto): string
    {
        return sprintf(
            "Email %s %s from:%s; to:%s is disabled",
            $this->getEmailTemplate()->Id,
            $this->getEmailTemplate()->Key,
            $dto->getEmail()->getFrom(),
            $dto->getEmail()->getTo()
        );
    }

    /**
     * Message to Audit Trail
     * @param ActionQueueDto $dto
     * @param int $editorUserId
     * @param string $message
     */
    protected function logMessageToAuditTrail(ActionQueueDto $dto, int $editorUserId, string $message): void
    {
        $section = isset($this->emailTemplate) ? "mail/{$this->emailTemplate->Name}" : 'mail/';
        $event = $message . composeSuffix(['subject' => $dto->getEmail()->getSubject()]);
        $accountId = $dto->getAccountId();
        $this->getAuditTrailLogger()
            ->setAccountId($accountId)
            ->setEditorUserId($editorUserId)
            ->setEvent($event)
            ->setSection($section)
            ->setUserId($editorUserId)
            ->log();
    }

    /**
     * @return AuctionEmailTemplate|EmailTemplate|null
     */
    public function getEmailTemplate(): AuctionEmailTemplate|EmailTemplate|null
    {
        return $this->emailTemplate;
    }

    /**
     * @param AuctionEmailTemplate|EmailTemplate|null $emailTemplate
     * @return static
     */
    public function setEmailTemplate(AuctionEmailTemplate|EmailTemplate|null $emailTemplate): static
    {
        $this->emailTemplate = $emailTemplate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * @return static
     */
    public function setIdentifier(string $identifier): static
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getGroup(): ?int
    {
        return $this->group;
    }

    /**
     * @param int|null $group
     * @return static
     */
    public function setGroup(?int $group): static
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxAttempts(): int
    {
        return $this->maxAttempts;
    }

    /**
     * @param int $maxAttempts
     * @return static
     */
    public function setMaxAttempts(int $maxAttempts): static
    {
        $this->maxAttempts = $maxAttempts;
        return $this;
    }
}
