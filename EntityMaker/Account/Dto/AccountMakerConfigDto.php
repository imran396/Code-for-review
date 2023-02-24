<?php
/**
 * Data Transfer Object for passing input data for Account entity creating/updating/validating
 * Dto does not have to accurately describe the fields of the entity, it describes the incoming data from the external interface
 *
 * SAM-8855: Account entity-maker module structural adjustments for v3-5
 * SAM-3942: Account entity maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Nov 2, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Account\Dto;

use Sam\EntityMaker\Base\Dto\ConfigDto;

/**
 * @package Sam\EntityMaker\Account
 */
class AccountMakerConfigDto extends ConfigDto
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
