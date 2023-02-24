<?php
/**
 * SAM-10844: Extend parameter filtering on auction lots, my items level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Aggregate;

use Closure;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Type\Definition\Type;
use Sam\Api\GraphQL\Type\AppContextAwareInterface;
use Sam\Api\GraphQL\Type\AppContextAwareTrait;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\Internal\AggregateTypeBuilderCreateTrait;
use Sam\Api\GraphQL\Type\Query\Definition\Common\Scalar\BigIntType;
use Sam\Api\GraphQL\Type\Query\Resolve\Aggregate\ItemAggregateType\ItemCustomFieldValueFormatter;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\User\Access\UnknownContextAccessCheckerAwareTrait;

/**
 * Class ItemCustomFieldCollectionAggregateType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Aggregate
 */
class ItemCustomFieldCollectionAggregateType extends CustomizableClass implements TypeInterface, TypeRegistryAwareInterface, AppContextAwareInterface
{
    use AggregateTypeBuilderCreateTrait;
    use AppContextAwareTrait;
    use LotCustomFieldLoaderCreateTrait;
    use TypeRegistryAwareTrait;
    use UnknownContextAccessCheckerAwareTrait;

    public const NAME = 'ItemCustomFieldCollectionAggregate';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function createTypeDefinition(): AggregateObjectType
    {
        $builder = $this->createAggregateTypeBuilder();
        $dbTransformer = DbTextTransformer::new();
        $accessRoles = $this->getUnknownContextAccessChecker()->detectRoles($this->getAppContext()->editorUserId)[0];
        $customFields = $this->createLotCustomFieldLoader()->loadByRole($accessRoles, true);
        foreach ($customFields as $customField) {
            $fieldName = $dbTransformer->toDbColumn($customField->Name);
            $dataField = 'c' . $fieldName;
            $valueFormatter = ItemCustomFieldValueFormatter::new()->construct($customField);

            $gqlType = $this->detectCustomFieldGqlType($customField->Type);
            $builder->addGroupField($fieldName, $gqlType, $dataField, $valueFormatter->format(...));
            if (
                $customField->Type === Constants\CustomField::TYPE_INTEGER
                || $customField->Type === Constants\CustomField::TYPE_DECIMAL
            ) {
                $builder->addNumericAggregateFieldsForScalarType(
                    $fieldName,
                    $gqlType,
                    $dataField,
                    $valueFormatter->format(...)
                );
            }
        }
        return $builder->build(self::NAME);
    }

    protected function detectCustomFieldGqlType(int $customFieldType): ScalarType|Closure
    {
        $gqlType = match ($customFieldType) {
            Constants\CustomField::TYPE_INTEGER => $this->getTypeRegistry()->getTypeDefinition(BigIntType::NAME),
            Constants\CustomField::TYPE_DECIMAL => Type::float(),
            Constants\CustomField::TYPE_CHECKBOX => Type::boolean(),
            default => Type::string()
        };
        return $gqlType;
    }
}
