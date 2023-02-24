<?php
/**
 *
 * * SAM-4558: Settlement Deleter module
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-02
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Delete;

/**
 * Trait SettlementItemDeleterAwareTrait
 * @package Sam\Settlement\Delete
 */
trait SettlementItemDeleterAwareTrait
{
    protected ?SettlementItemDeleter $settlementItemDeleter = null;

    /**
     * @return SettlementItemDeleter
     */
    protected function getSettlementItemDeleter(): SettlementItemDeleter
    {
        if ($this->settlementItemDeleter === null) {
            $this->settlementItemDeleter = SettlementItemDeleter::new();
        }
        return $this->settlementItemDeleter;
    }

    /**
     * @param SettlementItemDeleter $settlementItemDeleter
     * @return static
     * @internal
     */
    public function setSettlementItemDeleter(SettlementItemDeleter $settlementItemDeleter): static
    {
        $this->settlementItemDeleter = $settlementItemDeleter;
        return $this;
    }
}
