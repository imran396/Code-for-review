<?php
/**
 * SAM
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Common\Order;

use GraphQL\Type\Definition\EnumType;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Core\Service\CustomizableClass;

/**
 * Class OrderDirectionType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Common\Order
 */
class OrderDirectionType extends CustomizableClass implements TypeInterface
{
    public const NAME = 'OrderDirection';

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
                'values' => ['ASC', 'DESC'],
            ]
        );
    }
}
