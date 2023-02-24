<?php
/**
 * SAM-10627: Supply uniqueness for user fields: customer#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Lock\CustomerNo\Internal\Detect;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotItemUniqueItemNoLockingInput
 * @package Sam\EntityMaker\LotItem
 */
class UserUniqueCustomerNoLockRequirementCheckingInput extends CustomizableClass
{
    public ?int $userId;
    public bool $isSetCustomerNo;
    public string $customerNo;

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    public function construct(
        ?int $userId,
        bool $isSetCustomerNo,
        string $customerNo,
    ): static {
        $this->userId = $userId;
        $this->isSetCustomerNo = $isSetCustomerNo;
        $this->customerNo = $customerNo;
        return $this;
    }

    public function logData(): array
    {
        $logData['u'] = $this->userId;
        if ($this->isSetCustomerNo) {
            $logData['customerNo'] = $this->customerNo;
        }
        return $logData;
    }
}
