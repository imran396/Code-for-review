<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\Auction;

use Sam\Core\Constants;

/**
 * Class ResponsiveCatalogUrlConfig
 * @package Sam\Application\Url\Build\Config
 */
class ResponsiveAuctionFirstLotUrlConfig extends AbstractResponsiveSingleAuctionUrlConfig
{
    protected ?int $urlType = Constants\Url::P_AUCTIONS_FIRST_LOT;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $auctionId
     * @param string|null $seoUrl
     * @param array $options
     * @return static
     */
    public function construct(?int $auctionId, ?string $seoUrl = null, array $options = []): static
    {
        // Avoid appending with seo url
        parent::construct($auctionId, '', $options);
        return $this;
    }

}
