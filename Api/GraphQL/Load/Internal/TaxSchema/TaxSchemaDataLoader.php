<?php
/**
 * SAM-10787: Create in Admin Web the "Tax Schema List" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\TaxSchema;

use Sam\Api\GraphQL\Load\Aggregate\AggregateDataField;
use Sam\Api\GraphQL\Load\Aggregate\AggregateFunction;
use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\TaxSchema\TaxSchemaReadRepository;
use Sam\Storage\ReadRepository\Entity\TaxSchema\TaxSchemaReadRepositoryCreateTrait;

/**
 * Class TaxSchemaDataLoader
 * @package Sam\Api\GraphQL\Load\Internal\TaxSchema
 */
class TaxSchemaDataLoader extends CustomizableClass
{
    use ApplicationAccessCheckerCreateTrait;
    use TaxSchemaReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function load(array $ids, array $fields, int $editorUserId, int $systemAccountId, bool $isReadOnlyDb = false): array
    {
        if (!in_array('id', $fields, true)) {
            $fields[] = 'id';
        }
        $repository = $this->createTaxSchemaReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->select($fields)
            ->filterId($ids);
        if (!$this->createApplicationAccessChecker()->isCrossDomainAdminOnMainAccountForMultipleTenant($editorUserId, $systemAccountId, true)) {
            $repository = $repository->filterAccountId($systemAccountId);
        }
        $schemas = $repository->loadRows();
        return ArrayHelper::produceIndexedArray($schemas, 'id');
    }

    public function loadList(
        TaxSchemaFilterCondition $filterCondition,
        array $orderBy,
        int $limit,
        int $offset,
        array $fields,
        int $editorUserId,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): array {
        if (!in_array('id', $fields, true)) {
            $fields[] = 'id';
        }

        $repository = $this->prepareRepository($filterCondition, $limit, $offset, $fields, $editorUserId, $systemAccountId, $isReadOnlyDb);
        $repository = $this->applyOrder($repository, $orderBy);
        $definitions = $repository->loadRows();
        return ArrayHelper::produceIndexedArray($definitions, 'id');
    }

    /**
     * @param AggregateDataField[] $fields
     */
    public function aggregate(
        TaxSchemaFilterCondition $filterCondition,
        int $limit,
        int $offset,
        array $fields,
        int $editorUserId,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): array {
        $selectFields = [];
        $groupByFields = [];
        foreach ($fields as $field) {
            if (
                $field->aggregateFunction->isNumeric()
                || $field->aggregateFunction === AggregateFunction::COUNT
            ) {
                $selectFields[$field->alias] = sprintf('%s(%s) as %s', $field->aggregateFunction->name, $field->dataField, $field->alias);
            } elseif ($field->aggregateFunction === AggregateFunction::GROUP) {
                $selectFields[$field->alias] = "{$field->dataField} as {$field->alias}";
                $groupByFields[] = $field->alias;
            }
        }
        $repository = $this->prepareRepository($filterCondition, $limit, $offset, $selectFields, $editorUserId, $systemAccountId, $isReadOnlyDb);
        foreach (array_unique($groupByFields) as $groupByField) {
            $repository->qb()->group($groupByField);
        }
        $aggregatedData = $repository->loadRows();
        return $aggregatedData;
    }

    protected function prepareRepository(
        TaxSchemaFilterCondition $condition,
        int $limit,
        int $offset,
        array $fields,
        int $editorUserId,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): TaxSchemaReadRepository {
        $repository = $this->createTaxSchemaReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterSourceTaxSchemaId(null)
            ->limit($limit)
            ->offset($offset)
            ->select($fields);

        $repository = $this->applyFilterCondition($repository, $condition);
        if ($this->createApplicationAccessChecker()->isCrossDomainAdminOnMainAccountForMultipleTenant($editorUserId, $systemAccountId, true)) {
            $repository = $repository->filterAccountId($condition->accountId);
        } else {
            $repository = $repository->filterAccountId($systemAccountId);
        }

        return $repository;
    }

    protected function applyFilterCondition(
        TaxSchemaReadRepository $repository,
        TaxSchemaFilterCondition $condition
    ): TaxSchemaReadRepository {
        $repository = $repository->likeName($condition->name->contain);
        $repository = $repository->filterName($condition->name->in);
        $repository = $repository->skipName($condition->name->notIn);

        $repository->filterGeoType($condition->geoType);
        $repository->filterAmountSource($condition->amountSource);

        $repository = $repository->likeCountry($condition->country->contain);
        $repository = $repository->filterCountry($condition->country->in);
        $repository = $repository->skipCountry($condition->country->notIn);

        $repository = $repository->likeCity($condition->city->contain);
        $repository = $repository->filterCity($condition->city->in);
        $repository = $repository->skipCity($condition->city->notIn);

        $repository = $repository->likeCounty($condition->county->contain);
        $repository = $repository->filterCounty($condition->county->in);
        $repository = $repository->skipCounty($condition->county->notIn);

        $repository = $repository->likeState($condition->state->contain);
        $repository = $repository->filterState($condition->state->in);
        $repository = $repository->skipState($condition->state->notIn);

        if ($condition->forInvoice !== null) {
            $repository->filterForInvoice($condition->forInvoice);
        }

        if ($condition->forSettlement !== null) {
            $repository->filterForSettlement($condition->forSettlement);
        }

        return $repository;
    }

    protected function applyOrder(TaxSchemaReadRepository $repository, array $order): TaxSchemaReadRepository
    {
        foreach ($order as $field => $direction) {
            $repository = $repository->order(Constants\Db::A_TAX_SCHEMA . '.' . $field, $direction === 'ASC');
        }
        return $repository;
    }
}
