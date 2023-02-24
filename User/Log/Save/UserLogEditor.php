<?php
/**
 * SAM-4702: User Log modules
 * SAM-1444: Walmart - Track user profile changes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko, Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01.02.2019 (Feb 20, 2013)
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\User\Log\Save;

use DateTime;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\UserLog\UserLogWriteRepositoryAwareTrait;
use Sam\User\Log\Load\UserLogLoaderCreateTrait;

/**
 * Class UserLogEditor
 * @package Sam\User\Log\Save
 */
class UserLogEditor extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;
    use UserLogLoaderCreateTrait;
    use UserLogWriteRepositoryAwareTrait;

    public const ERR_MSG_EMPTY = 1;
    public const ERR_MSG_TOO_LARGE = 2;
    public const ERR_WRONG_DATE = 3;
    public const OK_SAVED = 1;

    /** @var int[] */
    protected array $messageErrors = [self::ERR_MSG_EMPTY, self::ERR_MSG_TOO_LARGE];
    /** @var int[] */
    protected array $editDateErrors = [self::ERR_WRONG_DATE];
    /**
     * log message maximal allowed length
     */
    protected int $messageMaxLength = 1000;
    protected ?int $userLogId = null;
    protected string $message = '';
    protected ?string $editDateTimeString = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        // Init ResultStatusCollector
        $errorMessages = [
            self::ERR_MSG_EMPTY => 'Message required',
            self::ERR_MSG_TOO_LARGE => 'Only accepts not more than ' . $this->getMessageMaxLength() . ' characters',
            self::ERR_WRONG_DATE => 'Invalid date time',
        ];
        $successMessages = [
            self::OK_SAVED => 'User log saved',
        ];
        $this->getResultStatusCollector()->construct($errorMessages, $successMessages);
        return $this;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $this->getResultStatusCollector()->clear();

        $this->validateMessage($this->getMessage());
        $this->validateDate($this->getEditDateTimeString());

        $isValid = !$this->getResultStatusCollector()->hasError();
        return $isValid;
    }

    public function update(int $editorUserId): void
    {
        $userLog = $this->createUserLogLoader()->load($this->getUserLogId(), true);
        if ($userLog) {
            $userLog->Note = $this->getMessage();
            $userLog->TimeLog = new DateTime($this->getEditDateTimeString());
            $this->getUserLogWriteRepository()->saveWithModifier($userLog, $editorUserId);
        }

        $this->getResultStatusCollector()->addSuccess(self::OK_SAVED);
    }

    /**
     * Check message for length
     * @param string|null $message null leads to empty message error
     */
    public function validateMessage(?string $message): void
    {
        $collector = $this->getResultStatusCollector();

        if ((string)$message === '') {
            $collector->addError(self::ERR_MSG_EMPTY);
        } elseif (mb_strlen($message) > $this->getMessageMaxLength()) {
            $collector->addError(self::ERR_MSG_TOO_LARGE);
        }
    }

    /**
     * Check date is valid
     * @param string|null $dateTimeString null leads to wrong date error
     */
    public function validateDate(?string $dateTimeString): void
    {
        $collector = $this->getResultStatusCollector();
        if (!strtotime($dateTimeString)) {
            $collector->addError(self::ERR_WRONG_DATE);
        }
    }

    /**
     * @return bool
     */
    public function hasMessageFieldError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->messageErrors);
    }

    /**
     * @return bool
     */
    public function hasEditDateError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->editDateErrors);
    }

    /**
     * @return string
     */
    public function messageFieldErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes($this->messageErrors);
    }

    /**
     * @return string
     */
    public function editDateErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes($this->editDateErrors);
    }

    /**
     * @return int
     */
    public function getMessageMaxLength(): int
    {
        return $this->messageMaxLength;
    }

    /**
     * @param int $messageMaxLength
     * @return static
     * @noinspection PhpUnused
     */
    public function setMessageMaxLength(int $messageMaxLength): static
    {
        $this->messageMaxLength = $messageMaxLength;
        return $this;
    }

    /**
     * @return string
     */
    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return UserLogEditor
     */
    public function setMessage(string $message): UserLogEditor
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEditDateTimeString(): ?string
    {
        return $this->editDateTimeString;
    }

    /**
     * @param string $editDateTimeString
     * @return static
     */
    public function setEditDateTimeString(string $editDateTimeString): static
    {
        $this->editDateTimeString = $editDateTimeString;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getUserLogId(): ?int
    {
        return $this->userLogId;
    }

    /**
     * @param int $userLogId
     * @return static
     */
    public function setUserLogId(int $userLogId): static
    {
        $this->userLogId = $userLogId;
        return $this;
    }

}
