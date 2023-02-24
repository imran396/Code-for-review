<?php

namespace Sam\Location\Load;

/**
 * Trait LocationLoaderAwareTrait
 * @package Sam\Location\Load
 */
trait LocationLoaderAwareTrait
{
    /**
     * @var LocationLoader|null
     */
    protected ?LocationLoader $locationLoader = null;

    /**
     * @param LocationLoader $locationLoader
     * @return static
     * @internal
     */
    public function setLocationLoader(LocationLoader $locationLoader): static
    {
        $this->locationLoader = $locationLoader;
        return $this;
    }

    /**
     * @return LocationLoader
     */
    protected function getLocationLoader(): LocationLoader
    {
        if ($this->locationLoader === null) {
            $this->locationLoader = LocationLoader::new();
        }
        return $this->locationLoader;
    }
}
