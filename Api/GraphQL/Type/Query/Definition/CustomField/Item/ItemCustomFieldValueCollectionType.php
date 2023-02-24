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
use Sam\Api\GraphQL\Type\AppContextAwareInterface;
use Sam\Api\GraphQL\Type\AppContextAwareTrait;
use Sam\Api\GraphQL\Type\Query\Resolve;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\User\Access\UnknownContextAccessCheckerAwareTrait;

/**
 * Class ItemCustomFieldValueCollectionType
 * @package Sam\Api\GraphQL\Type\Query\Definition\CustomField\Item
 */
class ItemCustomFieldValueCollectionType extends CustomizableClass implements TypeInterface, AppContextAwareInterface, TypeRegistryAwareInterface
{
    use AppContextAwareTrait;
    use LotCustomFieldLoaderCreateTrait;
    use TypeRegistryAwareTrait;
    use UnknownContextAccessCheckerAwareTrait;

    public const NAME = 'ItemCustomFieldValueCollection';

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
        $accessRoles = $this->getUnknownContextAccessChecker()->detectRoles($this->getAppContext()->editorUserId)[0];
        $customFields = $this->createLotCustomFieldLoader()->loadByRole($accessRoles, true);
        $fields = [];
        foreach ($customFields as $customField) {
            $fieldName = $dbTransformer->toDbColumn($customField->Name);
            $fields[$fieldName] = [
                'type' => $this->getTypeRegistry()->getTypeDefinition(ItemCustomFieldValueType::NAME),
                'resolver' => Resolve\ItemType\CustomFieldValueResolver::new()->construct($customField)
            ];
        }
        return $fields;
    }
}
