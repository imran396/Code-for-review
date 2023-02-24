<?php
/**
 * SAM-4819: Entity aware traits
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/8/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\AwareTrait;

use Sam\Storage\Entity\Aggregate\LocationAggregate;
use Location;

/**
 * Trait LocationAwareTrait
 * @package Sam\Storage\Entity\AwareTrait
 */
trait LocationAwareTrait
{
    protected ?LocationAggregate $locationAggregate = null;

    /**
     * @return int|null
     */
    public function getLocationId(): ?int
    {
        return $this->getLocationAggregate()->getLocationId();
    }

    /**
     * @param int|null $locationId
     * @return static
     */
    public function setLocationId(?int $locationId): static
    {
        $this->getLocationAggregate()->setLocationId($locationId);
        return $this;
    }

    // --- Location ---

    /**
     * Return Location|null object
     * @param bool $isReadOnlyDb
     * @return Location|null
     */
    public function getLocation(bool $isReadOnlyDb = false): ?Location
    {
        return $this->getLocationAggregate()->getLocation($isReadOnlyDb);
    }

    /**
     * @param Location|null $location
     * @return static
     */
    public function setLocation(?Location $location): static
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
