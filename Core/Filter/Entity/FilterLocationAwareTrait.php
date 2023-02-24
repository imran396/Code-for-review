<?php
/**
 * SAM-4616: Reports code refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           05/01/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\Entity;

use Location;
use Sam\Storage\Entity\Aggregate\LocationAggregate;

/**
 * Trait FilterLocationAwareTrait
 * @package Sam\Core\Filter\Entity
 */
trait FilterLocationAwareTrait
{
    protected ?LocationAggregate $locationAggregate = null;

    /**
     * @return int|null
     */
    public function getFilterLocationId(): ?int
    {
        return $this->getLocationAggregate()->getLocationId();
    }

    /**
     * @param int|null $locationId
     * @return static
     */
    public function filterLocationId(?int $locationId): static
    {
        $this->getLocationAggregate()->setLocationId($locationId);
        return $this;
    }

    /**
     * @return Location|null
     */
    public function getFilterLocation(): ?Location
    {
        return $this->getLocationAggregate()->getLocation(true);
    }

    /**
     * @param Location|null $location
     * @return static
     */
    public function filterLocation(?Location $location = null): static
    {
        $this->getLocationAggregate()->setLocation($location);
        return $this;
    }

    // --- LocationAggregate ---

    /**
     * @return LocationAggregate
     */
    protected function getLocationAggregate(): LocationAggregate
    {
        if ($this->locationAggregate === null) {
            $this->locationAggregate = LocationAggregate::new();
        }
        return $this->locationAggregate;
    }
}
