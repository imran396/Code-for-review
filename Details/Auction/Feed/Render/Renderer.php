<?php
/**
 * Rendering methods for placeholders of auction feed
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Feb 16, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Custom methods can be used there or in customized class
 *
 * Optional method called when rendering the custom auction field value
 * param AuctionCustField $customField the custom auction field object
 * param mixed $value the value
 * param ind $auctionId
 * return string the rendered value
 * public function AuctionCustomField_{Field name}_Render(AuctionCustField $customField, $value, $auctionId);
 *
 * {Field name} - Camel cased name of custom field (see TextTransformer::toCamelCase())
 */

namespace Sam\Details\Auction\Feed\Render;

use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionInfoUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveCatalogUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveLiveSaleUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Core\Constants;

/**
 * Class Renderer
 * @package Sam\Details\Auction\Base\Render
 */
class Renderer extends \Sam\Details\Auction\Base\Render\Renderer
{
    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function renderAuctionInfoUrl(array $row): string
    {
        return $this->getUrlBuilder()->build(
            ResponsiveAuctionInfoUrlConfig::new()->forDomainRule(
                (int)$row['id'],
                $row['auction_seo_url'],
                [
                    UrlConfigConstants::OP_ACCOUNT_ID => (int)$row['account_id'],
                    UrlConfigConstants::OP_AUCTION_INFO_LINK => $row['auction_info_link']
                ]
            )
        );
    }

    public function renderCatalogUrl(array $row): string
    {
        return $this->getUrlBuilder()->build(
            ResponsiveCatalogUrlConfig::new()->forDomainRule(
                (int)$row['id'],
                $row['auction_seo_url'],
                [UrlConfigConstants::OP_ACCOUNT_ID => (int)$row['account_id']]
            )
        );
    }

    protected function buildResponsiveLiveSaleUrl(int $auctionId, string $auctionSeoUrl, int $accountId): string
    {
        return $this->getUrlBuilder()->build(
            ResponsiveLiveSaleUrlConfig::new()->forDomainRule(
                $auctionId,
                $auctionSeoUrl,
                [UrlConfigConstants::OP_ACCOUNT_ID => $accountId]
            )
        );
    }

    public function renderRegisterToBidUrl(array $row): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forDomainRule(
                Constants\Url::P_LOGIN_REDIRECT_FEED,
                (int)$row['id'],
                [UrlConfigConstants::OP_ACCOUNT_ID => (int)$row['account_id']]
            )
        );
    }
}
