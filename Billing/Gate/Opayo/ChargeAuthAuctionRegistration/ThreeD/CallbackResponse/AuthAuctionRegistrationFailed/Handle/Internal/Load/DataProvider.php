<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Oct 2, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationFailed\Handle\Internal\Load;

use Sam\Billing\Gate\Opayo\Common\Url\UrlProvider;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;

class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getUrlParams(string $url): array
    {
        return UrlParser::new()->extractParams($url);
    }

    public function getReviseBillingUrl(int $auctionId, array $params): string
    {
        return UrlProvider::new()->buildReviseBillingUrl($auctionId, $params);
    }

    public function isResponsiveRoute(string $url): bool
    {
        return UrlProvider::new()->isAdminRoute($url) === false;
    }
}
