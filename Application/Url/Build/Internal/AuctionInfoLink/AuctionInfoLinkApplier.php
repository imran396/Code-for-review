<?php
/**
 * Produces url on base of Auction->AuctionInfoLink value.
 * It is an optional URL to an external auction info page instead of the default SAM auction info page.
 * It can be absolute, relative or schemeless URLs.
 *
 * SAM-10520: Auction info link redirects to "The site can't be reached" page with inappropriate URL
 * SAM-6649: Encapsulate url building values and parameters in config objects
 * SAM-3523: Auction info link
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\AuctionInfoLink;

use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionInfoUrlConfig;
use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\KnownUrlConfig;
use Sam\Application\Url\Build\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;

/**
 * Class AuctionInfoLinkApplier
 */
class AuctionInfoLinkApplier extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $url
     * @param ResponsiveAuctionInfoUrlConfig $urlConfig
     * @return string
     */
    public function apply(string $url, ResponsiveAuctionInfoUrlConfig $urlConfig): string
    {
        if ($urlConfig->isTemplateView()) {
            return $url;
        }

        $auctionInfoLink = $this->detectUrlByAuctionInfoLink($urlConfig);
        if ($auctionInfoLink === '') {
            // Auction info link is not set
            return $url;
        }

        // Replace processing url with the "Auction Info Link"
        $url = $auctionInfoLink;

        return $url;
    }

    /**
     * Transform "Auction Info" url-config to KnownUrlConfig, if auction contains absolute/schemeless url in AuctionInfoLink field.
     * @param ResponsiveAuctionInfoUrlConfig $urlConfig
     * @return AbstractUrlConfig
     */
    public function transformUrlConfig(ResponsiveAuctionInfoUrlConfig $urlConfig): AbstractUrlConfig
    {
        if ($urlConfig->isTemplateView()) {
            return $urlConfig;
        }

        $auctionInfoLinkUrl = $this->detectUrlByAuctionInfoLink($urlConfig);
        if ($auctionInfoLinkUrl === '') {
            return $urlConfig;
        }

        if (UrlParser::new()->hasHost($auctionInfoLinkUrl)) {
            log_trace("ResponsiveAuctionInfoUrlConfig is transformed to KnownUrlConfig with url: {$auctionInfoLinkUrl}");
            return KnownUrlConfig::new()->construct($auctionInfoLinkUrl, $urlConfig->toArray());
        }

        // When optional property is set, then AuctionSeoUrlAppender skips seo url parameter adding
        $urlConfig->setOptionalAuctionInfoLink($auctionInfoLinkUrl);

        return $urlConfig;
    }

    /**
     * Return auction's field AuctionInfoLink.
     * @param ResponsiveAuctionInfoUrlConfig $urlConfig
     * @return string
     */
    protected function detectUrlByAuctionInfoLink(ResponsiveAuctionInfoUrlConfig $urlConfig): string
    {
        if ($urlConfig->hasAuctionInfoLink()) {
            return trim((string)$urlConfig->getOptionalAuctionInfoLink());
        }

        $auction = $this->createDataProvider()->loadAuction($urlConfig->auctionId());
        if (!$auction) {
            log_error(
                'Available auction not found for auction info link fetching'
                . composeSuffix(['a' => $urlConfig->auctionId()])
            );
            return '';
        }

        return trim($auction->AuctionInfoLink);
    }
}
