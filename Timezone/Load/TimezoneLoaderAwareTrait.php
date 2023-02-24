<?php
/**
 * Trait for Timezone Loader
 *
 * SAM-4022: TimezoneLoader and TimezoneExistenceChecker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 15, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Timezone\Load;

/**
 * Trait TimezoneLoaderAwareTrait
 */
trait TimezoneLoaderAwareTrait
{
    protected ?TimezoneLoader $timezoneLoader = null;

    /**
     * @return TimezoneLoader
     */
    protected function getTimezoneLoader(): TimezoneLoader
    {
        if ($this->timezoneLoader === null) {
            $this->timezoneLoader = TimezoneLoader::new();
        }
        return $this->timezoneLoader;
    }

    /**
     * @param TimezoneLoader
     * @return static
     * @internal
     */
    public function setTimezoneLoader(TimezoneLoader $timezoneLoader): static
    {
        $this->timezoneLoader = $timezoneLoader;
        return $this;
    }
}
