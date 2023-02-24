<?php
/**
 * SAM-10467: Implement a GraphQL nested structure for a single auction
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;

/**
 * Interface OutputTypeInterface
 * @package Sam\Api\GraphQL\Type
 */
interface OutputTypeInterface extends TypeInterface
{
    /**
     * Create GraphQL type definition
     *
     * @return ObjectType
     */
    public function createTypeDefinition(): ObjectType;

    /**
     * Gather necessary fields to resolve a query
     *
     * @param array $referencedFields
     * @return array
     */
    public function collectDataFields(array $referencedFields): array;
}
