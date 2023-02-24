<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/18/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Delete;

/**
 * Trait SettlementDeleterAwareTrait
 * @package Sam\Settlement\Delete
 */
trait SettlementDeleterAwareTrait
{
    protected ?SettlementDeleter $settlementDeleter = null;

    /**
     * @return SettlementDeleter
     */
    protected function getSettlementDeleter(): SettlementDeleter
    {
        if ($this->settlementDeleter === null) {
            $this->settlementDeleter = SettlementDeleter::new();
        }
        return $this->settlementDeleter;
    }

    /**
     * @param SettlementDeleter $settlementDeleter
     * @return static
     * @internal
     */
    public function setSettlementDeleter(SettlementDeleter $settlementDeleter): static
    {
        $this->settlementDeleter = $settlementDeleter;
        return $this;
    }
}
