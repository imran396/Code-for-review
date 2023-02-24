<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\CustomField\Item;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use LotItemCustField;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Text\TextTransformer;

/**
 * Class ItemCustomFieldType
 * @package Sam\Api\GraphQL\Type\Query\Definition\CustomField\Item
 */
class ItemCustomFieldType extends CustomizableClass implements TypeInterface
{
    public const NAME = 'ItemCustomField';

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
                'name' => Type::string(),
                'order' => Type::int(),
                'in_catalog' => Type::boolean(),
                'in_invoices' => Type::boolean(),
                'in_settlements' => Type::boolean(),
                'in_admin_search' => Type::boolean(),
                'in_admin_catalog' => Type::boolean(),
                'search_field' => Type::boolean(),
                'barcode' => Type::boolean(),
                'barcode_type' => Type::int(),
                'parameters' => Type::string(),
            ],
            'resolveField' => function (LotItemCustField $customField, array $args, AppContext $context, ResolveInfo $info) {
                $property = TextTransformer::new()->toCamelCase($info->fieldName);
                return $customField->{$property};
            }
        ]);
    }
}
