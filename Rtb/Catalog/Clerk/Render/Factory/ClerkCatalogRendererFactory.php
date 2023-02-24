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

namespace Sam\Rtb\Catalog\Clerk\Render\Factory;

use InvalidArgumentException;
use RtbCurrent;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Rtb\Catalog\Clerk\Render\Base\AbstractClerkCatalogRenderer;
use Sam\Rtb\Catalog\Clerk\Render\Hybrid\HybridClerkCatalogRenderer;
use Sam\Rtb\Catalog\Clerk\Render\Live\LiveClerkCatalogRenderer;

/**
 * Class CatalogServiceFactory
 * @package Sam\Rtb\Catalog
 */
class ClerkCatalogRendererFactory extends CustomizableClass
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
     * @param RtbCurrent $rtbCurrent
     * @return AbstractClerkCatalogRenderer
     */
    public function create(RtbCurrent $rtbCurrent): AbstractClerkCatalogRenderer
    {
        return $this->createByAuctionId($rtbCurrent->AuctionId);
    }

    /**
     * @param string $auctionType
     * @return AbstractClerkCatalogRenderer
     */
    public function createByAuctionType(string $auctionType): AbstractClerkCatalogRenderer
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isLive($auctionType)) {
            return LiveClerkCatalogRenderer::new();
        }

        if ($auctionStatusPureChecker->isHybrid($auctionType)) {
            return HybridClerkCatalogRenderer::new();
        }

        throw new InvalidArgumentException(
            'Cannot create catalog service by auction type'
            . composeSuffix(['type' => $auctionType])
        );
    }

    /**
     * @param int|null $auctionId
     * @return AbstractClerkCatalogRenderer
     */
    public function createByAuctionId(?int $auctionId): AbstractClerkCatalogRenderer
    {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if ($auction) {
            return $this->createByAuctionType($auction->AuctionType);
        }

        throw new InvalidArgumentException(
            'Cannot create catalog service. Auction not found'
            . composeSuffix(['a' => $auctionId])
        );
    }
}
