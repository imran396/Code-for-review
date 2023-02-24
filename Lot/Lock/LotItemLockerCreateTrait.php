<?php
/**
 * SAM-6063: Race condition on Buy Now action
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           5/8/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Lock;

/**
 * Trait LotItemLockerCreateTrait
 * @package
 */
trait LotItemLockerCreateTrait
{
    /**
     * @var LotItemLocker|null
     */
    protected ?LotItemLocker $lotItemLocker = null;

    /**
     * @return LotItemLocker
     */
    protected function createLotItemLocker(): LotItemLocker
    {
        return $this->lotItemLocker ?: LotItemLocker::new();
    }

    /**
     * @param LotItemLocker $lotItemLocker
     * @return $this
     * @noinspection PhpUnused
     * @internal
     */
    public function setLotItemLocker(LotItemLocker $lotItemLocker): static
    {
        $this->lotItemLocker = $lotItemLocker;
        return $this;
    }
}
