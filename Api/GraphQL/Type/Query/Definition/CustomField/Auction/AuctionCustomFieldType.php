<?php
/**
 * SAM-10956: Adjust GraphQL custom fields for auction structure
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\CustomField\Auction;

use AuctionCustField;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Text\TextTransformer;

/**
 * Class AuctionCustomFieldType
 * @package Sam\Api\GraphQL\Type\Query\Definition\CustomField\Auction
 */
class AuctionCustomFieldType extends CustomizableClass implements TypeInterface
{
    public const NAME = 'AuctionCustomField';

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
                'public_list' => Type::boolean(),
                'admin_list' => Type::boolean(),
                'parameters' => Type::string(),
            ],
            'resolveField' => function (AuctionCustField $customField, array $args, AppContext $context, ResolveInfo $info) {
                $property = TextTransformer::new()->toCamelCase($info->fieldName);
                return $customField->{$property};
            }
        ]);
    }
}
