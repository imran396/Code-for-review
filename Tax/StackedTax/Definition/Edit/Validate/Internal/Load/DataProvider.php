<?php
/**
 * SAM-10775: Create in Admin Web the "Tax Definition Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Definition\Edit\Validate\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\TaxDefinition\TaxDefinitionReadRepositoryCreateTrait;

/**
 * Class DataProvider
 * @package Sam\Tax\StackedTax\Definition\Edit\Validate\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use TaxDefinitionReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isTaxDefinitionExists(int $taxDefinitionId, bool $isReadOnlyDb = false): bool
    {
        return $this->createTaxDefinitionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($taxDefinitionId)
            ->exist();
    }

    public function isNameUnique(string $name, ?int $skipTaxDefinitionId, int $accountId, bool $isReadOnlyDb = false): bool
    {
        $repository = $this->createTaxDefinitionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterAccountId($accountId)
            ->filterName($name);
        if ($skipTaxDefinitionId) {
            $repository = $repository->skipId($skipTaxDefinitionId);
        }
        return !$repository->exist();
    }
}
