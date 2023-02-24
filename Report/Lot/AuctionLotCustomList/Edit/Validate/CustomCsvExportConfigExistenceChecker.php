<?php
/**
 * SAM-813: Custom CSV export
 * SAM-6546: Refactor "Custom CSV Export" report management logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct. 04, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\AuctionLotCustomList\Edit\Validate;


use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\CustomCsvExportConfig\CustomCsvExportConfigReadRepositoryCreateTrait;

/**
 * Class CustomCsvExportConfigExistenceChecker
 * @package Sam\Report\Lot\AuctionLotCustomList\Edit\Validate
 */
class CustomCsvExportConfigExistenceChecker extends CustomizableClass
{
    use CustomCsvExportConfigReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $name
     * @param int|null $skipId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByName(string $name, ?int $skipId = null, bool $isReadOnlyDb = false): bool
    {
        $repository = $this->createCustomCsvExportConfigReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterName($name);
        if ($skipId) {
            $repository->skipId($skipId);
        }
        $configQty = $repository->count();
        return $configQty > 0;
    }
}
