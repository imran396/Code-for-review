<?php
/**
 * SAM-4720: Refactor logic of System Parameters management pages to editor modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Edit\Normalize;


/**
 * Trait PropertyMetadataProviderCreateTrait
 * @package Sam\Settings\Edit\Validate
 */
trait PropertyMetadataProviderCreateTrait
{
    /**
     * @var PropertyMetadataProvider|null
     */
    protected ?PropertyMetadataProvider $propertyMetadataProvider = null;

    /**
     * @return PropertyMetadataProvider
     */
    protected function createPropertyMetadataProvider(): PropertyMetadataProvider
    {
        return $this->propertyMetadataProvider ?: PropertyMetadataProvider::new();
    }

    /**
     * @param PropertyMetadataProvider $propertyMetadataProvider
     * @return static
     * @internal
     */
    public function setPropertyMetadataProvider(PropertyMetadataProvider $propertyMetadataProvider): static
    {
        $this->propertyMetadataProvider = $propertyMetadataProvider;
        return $this;
    }
}
