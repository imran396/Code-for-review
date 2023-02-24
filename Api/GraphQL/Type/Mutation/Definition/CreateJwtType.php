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

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Core\Service\CustomizableClass;

/**
 * Class CreateJwtType
 * @package Sam\Api\GraphQL\Type\Mutation\Definition
 */
class CreateJwtType extends CustomizableClass implements TypeInterface
{
    public const NAME = 'CreateJwt';

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
                'accessToken' => Type::string()
            ]
        ]);
    }
}
