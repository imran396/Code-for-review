<?php
/**
 * SAM-10626: Supply uniqueness for user fields: email
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Lock\Email\Internal\Detect;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class UniqueEmailLockRequirementCheckingResult
 * @package Sam\EntityMaker\User\Lock\Email\Internal
 */
class UniqueEmailLockRequirementCheckingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const INFO_DO_NOT_LOCK_BECAUSE_EMAIL_ABSENT_IN_INPUT = 1;
    public const INFO_DO_NOT_LOCK_BECAUSE_EMAIL_NOT_CHANGED = 2;

    public const OK_LOCK_BECAUSE_NEW_RECORD_CREATED = 11;
    public const OK_LOCK_BECAUSE_EMAIL_CHANGED = 12;

    protected const INFO_MESSAGES = [
        self::INFO_DO_NOT_LOCK_BECAUSE_EMAIL_ABSENT_IN_INPUT => 'Do not lock for unique email constraint, because email field is absent in input',
        self::INFO_DO_NOT_LOCK_BECAUSE_EMAIL_NOT_CHANGED => 'Do not lock for unique email constraint, because email will not be changed',
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_LOCK_BECAUSE_NEW_RECORD_CREATED => 'Lock for unique email constraint, because new user will be created',
        self::OK_LOCK_BECAUSE_EMAIL_CHANGED => 'Lock for unique email constraint, because email will be changed',
    ];

    protected ?int $userId;
    protected ?string $email;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(?int $userId, ?string $email): static
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->getResultStatusCollector()->construct([], self::SUCCESS_MESSAGES, [], self::INFO_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    public function addSuccess(int $code): static
    {
        $this->getResultStatusCollector()->addSuccess($code);
        return $this;
    }

    public function addInfo(int $code): static
    {
        $this->getResultStatusCollector()->addInfo($code);
        return $this;
    }

    // --- Query ---

    public function mustLock(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function message(): string
    {
        if ($this->getResultStatusCollector()->hasSuccess()) {
            return $this->getResultStatusCollector()->getConcatenatedSuccessMessage() . composeSuffix($this->logData());
        }

        if ($this->getResultStatusCollector()->hasInfo()) {
            return $this->getResultStatusCollector()->getConcatenatedInfoMessage() . composeSuffix($this->logData());
        }

        return '';
    }

    protected function logData(): array
    {
        $logData = [];
        if ($this->userId) {
            $logData['u'] = $this->userId;
        }
        if ($this->email) {
            $logData['email'] = $this->email;
        }
        return $logData;
    }
}
