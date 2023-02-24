<?php
/**
 * Helper class for adjoining (next/previous) lots searching
 *
 * Admin: auction lot edit
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Dec 29, 2013
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * @package       com.swb.sam2.api
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * As example we have the next query for lot list:
 * SELECT ... ORDER BY li.name DESC, ali.order ASC;
 * 1) We find values of current lot ($lotItemId), which are used for ordering in list query:
 * SELECT li.name AS li_name, ali.order AS ali_order ... WHERE li.id = $lotItemId;
 * $name = $result['li_name'];
 * $order = $result['ali_order'];
 * 2.1) To find the next lot:
 * SELECT li.id, ... FROM ... WHERE li.name < $name OR (li.name = $name AND ali.order > $order) ORDER BY li.name DESC, ali.order ASC LIMIT 1;
 * 2.2) To find the previous lot:
 * SELECT li.id, ... FROM ... WHERE li.name > $name OR (li.name = $name AND ali.order < $order) ORDER BY li.name ASC, ali.order DESC LIMIT 1;
 *
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\Assigned\Load;

use QMySqli5DatabaseResult;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\Lot\Search\Query\Build\Helper\LotSearchCustomFieldQueryBuilderHelperCreateTrait;
use Sam\Lot\Search\Query\Build\Helper\LotSearchQueryBuilderHelperCreateTrait;
use Sam\View\Admin\Form\AuctionLotListForm\Assigned\Load\Query\AssignedAuctionLotListQueryBuilderCreateTrait;
use Sam\View\Admin\Form\AuctionLotListForm\Assigned\Load\Query\AssignedAuctionLotListQueryCriteria;

/**
 * Class AssignedAuctionLotAdjoiningDataLoader
 * @package Sam\View\Admin\Form\AuctionLotListForm\Assigned\Load
 */
class AssignedAuctionLotAdjoiningDataLoader extends CustomizableClass
{
    use AssignedAuctionLotListDataLoaderCreateTrait;
    use AssignedAuctionLotListQueryBuilderCreateTrait;
    use BaseCustomFieldHelperAwareTrait;
    use DbConnectionTrait;
    use LotSearchCustomFieldQueryBuilderHelperCreateTrait;
    use LotSearchQueryBuilderHelperCreateTrait;

    protected const LOT_ITEM_ID_ALIAS = 'lot_id';

    /**
     * Class instantiation method
     * @return static or customized class extending MySearch_AdjoiningLotQuery
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return adjoining lot id
     * @param AssignedAuctionLotListQueryCriteria $criteria Filtering criteria
     * @param int $currentLotId Current lot item id (lot_item.id)
     * @param bool $findNext true - for searching next lot, false - for the previous
     * @param array $lotCustomFields Using lot item custom fields
     * @return int|null lot_item.id
     */
    public function findLotId(
        AssignedAuctionLotListQueryCriteria $criteria,
        int $currentLotId,
        bool $findNext,
        array $lotCustomFields = []
    ): ?int {
        $resultQuery = $this->buildQuery($criteria, $currentLotId, $findNext, $lotCustomFields) . ' LIMIT 1';
        log_traceQuery($resultQuery);
        $dbResult = $this->query($resultQuery);
        $adjoiningLotItemId = null;
        if ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $adjoiningLotItemId = Cast::toInt($row[self::LOT_ITEM_ID_ALIAS], Constants\Type::F_INT_POSITIVE);
        }
        return $adjoiningLotItemId;
    }

    /**
     * Return query parts to select adjoining lot
     * @param AssignedAuctionLotListQueryCriteria $criteria
     * @param int|null $lotItemId lot_item.id of current lot
     * @param bool $findNext true - for searching next lot, false - for the previous
     * @param array $lotCustomFields Lot item custom fields
     * @return string
     * Notes:
     * We parse ordering clause to get fields, which are used for ordering.
     * We get values of these fields for current lot.
     * We build query in which we are comparing found values with respective fields,
     * so we can determine which lots are next/previous.
     */
    protected function buildQuery(AssignedAuctionLotListQueryCriteria $criteria, ?int $lotItemId, bool $findNext, array $lotCustomFields = []): string
    {
        $queryBuilder = $this->createAssignedAuctionLotListQueryBuilder();
        $orderByExpression = $queryBuilder->buildOrderByExpression($criteria);
        $criteria->orderBy = null;
        $orderByDisassembled = $this->disassembleOrderByExpression($orderByExpression, $lotCustomFields);
        $orderFieldsValues = $this->loadCurrentLotOrderFieldsValues($criteria, $lotItemId, $orderByDisassembled, $lotCustomFields);

        /**
         * Build query parts for selecting adjoining lots (next or previous)
         */
        $selectCustomFields = [];
        $whereClauses = [];
        $orderClauses = [];
        $equalityComparingExpression = '';
        foreach ($orderByDisassembled as $orderItemData) {
            if ($orderItemData['customField']) {
                $selectCustomFields[] = $orderItemData['customField'];
            }

            $adjoiningLotDirection = $this->detectAdjoiningLotFieldDirection($orderItemData['direction'], $findNext);
            $inequalitySign = ($adjoiningLotDirection === 'asc') ? '>' : '<';

            $columnExpr = $this->makeColumnExpression($orderItemData);
            $alias = $orderItemData['alias'];
            $currentLotFieldValue = $orderFieldsValues[$alias];
            $defaultInequalityValue = is_numeric($currentLotFieldValue) ? 0 : "''";

            $inequalityCompareExpression = sprintf(
                'IFNULL(%s, %s) %s %s',
                $columnExpr,
                $defaultInequalityValue,
                $inequalitySign,
                $this->escape($currentLotFieldValue ?? '')
            );

            $whereClauses[] = '(' . $equalityComparingExpression . $inequalityCompareExpression . ')';
            $orderClauses[] = $orderItemData['field'] . ' ' . $adjoiningLotDirection;

            // Equal comparing expression of ordering fields, which were previously added to the query
            $equalityComparingExpression .= sprintf(
                '%s %s AND ',
                $columnExpr,
                $currentLotFieldValue === null ? 'IS NULL' : '= ' . $this->escape($currentLotFieldValue)
            );
        }
        $criteria->orderBy = implode(', ', $orderClauses);

        $sql = $queryBuilder
            ->build($criteria, $lotCustomFields, $selectCustomFields)
            ->addWhere('(' . implode(' OR ', array_reverse($whereClauses)) . ')')
            ->addSelect('li.id AS ' . self::LOT_ITEM_ID_ALIAS)
            ->getSql();
        return $sql;
    }

    /**
     * @param array $disassembledOrderByClause
     * @return string
     */
    protected function makeColumnExpression(array $disassembledOrderByClause): string
    {
        $field = $disassembledOrderByClause['field'];
        $customField = $disassembledOrderByClause['customField'];
        if ($customField) {
            $expression = $this->createLotSearchCustomFieldQueryBuilderHelper()->getCustomFieldSelectExpr($customField);
        } elseif ($field === 'image_count') {
            $mapping = $this->createAssignedAuctionLotListQueryBuilder()->getResultFieldsMapping();
            $expression = $mapping['image_count'];  // sub-query should be used in WHERE clause
        } else {
            $expression = $field;
        }
        return $expression;
    }

    /**
     * @param string $currentDirection
     * @param bool $findNext
     * @return string
     */
    protected function detectAdjoiningLotFieldDirection(string $currentDirection, bool $findNext): string
    {
        if ($findNext) {
            $adjoiningLotDirection = $currentDirection;
        } else {
            $adjoiningLotDirection = $currentDirection === 'asc' ? 'desc' : 'asc';
        }
        return $adjoiningLotDirection;
    }

    /**
     * @param AssignedAuctionLotListQueryCriteria $criteria
     * @param int|null $lotItemId
     * @param array $orderByDisassembled
     * @param array $lotCustomFields
     * @return array
     */
    protected function loadCurrentLotOrderFieldsValues(
        AssignedAuctionLotListQueryCriteria $criteria,
        ?int $lotItemId,
        array $orderByDisassembled,
        array $lotCustomFields
    ): array {
        $selectCustomFields = [];
        $selectRegularFields = [];
        foreach ($orderByDisassembled as $orderClause) {
            if ($orderClause['customField']) {
                $selectCustomFields[] = $orderClause['customField'];
            } elseif ($orderClause['alias'] !== 'image_count') {
                $selectRegularFields[] = $orderClause['field'] . ' AS ' . $orderClause['alias'];
            }
        }

        $sql = $this->createAssignedAuctionLotListQueryBuilder()
            ->build($criteria, $lotCustomFields, $selectCustomFields)
            ->addSelect($selectRegularFields)
            ->addWhere('li.id = ' . $this->escape($lotItemId))
            ->getSql();

        /**
         * Get current lot values, which are used for ordering
         */
        $dbResult = $this->query($sql);
        $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
        $values = [];
        foreach ($orderByDisassembled as $orderClause) {
            $values[$orderClause['alias']] = $row[$orderClause['alias']];
        }
        return $values;
    }

    /**
     * @param string $orderByExpression
     * @param array $lotCustomFields
     * @return array
     */
    protected function disassembleOrderByExpression(string $orderByExpression, array $lotCustomFields): array
    {
        $customFieldsIndexedByAlias = [];
        $customFieldAliases = [];
        foreach ($lotCustomFields as $lotCustomField) {
            $customFieldAlias = $this->getBaseCustomFieldHelper()->makeFieldAlias($lotCustomField->Name);
            $customFieldsIndexedByAlias[$customFieldAlias] = $lotCustomField;
            $customFieldAliases[] = $customFieldAlias;
        }

        $inputOrderClauses = $this->parseOrderByExpression($orderByExpression);
        if (count($inputOrderClauses) === 1) {
            /**
             * If single column passed, it may be sort order alias, that currently is passed in Assigned Auction Lot datagrid of Auction Lot List page
             * Then we try to get order expression for alias and pass it to later processing
             */
            $alias = $inputOrderClauses[0]['alias'];
            $direction = $inputOrderClauses[0]['direction'];
            $mapping = $this->createAssignedAuctionLotListDataLoader()
                ->setLotCustomFields($lotCustomFields)
                ->getOrderFieldsMapping();
            if (isset($mapping[$alias])) {
                $orderExpression = $mapping[$alias][$direction];
                $inputOrderClauses = $this->parseOrderByExpression($orderExpression);
            }
        }
        $inputOrderClauses[] = [
            'alias' => 'ali.id',
            'direction' => 'asc'
        ];

        $orderByClauseData = [];

        $dbTransformer = DbTextTransformer::new();
        $resultSetMapping = $this->createAssignedAuctionLotListQueryBuilder()->getResultFieldsMapping();
        foreach ($inputOrderClauses as $inputOrderClause) {
            $fieldName = $dbTransformer->filterDbColumn($inputOrderClause['alias']);
            if (!$this->isAvailableColumn($fieldName, $customFieldAliases)) {
                log_error(
                    "Invalid ordering column name by 'order'"
                    . composeSuffix(
                        [
                            'passed' => $fieldName,
                            'param' => $orderByExpression
                        ]
                    )
                );
                continue;
            }
            $alias = $dbTransformer->toDbColumn($fieldName);
            if (array_key_exists($alias, $resultSetMapping)) {
                $fieldName = $resultSetMapping[$alias];
            }
            $orderByClauseData[] = [
                'field' => $fieldName,
                'alias' => $alias,
                'direction' => $inputOrderClause['direction'],
                'customField' => $customFieldsIndexedByAlias[$alias] ?? null
            ];
        }
        return $orderByClauseData;
    }

    /**
     * Parse ORDER BY expression and extract field name, direction
     * Field name may be alias of custom field select sub-query
     *
     * @param string $orderByExpression
     * @return array
     */
    protected function parseOrderByExpression(string $orderByExpression): array
    {
        $orderClauses = [];
        $rawData = explode(',', $orderByExpression);
        foreach ($rawData as $rawOrderBy) {
            $components = explode(' ', trim($rawOrderBy));
            $alias = $components[0];
            $direction = 'asc';
            if (
                isset($components[1])
                && strtolower($components[1]) === 'desc'
            ) {
                $direction = 'desc';
            }
            $orderClauses[] = [
                'alias' => $alias,
                'direction' => $direction
            ];
        }
        return $orderClauses;
    }

    /**
     * Simple way to validate ordering columns passed via GET param
     * @param string $column
     * @param string[] $customFieldAliases
     * @return bool
     */
    protected function isAvailableColumn(string $column, array $customFieldAliases): bool
    {
        $column = preg_replace("/[^\w_.]+/", '', $column);
        $resultSetMapping = $this->createAssignedAuctionLotListQueryBuilder()->getResultFieldsMapping();
        $is = in_array($column, $resultSetMapping, true)
            || array_key_exists($column, $resultSetMapping)
            || in_array($column, $customFieldAliases, true);
        return $is;
    }
}
