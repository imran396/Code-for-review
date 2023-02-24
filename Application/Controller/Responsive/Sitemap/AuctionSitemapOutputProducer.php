<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Sitemap;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Sitemap\AuctionLots\Manager;

/**
 * Class AuctionSitemapOutputProducer
 * @package Sam\Application\Controller\Responsive\Sitemap
 */
class AuctionSitemapOutputProducer extends CustomizableClass
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

    public function produce(?int $auctionId): void
    {
        header('Content-type: text/xml');
        $auction = $this->getAuctionLoader()->load($auctionId);
        if ($auction) {
            $output = Manager::new()
                ->setAuction($auction)
                ->generate();
            echo $output;
        }
    }
}
