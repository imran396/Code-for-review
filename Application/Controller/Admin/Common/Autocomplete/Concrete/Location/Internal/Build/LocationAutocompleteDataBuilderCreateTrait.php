<?php
/**
 * SAM-10121: Separate location auto-completer end-points per controllers and fix filtering by entity-context account
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Location\Internal\Build;

/**
 * Trait LocationAutocompleteDataBuilderCreateTrait
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Location\Internal\Build
 */
trait LocationAutocompleteDataBuilderCreateTrait
{
    /**
     * @var LocationAutocompleteDataBuilder|null
     */
    protected ?LocationAutocompleteDataBuilder $locationAutocompleteDataBuilder = null;

    /**
     * @return LocationAutocompleteDataBuilder
     */
    protected function createLocationAutocompleteDataBuilder(): LocationAutocompleteDataBuilder
    {
        return $this->locationAutocompleteDataBuilder ?: LocationAutocompleteDataBuilder::new();
    }

    /**
     * @param LocationAutocompleteDataBuilder $locationAutocompleteDataBuilder
     * @return static
     * @internal
     */
    public function setLocationAutocompleteDataBuilder(LocationAutocompleteDataBuilder $locationAutocompleteDataBuilder): static
    {
        $this->locationAutocompleteDataBuilder = $locationAutocompleteDataBuilder;
        return $this;
    }
}
