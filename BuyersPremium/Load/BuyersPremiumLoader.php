<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Load;

use BuyersPremium;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\BuyersPremium\BuyersPremiumReadRepositoryCreateTrait;

/**
 * Class BuyersPremiumLoader
 * @package Sam\BuyersPremium\Load
 */
class BuyersPremiumLoader extends CustomizableClass
{
    use BuyersPremiumReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load a BuyersPremium entity by id
     *
     * @param int|null $id
     * @param bool $isReadOnlyDb query to read-only db
     * @return BuyersPremium|null
     */
    public function load(?int $id, bool $isReadOnlyDb = false): ?BuyersPremium
    {
        if (!$id) {
            return null;
        }

        $buyerGroup = $this->createBuyersPremiumReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($id)
            ->filterActive(true)
            ->loadEntity();
        return $buyerGroup;
    }

    /**
     * @param int $accountId account.id
     * @param bool $isReadOnlyDb
     * @return BuyersPremium[]
     */
    public function loadByAccountId(int $accountId, bool $isReadOnlyDb = false): array
    {
        return $this->createBuyersPremiumReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->loadEntities();
    }

    /**
     * Load selected fields of Buyer's Premium by account id.
     * @param array $select
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadSelectedByAccountId(array $select, int $accountId, bool $isReadOnlyDb = false): array
    {
        return $this->createBuyersPremiumReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->select($select)
            ->loadRows();
    }

    /**
     * Load Buyer's Premium records that are defined on account auction type level for specific account.
     * @param int $accountId account.id
     * @param bool $isReadOnlyDb
     * @return BuyersPremium[]
     */
    public function loadAuctionTypeBpByAccountId(int $accountId, bool $isReadOnlyDb = false): array
    {
        return $this->createBuyersPremiumReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterShortName(Constants\Auction::AUCTION_TYPES)
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->loadEntities();
    }

    /**
     * Load Named Buyer's Premium records defined for an account except account auction type records.
     * @param int $accountId account.id
     * @param bool $isReadOnlyDb
     * @return BuyersPremium[]
     */
    public function loadNamedByAccountId(int $accountId, bool $isReadOnlyDb = false): array
    {
        return $this->createBuyersPremiumReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->skipShortName(Constants\Auction::AUCTION_TYPES)
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->loadEntities();
    }

    /**
     * Load selected field of Named Buyer's Premium records defined for an account except account auction type records.
     * @param array $select
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadSelectedNamedByAccountId(array $select, int $accountId, bool $isReadOnlyDb = false): array
    {
        return $this->createBuyersPremiumReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->skipShortName(Constants\Auction::AUCTION_TYPES)
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->select($select)
            ->loadRows();
    }


    /**
     * @param string $shortName buyers_premium.short_name
     * @param int $accountId buyers_premium.account_id
     * @param bool $isReadOnlyDb
     * @return BuyersPremium|null
     */
    public function loadByShortNameAndAccount(string $shortName, int $accountId, bool $isReadOnlyDb = false): ?BuyersPremium
    {
        return $this->createBuyersPremiumReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterShortName($shortName)
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->loadEntity();
    }

    /**
     * @param int $accountId buyers_premium.account_id
     * @param bool $isReadOnlyDb
     * @return BuyersPremium
     */
    public function loadTimed(int $accountId, bool $isReadOnlyDb = false): BuyersPremium
    {
        return $this->loadByShortNameAndAccount(Constants\Auction::TIMED, $accountId, $isReadOnlyDb);
    }

    /**
     * @param int $accountId buyers_premium.account_id
     * @param bool $isReadOnlyDb
     * @return BuyersPremium
     */
    public function loadLive(int $accountId, bool $isReadOnlyDb = false): BuyersPremium
    {
        return $this->loadByShortNameAndAccount(Constants\Auction::LIVE, $accountId, $isReadOnlyDb);
    }

    /**
     * @param int $accountId buyers_premium.account_id
     * @param bool $isReadOnlyDb
     * @return BuyersPremium
     */
    public function loadHybrid(int $accountId, bool $isReadOnlyDb = false): BuyersPremium
    {
        return $this->loadByShortNameAndAccount(Constants\Auction::HYBRID, $accountId, $isReadOnlyDb);
    }

    /**
     * Load by short name, where it's not in [Default, H, L, T]
     * @param string $shortName
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return BuyersPremium|null
     */
    public function loadNotDefault(string $shortName, int $accountId, bool $isReadOnlyDb = false): ?BuyersPremium
    {
        return in_array($shortName, array_merge(['Default'], Constants\Auction::AUCTION_TYPES), true)
            ? null
            : $this->loadByShortNameAndAccount($shortName, $accountId, $isReadOnlyDb);
    }

}
