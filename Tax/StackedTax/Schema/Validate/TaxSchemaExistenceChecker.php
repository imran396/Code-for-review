<?php
/**
 * SAM-11014: Stacked Tax. Invoice settings management. Add tax schema at account level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 08, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\TaxSchema\TaxSchemaReadRepositoryCreateTrait;

/**
 * Class TaxSchemaExistenceChecker
 * @package Sam\Tax\StackedTax\Schema\Validate
 */
class TaxSchemaExistenceChecker extends CustomizableClass
{
    use TaxSchemaReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function existById(
        int $taxSchemaId,
        ?int $accountId = null,
        ?int $amountSource = null,
        ?string $country = '',
        bool $isReadOnlyDb = false
    ): bool {
        $repository = $this->createTaxSchemaReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($taxSchemaId)
            ->filterActive(true);
        if ($accountId) {
            $repository->filterAccountId($accountId);
        }
        if ($amountSource) {
            $repository->filterAmountSource($amountSource);
        }
        if ($country) {
            $repository->filterCountry($country);
        }
        return $repository->exist();
    }
}
