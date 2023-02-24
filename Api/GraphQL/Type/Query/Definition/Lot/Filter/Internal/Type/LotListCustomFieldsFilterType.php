<?php
/**
 * SAM-10844: GraphQL extend parameter filtering on auction lots, my items level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type;

use Closure;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use LotItemCustField;
use Sam\Api\GraphQL\Type\AppContextAwareInterface;
use Sam\Api\GraphQL\Type\AppContextAwareTrait;
use Sam\Api\GraphQL\Type\Query\Definition\Common\Filter\StringFilterType;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\User\Access\UnknownContextAccessCheckerAwareTrait;

/**
 * Class LotListCustomFieldsFilterType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type
 */
class LotListCustomFieldsFilterType extends CustomizableClass implements TypeInterface, AppContextAwareInterface, TypeRegistryAwareInterface
{
    use AppContextAwareTrait;
    use LotCustomFieldLoaderCreateTrait;
    use TypeRegistryAwareTrait;
    use UnknownContextAccessCheckerAwareTrait;

    public const NAME = 'LotListCustomFieldsFilter';

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
        $fieldsConfiguration = $this->makeFieldsConfiguration();

        $definition = new InputObjectType([
            'name' => self::NAME,
            'fields' => $fieldsConfiguration,
            'parseValue' => function (array $values) use ($fieldsConfiguration) {
                $result = [];
                foreach ($values as $fieldName => $value) {
                    $customFieldId = $fieldsConfiguration[$fieldName]['customFieldId'];
                    $result[$customFieldId] = $value;
                }
                return $result;
            }
        ]);
        return $definition;
    }

    protected function makeFieldsConfiguration(): array
    {
        $accessRoles = $this->getUnknownContextAccessChecker()->detectRoles($this->getAppContext()->editorUserId)[0];
        $customFields = $this->createLotCustomFieldLoader()->loadByRole($accessRoles, true);
        $fields = [];
        foreach ($customFields as $customField) {
            $fields[$this->makeInputName($customField)] = [
                'type' => $this->detectCustomFieldFilterType($customField),
                'customFieldId' => $customField->Id
            ];
        }
        return $fields;
    }

    protected function makeInputName(LotItemCustField $customField): string
    {
        $asDbColumn = DbTextTransformer::new()->toDbColumn($customField->Name);
        $inputName = TextTransformer::new()->toPascalCase($asDbColumn);
        return $inputName;
    }

    protected function detectCustomFieldFilterType(LotItemCustField $customField): Type|Closure
    {
        $type = match ($customField->Type) {
            Constants\CustomField::TYPE_INTEGER => $this->getTypeRegistry()->getTypeDefinition(LotListIntegerCustomFieldFilterType::NAME),
            Constants\CustomField::TYPE_DECIMAL => $this->getTypeRegistry()->getTypeDefinition(LotListDecimalCustomFieldFilterType::NAME),
            Constants\CustomField::TYPE_DATE => $this->getTypeRegistry()->getTypeDefinition(LotListDateCustomFieldFilterType::NAME),
            Constants\CustomField::TYPE_POSTALCODE => $this->getTypeRegistry()->getTypeDefinition(LotListPostalCodeCustomFieldFilterType::NAME),
            default => $this->getTypeRegistry()->getTypeDefinition(StringFilterType::NAME)
        };
        return $type;
    }
}
