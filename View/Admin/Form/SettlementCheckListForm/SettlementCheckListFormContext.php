<?php
/**
 * SAM-9888: Check Printing for Settlements: Bulk Checks Processing - Account level, Settlements List level (Part 2)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementCheckListForm;

use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementCheckListFormContext
 * @package Sam\View\Admin\Form\SettlementCheckListForm
 */
class SettlementCheckListFormContext extends CustomizableClass
{
    /**
     * @var int[]
     */
    public array $settlementIds = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function setSettlementId(array $settlementIds): static
    {
        $this->settlementIds = $settlementIds;
        return $this;
    }

    public function isSettlementContext(): bool
    {
        return count($this->settlementIds) > 0;
    }

    public function isSingleSettlementContext(): bool
    {
        return count($this->settlementIds) === 1;
    }

    public function isMultipleSettlementContext(): bool
    {
        return count($this->settlementIds) > 1;
    }

    public function getSingleSettlementId(): int
    {
        if (!$this->isSingleSettlementContext()) {
            throw new \RuntimeException(__METHOD__ . ' method is applicable only for single settlement context');
        }
        return reset($this->settlementIds);
    }

    public function getSettlementIds(): array
    {
        return $this->settlementIds;
    }
}
