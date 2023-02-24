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

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Location;

/**
 * Trait LocationAutocompleteResponseProducerCreateTrait
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Location
 */
trait LocationAutocompleteResponseProducerCreateTrait
{
    /**
     * @var LocationAutocompleteResponseProducer|null
     */
    protected ?LocationAutocompleteResponseProducer $locationAutocompleteResponseProducer = null;

    /**
     * @return LocationAutocompleteResponseProducer
     */
    protected function createLocationAutocompleteResponseProducer(): LocationAutocompleteResponseProducer
    {
        return $this->locationAutocompleteResponseProducer ?: LocationAutocompleteResponseProducer::new();
    }

    /**
     * @param LocationAutocompleteResponseProducer $locationAutocompleteResponseProducer
     * @return static
     * @internal
     */
    public function setLocationAutocompleteResponseProducer(LocationAutocompleteResponseProducer $locationAutocompleteResponseProducer): static
    {
        $this->locationAutocompleteResponseProducer = $locationAutocompleteResponseProducer;
        return $this;
    }
}
