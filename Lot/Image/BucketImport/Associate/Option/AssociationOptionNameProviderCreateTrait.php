<?php
/**
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Associate\Option;

/**
 * Trait AssociationOptionNameProviderCreateTrait
 * @package Sam\Lot\Image\BucketImport\Associate\Option
 */
trait AssociationOptionNameProviderCreateTrait
{
    protected ?AssociationOptionNameProvider $associationOptionNameProvider = null;

    /**
     * @return AssociationOptionNameProvider
     */
    protected function createAssociationOptionNameProvider(): AssociationOptionNameProvider
    {
        return $this->associationOptionNameProvider ?: AssociationOptionNameProvider::new();
    }

    /**
     * @param AssociationOptionNameProvider $associationOptionNameProvider
     * @return static
     * @internal
     */
    public function setAssociationOptionNameProvider(AssociationOptionNameProvider $associationOptionNameProvider): static
    {
        $this->associationOptionNameProvider = $associationOptionNameProvider;
        return $this;
    }
}
