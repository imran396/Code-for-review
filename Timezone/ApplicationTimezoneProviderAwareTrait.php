<?php
/**
 * SAM-5925: Allow to set installation timezone and User preferred timezone
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Timezone;

/**
 * Trait ApplicationTimezoneProviderAwareTrait
 * @package Sam\Timezone
 */
trait ApplicationTimezoneProviderAwareTrait
{
    protected ?ApplicationTimezoneProvider $defaultTimezoneProvider = null;

    /**
     * @return ApplicationTimezoneProvider
     */
    protected function getApplicationTimezoneProvider(): ApplicationTimezoneProvider
    {
        if ($this->defaultTimezoneProvider === null) {
            $this->defaultTimezoneProvider = ApplicationTimezoneProvider::new();
        }
        return $this->defaultTimezoneProvider;
    }

    /**
     * @param ApplicationTimezoneProvider $defaultTimezoneProvider
     * @return static
     * @internal
     */
    public function setApplicationTimezoneProvider(ApplicationTimezoneProvider $defaultTimezoneProvider): static
    {
        $this->defaultTimezoneProvider = $defaultTimezoneProvider;
        return $this;
    }
}
