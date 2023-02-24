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

use GraphQL\Type\Definition\ObjectType;
use Sam\Api\GraphQL\Type\AppContextAwareInterface;
use Sam\Api\GraphQL\Type\AppContextAwareTrait;
use Sam\Api\GraphQL\Type\Query\Resolve;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\CustomField\Auction\Load\AuctionCustomFieldLoaderAwareTrait;

/**
 * Class AuctionCustomFieldValueCollectionType
 * @package Sam\Api\GraphQL\Type\Query\Definition\CustomField\Auction
 */
class AuctionCustomFieldValueCollectionType extends CustomizableClass implements TypeInterface, AppContextAwareInterface, TypeRegistryAwareInterface
{
    use AppContextAwareTrait;
    use AuctionCustomFieldLoaderAwareTrait;
    use TypeRegistryAwareTrait;

    public const NAME = 'AuctionCustomFieldValueCollection';

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
            'fields' => $this->makeFieldsConfiguration(),
        ]);
    }

    protected function makeFieldsConfiguration(): array
    {
        $dbTransformer = DbTextTransformer::new();
        $customFields = $this->getAuctionCustomFieldLoader()->loadAll(true);
        $fields = [];
        foreach ($customFields as $customField) {
            $fieldName = $dbTransformer->toDbColumn($customField->Name);
            $fields[] = [
                'name' => $fieldName,
                'type' => $this->getTypeRegistry()->getTypeDefinition(AuctionCustomFieldValueType::NAME),
                'resolver' => Resolve\AuctionType\CustomFieldValueResolver::new()->construct($customField)
            ];
        }
        return $fields;
    }
}
