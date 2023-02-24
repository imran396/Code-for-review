<?php
/**
 * SAM-9767: Check Printing for Settlements: Unit Test Case Development and Execution (Developer)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 09, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\HardDelete\Single\Delete;

use Sam\Core\Service\CustomizableClass;
use SettlementCheck;

/**
 * Class SingleSettlementCheckDeletionResult
 * @package Sam\Settlement\Check\Action\HardDelete\Single\Delete
 */
class SingleSettlementCheckDeletionResult extends CustomizableClass
{
    public readonly SettlementCheck $settlementCheck;
    public readonly bool $isDeleted;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param SettlementCheck $settlementCheck
     * @param bool $isDeleted
     * @return $this
     */
    public function construct(SettlementCheck $settlementCheck, bool $isDeleted = false): static
    {
        $this->settlementCheck = $settlementCheck;
        $this->isDeleted = $isDeleted;
        return $this;
    }

    public function toArray(): array
    {
        return [$this->settlementCheck, $this->isDeleted];
    }
}
