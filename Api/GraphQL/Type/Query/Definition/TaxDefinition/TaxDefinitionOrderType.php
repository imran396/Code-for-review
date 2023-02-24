<?php
/**
 * SAM-10782: Create in Admin Web the "Tax Definition List" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 06, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\TaxDefinition;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use Sam\Api\GraphQL\Type\Query\Definition\Common\Order\OrderDirectionType;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxDefinitionOrderType
 * @package Sam\Api\GraphQL\Type\Query\Definition\TaxDefinition
 */
class TaxDefinitionOrderType extends CustomizableClass implements TypeInterface, TypeRegistryAwareInterface
{
    use TypeRegistryAwareTrait;

    public const NAME = 'TaxDefinitionOrder';

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
        $direction = $this->getTypeRegistry()->getTypeDefinition(OrderDirectionType::NAME);

        return new InputObjectType([
            'name' => self::NAME,
            'fields' => [
                'id' => $direction,
                'name' => $direction,
                'geo_type' => $direction,
                'tax_type' => $direction,
                'country' => $direction,
                'state' => $direction,
                'county' => $direction,
                'city' => $direction,
                'description' => $direction,
                'note' => $direction,
            ]
        ]);
    }
}
