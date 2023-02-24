<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\HardDelete\Single\Delete;

/**
 * Trait SingleSettlementCheckDeleterCreateTrait
 * @package Sam\Settlement\Check
 */
trait SingleSettlementCheckDeleterCreateTrait
{
    protected ?SingleSettlementCheckDeleter $singleSettlementCheckDeleter = null;

    /**
     * @return SingleSettlementCheckDeleter
     */
    protected function createSingleSettlementCheckDeleter(): SingleSettlementCheckDeleter
    {
        return $this->singleSettlementCheckDeleter ?: SingleSettlementCheckDeleter::new();
    }

    /**
     * @param SingleSettlementCheckDeleter $singleSettlementCheckDeleter
     * @return $this
     * @internal
     */
    public function setSingleSettlementCheckDeleter(SingleSettlementCheckDeleter $singleSettlementCheckDeleter): static
    {
        $this->singleSettlementCheckDeleter = $singleSettlementCheckDeleter;
        return $this;
    }
}
