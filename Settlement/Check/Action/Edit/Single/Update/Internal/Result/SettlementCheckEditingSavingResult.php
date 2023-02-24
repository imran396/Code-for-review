<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           11-16, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\Edit\Single\Update\Internal\Result;

use Sam\Core\Service\CustomizableClass;
use SettlementCheck;

/**
 * Class SettlementCheckEditingSavingResult
 * @package Sam\Settlement\Check
 */
class SettlementCheckEditingSavingResult extends CustomizableClass
{
    public readonly SettlementCheck $settlementCheck;
    public readonly bool $isNew;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(SettlementCheck $settlementCheck, bool $isNew): static
    {
        $this->settlementCheck = $settlementCheck;
        $this->isNew = $isNew;

        return $this;
    }
}
