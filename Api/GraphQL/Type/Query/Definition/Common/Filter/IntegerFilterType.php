<?php
/**
 * SAM-10844: Extend parameter filtering on auction lots, my items level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Common\Filter;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\Type;
use Sam\Api\GraphQL\Type\Query\Definition\Common\Scalar\BigIntType;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class IntegerFilterType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Common\Filter
 */
class IntegerFilterType extends CustomizableClass implements TypeInterface, TypeRegistryAwareInterface
{
    use TypeRegistryAwareTrait;

    public const NAME = 'IntFilter';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function createTypeDefinition(): Type
    {
        $bigIntType = $this->getTypeRegistry()->getTypeDefinition(BigIntType::NAME);
        return new InputObjectType(
            [
                'name' => self::NAME,
                'fields' => [
                    'in' => new ListOfType($bigIntType),
                    'notIn' => new ListOfType($bigIntType),
                    'lt' => $bigIntType,
                    'lte' => $bigIntType,
                    'gt' => $bigIntType,
                    'gte' => $bigIntType,
                ]
            ]
        );
    }
}
