<?php
/**
 * SAM-9688: Ability to make Auction and Lot/Item fields required to have values: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\FieldConfig\Internal\Load;

use AuctionFieldConfig;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionFieldConfig\AuctionFieldConfigReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionFieldConfig\AuctionFieldConfigReadRepositoryCreateTrait;

/**
 * Class AuctionFieldConfigLoader
 * @package Sam\Auction\FieldConfig\Internal\Load
 * @internal
 */
class AuctionFieldConfigLoader extends CustomizableClass
{
    use AuctionFieldConfigReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load ordered array of LotFieldConfig records for definite account
     *
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return AuctionFieldConfig[]
     */
    public function loadAll(int $accountId, bool $isReadOnlyDb = false): array
    {
        $fieldConfigs = $this
            ->prepareRepository($accountId, $isReadOnlyDb)
            ->orderByOrder()
            ->loadEntities();
        return $fieldConfigs;
    }

    /**
     * Load LotFieldConfig entity by its index
     *
     * @param string $index
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return AuctionFieldConfig|null
     */
    public function loadByIndex(string $index, int $accountId, bool $isReadOnlyDb = false): ?AuctionFieldConfig
    {
        $fieldConfig = $this
            ->prepareRepository($accountId, $isReadOnlyDb)
            ->filterIndex($index)
            ->loadEntity();
        return $fieldConfig;
    }

    protected function prepareRepository(int $accountId, bool $isReadOnlyDb = false): AuctionFieldConfigReadRepository
    {
        $repository = $this->createAuctionFieldConfigReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterActive(true);
        return $repository;
    }
}
