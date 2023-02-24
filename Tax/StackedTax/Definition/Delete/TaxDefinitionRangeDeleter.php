<?php
/**
 * SAM-10775: Create in Admin Web the "Tax Definition Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Definition\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\TaxDefinitionRange\TaxDefinitionRangeWriteRepositoryAwareTrait;
use Sam\Tax\StackedTax\Definition\Load\TaxDefinitionRangeLoaderCreateTrait;

/**
 * Class TaxDefinitionRangeDeleter
 * @package Sam\Tax\StackedTax\Definition\Delete
 */
class TaxDefinitionRangeDeleter extends CustomizableClass
{
    use TaxDefinitionRangeLoaderCreateTrait;
    use TaxDefinitionRangeWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function delete(int $rangeId, int $editorUserId): void
    {
        $range = $this->createTaxDefinitionRangeLoader()->load($rangeId);
        if (!$range) {
            log_error("Available tax definition rate range not found" . composeSuffix(['id' => $rangeId]));
            return;
        }
        $range->toSoftDeleted();
        $this->getTaxDefinitionRangeWriteRepository()->saveWithModifier($range, $editorUserId);
    }
}
