<?php
/**
 * General repository for AuctionBidderOptionSelectionReadRepository Parameters entity
 *
 * SAM-3680: Bidder and consignor related repositories https://bidpath.atlassian.net/browse/SAM-3680
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           05 Apr, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * Usage example:
 * $auctionBidderOptionSelectionRepository = \Sam\Storage\ReadRepository\Entity\AuctionBidderOptionSelection\AuctionBidderOptionSelectionReadRepository::new()
 *      ->enableReadOnlyDb(true)
 *      ->filterAuctionId($auctionIds);
 * $isFound = $auctionBidderOptionSelectionRepository->exist();
 * $count = $auctionBidderOptionSelectionRepository->count();
 * $item = $auctionBidderOptionSelectionRepository->loadEntity();
 * $items = $auctionBidderOptionSelectionRepository->loadEntities();
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionBidderOptionSelection;

/**
 * Class AuctionBidderOptionSelectionReadRepository
 */
class AuctionBidderOptionSelectionReadRepository extends AbstractAuctionBidderOptionSelectionReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'auction_bidder_option' => 'JOIN `auction_bidder_option` AS `abo` ON `abos`.`auction_bidder_option_id` = `abo`.`id`',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Left join auction_bidder_option table
     * @return static
     */
    public function joinAuctionBidderOption(): static
    {
        $this->join('auction_bidder_option');
        return $this;
    }
}
