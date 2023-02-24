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
 * Trait MultipleSettlementDeleterAwareTrait
 * @package Sam\Settlement\Delete
 */
trait MultipleSettlementDeleterAwareTrait
{
    protected ?MultipleSettlementDeleter $multipleSettlementDeleter = null;

    /**
     * @return MultipleSettlementDeleter
     */
    protected function getMultipleSettlementDeleter(): MultipleSettlementDeleter
    {
        if ($this->multipleSettlementDeleter === null) {
            $this->multipleSettlementDeleter = MultipleSettlementDeleter::new();
        }
        return $this->multipleSettlementDeleter;
    }

    /**
     * @param MultipleSettlementDeleter $multipleSettlementDeleter
     * @return static
     * @internal
     */
    public function setMultipleSettlementDeleter(MultipleSettlementDeleter $multipleSettlementDeleter): static
    {
        $this->multipleSettlementDeleter = $multipleSettlementDeleter;
        return $this;
    }
}
