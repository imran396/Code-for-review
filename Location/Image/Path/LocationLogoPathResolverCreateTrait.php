<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Location\Image\Path;

/**
 * Trait LocationLogoPathResolverCreateTrait
 * @package Sam\Location\Image\Path
 */
trait LocationLogoPathResolverCreateTrait
{
    /**
     * @var LocationLogoPathResolver|null
     */
    protected ?LocationLogoPathResolver $locationLogoPathResolver = null;

    /**
     * @return LocationLogoPathResolver
     */
    protected function createLocationLogoPathResolver(): LocationLogoPathResolver
    {
        return $this->locationLogoPathResolver ?: LocationLogoPathResolver::new();
    }

    /**
     * @param LocationLogoPathResolver $locationLogoPathResolver
     * @return $this
     * @internal
     */
    public function setLocationLogoPathResolver(LocationLogoPathResolver $locationLogoPathResolver): static
    {
        $this->locationLogoPathResolver = $locationLogoPathResolver;
        return $this;
    }
}
