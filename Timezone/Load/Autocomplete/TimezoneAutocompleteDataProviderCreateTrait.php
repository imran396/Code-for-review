<?php
/**
 * SAM-5925: Allow to set installation timezone and User preferred timezone
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Timezone\Load\Autocomplete;

/**
 * Trait TimezoneAutocompleteDataProviderCreateTrait
 * @package Sam\Timezone\Load\Autocomplete
 */
trait TimezoneAutocompleteDataProviderCreateTrait
{
    protected ?TimezoneAutocompleteDataProvider $timezoneAutocompleteDataProvider = null;

    /**
     * @return TimezoneAutocompleteDataProvider
     */
    protected function createTimezoneAutocompleteDataProvider(): TimezoneAutocompleteDataProvider
    {
        return $this->timezoneAutocompleteDataProvider ?: TimezoneAutocompleteDataProvider::new();
    }

    /**
     * @param TimezoneAutocompleteDataProvider $timezoneAutocompleteDataProvider
     * @return static
     * @internal
     */
    public function setTimezoneAutocompleteDataProvider(TimezoneAutocompleteDataProvider $timezoneAutocompleteDataProvider): static
    {
        $this->timezoneAutocompleteDataProvider = $timezoneAutocompleteDataProvider;
        return $this;
    }
}
