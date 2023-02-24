<?php
/**
 * SAM-10384: Implement a GraphQL prototype for a list of lots
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 05, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal;

/**
 * Trait FilterFieldConfigurationFactoryCreateTrait
 * @package Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal
 */
trait FilterFieldConfigurationFactoryCreateTrait
{
    protected ?FilterFieldConfigurationFactory $filterFieldConfigurationFactory = null;

    /**
     * @return FilterFieldConfigurationFactory
     */
    protected function createFilterFieldConfigurationFactory(): FilterFieldConfigurationFactory
    {
        return $this->filterFieldConfigurationFactory ?: FilterFieldConfigurationFactory::new();
    }

    /**
     * @param FilterFieldConfigurationFactory $filterFieldConfigurationFactory
     * @return static
     * @internal
     */
    public function setFilterFieldConfigurationFactory(FilterFieldConfigurationFactory $filterFieldConfigurationFactory): static
    {
        $this->filterFieldConfigurationFactory = $filterFieldConfigurationFactory;
        return $this;
    }
}
