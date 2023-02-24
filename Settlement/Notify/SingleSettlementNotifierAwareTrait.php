<?php
/**
 * SAM-4855: Settlement consignor by email notifier module
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           15.02.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Settlement\Notify;

/**
 * Trait SingleSettlementNotifierAwareTrait
 * @package Sam\Settlement\Notify
 */
trait SingleSettlementNotifierAwareTrait
{
    protected ?SingleSettlementNotifier $singleSettlementNotifier = null;

    /**
     * @return SingleSettlementNotifier
     */
    protected function getSingleSettlementNotifier(): SingleSettlementNotifier
    {
        if ($this->singleSettlementNotifier === null) {
            $this->singleSettlementNotifier = SingleSettlementNotifier::new();
        }
        return $this->singleSettlementNotifier;
    }

    /**
     * @param SingleSettlementNotifier $singleSettlementNotifier
     * @return static
     * @internal
     */
    public function setSingleSettlementNotifier(SingleSettlementNotifier $singleSettlementNotifier): static
    {
        $this->singleSettlementNotifier = $singleSettlementNotifier;
        return $this;
    }
}
