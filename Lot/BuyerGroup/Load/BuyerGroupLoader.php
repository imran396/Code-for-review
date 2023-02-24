<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\BuyerGroup\Load;

use BuyerGroup;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\BuyerGroup\BuyerGroupReadRepositoryCreateTrait;

/**
 * Class BuyerGroupLoader
 * @package Sam\Lot\BuyerGroup\Load
 */
class BuyerGroupLoader extends CustomizableClass
{
    use BuyerGroupReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load a BuyerGroup from PK Info
     * @param int|null $id (can be null if we creating new Buyer group, or can not fetch int positive value from url request route.
     * We dont need to fetch any data from DB, in this case.)
     * @param bool $isReadOnlyDb
     * @return BuyerGroup|null
     */
    public function load(?int $id, bool $isReadOnlyDb = false): ?BuyerGroup
    {
        if (!$id) {
            return null;
        }

        return $this->createBuyerGroupReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($id)
            ->loadEntity();
    }

    /**
     * Returns all active buyer groups.
     * @param bool $isReadOnlyDb
     * @return BuyerGroup[]
     */
    public function loadAll(bool $isReadOnlyDb = false): array
    {
        return $this->createBuyerGroupReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->loadEntities();
    }

    /**
     * Returns all active buyer groups filtered by ids.
     * @param array $buyerGroupIds
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadByIds(array $buyerGroupIds, bool $isReadOnlyDb = false): array
    {
        return $this->createBuyerGroupReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($buyerGroupIds)
            ->loadEntities();
    }
}
