<?php
/**
 * SAM-10467: Implement a GraphQL nested structure for a single auction
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Sam\Core\Service\CustomizableClass;
use Sam\Api\GraphQL\Type\Query\Resolve;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;

/**
 * Class LocationType
 * @package Sam\Api\GraphQL\Type
 */
class LocationType extends CustomizableClass implements TypeInterface, TypeRegistryAwareInterface
{
    use TypeRegistryAwareTrait;

    public const NAME = 'Location';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function createTypeDefinition(): ObjectType
    {
        return new ObjectType([
            'name' => self::NAME,
            'fields' => [
                'id' => Type::id(),
                'account' => [
                    'type' => $this->getTypeRegistry()->getTypeDefinition(AccountType::NAME),
                    'resolver' => Resolve\Common\AccountFieldResolver::new(),
                ],
                'name' => Type::string(),
                'logo' => Type::string(),
                'address' => Type::string(),
                'country' => Type::string(),
                'city' => Type::string(),
                'county' => Type::string(),
                'state' => Type::string(),
                'zip' => Type::string(),
            ]
        ]);
    }
}
