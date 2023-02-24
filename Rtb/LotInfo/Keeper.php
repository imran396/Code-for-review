<?php
/**
 * This singleton stores current lot id for auction
 *
 * Related tickets:
 * SAM-3134: Refactorings and fixes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Feb 1, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\LotInfo;

use Sam\Core\Service\Singleton;

/**
 * Class Keeper
 * @package Sam\Rtb\LotInfo
 */
class Keeper extends Singleton
{
    /** @var int[] */
    protected array $statuses = [];

    /**
     * @return static
     */
    public static function getInstance(): static
    {
        return parent::_getInstance(self::class);
    }

    /**
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @return bool
     */
    public function check(?int $lotItemId, ?int $auctionId): bool
    {
        $success = isset($this->statuses[$auctionId])
            && $this->statuses[$auctionId] === $lotItemId;
        return $success;
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @return static
     */
    public function set(int $lotItemId, int $auctionId): static
    {
        $this->statuses[$auctionId] = $lotItemId;
        return $this;
    }

    /**
     * @param int $auctionId
     */
    public function drop(int $auctionId): void
    {
        unset($this->statuses[$auctionId]);
    }
}
