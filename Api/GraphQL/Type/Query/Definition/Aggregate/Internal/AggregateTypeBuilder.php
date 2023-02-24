<?php
/**
 * SAM-10844: Extend parameter filtering on auction lots, my items level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 21, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Aggregate\Internal;

use Closure;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use InvalidArgumentException;
use Sam\Api\GraphQL\Load\Aggregate\AggregateFunction;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\AggregateObjectType;
use Sam\Api\GraphQL\Type\Query\Definition\Common\Scalar\BigIntType;
use Sam\Api\GraphQL\Type\Query\Resolve\Aggregate\AggregateFieldResolverInterface;
use Sam\Api\GraphQL\Type\Query\Resolve\Aggregate\ConfigurableAggregateFieldResolver;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverInterface;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AggregateTypeBuilder
 * @package Sam\Api\GraphQL\Type\Query\Definition\Aggregate
 */
class AggregateTypeBuilder extends CustomizableClass
{
    protected array $aggFields = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function addField(string $fieldName, Type|Closure $type, ?AggregateFieldResolverInterface $resolver = null): static
    {
        $this->aggFields[$fieldName] = [
            'type' => $type,
            'resolver' => $resolver ?? ConfigurableAggregateFieldResolver::new()->construct($fieldName, $type),
        ];
        return $this;
    }

    public function addCountField(): static
    {
        $intType = Type::int();
        return $this->addField(
            'count',
            $intType,
            ConfigurableAggregateFieldResolver::new()->construct('count', $intType, AggregateFunction::COUNT, ['*'])
        );
    }

    /**
     * @param string $fieldName
     * @param ScalarType|Closure $type
     * @param string|null $dataField
     * @param Closure|null $resolveFn
     * @param AggregateFunction[] $aggregateFunctions
     * @return static
     */
    public function addNumericAggregateFieldsForScalarType(
        string $fieldName,
        ScalarType|Closure $type,
        ?string $dataField = null,
        ?Closure $resolveFn = null,
        ?array $aggregateFunctions = null
    ): static {
        $fieldType = Schema::resolveType($type);
        if (
            !$fieldType instanceof ScalarType
            || !$this->isNumericType($fieldType)
        ) {
            throw new InvalidArgumentException('Type must be a numeric type got: ' . get_debug_type($fieldType));
        }
        $aggregateFunctions ??= AggregateFunction::numericFunctions();
        foreach ($aggregateFunctions as $aggregateFunction) {
            if (!$aggregateFunction->isNumeric()) {
                throw new InvalidArgumentException('Allowed only numeric aggregate functions');
            }
            $aggFieldName = $fieldName . '_' . strtolower($aggregateFunction->name);
            $this->addField(
                $aggFieldName,
                $fieldType,
                ConfigurableAggregateFieldResolver::new()->construct(
                    $aggFieldName,
                    $fieldType,
                    $aggregateFunction,
                    [$dataField ?? $fieldName],
                    $resolveFn
                )
            );
        }
        return $this;
    }

    public function addGroupField(
        string $fieldName,
        ScalarType|EnumType|Closure $type,
        string|array|null $dataFields = null,
        ?Closure $resolveFn = null,
    ): static {
        $fieldType = Schema::resolveType($type);
        if (
            !$fieldType instanceof ScalarType
            && !$fieldType instanceof EnumType
        ) {
            throw new InvalidArgumentException(
                'Type must be a ScalarType got: ' . get_debug_type($fieldType)
            );
        }
        return $this->addField(
            $fieldName,
            $type,
            ConfigurableAggregateFieldResolver::new()->construct(
                $fieldName,
                $fieldType,
                AggregateFunction::GROUP,
                $dataFields === null ? [$fieldName] : (array)$dataFields,
                $resolveFn
            )
        );
    }

    public function addAggregateFieldsForCompositeType(ObjectType|Closure $type): static
    {
        $sourceType = Schema::resolveType($type);
        if (!is_a($sourceType, ObjectType::class)) {
            throw new InvalidArgumentException('SourceType must be of type ObjectType got: ' . get_debug_type($sourceType));
        }
        $sourceFields = $sourceType->getFields();
        foreach ($sourceFields as $sourceFieldName => $fieldDefinition) {
            $sourceFieldType = $fieldDefinition->getType();
            if (
                ($fieldDefinition->config['args'] ?? false)
                || (
                    !$sourceFieldType instanceof ScalarType
                    && !$sourceFieldType instanceof EnumType
                )
            ) {
                continue;
            }

            /** @var ?FieldResolverInterface $resolver */
            $resolver = $fieldDefinition->config['resolver'] ?? null;
            $dependentOnFields = $resolver?->dependentOnDataFields([]) ?? [$sourceFieldName];
            $this->addGroupField($sourceFieldName, $sourceFieldType, $dependentOnFields);
            if (
                $this->isNumericType($sourceFieldType)
                && count($dependentOnFields) === 1
            ) {
                $this->addNumericAggregateFieldsForScalarType($sourceFieldName, $sourceFieldType, reset($dependentOnFields));
            }
        }
        return $this;
    }

    public function build(string $name): AggregateObjectType
    {
        $type = new AggregateObjectType([
            'name' => $name,
            'fields' => $this->aggFields
        ]);
        return $type;
    }

    protected function isNumericType(Type $fieldType): bool
    {
        return in_array($fieldType->name, [Type::INT, Type::FLOAT, BigIntType::NAME], true);
    }
}
