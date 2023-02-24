<?php
/**
 * SAM-9741: Admin options Inventory page - Add "Required" property for all fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Load;

use LotFieldConfig;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LotFieldConfig\LotFieldConfigReadRepositoryCreateTrait;

/**
 * Class LotFieldConfigLoader
 * @package Sam\Lot\LotFieldConfig\Load
 */
class LotFieldConfigLoader extends CustomizableClass
{
    use LotFieldConfigReadRepositoryCreateTrait;

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
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return LotFieldConfig[]
     */
    public function loadForAccount(int $accountId, bool $isReadOnlyDb = false): array
    {
        $lotFieldConfigs = $this->createLotFieldConfigReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->orderByOrder()
            ->loadEntities();
        return $lotFieldConfigs;
    }

    public function loadByIndexForAccount(string $index, int $accountId, bool $isReadOnlyDb = false): ?LotFieldConfig
    {
        $lotFieldConfigs = $this->createLotFieldConfigReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterIndex($index)
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->loadEntity();
        return $lotFieldConfigs;
    }

    /**
     * Load ordered array of LotFieldConfig records for definite account
     * @param string $index
     * @param bool $isReadOnlyDb
     * @return LotFieldConfig[]
     */
    public function loadByIndex(string $index, bool $isReadOnlyDb = false): array
    {
        $lotFieldConfigs = $this->createLotFieldConfigReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterIndex($index)
            ->filterActive(true)
            ->loadEntities();
        return $lotFieldConfigs;
    }
}
