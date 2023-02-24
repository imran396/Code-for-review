<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 * SAM-10581: Split lot GraphQL structure into item and lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Sam\Api\GraphQL\Type\AppContextAwareInterface;
use Sam\Api\GraphQL\Type\AppContextAwareTrait;
use Sam\Api\GraphQL\Type\Query\Definition\CustomField\Item\ItemCustomFieldValueCollectionType;
use Sam\Api\GraphQL\Type\Query\Resolve;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ItemType
 * @package Sam\Api\GraphQL\Type\Query\Definition
 */
class ItemType extends CustomizableClass implements TypeInterface, AppContextAwareInterface, TypeRegistryAwareInterface
{
    use AppContextAwareTrait;
    use TypeRegistryAwareTrait;

    public const NAME = 'Item';

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
            'fields' => $this->makeFieldsConfiguration()
        ]);
    }

    protected function makeFieldsConfiguration(): array
    {
        $fields = [
            'id' => Type::id(),
            'changes' => Type::string(),
            'item_num_full' => [
                'type' => Type::string(),
                'resolver' => Resolve\ItemType\ItemNumFullFieldResolver::new(),
            ],
            'item_num' => Type::int(),
            'item_num_ext' => Type::string(),
            'lot_desc' => Type::string(),
            'lot_name' => Type::string(),
            'images' => [
                'type' => new ListOfType($this->getTypeRegistry()->getTypeDefinition(ItemImageType::NAME)),
                'args' => [
                    'limit' => [
                        'type' => new NonNull(Type::int()),
                        'defaultValue' => 1
                    ]
                ],
                'resolver' => Resolve\ItemType\ImagesFieldResolver::new(),
            ],
            'account' => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(AccountType::NAME),
                'resolver' => Resolve\ItemType\AccountFieldResolver::new(),
            ],
            'winning_auction' => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(AuctionType::NAME),
                'resolver' => Resolve\ItemType\WinningAuctionFieldResolver::new(),
            ],
            'consignor' => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(UserType::NAME),
                'resolver' => Resolve\ItemType\ConsignorFieldResolver::new(),
            ],
            'location' => [
                'type' => $this->getTypeRegistry()->getTypeDefinition(LocationType::NAME),
                'resolver' => Resolve\ItemType\LocationFieldResolver::new(),
            ],
            'categories' => [
                'type' => new ListOfType($this->getTypeRegistry()->getTypeDefinition(LotCategoryType::NAME)),
                'resolver' => Resolve\ItemType\CategoriesFieldResolver::new(),
            ],
        ];

        /** @var ObjectType $customFieldsType */
        $customFieldsType = $this->getTypeRegistry()->getTypeDefinition(ItemCustomFieldValueCollectionType::NAME)();
        if ($customFieldsType->getFieldNames()) {
            $fields['custom_fields'] = [
                'type' => $customFieldsType,
                'resolver' => Resolve\ItemType\CustomFieldsFieldResolver::new()->construct($customFieldsType),
            ];
        }
        return $fields;
    }
}
