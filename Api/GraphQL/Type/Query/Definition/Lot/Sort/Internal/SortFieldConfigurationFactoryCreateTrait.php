<?php
/**
 * SAM-10384: Implement a GraphQL prototype for a list of lots
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 06, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Lot\Sort\Internal;

/**
 * Trait SortFieldConfigurationFactoryCreateTrait
 * @package Sam\Api\GraphQL\Type\Query\Definition\Lot\Sort\Internal
 */
trait SortFieldConfigurationFactoryCreateTrait
{
    protected ?SortFieldConfigurationFactory $sortFieldConfigurationFactory = null;

    /**
     * @return SortFieldConfigurationFactory
     */
    protected function createSortFieldConfigurationFactory(): SortFieldConfigurationFactory
    {
        return $this->sortFieldConfigurationFactory ?: SortFieldConfigurationFactory::new();
    }

    /**
     * @param SortFieldConfigurationFactory $sortFieldConfigurationFactory
     * @return static
     * @internal
     */
    public function setSortFieldConfigurationFactory(SortFieldConfigurationFactory $sortFieldConfigurationFactory): static
    {
        $this->sortFieldConfigurationFactory = $sortFieldConfigurationFactory;
        return $this;
    }
}
