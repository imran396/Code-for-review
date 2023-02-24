<?php
/**
 * SAM-10844: Extend parameter filtering on auction lots, my items level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 21, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Aggregate\Internal;

/**
 * Trait AggregateTypeBuilderCreateTrait
 * @package Sam\Api\GraphQL\Type\Query\Definition\Aggregate
 */
trait AggregateTypeBuilderCreateTrait
{
    protected ?AggregateTypeBuilder $aggregateTypeBuilder = null;

    /**
     * @return AggregateTypeBuilder
     */
    protected function createAggregateTypeBuilder(): AggregateTypeBuilder
    {
        return $this->aggregateTypeBuilder ?: AggregateTypeBuilder::new();
    }

    /**
     * @param AggregateTypeBuilder $aggregateTypeBuilder
     * @return static
     * @internal
     */
    public function setAggregateTypeBuilder(AggregateTypeBuilder $aggregateTypeBuilder): static
    {
        $this->aggregateTypeBuilder = $aggregateTypeBuilder;
        return $this;
    }
}
