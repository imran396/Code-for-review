<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\Resolve;

use Account;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Application\Url\Build\Config\Auction\AbstractResponsiveSingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Invoice\AbstractResponsiveSingleInvoiceUrlConfig;
use Sam\Application\Url\Build\Config\Settlement\AbstractResponsiveSingleSettlementUrlConfig;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Settlement\Load\SettlementLoaderAwareTrait;

/**
 * Class AccountFromUrlConfigResolver
 * @package Sam\Application\Url\Build\Internal\Resolve
 */
class AccountFromUrlConfigResolver extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use InvoiceLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use SettlementLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Search for Account from entity, that is related to url building params
     * @param AbstractUrlConfig $urlConfig
     * @return Account|null
     */
    public function detectAccount(AbstractUrlConfig $urlConfig): ?Account
    {
        /**
         * Try to find account from optional additionals
         */
        if ($urlConfig->getOptionalAccount() instanceof Account) {
            return $urlConfig->getOptionalAccount();
        }

        /**
         * Search for Account entity from url-config related entity
         */
        $accountId = $this->detectAccountId($urlConfig);
        $account = $accountId ? $this->getAccountLoader()->load($accountId) : null;
        return $account;
    }

    /**
     * Search for account id from entity, that is related to url building params
     * @param AbstractUrlConfig $urlConfig
     * @return int|null null when account id cannot be found
     */
    public function detectAccountId(AbstractUrlConfig $urlConfig): ?int
    {
        /**
         * Try to find account id from optional additionals
         */
        if ($urlConfig->getOptionalAccountId()) {
            return $urlConfig->getOptionalAccountId();
        }

        /**
         * Try to find account from respective entity, that is defined via url parameters
         */
        $accountId = null;
        if ($urlConfig instanceof ResponsiveLotDetailsUrlConfig) {
            $lotItem = $this->getLotItemLoader()->load($urlConfig->lotItemId(), true);
            $accountId = $lotItem->AccountId ?? null;
        } elseif ($urlConfig instanceof AbstractResponsiveSingleAuctionUrlConfig) {
            $auction = $this->getAuctionLoader()->load($urlConfig->auctionId(), true);
            $accountId = $auction->AccountId ?? null;
        } elseif ($urlConfig instanceof AbstractResponsiveSingleInvoiceUrlConfig) {
            $invoice = $this->getInvoiceLoader()->load($urlConfig->invoiceId(), true);
            $accountId = $invoice->AccountId ?? null;
        } elseif ($urlConfig instanceof AbstractResponsiveSingleSettlementUrlConfig) {
            $settlement = $this->getSettlementLoader()->load($urlConfig->settlementId(), true);
            $accountId = $settlement->AccountId ?? null;
        }
        if (!$accountId) {
            log_debug('Account id cannot be resolved by provided url config' . composeSuffix($urlConfig->toArray()));
        }
        return $accountId;
    }
}
