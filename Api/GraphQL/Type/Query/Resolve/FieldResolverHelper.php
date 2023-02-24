<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 22, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve;

use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\TypeWithFields;
use RuntimeException;
use Sam\Core\Service\CustomizableClass;

/**
 * Class FieldResolverHelper
 * @package Sam\Api\GraphQL\Type\Query\Resolve
 */
class FieldResolverHelper extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getFieldResolver(FieldDefinition $fieldDefinition): ?FieldResolverInterface
    {
        $resolver = $fieldDefinition->config['resolver'] ?? null;
        if ($resolver && !is_a($resolver, FieldResolverInterface::class)) {
            throw new RuntimeException('Field resolver must be of type FieldResolverInterface got: ' . get_debug_type($resolver));
        }

        return $resolver;
    }

    /**
     * @param TypeWithFields $rootType
     * @param array $fieldNodes
     * @return string[]
     */
    public function collectDependentOnFields(TypeWithFields $rootType, array $fieldNodes): array
    {
        $fields = [];
        $complexFields = [];
        foreach ($fieldNodes as $fieldName => $fieldNode) {
            $resolver = $this->getFieldResolver($rootType->getField($fieldName));
            if ($resolver) {
                $complexFields[] = $resolver->dependentOnDataFields($fieldNode['fields']);
            } else {
                $fields[] = $fieldName;
            }
        }
        $fields = array_merge($fields, ...$complexFields);
        return $fields;
    }

    public function hasEnoughDataToResolve(ResolveInfo $resolveInfo, array $data): bool
    {
        if (!is_a($resolveInfo->returnType, TypeWithFields::class)) {
            return true;
        }
        $dependentOnFields = $this->collectDependentOnFields($resolveInfo->returnType, $resolveInfo->lookAhead()->queryPlan());
        return !array_diff($dependentOnFields, array_keys($data));
    }
}
