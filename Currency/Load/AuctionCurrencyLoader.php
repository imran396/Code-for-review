<?php
/**
 * SAM-4560: Currency loaders
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/15/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Currency\Load;

use AuctionCurrency;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\AuctionCurrency\AuctionCurrencyReadRepositoryCreateTrait;

/**
 * Class AuctionCurrencyLoader
 * @package Sam\Currency\Load
 */
class AuctionCurrencyLoader extends EntityLoaderBase
{
    use AuctionCurrencyReadRepositoryCreateTrait;
    use CurrencyLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get all selected currency symbols for this auction
     *
     * @param int $auctionId auction.id
     * @param bool $isReadOnlyDb
     * @return AuctionCurrency[]
     */
    public function loadAuctionCurrencies(int $auctionId, bool $isReadOnlyDb = false): array
    {
        $auctionCurrencies = $this->createAuctionCurrencyReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->orderById()
            ->loadEntities();
        return $auctionCurrencies;
    }

    /**
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadCurrencyIds(int $auctionId, bool $isReadOnlyDb = false): array
    {
        $rows = $this->createAuctionCurrencyReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->select(['currency_id'])
            ->loadRows();
        $currencyIds = ArrayCast::arrayColumnInt($rows, 'currency_id');
        return $currencyIds;
    }
}
