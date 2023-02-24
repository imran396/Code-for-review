<?php
/**
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           16.12.2018
 * file encoding    UTF-8
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Location\Delete;

/**
 * Trait LocationDeleterAwareTrait
 * @package Sam\Location\Delete
 */
trait LocationDeleterAwareTrait
{
    /**
     * @var LocationDeleter|null
     */
    protected ?LocationDeleter $locationDeleter = null;

    /**
     * @return LocationDeleter
     */
    protected function getLocationDeleter(): LocationDeleter
    {
        if ($this->locationDeleter === null) {
            $this->locationDeleter = LocationDeleter::new();
        }
        return $this->locationDeleter;
    }

    /**
     * @param LocationDeleter $locationDeleter
     * @return static
     * @internal
     */
    public function setLocationDeleter(LocationDeleter $locationDeleter): static
    {
        $this->locationDeleter = $locationDeleter;
        return $this;
    }
}
