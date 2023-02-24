<?php
/**
 * SAM-9355: Refactor Domain Detector and Domain Redirector for unit testing
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl\Internal\DetectAccount\Internal\Request;

use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class RequestFetcher
 * @package Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl\Internal\Request
 */
class RequestFetcher extends CustomizableClass
{
    use ParamFetcherForRouteAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function fetchAuctionId(): ?int
    {
        return $this->getParamFetcherForRoute()->getIntPositive(Constants\UrlParam::R_ID);
    }

    public function fetchCatalogAuctionId(): ?int
    {
        return $this->getParamFetcherForRoute()->getIntPositiveOrZero(Constants\UrlParam::R_CATALOG); // "0" for preview of unassigned lot
    }

    public function fetchInvoiceId(): ?int
    {
        return $this->getParamFetcherForRoute()->getIntPositive(Constants\UrlParam::R_ID);
    }

    public function fetchSettlementId(): ?int
    {
        return $this->getParamFetcherForRoute()->getIntPositive(Constants\UrlParam::R_ID);
    }

    public function fetchControllerName(): string
    {
        return $this->getParamFetcherForRoute()->getControllerName();
    }

    public function fetchActionName(): string
    {
        return $this->getParamFetcherForRoute()->getActionName();
    }
}
