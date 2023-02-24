<?php
/**
 * SAM-4855: Settlement consignor by email notifier module
 *
 * Fail settlement notification,
 * * when settlement record is not available,
 * * or locked because of issues with related entities,
 * * or consignor user's email cannot be found,
 * * or settlement status is not Open, Pending, Paid.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           15.02.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Settlement\Notify;

use Email_Template;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Settlement\Validate\SettlementRelatedEntityValidatorAwareTrait;
use Sam\Storage\Entity\AwareTrait\SettlementAwareTrait;
use Sam\Storage\WriteRepository\Entity\Settlement\SettlementWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Settlement;

/**
 * Class SingleSettlementNotifier
 * @package Sam\Settlement\Notify
 */
class SingleSettlementNotifier extends CustomizableClass
{
    use SettlementAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use SettlementRelatedEntityValidatorAwareTrait;
    use SettlementWriteRepositoryAwareTrait;
    use TranslatorAwareTrait;
    use UserLoaderAwareTrait;

    protected const NOTIFIED_SETTLEMENT_STATUSES = [
        Constants\Settlement::SS_OPEN,
        Constants\Settlement::SS_PENDING,
        Constants\Settlement::SS_PAID
    ];

    public const ERR_ABSENT_SETTLEMENT = 1;
    public const ERR_ABSENT_EMAIL = 2;
    public const ERR_WRONG_STATUS = 3;
    public const ERR_LOCKED = 4;

    public const OK_NOTIFIED = 11;

    /**
     * @var int|null - null means invalid priority value
     * @see \Sam\Core\Constants\ActionQueue::$priorities, while we setup it with
     * @see \Sam\Settlement\Notify\SingleSettlementNotifier::setPriority
     */
    public ?int $priority = Constants\ActionQueue::MEDIUM;

    protected ?Email_Template $emailTemplate = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Initialize object
     * @return static
     */
    public function initInstance(): static
    {
        $this->getResultStatusCollector()->clear();

        $errorMessages = [
            self::ERR_ABSENT_SETTLEMENT => 'Available settlement not found in notifier',
            self::ERR_ABSENT_EMAIL => '"%s" doesn\'t have an email address so settlement #%d couldn\'t be sent!',
            self::ERR_WRONG_STATUS => 'Settlement has not allowed status. Allowed statuses: Open, Pending, Paid.',
            self::ERR_LOCKED => 'Settlement locked for action (not operable)',
        ];

        $successMessages = [
            self::OK_NOTIFIED => 'Settlement email has been sent',
        ];

        $this->getResultStatusCollector()->construct($errorMessages, $successMessages);
        return $this;
    }

    /**
     * @param int $editorUserId
     * @return bool
     */
    public function notify(int $editorUserId): bool
    {
        if (!$this->validate($editorUserId)) {
            return false;
        }

        /** @var Settlement $settlement */
        $settlement = $this->getSettlement();

        if ($settlement->isOpen()) {
            $settlement->toPending();
            $this->getSettlementWriteRepository()->saveWithModifier($settlement, $editorUserId);
        }

        $emailManager = $this->buildEmailTemplate($editorUserId);
        $emailManager->addToActionQueue($this->getPriority());

        log_debug(
            'Successfully sending settlement email'
            . composeSuffix([
                's' => $settlement->Id,
                's#' => $settlement->SettlementNo,
                'to user' => $this->buildUserName($settlement)
            ])
        );
        $this->getResultStatusCollector()->addSuccess(self::OK_NOTIFIED);
        return true;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     * @return static
     */
    public function setPriority(int $priority): static
    {
        $this->priority = Cast::toInt($priority, Constants\ActionQueue::$priorities);
        return $this;
    }

    /**
     * @return string
     */
    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    public function errorMessage(?string $glue = null): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    /**
     * @return bool
     */
    public function hasSettlementLockedError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_LOCKED]);
    }

    /**
     * @return bool
     */
    public function hasSettlementWrongStatusError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_WRONG_STATUS]);
    }

    protected function validate(int $editorUserId): bool
    {
        $settlement = $this->getSettlement();
        if (!$settlement) {
            $this->getResultStatusCollector()->addError(self::ERR_ABSENT_SETTLEMENT);
            log_error('Available settlement is not found, when sending email');
            return false;
        }

        $logData = [
            's' => $settlement->Id,
            's#' => $settlement->SettlementNo,
            'status' => $settlement->SettlementStatusId,
            'u' => $settlement->ConsignorId
        ];

        $isOperable = $this->getSettlementRelatedEntityValidator()
            ->setSettlementId($settlement->Id)
            ->validate();
        if (!$isOperable) {
            $this->getResultStatusCollector()->addError(self::ERR_LOCKED);
            log_warning('Cannot send settlement email, because settlement is locked (not operable)' . composeSuffix($logData));
            return false;
        }

        $isValidSettlementStatus = in_array($settlement->SettlementStatusId, self::NOTIFIED_SETTLEMENT_STATUSES, true);
        if (!$isValidSettlementStatus) {
            $this->getResultStatusCollector()->addError(self::ERR_WRONG_STATUS);
            log_warning('Cannot send settlement email, because of unexpected settlement status' . composeSuffix($logData));
            return false;
        }

        $emailManager = $this->buildEmailTemplate($editorUserId);
        $emailTo = $emailManager->getEmail()->getTo();
        if (!$emailTo) {
            $this->getResultStatusCollector()->addErrorWithInjectedInMessageArguments(
                self::ERR_ABSENT_EMAIL,
                [$this->buildUserName($settlement), $settlement->SettlementNo]
            );
            log_warning('Cannot send settlement email, because sendTo email is absent' . composeSuffix($logData));
            return false;
        }

        return true;
    }

    protected function logError(string $message): void
    {
        log_error(
            $message
            . composeSuffix(['s' => $this->getSettlementId()])
        );
    }

    protected function buildEmailTemplate(int $editorUserId): Email_Template
    {
        if ($this->emailTemplate === null) {
            /** @var Settlement $settlement */
            $settlement = $this->getSettlement(true);

            $this->emailTemplate = Email_Template::new()->construct(
                $settlement->AccountId,
                Constants\EmailKey::SETTLEMENT,
                $editorUserId,
                [$settlement]
            );
        }

        return $this->emailTemplate;
    }

    protected function buildUserName(Settlement $settlement): string
    {
        $user = $this->getUserLoader()->load($settlement->ConsignorId, true);
        $userName = $user->Username ?? 'n/a';
        return $userName;
    }
}
