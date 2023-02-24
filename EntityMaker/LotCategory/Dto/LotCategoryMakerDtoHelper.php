<?php
/**
 * Helper for DTO
 *
 * SAM-8856: Lot category entity-maker module structural adjustments for v3-5
 * SAM-4048: LotCategory entity maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 5, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotCategory\Dto;

use Sam\EntityMaker\Base\Dto\DtoHelper;

/**
 * @package Sam\EntityMaker\LotCategory
 */
class LotCategoryMakerDtoHelper extends DtoHelper
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
