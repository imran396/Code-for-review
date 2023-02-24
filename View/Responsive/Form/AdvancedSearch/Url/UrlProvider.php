<?php
/**
 * SAM-9416: Decouple logic of AdvancedSearch class for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\Url;

use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionRegistrationUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveCatalogUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveLiveSaleUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\AnySingleAuctionLotUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveAuctionLotChangesUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Auth\ResponsiveLoginUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Application\Url\UrlAdvisorAwareTrait;
use Sam\Core\Constants\Responsive\AdvancedSearchConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Url\UrlParser;

/**
 * Class UrlProvider
 * @package
 */
class UrlProvider extends CustomizableClass
{
    use BackUrlParserAwareTrait;
    use ServerRequestReaderAwareTrait;
    use UrlAdvisorAwareTrait;
    use UrlBuilderAwareTrait;

    private array $lotDetailsUrls = [];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function buildLotDetailsUrl(
        int $lotItemId,
        int $auctionId,
        string $seoUrl,
        int $accountId
    ): string {
        $key = $auctionId . '_' . $lotItemId;
        if (!isset($this->lotDetailsUrls[$key])) {
            $lotDetailsUrlConfig = ResponsiveLotDetailsUrlConfig::new()->forWeb(
                $lotItemId,
                $auctionId,
                $seoUrl,
                [UrlConfigConstants::OP_ACCOUNT_ID => $accountId]
            );
            $lotDetailsUrl = $this->getUrlBuilder()->build($lotDetailsUrlConfig);
            $this->lotDetailsUrls[$key] = $lotDetailsUrl;
        }
        return $this->lotDetailsUrls[$key];
    }

    public function buildCatalogUrl(
        int $auctionId,
        string $seoUrl,
        int $accountId
    ): string {
        $urlConfig = ResponsiveCatalogUrlConfig::new()->forWeb(
            $auctionId,
            $seoUrl,
            [UrlConfigConstants::OP_ACCOUNT_ID => $accountId]
        );
        $url = $this->getUrlBuilder()->build($urlConfig);
        return $url;
    }

    public function buildBuyNowConfirmUrl(
        int $lotItemId,
        int $auctionId,
        string $quantityPlaceholder = ''
    ): string {
        $urlConfig = AnySingleAuctionLotUrlConfig::new()
            ->forWeb(Constants\Url::P_AUCTIONS_CONFIRM_BUY, $lotItemId, $auctionId);
        $url = $this->getUrlBuilder()->build($urlConfig);
        if ($quantityPlaceholder) {
            $url = UrlParser::new()->replaceParams($url, [Constants\UrlParam::QUANTITY => $quantityPlaceholder]);
        }
        return $url;
    }

    public function buildLoginUrlForBidButtonsAddBackUrl(int $auctionId, int $auctionLotId): string
    {
        $urlParser = UrlParser::new();
        $backUrl = $urlParser->replaceParams(
            $this->getServerRequestReader()->currentUrl(),
            [Constants\UrlParam::SALE_REG => $auctionId]
        );
        $backUrl = $urlParser->replaceFragment(
            $backUrl,
            sprintf(AdvancedSearchConstants::CID_BLK_AUCTION_LOT_ITEM_TPL, $auctionLotId)
        );

        $url = $this->getUrlBuilder()->build(ResponsiveLoginUrlConfig::new()->forWeb());
        $url = $this->getBackUrlParser()->replace($url, $backUrl);
        return $url;
    }

    public function buildAuctionsConfirmBidUrlWithParamsAddBackUrl(array $params): string
    {
        $urlParser = UrlParser::new();
        $url = $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forRedirect(Constants\Url::P_AUCTIONS_CONFIRM_BID)
        );
        $url = $urlParser->replaceParams($url, $params);
        $backUrl = $urlParser->removeParams($this->getServerRequestReader()->currentUrl(), [Constants\UrlParam::GA]);
        $url = $this->getBackUrlParser()->replace($url, $backUrl);
        return $url;
    }

    public function buildLoginUrlAddBackUrl(): string
    {
        $url = $this->getUrlBuilder()->build(ResponsiveLoginUrlConfig::new()->forRedirect());
        $url = $this->getUrlAdvisor()->addBackUrl($url);
        return $url;
    }

    public function buildAuctionPrintRealizedPricesUrl(int $auctionId): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forWeb(
                Constants\Url::P_AUCTIONS_PRINT_REALIZED_PRICES,
                $auctionId
            )
        );
    }

    public function buildAuctionPrintCatalogUrl(int $auctionId): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forWeb(
                Constants\Url::P_AUCTIONS_PRINT_CATALOG,
                $auctionId
            )
        );
    }

    public function buildLiveSaleUrl(int $auctionId): string
    {
        return $this->getUrlBuilder()->build(
            ResponsiveLiveSaleUrlConfig::new()->forRedirect($auctionId)
        );
    }

    public function buildRegisterSpecialTermsAndConditionsUrl(int $lotItemId, int $auctionId): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionLotUrlConfig::new()->forRedirect(
                Constants\Url::P_REGISTER_SPECIAL_TERMS_AND_CONDITIONS,
                $lotItemId,
                $auctionId
            )
        );
    }

    public function buildLotChangesUrl(int $lotItemId, int $auctionId): string
    {
        return $this->getUrlBuilder()->build(
            ResponsiveAuctionLotChangesUrlConfig::new()->forRedirect([$lotItemId], $auctionId)
        );
    }

    public function buildLotImageUrl(int $lotImageId, string $size, int $accountId): string
    {
        return $this->getUrlBuilder()->build(
            LotImageUrlConfig::new()->construct($lotImageId, $size, $accountId)
        );
    }

    public function buildAuctionRegistrationUrl(int $auctionId, string $backUrl = ''): string
    {
        $url = $this->getUrlBuilder()->build(
            ResponsiveAuctionRegistrationUrlConfig::new()->forWeb($auctionId)
        );
        if ($backUrl) {
            $url = $this->getBackUrlParser()->replace($url, $backUrl);
        }
        return $url;
    }
}
