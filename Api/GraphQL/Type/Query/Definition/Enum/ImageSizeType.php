<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 21, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Enum;

use GraphQL\Type\Definition\EnumType;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Api\GraphQL\Type\TypeInterface;

/**
 * Class ImageSizeType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Enum
 */
class ImageSizeType extends CustomizableClass implements TypeInterface
{
    public const NAME = 'ImageSize';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function createTypeDefinition(): EnumType
    {
        return new EnumType(
            [
                'name' => self::NAME,
                'values' => Constants\Image::$sizes,
            ]
        );
    }
}
