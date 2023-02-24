<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/19/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Currency\Validate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepository;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionCurrency\AuctionCurrencyReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\Currency\CurrencyReadRepository;
use Sam\Storage\ReadRepository\Entity\Currency\CurrencyReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepository;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\SettlementItem\SettlementItemReadRepository;
use Sam\Storage\ReadRepository\Entity\SettlementItem\SettlementItemReadRepositoryCreateTrait;

/**
 * Class CurrencyExistenceChecker
 * @package Sam\Currency\Validate
 */
class CurrencyExistenceChecker extends CustomizableClass
{
    use AuctionCurrencyReadRepositoryCreateTrait;
    use AuctionReadRepositoryCreateTrait;
    use CurrencyLoaderAwareTrait;
    use CurrencyReadRepositoryCreateTrait;
    use InvoiceItemReadRepositoryCreateTrait;
    use SettlementItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Count additional currencies
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function countAdditionalCurrencies(int $auctionId, bool $isReadOnlyDb = false): int
    {
        $currencyId = $this->getCurrencyLoader()->loadAuctionMainCurrencyId($auctionId);
        if ($currencyId === null) {
            $currency = $this->prepareCurrencyRepository($isReadOnlyDb)
                ->filterSign($this->getCurrencyLoader()->detectDefaultSign($auctionId))
                ->loadEntity();
            $currencyId = $currency->Id ?? null;
        }

        $additionalCurrencyCount = $this->createAuctionCurrencyReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->skipCurrencyId($currencyId)
            ->count();
        return $additionalCurrencyCount;
    }

    /**
     * Check if currency exists by id
     * @param int $id
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existById(int $id, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->prepareCurrencyRepository($isReadOnlyDb)
            ->filterId($id)
            ->exist();
        return $isFound;
    }

    /**
     * Check if the currency name exists
     *
     * @param string $name currency.name
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByName(string $name, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->prepareCurrencyRepository($isReadOnlyDb)
            ->filterName($name)
            ->exist();
        return $isFound;
    }

    /**
     * Check if the currency sign exists
     *
     * @param string $sign currency.sign
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existBySign(string $sign, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->prepareCurrencyRepository($isReadOnlyDb)
            ->filterSign($sign)
            ->exist();
        return $isFound;
    }

    /**
     * @param int $currencyId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isAlreadyUsed(int $currencyId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->isUsedInAuctions($currencyId, $isReadOnlyDb)
            || $this->isUsedInInvoices($currencyId, $isReadOnlyDb)
            || $this->isUsedInSettlements($currencyId, $isReadOnlyDb);
        return $isFound;
    }

    /**
     * Check if currency is already used in one or more auctions
     * @param int $currencyId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    protected function isUsedInAuctions(int $currencyId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->prepareAuctionRepository($isReadOnlyDb)
            ->filterCurrency($currencyId)
            ->filterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->exist();
        return $isFound;
    }

    /**
     * Check if currency is already used in one or more invoices
     * @param int $currencyId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    protected function isUsedInInvoices(int $currencyId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->prepareInvoiceItemRepository($isReadOnlyDb)
            // ->joinAccountFilterActive(true)
            // no auction filtering, because auction may be absent at all for sale sold (lot_item.auction_id)
            // ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->joinAuctionFilterCurrency($currencyId)
            // ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            // ->joinLotItemFilterActive(true)
            ->joinInvoiceFilterInvoiceStatusId(Constants\Invoice::$availableInvoiceStatuses)
            // ->joinUserWinningBidderFilterUserStatusId(Constants\User::US_ACTIVE)
            ->exist();
        return $isFound;
    }

    /**
     * Check if currency is already used in one or more settlements
     * @param int $currencyId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    protected function isUsedInSettlements(int $currencyId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->prepareSettlementItemRepository($isReadOnlyDb)
            ->joinSettlementFilterSettlementStatusId(Constants\Settlement::$availableSettlementStatuses)
            ->joinAuctionFilterCurrency($currencyId)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->exist();
        return $isFound;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return AuctionReadRepository
     */
    protected function prepareAuctionRepository(bool $isReadOnlyDb = false): AuctionReadRepository
    {
        $auctionRepository = $this->createAuctionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        return $auctionRepository;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return CurrencyReadRepository
     */
    protected function prepareCurrencyRepository(bool $isReadOnlyDb = false): CurrencyReadRepository
    {
        $currencyRepository = $this->createCurrencyReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true);
        return $currencyRepository;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return InvoiceItemReadRepository
     */
    protected function prepareInvoiceItemRepository(bool $isReadOnlyDb = false): InvoiceItemReadRepository
    {
        $invoiceItemRepository = $this->createInvoiceItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true);
        return $invoiceItemRepository;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return SettlementItemReadRepository
     */
    protected function prepareSettlementItemRepository(bool $isReadOnlyDb = false): SettlementItemReadRepository
    {
        $settlementItemRepository = $this->createSettlementItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true);
        return $settlementItemRepository;
    }
}
