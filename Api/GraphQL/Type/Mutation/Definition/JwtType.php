<?php
/**
 * SAM-10700: Implement GraphQL authentication endpoint
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Mutation\Definition;

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Sam\Api\GraphQL\Type\Mutation\Resolve\JwtType\CreateResolver;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class JwtType
 * @package Sam\Api\GraphQL\Type\Mutation\Definition
 */
class JwtType extends CustomizableClass implements TypeInterface, TypeRegistryAwareInterface
{
    use TypeRegistryAwareTrait;

    public const NAME = 'JWT';

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
        return new ObjectType([
            'name' => self::NAME,
            'fields' => [
                'create' => [
                    'type' => $this->getTypeRegistry()->getTypeDefinition(CreateJwtType::NAME),
                    'args' => [
                        'username' => new NonNull(Type::string()),
                        'password' => new NonNull(Type::string()),
                    ],
                    'resolve' => CreateResolver::new()->resolve(...)
                ]
            ]
        ]);
    }
}
