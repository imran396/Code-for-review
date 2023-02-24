<?php
/**
 * SAM-10627: Supply uniqueness for user fields: customer#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Lock\CustomerNo\Internal\Detect;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Lock\CustomerNo\Internal\Detect\UserUniqueCustomerNoLockRequirementCheckingInput as Input;

/**
 * Class UserUniqueCustomerNoLockRequirementCheckingResult
 * @package Sam\EntityMaker\User\Lock\CustomerNo\Internal\Detect
 */
class UserUniqueCustomerNoLockRequirementCheckingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const INFO_DO_NOT_LOCK_WHEN_CREATE_USER_WITH_EMPTY_INPUT_BECAUSE_GENERATION_DISABLED = 1;
    public const INFO_DO_NOT_LOCK_WHEN_EDIT_USER_BECAUSE_CUSTOMER_NO_ABSENT_IN_INPUT = 2;
    public const INFO_DO_NOT_LOCK_WHEN_EDIT_USER_BECAUSE_CUSTOMER_NO_NOT_MODIFIED = 3;

    public const OK_LOCK_WHEN_CREATE_USER_WITH_EMPTY_INPUT_BECAUSE_GENERATION_ENABLED = 11;
    public const OK_LOCK_WHEN_CREATE_USER_WITH_FILLED_INPUT = 12;
    public const OK_LOCK_WHEN_EDIT_USER_BECAUSE_CUSTOMER_NO_MODIFIED = 13;

    protected const INFO_MESSAGES = [
        self::INFO_DO_NOT_LOCK_WHEN_CREATE_USER_WITH_EMPTY_INPUT_BECAUSE_GENERATION_DISABLED => 'Do not lock for unique customer# constraint, when creating new user with empty input, because customer# generation is disabled',
        self::INFO_DO_NOT_LOCK_WHEN_EDIT_USER_BECAUSE_CUSTOMER_NO_ABSENT_IN_INPUT => 'Do not lock for unique customer# constraint, when editing the existing user, because customer# field is absent in input',
        self::INFO_DO_NOT_LOCK_WHEN_EDIT_USER_BECAUSE_CUSTOMER_NO_NOT_MODIFIED => 'Do not lock for unique customer# constraint, when editing the existing user, because customer# is not modified',
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_LOCK_WHEN_CREATE_USER_WITH_EMPTY_INPUT_BECAUSE_GENERATION_ENABLED => 'Lock for unique customer# constraint, when creating new user with empty input, because customer# generation is enabled',
        self::OK_LOCK_WHEN_CREATE_USER_WITH_FILLED_INPUT => 'Lock for unique customer# constraint, when creating new user with filled customer# input',
        self::OK_LOCK_WHEN_EDIT_USER_BECAUSE_CUSTOMER_NO_MODIFIED => 'Lock for unique customer# constraint, when editing the existing user, because customer# is modified',
    ];

    protected Input $input;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(Input $input): static
    {
        $this->input = $input;
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
        $logData['u'] = $this->input->userId;
        if ($this->input->isSetCustomerNo) {
            $logData['customer#'] = $this->input->customerNo;
        }
        return $logData;
    }
}
