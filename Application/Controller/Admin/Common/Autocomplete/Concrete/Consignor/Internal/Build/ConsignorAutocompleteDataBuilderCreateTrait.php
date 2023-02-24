<?php
/**
 * SAM-10099: Distinguish consignor autocomplete data loading end-points for different pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Consignor\Internal\Build;

/**
 * Trait ConsignorAutocompleteDataBuilderCreateTrait
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Consignor\Internal\Build
 */
trait ConsignorAutocompleteDataBuilderCreateTrait
{
    protected ?ConsignorAutocompleteDataBuilder $consignorAutocompleteDataBuilder = null;

    /**
     * @return ConsignorAutocompleteDataBuilder
     */
    protected function createConsignorAutocompleteDataBuilder(): ConsignorAutocompleteDataBuilder
    {
        return $this->consignorAutocompleteDataBuilder ?: ConsignorAutocompleteDataBuilder::new();
    }

    /**
     * @param ConsignorAutocompleteDataBuilder $consignorAutocompleteDataBuilder
     * @return $this
     * @internal
     */
    public function setConsignorAutocompleteDataBuilder(ConsignorAutocompleteDataBuilder $consignorAutocompleteDataBuilder): static
    {
        $this->consignorAutocompleteDataBuilder = $consignorAutocompleteDataBuilder;
        return $this;
    }
}
