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

namespace Sam\Api\GraphQL\Type\Query\Resolve\Aggregate;

use GraphQL\Type\Definition\FieldDefinition;
use RuntimeException;
use Sam\Api\GraphQL\Load\Aggregate\AggregateDataField;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\AggregateObjectType;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AggregateFieldResolverHelper
 * @package Sam\Api\GraphQL\Type\Query\Resolve\Aggregate
 */
class AggregateFieldResolverHelper extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AggregateObjectType $rootType
     * @param array $fieldNodes
     * @return AggregateDataField[]
     */
    public function collectAggregateDependentOnFields(AggregateObjectType $rootType, array $fieldNodes): array
    {
        $dataFields = [];
        foreach ($fieldNodes as $fieldName => $fieldNode) {
            $fieldDefinition = $rootType->getField($fieldName);
            $dataFields[] = $this->getFieldResolver($fieldDefinition)->dependentOnAggregateDataFields($fieldNode['fields']);
        }
        $dataFields = array_merge(...$dataFields);
        return $dataFields;
    }

    public function getFieldResolver(FieldDefinition $fieldDefinition): AggregateFieldResolverInterface
    {
        $resolver = $fieldDefinition->config['resolver'] ?? null;
        if (!$resolver) {
            throw new RuntimeException("Resolver for field {$fieldDefinition->getName()} is not specified");
        }
        if (!is_a($resolver, AggregateFieldResolverInterface::class)) {
            throw new RuntimeException('Field resolver must be of type AggregateFieldResolverInterface got: ' . get_debug_type($resolver));
        }

        return $resolver;
    }
}
