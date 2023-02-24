<?php
/**
 * SAM-10844: Extend parameter filtering on auction lots, my items level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\Aggregate;

/**
 * Trait AggregateFieldResolverHelperCreateTrait
 * @package Sam\Api\GraphQL\Type\Query\Resolve\Aggregate
 */
trait AggregateFieldResolverHelperCreateTrait
{
    protected ?AggregateFieldResolverHelper $aggregateFieldResolverHelper = null;

    /**
     * @return AggregateFieldResolverHelper
     */
    protected function createAggregateFieldResolverHelper(): AggregateFieldResolverHelper
    {
        return $this->aggregateFieldResolverHelper ?: AggregateFieldResolverHelper::new();
    }

    /**
     * @param AggregateFieldResolverHelper $aggregateFieldResolverHelper
     * @return static
     * @internal
     */
    public function setAggregateFieldResolverHelper(AggregateFieldResolverHelper $aggregateFieldResolverHelper): static
    {
        $this->aggregateFieldResolverHelper = $aggregateFieldResolverHelper;
        return $this;
    }
}
