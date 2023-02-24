<?php
/**
 * SAM-10844: Extend parameter filtering on auction lots, my items level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\Lot\Internal;

use Sam\Api\GraphQL\Load\Aggregate\AggregateDataField;
use Sam\Api\GraphQL\Load\Aggregate\AggregateFunction;

/**
 * Trait LotAggregateTrait
 * @package Sam\Api\GraphQL\Load\Internal\Lot\Internal
 */
trait LotAggregateTrait
{
    /** @var AggregateDataField[] */
    protected array $aggregateFields;
    protected array $groupByFields = [];

    protected function initResultFieldsMapping(): void
    {
        parent::initResultFieldsMapping();
        $this->resultFieldsMapping['category_id'] = [
            'select' => 'lic.lot_category_id',
            'join' => ['lot_item_category'],
        ];
        $this->resultFieldsMapping['category_name'] = [
            'select' => 'lc.name',
            'join' => ['lot_item_category', 'lot_category'],
        ];
        $this->resultFieldsMapping['category_level'] = [
            'select' => 'lc.level',
            'join' => ['lot_item_category', 'lot_category'],
        ];
        $this->resultFieldsMapping['category_parent_id'] = [
            'select' => 'lc.parent_id',
            'join' => ['lot_item_category', 'lot_category'],
        ];
    }

    /**
     * @param AggregateDataField[] $aggregateFields
     */
    public function setAggregateFields(array $aggregateFields): static
    {
        $this->aggregateFields = $aggregateFields;
        return $this;
    }

    protected function initJoinsMapping(): void
    {
        parent::initJoinsMapping();
        // @formatter:off
        $this->joinsMapping['lot_item_category'] =
            'LEFT JOIN `lot_item_category` lic ' .
            'ON lic.lot_item_id = ali.lot_item_id';
        $this->joinsMapping['lot_category'] =
            'LEFT JOIN `lot_category` lc ' .
            'ON lc.id = lic.lot_category_id';
    }

    protected function getResultQueries(): array
    {
        $this->addCustomFieldOptionsToMapping();
        foreach ($this->aggregateFields as $aggregateField) {
            $aggregateFunction = $aggregateField->aggregateFunction;
            if ($aggregateFunction->isNumeric()) {
                $mapping = $this->resultFieldsMapping[$aggregateField->dataField];
                $mapping['select'] = sprintf('%s(%s)', $aggregateFunction->name, $mapping['select']);
                $this->resultFieldsMapping[$aggregateField->alias] = $mapping;
            } elseif ($aggregateFunction === AggregateFunction::GROUP) {
                $this->groupByFields[] = $aggregateField->alias;
                $this->addResultSetFields([$aggregateField->alias]);
                if (
                    !isset($this->resultFieldsMapping[$aggregateField->alias])
                    && isset($this->resultFieldsMapping[$aggregateField->dataField])
                ) {
                    $this->resultFieldsMapping[$aggregateField->alias] = $this->resultFieldsMapping[$aggregateField->dataField];
                }
            } elseif ($aggregateFunction === AggregateFunction::COUNT) {
                $this->resultFieldsMapping[$aggregateField->alias] = [
                    'select' => 'COUNT(*)'
                ];
            }
            $this->addResultSetFields([$aggregateField->alias]);
        }
        return parent::getResultQueries();
    }

    protected function initializeFilterQueryParts(array $queryParts = []): array
    {
        $queryParts['limit'] = '';
        $queryParts = parent::initializeFilterQueryParts($queryParts);
        return $queryParts;
    }

    protected function initializeAggregatedListQueryParts(array $queryParts = []): array
    {
        $groupByFields = array_map(static fn(string $field) => "`{$field}`", $this->groupByFields);
        $queryParts['group'] = implode(',', $groupByFields);
        $queryParts['order'] = '';
        if ($this->limit) {
            $queryParts['limit'] = 'LIMIT ' . $this->offset . ', ' . $this->limit;
        }
        $queryParts = parent::initializeAggregatedListQueryParts($queryParts);
        return $queryParts;
    }
}
