<?php
/**
 * SAM-9355 : Refactor Domain Detector and Domain Redirector for unit testing
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

namespace Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl\Internal\DetectAccount;

use Account;
use Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl\Internal\DetectAccount\Internal\Load\DataProviderCreateTrait;
use Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl\Internal\DetectAccount\Internal\Request\RequestFetcherCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AccountDetector
 * @package Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl\Internal\DetectAccount
 */
class AccountDetector extends CustomizableClass
{
    use DataProviderCreateTrait;
    use RequestFetcherCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function detect(int $systemAccountId): ?Account
    {
        $dataProvider = $this->createDataProvider();
        $requestFetcher = $this->createRequestFetcher();
        $routeType = $dataProvider->detectRouteType(
            $requestFetcher->fetchControllerName(),
            $requestFetcher->fetchActionName()
        );

        if ($routeType->isNone()) {
            return null;
        }

        $accountId = null;
        if ($routeType->isInvoice()) {
            $accountId = $dataProvider->loadInvoiceAccountId($requestFetcher->fetchInvoiceId(), true);
        } elseif ($routeType->isSettlement()) {
            $accountId = $dataProvider->loadSettlementAccountId($requestFetcher->fetchSettlementId(), true);
        } elseif ($routeType->isAuction()) {
            $auctionId = $requestFetcher->fetchAuctionId() ?: $requestFetcher->fetchCatalogAuctionId();
            $accountId = $dataProvider->loadAuctionAccountId($auctionId, true);
        }
        if (!$accountId) {
            $accountId = $systemAccountId;
        }
        $account = $dataProvider->loadAccount($accountId, true);
        return $account;
    }

}
