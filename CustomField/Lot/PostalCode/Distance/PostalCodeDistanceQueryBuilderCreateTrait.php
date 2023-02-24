<?php
/**
 * SAM-7715: Refactor \Lot_DistanceQuery
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\PostalCode\Distance;

/**
 * Trait PostalCodeDistanceQueryBuilderCreateTrait
 * @package Sam\CustomField\Lot\PostalCode\Distance
 */
trait PostalCodeDistanceQueryBuilderCreateTrait
{
    protected ?PostalCodeDistanceQueryBuilder $postalCodeDistanceQueryBuilder = null;

    /**
     * @return PostalCodeDistanceQueryBuilder
     */
    protected function createPostalCodeDistanceQueryBuilder(): PostalCodeDistanceQueryBuilder
    {
        return $this->postalCodeDistanceQueryBuilder ?: PostalCodeDistanceQueryBuilder::new();
    }

    /**
     * @param PostalCodeDistanceQueryBuilder $postalCodeDistanceQueryBuilder
     * @return static
     * @internal
     */
    public function setPostalCodeDistanceQueryBuilder(PostalCodeDistanceQueryBuilder $postalCodeDistanceQueryBuilder): static
    {
        $this->postalCodeDistanceQueryBuilder = $postalCodeDistanceQueryBuilder;
        return $this;
    }
}
