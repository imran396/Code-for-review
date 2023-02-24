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

use GraphQL\Type\Definition\ResolveInfo;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Load\Aggregate\AggregateDataField;

/**
 * Interface AggregateFieldResolverInterface
 * @package Sam\Api\GraphQL\Type\Query\Resolve\Aggregate
 */
interface AggregateFieldResolverInterface
{
    /**
     * Returns the set of data fields required to resolve the field
     * @param array $referencedFieldNodes
     * @return AggregateDataField[]
     */
    public function dependentOnAggregateDataFields(array $referencedFieldNodes): array;

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): mixed;
}
