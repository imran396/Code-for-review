<?php
/**
 * SAM-10431: Refactor rtb catalog renderer for v3-7
 * SAM-5400: Rtb state update refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Catalog\Bidder\Manage;

use InvalidArgumentException;
use RtbCurrent;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Rtb\Catalog\Bidder\Render\Hybrid\HybridBidderCatalogRenderer;
use Sam\Rtb\Catalog\Bidder\Render\Live\LiveBidderCatalogRenderer;

/**
 * Class CatalogServiceFactory
 * @package Sam\Rtb\Catalog
 */
class BidderCatalogManagerFactory extends CustomizableClass
{
    use AuctionLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $auctionType
     * @param int $systemAccountId
     * @param int $viewLanguageId
     * @return BidderCatalogManager
     */
    public function createByAuctionType(string $auctionType, int $systemAccountId, int $viewLanguageId): BidderCatalogManager
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isLive($auctionType)) {
            return BidderCatalogManager::new()->construct(
                LiveBidderCatalogRenderer::new()->construct($systemAccountId, $viewLanguageId)
            );
        }

        if ($auctionStatusPureChecker->isHybrid($auctionType)) {
            return BidderCatalogManager::new()->construct(
                HybridBidderCatalogRenderer::new()->construct($systemAccountId, $viewLanguageId)
            );
        }

        throw new InvalidArgumentException(
            'Cannot create catalog service by auction type'
            . composeSuffix(['type' => $auctionType])
        );
    }

    /**
     * @param int|null $auctionId
     * @return BidderCatalogManager
     */
    public function createByAuctionId(?int $auctionId, int $systemAccountId, int $viewLanguageId): BidderCatalogManager
    {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if ($auction) {
            return $this->createByAuctionType($auction->AuctionType, $systemAccountId, $viewLanguageId);
        }

        throw new InvalidArgumentException(
            'Cannot create catalog service. Auction not found'
            . composeSuffix(['a' => $auctionId])
        );
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @return BidderCatalogManager
     */
    public function createByRtbCurrent(RtbCurrent $rtbCurrent, int $systemAccountId, int $viewLanguageId): BidderCatalogManager
    {
        return $this->createByAuctionId($rtbCurrent->AuctionId, $systemAccountId, $viewLanguageId);
    }
}
