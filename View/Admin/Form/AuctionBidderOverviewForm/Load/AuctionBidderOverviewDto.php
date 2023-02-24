<?php
/**
 * SAM-8830: Apply DTOs for loaded data at Auction Bidder Overview page at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           May 28, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderOverviewForm\Load;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionBidderOverviewDto
 * @package Sam\View\Admin\Form\AuctionBidderOverviewForm\Load
 */
class AuctionBidderOverviewDto extends CustomizableClass
{
    public ?float $bid;
    public int $lotItemId = 0;
    public ?float $maxBid;
    public int $userId = 0;
    public string $username = '';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param float|null $bid for timed auction only
     * @param int $lotItemId
     * @param float|null $maxBid for live or hybrid auctions only
     * @param int $userId
     * @param string $username
     * @return $this
     */
    public function construct(
        ?float $bid,
        int $lotItemId,
        ?float $maxBid,
        int $userId,
        string $username
    ): static {
        $this->bid = $bid;
        $this->lotItemId = $lotItemId;
        $this->maxBid = $maxBid;
        $this->userId = $userId;
        $this->username = $username;
        return $this;
    }

    public function fromDbRow(array $row): static
    {
        return $this->construct(
            isset($row['bid']) ? Cast::toFloat($row['bid']) : null,
            (int)$row['lot_item_id'],
            isset($row['max_bid']) ? Cast::toFloat($row['max_bid']) : null,
            (int)$row['user_id'],
            (string)$row['username']
        );
    }
}
