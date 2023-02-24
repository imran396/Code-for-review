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

namespace Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl\Internal\DetectAccount\Internal\Load;

use Account;
use Sam\Account\Load\AccountLoader;
use Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl\Internal\RouteType\RouteType;
use Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl\Internal\RouteType\RouteTypeDetector;
use Sam\Auction\Load\AuctionLoader;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceLoader;
use Sam\Settlement\Load\SettlementLoader;

/**
 * Class DataProvider
 * @package
 */
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

    public function detectRouteType(string $controller, string $action): RouteType
    {
        return RouteTypeDetector::new()->detect($controller, $action);
    }

    public function loadAccount(int $accountId, bool $isReadOnlyDb = false): ?Account
    {
        return AccountLoader::new()->load($accountId, $isReadOnlyDb);
    }

    public function loadAuctionAccountId(?int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        $auction = AuctionLoader::new()->load($auctionId, $isReadOnlyDb);
        return $auction->AccountId ?? null;
    }

    public function loadInvoiceAccountId(?int $invoiceId, bool $isReadOnlyDb = false): ?int
    {
        $invoice = InvoiceLoader::new()->load($invoiceId, $isReadOnlyDb);
        return $invoice->AccountId ?? null;
    }

    public function loadSettlementAccountId(?int $settlementId, bool $isReadOnlyDb = false): ?int
    {
        $settlement = SettlementLoader::new()->load($settlementId, $isReadOnlyDb);
        return $settlement->AccountId ?? null;
    }
}
