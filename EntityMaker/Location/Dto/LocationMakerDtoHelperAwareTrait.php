<?php
/**
 * SAM-10316: Decouple DtoHelperAwareTrait from BaseMakerValidator and BaseMakerProducer
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 21, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Location\Dto;

/**
 * Trait LocationMakerDtoHelperAwareTrait
 * @package Sam\EntityMaker\Location\Dto
 */
trait LocationMakerDtoHelperAwareTrait
{
    protected ?LocationMakerDtoHelper $locationMakerDtoHelper = null;

    /**
     * @return LocationMakerDtoHelper
     */
    protected function getLocationMakerDtoHelper(): LocationMakerDtoHelper
    {
        if ($this->locationMakerDtoHelper === null) {
            $this->locationMakerDtoHelper = LocationMakerDtoHelper::new();
        }
        return $this->locationMakerDtoHelper;
    }

    /**
     * @param LocationMakerDtoHelper $locationMakerDtoHelper
     * @return static
     * @internal
     */
    public function setLocationMakerDtoHelper(LocationMakerDtoHelper $locationMakerDtoHelper): static
    {
        $this->locationMakerDtoHelper = $locationMakerDtoHelper;
        return $this;
    }
}
