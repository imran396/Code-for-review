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

namespace Sam\Report\Lot\CustomList\Template\Load;

use CustomLotsTemplateConfig;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\CustomLotsTemplateConfig\CustomLotsTemplateConfigReadRepositoryCreateTrait;

/**
 * Class CustomLotsTemplateConfigLoader
 * @package Sam\Report\Lot\CustomList\Template
 */
class CustomLotsTemplateConfigLoader extends CustomizableClass
{
    use CustomLotsTemplateConfigReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load a CustomLotsTemplateConfig by id
     * @param int|null $id
     * @param bool $isReadOnlyDb query to read-only db
     * @return CustomLotsTemplateConfig|null
     */
    public function load(?int $id, bool $isReadOnlyDb = false): ?CustomLotsTemplateConfig
    {
        if (!$id) {
            return null;
        }
        $customLotsTemplateConfig = $this->createCustomLotsTemplateConfigReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($id)
            ->filterActive(true)
            ->loadEntity();
        return $customLotsTemplateConfig;
    }

    /**
     * @param string $name
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return CustomLotsTemplateConfig
     */
    public function loadByName(string $name, int $accountId, bool $isReadOnlyDb = false): ?CustomLotsTemplateConfig
    {
        $customLotsTemplateConfig = $this->createCustomLotsTemplateConfigReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->filterName($name)
            ->orderById()
            ->loadEntity();
        return $customLotsTemplateConfig;
    }

    /**
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return array|CustomLotsTemplateConfig[]
     */
    public function loadAll(int $accountId, bool $isReadOnlyDb = false): array
    {
        $customLotsTemplateConfigs = $this->createCustomLotsTemplateConfigReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->loadEntities();
        return $customLotsTemplateConfigs;
    }
}
