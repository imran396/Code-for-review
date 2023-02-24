<?php
/**
 * SAM-4700: Location deleter
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SAM 3.1
 * @since           16.12.2018
 * file encoding    UTF-8
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Location\Delete;

use Location;
use Sam\Core\Service\CustomizableClass;
use Sam\Location\Load\LocationLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\Location\LocationWriteRepositoryAwareTrait;

/**
 * Class Deleter
 * @package Sam\Location\Delete
 */
class LocationDeleter extends CustomizableClass
{
    use LocationLoaderAwareTrait;
    use LocationWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete Location record
     * @param Location $location
     * @param int $editorUserId
     * @return static
     */
    public function delete(Location $location, int $editorUserId): static
    {
        $location->Active = false;
        $this->getLocationWriteRepository()->saveWithModifier($location, $editorUserId);
        return $this;
    }

    /**
     * Delete Location record loaded by id
     * @param int $locationId
     * @param int $editorUserId
     * @return static
     */
    public function deleteById(int $locationId, int $editorUserId): static
    {
        $location = $this->getLocationLoader()->load($locationId, true);
        if (!$location) {
            return $this;
        }

        $this->delete($location, $editorUserId);
        return $this;
    }

    public function deleteByTypeAndEntityId(int $type, ?int $entityId, int $editorUserId): static
    {
        $location = $this->getLocationLoader()->loadByTypeAndEntityId($type, $entityId);
        if (!$location) {
            return $this;
        }

        $this->delete($location, $editorUserId);
        return $this;
    }
}
