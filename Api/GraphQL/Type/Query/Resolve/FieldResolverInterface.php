<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 22, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve;

use GraphQL\Type\Definition\ResolveInfo;
use Sam\Api\GraphQL\AppContext;

/**
 * Interface FieldResolverInterface
 * @package Sam\Api\GraphQL\Type\Query\Resolve
 */
interface FieldResolverInterface
{
    /**
     * Returns the set of data fields required to resolve the field
     * @param array $referencedFieldNodes
     * @return string[]
     */
    public function dependentOnDataFields(array $referencedFieldNodes): array;

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): mixed;
}
