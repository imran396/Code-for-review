<?php
/**
 * SAM-10787: Create in Admin Web the "Tax Schema List" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Sam\Api\GraphQL\Type\Query\Definition\TaxSchema\TaxSchemaAmountSourceType;
use Sam\Api\GraphQL\Type\Query\Resolve;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxSchemaType
 * @package Sam\Api\GraphQL\Type\Query\Definition
 */
class TaxSchemaType extends CustomizableClass implements TypeInterface, TypeRegistryAwareInterface
{
    use TypeRegistryAwareTrait;

    public const NAME = 'TaxSchema';

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
        return new ObjectType(
            [
                'name' => self::NAME,
                'fields' => [
                    'id' => Type::id(),
                    'name' => Type::string(),
                    'geo_type' => Type::int(),
                    'country' => Type::string(),
                    'state' => Type::string(),
                    'county' => Type::string(),
                    'city' => Type::string(),
                    'description' => Type::string(),
                    'note' => Type::string(),
                    'for_invoice' => Type::boolean(),
                    'for_settlement' => Type::boolean(),
                    'amount_source' => $this->getTypeRegistry()->getTypeDefinition(TaxSchemaAmountSourceType::NAME),
                    'account' => [
                        'type' => $this->getTypeRegistry()->getTypeDefinition(AccountType::NAME),
                        'resolver' => Resolve\Common\AccountFieldResolver::new(),
                    ],
                ]
            ]
        );
    }
}
