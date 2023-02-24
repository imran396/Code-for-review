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

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use RuntimeException;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Load\Aggregate\AggregateDataField;
use Sam\Api\GraphQL\Load\Aggregate\AggregateFunction;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\AggregateObjectType;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ConfigurableAggregateFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\Aggregate
 */
class ConfigurableAggregateFieldResolver extends CustomizableClass implements AggregateFieldResolverInterface
{
    use AggregateFieldResolverHelperCreateTrait;

    protected string $schemaFieldName;
    protected ?AggregateFunction $aggregateFunction;
    protected array $dependentOnDataFields;
    protected Type|Closure $type;
    protected ?Closure $resolveFn;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        string $schemaFieldName,
        Type|Closure $type,
        ?AggregateFunction $aggregateFunction = null,
        array $dependentOnDataFields = [],
        ?Closure $resolveFn = null
    ): static {
        $this->schemaFieldName = $schemaFieldName;
        $this->type = $type;
        $this->aggregateFunction = $aggregateFunction;
        $this->dependentOnDataFields = $dependentOnDataFields;
        $this->resolveFn = $resolveFn;
        return $this;
    }

    public function dependentOnAggregateDataFields(array $referencedFieldNodes): array
    {
        if (!$this->aggregateFunction) {
            $type = Schema::resolveType($this->type);
            if (is_a($type, AggregateObjectType::class)) {
                return $this->createAggregateFieldResolverHelper()->collectAggregateDependentOnFields($type, $referencedFieldNodes);
            }
            throw new RuntimeException("AggregateFunction for field {$this->schemaFieldName} is not specified");
        }

        return array_map(
            fn(string $dataField) => AggregateDataField::new()->construct(
                $dataField,
                $this->aggregateFunction,
                $this->makeAggregateDataFieldAlias($dataField)
            ),
            $this->dependentOnDataFields
        );
    }

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): mixed
    {
        $dependentOnValues = $objectValue;
        if ($this->dependentOnDataFields) {
            $dependentOnValues = [];
            foreach ($this->dependentOnDataFields as $dataField) {
                $alias = $this->makeAggregateDataFieldAlias($dataField);
                $dependentOnValues[$dataField] = $objectValue[$alias] ?? null;
            }
        }

        if ($this->resolveFn) {
            return call_user_func($this->resolveFn, $dependentOnValues, $args, $appContext, $info);
        }

        $type = Schema::resolveType($this->type);
        if (is_a($type, AggregateObjectType::class)) {
            return $dependentOnValues;
        }

        $dependentOnDataField = (string)reset($this->dependentOnDataFields);
        if (array_key_exists($dependentOnDataField, $dependentOnValues)) {
            return $dependentOnValues[$dependentOnDataField];
        }

        throw new RuntimeException("No Data for {$this->schemaFieldName} field");
    }

    protected function makeAggregateDataFieldAlias(string $dataField): string
    {
        if($dataField === '*') {
            return $this->schemaFieldName;
        }
        return $this->schemaFieldName . '_' . $dataField;
    }
}
