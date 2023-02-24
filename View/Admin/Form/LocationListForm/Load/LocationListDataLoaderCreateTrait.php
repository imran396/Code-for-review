<?php
/**
 * Location List Data Loader Create Trait
 *
 * SAM-6234: Refactor Location List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 25, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LocationListForm\Load;

/**
 * Trait LocationListDataLoaderCreateTrait
 */
trait LocationListDataLoaderCreateTrait
{
    protected ?LocationListDataLoader $locationListDataLoader = null;

    /**
     * @return LocationListDataLoader
     */
    protected function createLocationListDataLoader(): LocationListDataLoader
    {
        $locationListDataLoader = $this->locationListDataLoader ?: LocationListDataLoader::new();
        return $locationListDataLoader;
    }

    /**
     * @param LocationListDataLoader $locationListDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setLocationListDataLoader(LocationListDataLoader $locationListDataLoader): static
    {
        $this->locationListDataLoader = $locationListDataLoader;
        return $this;
    }
}
