<?php
/**
 * Data Transfer Object for passing input data for Lot Category entity creating/updating/validating
 * Dto does not have to accurately describe the fields of the entity, it describes the incoming data from the external interface
 *
 * SAM-10375: Input DTO adjustments and fixes for v3-7
 * SAM-8856: Lot category entity-maker module structural adjustments for v3-5
 * SAM-4048: LotCategory entity maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Feb 5, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotCategory\Dto;

use Sam\EntityMaker\Base\Dto\InputDto;

/**
 * @package Sam\EntityMaker\LotCategory
 * @property string|null $active
 * @property string|int|null $afterId
 * @property string|null $afterName
 * @property string|int|null $beforeId
 * @property string|null $beforeName
 * @property string|null $buyNowAmount
 * @property string|null $consignmentCommission
 * @property string|null $first
 * @property string|int|null $id
 * @property string|null $imageLink
 * @property string|null $last
 * @property string|null $name
 * @property string|int|null $parentId
 * @property string|null $parentName
 * @property string|null $startingBid
 * @property string|null $quantityDigits
 */
class LotCategoryMakerInputDto extends InputDto
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
