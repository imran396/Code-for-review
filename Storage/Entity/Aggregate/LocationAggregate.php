<?php
/**
 * SAM-4819: Entity aware traits
 *
 * Aggregate class can be used, when we need to operate we several Location entities in one class namespace.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/21/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\Aggregate;

use Sam\Location\Load\LocationLoaderAwareTrait;
use Location;

/**
 * Class LocationAggregate
 * @package Sam\Storage\Entity\Aggregate
 */
class LocationAggregate extends EntityAggregateBase
{
    use LocationLoaderAwareTrait;

    private ?int $locationId = null;
    private ?Location $location = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Empty aggregated entities
     * @return static
     */
    public function clear(): static
    {
        $this->locationId = null;
        $this->location = null;
        return $this;
    }

    // --- location.id ---

    /**
     * @return int|null
     */
    public function getLocationId(): ?int
    {
        return $this->locationId;
    }

    /**
     * @param int|null $locationId
     * @return static
     */
    public function setLocationId(?int $locationId): static
    {
        $locationId = $locationId ?: null;
        if ($this->locationId !== $locationId) {
            $this->clear();
        }
        $this->locationId = $locationId;
        return $this;
    }

    // --- Location ---

    /**
     * @return bool
     */
    public function hasLocation(): bool
    {
        return ($this->location !== null);
    }

    /**
     * Return Location object
     * @param bool $isReadOnlyDb
     * @return Location|null
     */
    public function getLocation(bool $isReadOnlyDb = false): ?Location
    {
        if ($this->location === null) {
            $this->location = $this->getLocationLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->load($this->locationId, $isReadOnlyDb);
        }
        return $this->location;
    }

    /**
     * @param Location|null $location
     * @return static
     */
    public function setLocation(?Location $location = null): static
    {
        if (!$location) {
            $this->clear();
        } elseif ($location->Id !== $this->locationId) {
            $this->clear();
            $this->locationId = $location->Id;
        }
        $this->location = $location;
        return $this;
    }
}
