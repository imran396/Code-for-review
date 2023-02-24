<?php
/**
 * SAM-7949: Predictive upcoming auction stats script
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Stat\UpcomingAuction\Load\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * Class UpcomingAuctionActivityDto
 * @package Sam\Stat\UpcomingAuction\Load\Dto
 */
class UpcomingAuctionActivityDto extends CustomizableClass
{
    public readonly string $accountDomain;
    public readonly int $activeBidders;
    public readonly int $auctionId;
    public readonly string $auctionName;
    public readonly string $auctionType;
    public readonly int $bidsPlaced;
    public readonly string $dateIso;
    public readonly bool $isPublished;
    public readonly int $lots;
    public readonly int $registeredBidders;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $row
     * @return static
     */
    public function fromDbRow(array $row): static
    {
        $dto = self::new();
        $dto->accountDomain = $row['domain'];
        $dto->activeBidders = (int)$row['bidders_bidding'];
        $dto->auctionId = (int)$row['auction_id'];
        $dto->auctionName = $row['name'];
        $dto->auctionType = $row['auction_type'];
        $dto->bidsPlaced = (int)$row['bids_placed'];
        $dto->dateIso = $row['utc'];
        $dto->isPublished = (bool)$row['published'];
        $dto->lots = (int)$row['lots_active'];
        $dto->registeredBidders = (int)$row['bidders_registered'];
        return $dto;
    }
}
