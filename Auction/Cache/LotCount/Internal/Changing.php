<?php
/**
 * Internal immutable value-object for AuctionLotCountCache
 *
 * SAM-6042: Extract lot count updating logic for auction cache to separate class
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 26, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Cache\LotCount\Internal;

/**
 * Class Changing for internal usage only
 * @internal
 */
final class Changing
{
    /** @var int - delta of change is registered (in-process memory), but not persisted yet */
    public const ST_REGISTERED = 1;
    /** @var int - delta of change is persisted (in out-of-process db) in AuctionCache record */
    public const ST_PERSISTED = 2;

    /**
     * @param int $delta
     * @param int $status
     */
    public function __construct(
        public int $delta = 0,
        public int $status = self::ST_REGISTERED
    ) {
    }

    /**
     * @param int $status
     * @return $this
     */
    public function withStatus(int $status): static
    {
        return new static($this->delta, $status);
    }

    /**
     * @param int $delta
     * @return $this
     */
    public function withDelta(int $delta): static
    {
        return new static($delta, $this->status);
    }
}
