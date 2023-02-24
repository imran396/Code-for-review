<?php
/**
 * SAM-10957: GraphQL item image extension
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Sam\Api\GraphQL\Type\Query\Definition\Enum\ImageSizeType;
use Sam\Api\GraphQL\Type\Query\Resolve;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ItemImageType
 * @package Sam\Api\GraphQL\Type\Query
 */
class ItemImageType extends CustomizableClass implements TypeInterface, TypeRegistryAwareInterface
{
    use TypeRegistryAwareTrait;

    public const NAME = 'ItemImage';

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
                'id' => Type::id(),
                'image_link' => Type::string(),
                'url' => [
                    'type' => Type::string(),
                    'args' => [
                        'size' => [
                            'type' => $this->getTypeRegistry()->getTypeDefinition(ImageSizeType::NAME),
                            'defaultValue' => Constants\Image::SMALL
                        ]
                    ],
                    'resolver' => Resolve\ItemImageType\UrlFieldResolver::new()
                ]
            ]
        ]);
    }
}
