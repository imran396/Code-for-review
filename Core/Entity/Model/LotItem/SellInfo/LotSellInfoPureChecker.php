<?php
/**
 * SAM-6867: Enrich LotItem entity
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\LotItem\SellInfo;

use Sam\Core\Service\CustomizableClass;

/**
 * Class WinInfoPureChecker
 * @package Sam\Core\Entity\Model\LotItem\SellInfo
 */
class LotSellInfoPureChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check HP is legal for lot to be invoiced. Zero HP is expected as well.
     * @param float|null $hammerPrice
     * @return bool
     */
    public function isHammerPrice(?float $hammerPrice): bool
    {
        return $hammerPrice !== null;
    }

    /**
     * Check presence of winning bidder reference to his user.id
     * @param int|null $winningBidderUserId
     * @return bool
     */
    public function isWinningBidder(?int $winningBidderUserId): bool
    {
        return (int)$winningBidderUserId > 0;
    }

    /**
     * Check winning bidder is assigned and equal to the user is passed.
     * @param int|null $lotItemWinningBidderUserId
     * @param int|null $userId
     * @return bool
     */
    public function isWinningBidderLinkedWith(?int $lotItemWinningBidderUserId, ?int $userId): bool
    {
        return $lotItemWinningBidderUserId
            && $userId
            && $lotItemWinningBidderUserId === $userId;
    }

    /**
     * Check presence of "Sale Sold" reference to auction.id
     * @param int|null $auctionId
     * @return bool
     */
    public function isSaleSoldAuction(?int $auctionId): bool
    {
        return (int)$auctionId > 0;
    }

    /**
     * Check "Sale Sold" auction reference present and is linked with passed auction.
     * @param int|null $lotItemSaleSoldAuctionId
     * @param int|null $auctionId
     * @return bool
     */
    public function isSaleSoldAuctionLinkedWith(?int $lotItemSaleSoldAuctionId, ?int $auctionId): bool
    {
        return $lotItemSaleSoldAuctionId
            && $auctionId
            && $lotItemSaleSoldAuctionId === $auctionId;
    }

    /**
     * Check "Sale Sold" auction reference points to one of the auction ids in passed array.
     * @param int|null $lotItemSaleSoldAuctionId
     * @param array $auctionIds
     * @return bool
     */
    public function isSaleSoldAuctionAmong(?int $lotItemSaleSoldAuctionId, array $auctionIds): bool
    {
        return $lotItemSaleSoldAuctionId
            && in_array($lotItemSaleSoldAuctionId, $auctionIds, true);
    }
}
