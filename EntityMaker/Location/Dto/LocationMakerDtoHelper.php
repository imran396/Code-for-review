<?php
/**
 * Helper for DTO
 *
 * SAM-10273: Entity locations: Implementation (Dev)
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 7, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Location\Dto;

use Sam\EntityMaker\Base\Dto\DtoHelper;

/**
 * @package Sam\EntityMaker\Location
 */
class LocationMakerDtoHelper extends DtoHelper
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
