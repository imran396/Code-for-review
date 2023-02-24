<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Oct 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\Common\Url;

use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionLandingUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;

/**
 * Class UrlProvider
 * @package Sam\Billing\Gate\Opayo\Common\Url
 */
class UrlProvider extends CustomizableClass
{
    use BackUrlParserAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use UrlBuilderAwareTrait;
    use UrlParserAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function buildAuctionsLandingUrl(int $auctionId, int $accountId, string $auctionInfoLink, $urlParams = []): string
    {
        $optionals = [
            UrlConfigConstants::OP_ACCOUNT_ID => $accountId,
            UrlConfigConstants::OP_AUCTION_INFO_LINK => $auctionInfoLink,
        ];
        $landingUrlConfig = ResponsiveAuctionLandingUrlConfig::new()
            ->forWeb($auctionId, '', $optionals);
        $url = $this->getUrlBuilder()->build($landingUrlConfig);

        $urlParser = $this->getUrlParser();
        $url = $urlParser->removeParams(
            $url,
            [
                Constants\UrlParam::GA,
                Constants\UrlParam::BACK_URL,
                'carr_m',
                'vtx',
            ]
        );

        $url = $urlParser->replaceParams($url, $urlParams);

        return $url;
    }

    public function buildRegistrationConfirmUrl(int $auctionId, string $backUrl): string
    {
        $registrationConfirmUrl = $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::P_REGISTER_REGISTRATION_CONFIRM, $auctionId)
        );
        $url = $this->getBackUrlParser()->replace($registrationConfirmUrl, $backUrl);
        return $url;
    }

    public function buildReviseBillingUrl(int $auctionId, $urlParams = []): string
    {
        $url = $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forRedirect(Constants\Url::P_REGISTER_RENEW_BILLING, $auctionId)
        );
        $url = $this->getUrlParser()->replaceParams($url, $urlParams);
        return $url;
    }

    public function isAdminRoute(string $url): bool
    {
        return strpos($url, '/admin/');
    }
}
