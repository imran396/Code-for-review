<?php
/**
 * SAM-10467: Implement a GraphQL nested structure for a single auction
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal;

use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\QueryPlan;
use GraphQL\Type\Definition\TypeWithFields;
use GraphQL\Type\Definition\WrappingType;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\AggregateObjectType;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverHelperCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class QueryPlanAnalyzer
 * @package Sam\Api\GraphQL\Load\Internal
 */
class QueryPlanAnalyzer extends CustomizableClass
{
    use FieldResolverHelperCreateTrait;

    protected array $referencedTypeDataFields = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function applyQueryPlan(FieldDefinition $field, QueryPlan $queryPlan): void
    {
        $this->collectReferencedTypeDependentOnDataFields($field, $queryPlan->queryPlan());
        foreach ($this->referencedTypeDataFields as $typeName => $dependentOnFields) {
            $this->referencedTypeDataFields[$typeName] = array_unique($dependentOnFields);
        }
    }

    public function getTypeDataFields(string $type): array
    {
        return $this->referencedTypeDataFields[$type] ?? [];
    }

    protected function collectReferencedTypeDependentOnDataFields(FieldDefinition $rootField, array $subFieldNodes): void
    {
        $rootFieldType = $rootField->getType();
        if (is_a($rootFieldType, WrappingType::class)) {
            $rootFieldType = $rootFieldType->getWrappedType();
        }
        if (is_a($rootFieldType, AggregateObjectType::class)) {
            return;
        }
        if (!is_a($rootFieldType, TypeWithFields::class)) {
            return;
        }

        $typeName = $rootFieldType->name;
        $this->referencedTypeDataFields[$typeName] = array_merge(
            $this->referencedTypeDataFields[$typeName] ?? [],
            $this->createFieldResolverHelper()->collectDependentOnFields($rootFieldType, $subFieldNodes)
        );

        foreach ($subFieldNodes as $subFieldName => $subFieldNode) {
            if (count($subFieldNode['fields']) > 0) {
                $fieldDefinition = $rootFieldType->getField($subFieldName);
                $this->collectReferencedTypeDependentOnDataFields($fieldDefinition, $subFieldNode['fields']);
            }
        }
    }
}
