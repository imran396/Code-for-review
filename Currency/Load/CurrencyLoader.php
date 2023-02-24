<?php
/**
 * SAM-4560: Currency loaders
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/14/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Currency\Load;

use Currency;
use RuntimeException;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionCurrency\AuctionCurrencyReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\Currency\CurrencyReadRepository;
use Sam\Storage\ReadRepository\Entity\Currency\CurrencyReadRepositoryCreateTrait;

/**
 * Class CurrencyLoader
 * @package Sam\Currency\Load
 */
class CurrencyLoader extends EntityLoaderBase
{
    use AuctionCurrencyReadRepositoryCreateTrait;
    use AuctionReadRepositoryCreateTrait;
    use CurrencyReadRepositoryCreateTrait;
    use MemoryCacheManagerAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return default currency
     *
     * @param int|null $auctionId null leads to primary currency sign
     * @param bool $isReadOnlyDb
     * @return Currency|null
     */
    public function detectDefaultCurrency(?int $auctionId = null, bool $isReadOnlyDb = false): ?Currency
    {
        $defaultSign = $this->detectDefaultSign($auctionId, $isReadOnlyDb);
        $currency = $this->prepareCurrencyRepository($isReadOnlyDb)
            ->filterSign($defaultSign)
            ->loadEntity();
        return $currency;
    }

    /**
     * Return default currency id
     *
     * @param int|null $auctionId null leads to primary currency sign
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    public function detectDefaultCurrencyId(?int $auctionId = null, bool $isReadOnlyDb = false): ?int
    {
        $defaultSign = $this->detectDefaultSign($auctionId, $isReadOnlyDb);
        $rows = $this->prepareCurrencyRepository($isReadOnlyDb)
            ->filterSign($defaultSign)
            ->select(['id'])
            ->loadRow();
        return Cast::toInt($rows['id'] ?? null);
    }

    /**
     * Returns auction.sign if set, setting_system.primary_currency_id otherwise
     *
     * @param int|null $auctionId optional auction.id if no id, then we return primary currency sign
     * @param bool $isReadOnlyDb
     * @return string currency
     */
    public function detectDefaultSign(?int $auctionId = null, bool $isReadOnlyDb = false): string
    {
        $fn = function () use ($auctionId, $isReadOnlyDb) {
            $currencySign = null;
            if ($auctionId) {
                $row = $this->createAuctionReadRepository()
                    ->enableReadOnlyDb($isReadOnlyDb)
                    ->filterId($auctionId)
                    ->joinCurrency()
                    ->select(['curr.sign'])
                    ->loadRow();
                $currencySign = $row['sign'] ?? null;
            }
            $currencySign = $currencySign ?: $this->findPrimaryCurrencySign();
            return $currencySign;
        };

        $cacheKey = sprintf(Constants\MemoryCache::CURRENCY_SIGN_AUCTION_ID, (int)$auctionId);
        $currencySign = $this->getMemoryCacheManager()->load($cacheKey, $fn);
        return $currencySign;
    }

    /**
     * Return primary currency sign (always main account)
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function findPrimaryCurrencySign(bool $isReadOnlyDb = false): string
    {
        $primaryCurrency = $this->loadPrimaryCurrency($isReadOnlyDb);
        return $primaryCurrency->Sign ?? '';
    }

    /**
     * Return primary currency code (always main account)
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function findPrimaryCurrencyCode(bool $isReadOnlyDb = false): string
    {
        $primaryCurrency = $this->loadPrimaryCurrency($isReadOnlyDb);
        return $primaryCurrency->Code ?? '';
    }

    /**
     * Return primary currency id (always main account)
     * @return int
     */
    public function findPrimaryCurrencyId(): int
    {
        $primaryCurrencyIdRaw = $this->getSettingsManager()->getForMain(Constants\Setting::PRIMARY_CURRENCY_ID);
        $primaryCurrencyId = Cast::toInt($primaryCurrencyIdRaw, Constants\Type::F_INT_POSITIVE);
        if (!$primaryCurrencyId) {
            $message = 'Primary currency is not defined for main account';
            log_error($message);
            throw new RuntimeException($message);
        }
        return $primaryCurrencyId;
    }

    /**
     * Return primary currency entity (always main account)
     * @param bool $isReadOnlyDb
     * @return Currency
     */
    public function loadPrimaryCurrency(bool $isReadOnlyDb = false): Currency
    {
        $currencyId = $this->findPrimaryCurrencyId();
        $currency = $this->load($currencyId, $isReadOnlyDb);
        if (!$currency) {
            $message = 'Primary currency cannot be found by id' . composeSuffix(['curr' => $currencyId]);
            log_error($message);
            throw new RuntimeException($message);
        }
        return $currency;
    }

    /**
     * Load currency by id.
     * Memory cache used.
     *
     * @param int|null $currencyId
     * @param bool $isReadOnlyDb
     * @return Currency|null $currency
     */
    public function load(?int $currencyId, bool $isReadOnlyDb = false): ?Currency
    {
        if (!$currencyId) {
            return null;
        }

        $fn = function () use ($currencyId, $isReadOnlyDb) {
            $currency = $this->prepareCurrencyRepository($isReadOnlyDb)
                ->filterId($currencyId)
                ->loadEntity();
            return $currency;
        };

        $cacheKey = sprintf(Constants\MemoryCache::CURRENCY_ID, $currencyId);
        $currency = $this->getMemoryCacheManager()->load($cacheKey, $fn);
        return $currency;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return Currency[]
     */
    public function loadAll(bool $isReadOnlyDb = false): array
    {
        $currency = $this->prepareCurrencyRepository($isReadOnlyDb)
            ->orderByName()
            ->loadEntities();
        return $currency;
    }

    /**
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return Currency|null
     */
    public function loadAuctionMainCurrency(int $auctionId, bool $isReadOnlyDb = false): ?Currency
    {
        $currency = $this->createCurrencyReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->joinAuctionFilterId($auctionId)
            ->loadEntity();
        return $currency;
    }

    /**
     * Load auction's main currency id
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    public function loadAuctionMainCurrencyId(int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        $row = $this->createAuctionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->select(['currency'])
            ->filterId($auctionId)
            ->loadRow();
        return Cast::toInt($row['currency'] ?? null);
    }

    /**
     * Get a currency object by name
     *
     * @param string $name currency.name
     * @param bool $isReadOnlyDb
     * @return Currency|null
     */
    public function loadByName(string $name, bool $isReadOnlyDb = false): ?Currency
    {
        $currency = $this->prepareCurrencyRepository($isReadOnlyDb)
            ->filterName($name)
            ->loadEntity();
        return $currency;
    }

    /**
     * Returns the currency code
     *
     * @param string $currencySign currency.sign
     * @param bool $isReadOnlyDb null leads to primary currency code
     * @return string|null
     */
    public function loadCodeBySign(string $currencySign, bool $isReadOnlyDb = false): ?string
    {
        $currencySign = $currencySign ?: $this->findPrimaryCurrencySign();
        $currency = $this->prepareCurrencyRepository($isReadOnlyDb)
            ->filterSign($currencySign)
            ->loadEntity();
        $code = $currency->Code ?? null;
        return $code;
    }

    /**
     * Load array of currency ids by currency names
     * @param array|null $currencyNames null leads to empty array
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadCurrencyIdsByNames(?array $currencyNames, bool $isReadOnlyDb = false): array
    {
        if (!$currencyNames) {
            return [];
        }

        $rows = $this->prepareCurrencyRepository($isReadOnlyDb)
            ->filterName($currencyNames)
            ->select(['id'])
            ->loadRows();
        $currencyIds = ArrayCast::arrayColumnInt($rows, 'id');
        return $currencyIds;
    }

    /**
     * Returns the default currency id and all selected currency ids for this auction
     *
     * @param int $auctionId auction.id
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadCurrencyIdsForAuction(int $auctionId, bool $isReadOnlyDb = false): array
    {
        $fn = function () use ($auctionId, $isReadOnlyDb) {
            $currencyIds[] = $this->loadAuctionMainCurrencyId($auctionId);
            $rows = $this->createAuctionCurrencyReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->select(['currency_id'])
                ->filterAuctionId($auctionId)
                ->loadRows();
            $addedCurrencyIds = ArrayCast::arrayColumnInt($rows, 'currency_id');
            $currencyIds = array_merge($currencyIds, $addedCurrencyIds);
            return $currencyIds;
        };

        $cacheKey = sprintf(Constants\MemoryCache::CURRENCY_IDS_AUCTION_ID, $auctionId);
        $currencyIds = $this->getMemoryCacheManager()->load($cacheKey, $fn);
        return $currencyIds;
    }

    /**
     * @param int $auctionId
     * @param int|null $skipCurrencyId null means that there is no skipped currency id
     * @param bool $isReadOnlyDb
     * @return Currency[]
     */
    public function loadCurrenciesForAuction(int $auctionId, ?int $skipCurrencyId = null, bool $isReadOnlyDb = false): array
    {
        $repo = $this->createCurrencyReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->joinAuctionCurrencyFilterAuctionId($auctionId);
        if ($skipCurrencyId) {
            $repo->skipId($skipCurrencyId);
        }
        $currencies = $repo->loadEntities();
        return $currencies;
    }

    /**
     * Load array of exchange rates indexed by currency.id in array
     *
     * @param bool $isReadOnlyDb
     * @return float[]
     */
    public function loadExRates(bool $isReadOnlyDb = false): array
    {
        $exRates = [];
        $exRates[0] = 1;

        $rows = $this->prepareCurrencyRepository($isReadOnlyDb)
            ->select(['id', 'ex_rate'])
            ->loadRows();

        foreach ($rows as $row) {
            $exRates[(int)$row['id']] = (float)$row['ex_rate'];
        }

        return $exRates;
    }

    /**
     * Returns the currency ex rate
     *
     * @param string $code currency.code
     * @param bool $isReadOnlyDb
     * @return float|null
     */
    public function loadExRateByCode(string $code = "USD", bool $isReadOnlyDb = false): ?float
    {
        $currency = $this->prepareCurrencyRepository($isReadOnlyDb)
            ->filterCode($code)
            ->loadEntity();
        $exRate = $currency->ExRate ?? null;
        return $exRate;
    }

    /**
     * Returns the currency ex rate
     *
     * @param int $currencyId currency.id
     * @param bool $isReadOnlyDb
     * @return float|null
     */
    public function loadExRateById(int $currencyId, bool $isReadOnlyDb = false): ?float
    {
        $currency = $this->prepareCurrencyRepository($isReadOnlyDb)
            ->filterId($currencyId)
            ->loadEntity();
        $exRate = $currency->ExRate ?? null;
        return $exRate;
    }

    /**
     * Returns the currency ex rate
     *
     * @param string|null $currencySign null leads to primary currency exchange rate
     * @param bool $isReadOnlyDb
     * @return float|null
     */
    public function loadExRateBySign(?string $currencySign = null, bool $isReadOnlyDb = false): ?float
    {
        $currencySign = $currencySign ?? $this->findPrimaryCurrencySign();
        $currency = $this->prepareCurrencyRepository($isReadOnlyDb)
            ->filterSign($currencySign)
            ->loadEntity();
        return $currency->ExRate ?? null;
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
}
