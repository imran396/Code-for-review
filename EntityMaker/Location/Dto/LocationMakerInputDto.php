<?php
/**
 * Data transfer object for passing input data for Location entity creating/updating/validating
 * Dto does not have to accurately describe the fields of the entity, it describes the incoming data from the external interface
 *
 * SAM-10375: Input DTO adjustments and fixes for v3-7
 * SAM-10273: Entity locations: Implementation (Dev)
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Feb 7, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Location\Dto;

use Sam\EntityMaker\Base\Dto\InputDto;

/**
 * @package Sam\EntityMaker\Location
 * @property string|null $address
 * @property string|null $city
 * @property string|null $country
 * @property string|null $county
 * @property string|int|null $id
 * @property string|null $logo
 * @property string|null $name
 * @property string|null $state
 * @property string|null $syncKey
 * @property string|null $syncNamespaceId
 * @property string|null $zip
 */
class LocationMakerInputDto extends InputDto
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
