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

namespace Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use Sam\Core\Service\CustomizableClass;
use Sam\Api\GraphQL\Type\TypeInterface;

/**
 * Class LotListDateCustomFieldFilterType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type
 */
class LotListDateCustomFieldFilterType extends CustomizableClass implements TypeInterface
{
    public const NAME = 'LotListDateCustomFieldFilter';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function createTypeDefinition(): InputObjectType
    {
        return new InputObjectType([
            'name' => self::NAME,
            'fields' => [
                'min' => [
                    'type' => Type::int(),
                    'description' => 'Timestamp'
                ],
                'max' => [
                    'type' => Type::int(),
                    'description' => 'Timestamp'
                ],
            ]
        ]);
    }
}
