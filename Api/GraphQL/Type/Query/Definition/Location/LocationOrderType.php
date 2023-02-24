<?php
/**
 * SAM-10719: SAM 3.7 Taxes. Add Search/Filter panel at Account Location List page
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

namespace Sam\Api\GraphQL\Type\Query\Definition\Location;

use GraphQL\Type\Definition\InputObjectType;
use Sam\Api\GraphQL\Type\Query\Definition\Common\Order\OrderDirectionType;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LocationOrderType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Location
 */
class LocationOrderType extends CustomizableClass implements TypeInterface, TypeRegistryAwareInterface
{
    use TypeRegistryAwareTrait;

    public const NAME = 'LocationOrder';

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
        $direction = $this->getTypeRegistry()->getTypeDefinition(OrderDirectionType::NAME);
        return new InputObjectType(
            [
                'name' => self::NAME,
                'fields' => [
                    'name' => $direction,
                    'address' => $direction,
                    'country' => $direction,
                    'city' => $direction,
                    'county' => $direction,
                    'state' => $direction,
                    'zip' => $direction,
                ]
            ]
        );
    }
}
