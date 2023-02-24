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
 * Trait MultipleSettlementNotifierAwareTrait
 * @package Sam\Settlement\Notify
 */
trait MultipleSettlementNotifierAwareTrait
{
    protected ?MultipleSettlementNotifier $multipleSettlementNotifier = null;

    /**
     * @return MultipleSettlementNotifier
     */
    protected function getMultipleSettlementNotifier(): MultipleSettlementNotifier
    {
        if ($this->multipleSettlementNotifier === null) {
            $this->multipleSettlementNotifier = MultipleSettlementNotifier::new();
        }
        return $this->multipleSettlementNotifier;
    }

    /**
     * @param MultipleSettlementNotifier $multipleSettlementNotifier
     * @return static
     * @internal
     */
    public function setMultipleSettlementNotifier(MultipleSettlementNotifier $multipleSettlementNotifier): static
    {
        $this->multipleSettlementNotifier = $multipleSettlementNotifier;
        return $this;
    }
}
