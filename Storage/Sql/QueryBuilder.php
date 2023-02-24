<?php

/**
 * Parent class for entity repositories
 *
 * SAM-3676: Repository approach integration
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           17 Feb, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Sql;

use InvalidArgumentException;
use RuntimeException;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\ClearableInterface;
use Sam\Core\Service\CustomizableClass;

/**
 * Class QueryBuilder
 * @package Sam\Storage\Repository
 */
class QueryBuilder extends CustomizableClass implements ClearableInterface
{
    /**
     * Only for escaping
     * TODO: replace qcodo code and add own escape(), then remove DbConnectionTrait from here.
     * QueryBuilder class shouldn't communicate with DB layer
     */
    use DbConnectionTrait;

    public const OR_OPERATOR = 'OR';
    public const AND_OPERATOR = 'AND';

    /**
     * @var string table alias is unique
     */
    protected string $alias = '';
    /**
     * @var int sequence number of fetched portion of data
     */
    protected int $chunkNum = 0;
    /**
     * @var int|null size of portions for fetched data per request
     */
    protected ?int $chunkSize = null;
    /**
     * If filter property contains "null", we also consider it in filtering "IS NULL" condition
     * @var array filtering values
     */
    protected array $filter = [];
    /**
     * @var array filtering values by Between
     */
    protected array $filterBetween = [];
    /**
     * @var array filtering values by Inequality
     */
    protected array $filterInequality = [];
    /**
     * @var string[] grouping columns
     */
    protected array $group = [];
    /**
     * @var string[] having columns
     */
    protected array $having = [];
    /**
     * @var bool
     */
    protected bool $isDistinct = false;
    /**
     * @var string[]
     */
    protected array $inline = [];
    /**
     * @var string[] necessary inner join. Has bigger priority over left join
     */
    protected array $innerJoin = [];
    /**
     * Mapping for join clauses in concrete repositories, should be started with "JOIN"
     * @var string[]
     */
    protected array $joins = [];
    /**
     * @var string[] necessary left joins
     */
    protected array $leftJoin = [];
    /**
     * @var array filter like values
     */
    protected array $like = [];
    protected string $likeConjunctionOperator = self::OR_OPERATOR;
    protected ?int $limit = null;
    protected ?int $offset = null;
    /**
     * @var array ordering info
     */
    protected array $order = [];
    /**
     * @var array define concrete result set of fields
     */
    protected array $select = [];
    /**
     * @var array skip values
     */
    protected array $skip = [];
    /**
     * @var string DB table name
     */
    protected string $table = '';
    /**
     * Explicitly define result query
     * @var string
     */
    protected string $resultQuery = '';
    /**
     * Explicitly define count query
     * @var string
     */
    protected string $countQuery = '';

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->clear();
        return $this;
    }

    /**
     * Clear all filtering and ordering requirements. Set options to default
     * @return static
     */
    public function clear(): static
    {
        $this->dropAlias();
        $this->dropChunkNum();
        $this->dropChunkSize();
        $this->filter = [];
        $this->filterBetween = [];
        $this->filterInequality = [];
        $this->group = [];
        $this->having = [];
        $this->inline = [];
        $this->innerJoin = [];
        $this->enableDistinct(false);
        $this->setJoins([]);
        $this->leftJoin = [];
        $this->like = [];
        $this->setLikeConjunctionOperator(self::OR_OPERATOR);
        $this->dropLimit();
        $this->dropOffset();
        $this->order = [];
        $this->select([]);
        $this->skip = [];
        $this->dropTable();
        return $this;
    }

    /**
     * @param string $query
     * @param mixed $value
     * @return static
     */
    public function filterSubquery(string $query, mixed $value): static
    {
        $this->filterArray($query, $value);
        return $this;
    }

    /**
     * @param string $condition
     * @return static
     */
    public function inlineCondition(string $condition): static
    {
        if (trim($condition)) {
            $this->inline[] = '(' . $condition . ')';
        }
        return $this;
    }

    /**
     * @param string $query
     * @param string $value
     * @return static
     */
    public function likeSubquery(string $query, string $value): static
    {
        $this->like($query, $value);
        return $this;
    }

    /**
     * @param int|null $limit
     * @return static
     */
    public function limit(?int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param int|null $offset
     * @return static
     */
    public function offset(?int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param string|string[] $resultSet
     * @return static
     */
    public function addSelect(string|array $resultSet): static
    {
        $resultSet = (array)$resultSet;
        $this->select = array_merge($this->select, $resultSet);
        $this->select = array_unique($this->select);
        return $this;
    }

    /**
     * @param string $query
     * @param mixed $value
     * @return static
     */
    public function skipSubquery(string $query, mixed $value): static
    {
        $this->skipArray($query, $value);
        return $this;
    }

    /**
     * Define filter array of values
     * @param string $column
     * @param mixed $value
     * @return static
     */
    public function filterArray(string $column, mixed $value): static
    {
        $this->filter[$column] = $this->makeArray($value);
        return $this;
    }

    /**
     * Define inequality filter
     * @param string $column
     * @param mixed $value
     * @param string $inequality
     * @return static
     */
    public function filterInequality(string $column, mixed $value, string $inequality): static
    {
        if (!in_array($inequality, ['<', '>', '<=', '>='])) {
            throw new RuntimeException('Wrong inequality sign passed: ' . $inequality);
        }
        if ($value === null) {
            $value = 0;
        }
        $this->filterInequality[$column][] = [$value, $inequality];
        return $this;
    }

    /**
     * Define between filter
     * @param string $column
     * @param int|float|string $from
     * @param int|float|string $to
     * @param bool $not
     * @return static
     */
    public function between(string $column, int|float|string $from, int|float|string $to, bool $not = false): static
    {
        $this->filterBetween[$column][] = [$from, $to, $not];
        return $this;
    }

    /**
     * @param string $table
     * @param string $condition
     * @return $this
     */
    public function extendJoinCondition(string $table, string $condition): static
    {
        $this->joins[$table] .= ' ' . $condition;
        return $this;
    }

    /**
     * Should be overwritten in concrete repositories
     * @return array
     */
    public function getConditions(): array
    {
        $conditions = [];
        foreach ($this->filter as $column => $value) {
            $conditions[] = $this->getFilterConditionForArray($column);
        }
        foreach ($this->skip as $column => $value) {
            $conditions[] = $this->getSkipConditionForArray($column);
        }
        foreach ($this->filterInequality as $column => $value) {
            $conditions[] = $this->getFilterConditionForInequality($column);
        }
        $conditions = array_merge($conditions, $this->getBetweenConditions());
        $conditions = array_merge($conditions, $this->getLikeConditions());
        $conditions = array_merge($conditions, $this->inline);
        $conditions = array_filter($conditions);
        return $conditions;
    }

    /**
     * Return filter condition by list passed in array
     * E.g: filter [null, 'T', 'L'] will produce conditions like "alias.column IS NULL OR alias.column IN ('T', 'L')"
     * @param string $column
     * @return string
     */
    public function getFilterConditionForArray(string $column): string
    {
        $cond = '';
        $values = $this->getFilterValue($column);
        if ($values) {
            $conditions = [];
            // search for "null" value
            foreach ($values as $key => $value) {
                if ($value === null) {
                    if (str_contains($column, '.')) {
                        $conditions[] = $column . ' IS NULL';
                    } else {
                        $conditions[] = $this->getAlias() . '.' . $column . ' IS NULL';
                    }
                    unset($values[$key]);
                }
            }
            if ($values) {
                foreach ($values as $i => $value) {
                    $values[$i] = $this->escape($value);
                }
                $list = implode(',', $values);
                if (str_contains($column, '.')) {
                    $template = count($values) === 1
                        ? "%s = %s"
                        : "%s IN (%s)";
                    $conditions[] = sprintf($template, $column, $list);
                } else {
                    $template = count($values) === 1
                        ? "`%s`.`%s` = %s"
                        : "`%s`.`%s` IN (%s)";
                    $conditions[] = sprintf($template, $this->getAlias(), $column, $list);
                }
            }
            $cond = implode(' OR ', $conditions);
            $cond = count($conditions) > 1 ? '(' . $cond . ')' : $cond;
        }
        return $cond;
    }

    /**
     * Return filter condition by Inequality
     * @param string $column
     * @return string
     */
    public function getFilterConditionForInequality(string $column): string
    {
        $conditions = [];
        $values = $this->getFilterInequalityValue($column);
        foreach ($values as $value) {
            if ($value) {
                [$value, $inequality] = $value;
                $value = $this->escape($value);
                if (str_contains($column, '.')) {
                    $template = "%s " . $inequality . " %s";
                    $conditions[] = sprintf($template, $column, $value);
                } else {
                    $template = "`%s`.`%s` " . $inequality . " %s";
                    $conditions[] = sprintf($template, $this->getAlias(), $column, $value);
                }
            }
        }
        return implode(' AND ', $conditions);
    }

    /**
     * Return filter condition by between
     * @param string $column
     * @return string
     */
    public function getBetweenCondition(string $column): string
    {
        $conditions = [];
        $values = $this->getBetweenValue($column);
        foreach ($values as $value) {
            if ($value) {
                [$from, $to, $not] = $value;
                $column = (str_contains($column, '.')) ? $column : $this->getAlias() . '.' . $column;
                $not = $not ? ' NOT' : '';
                $conditions[] = $column . $not . ' BETWEEN ' . $this->escape($from) . ' AND ' . $this->escape($to);
            }
        }
        return implode(' AND ', $conditions);
    }

    /**
     * Return filter conditions by between
     * @return array
     */
    public function getBetweenConditions(): array
    {
        $conditions = [];
        foreach ($this->filterBetween as $column => $range) {
            $conditions[] = $this->getBetweenCondition($column);
        }

        return $conditions;
    }

    /**
     * Return filtering value by column name
     * @param string $column
     * @return array
     */
    public function getFilterValue(string $column): array
    {
        $value = $this->filter[$column] ?? [];
        return $value;
    }

    /**
     * Return filtering inequality value by column name
     * @param string $column
     * @return array
     */
    public function getFilterInequalityValue(string $column): array
    {
        $props = $this->filterInequality[$column] ?? [];
        return $props;
    }

    /**
     * Return filtering inequality value by column name
     * @param string $column
     * @return array
     */
    public function getBetweenValue(string $column): array
    {
        $props = $this->filterBetween[$column] ?? [];
        return $props;
    }

    /**
     * @param string $column
     * @return string
     */
    public function getLikeCondition(string $column): string
    {
        $cond = '';
        $value = $this->getLikeValue($column);
        $valueContent = trim($value, '% ');
        if ($valueContent) {
            $cond = sprintf('%s LIKE %s', $column, $this->escape($value));
        }
        return $cond;
    }

    /**
     * @return array
     */
    public function getLikeConditions(): array
    {
        $conditions = [];
        foreach ($this->like as $column => $value) {
            $conditions[] = $this->getLikeCondition($column);
        }
        $conditions = array_filter($conditions);
        $operator = ' ' . $this->likeConjunctionOperator . ' ';
        $groupConditions = implode($operator, $conditions);
        $conditions = count($conditions) > 1 ? ['(' . $groupConditions . ')'] : $conditions;
        return $conditions;
    }

    /**
     * @param string $operator
     * @return static
     */
    public function setLikeConjunctionOperator(string $operator): static
    {
        $this->likeConjunctionOperator = $operator;
        return $this;
    }

    /**
     * @param string $column
     * @return string
     */
    public function getLikeValue(string $column): string
    {
        $value = $this->like[$column] ?? '';
        return $value;
    }

    /**
     * Return skip condition
     * @param string $column
     * @return string
     */
    public function getSkipConditionForArray(string $column): string
    {
        $condition = '';
        $expressions = [];
        $values = $this->getSkipValue($column);
        if ($values) {
            if (str_contains($column, '.')) {
                $col = $column;
            } else {
                $col = $this->getAlias() . '.' . $column;
            }

            $shouldSkipNull = in_array(null, $values, true);
            if ($shouldSkipNull) {
                foreach ($values as $key => $value) {
                    if ($value === null) {
                        $expressions[] = $col . ' IS NOT NULL ';
                        unset($values[$key]);
                    }
                }
                $logicOp = ' AND ';
            } else {
                $expressions[] = $col . ' IS NULL';
                $logicOp = ' OR ';
            }

            if ($values) {
                $list = implode(',', $this->escapeArray($values));
                $expressions[] = $col . ' NOT IN (' . $list . ')';
            }
            if (count($expressions) > 1) {
                $condition = '(' . implode($logicOp, $expressions) . ')';
            } else {
                $condition = $expressions[0];
            }
        }
        return $condition;
    }

    /**
     * Return skipping value by column name
     * @param string $column
     * @return array
     */
    public function getSkipValue(string $column): array
    {
        $value = $this->skip[$column] ?? [];
        return $value;
    }

    /**
     * Define GROUP BY clause
     * @param string $column
     * @return static
     */
    public function group(string $column): static
    {
        $this->group[] = $column;
        return $this;
    }

    /**
     * Define HAVING clause
     * @param string $condition
     * @return static
     */
    public function having(string $condition): static
    {
        $this->having[] = $condition;
        return $this;
    }

    /**
     * Left Join table
     * @param string $table
     * @return static
     */
    public function join(string $table): static
    {
        if (!in_array($table, $this->leftJoin, true)) {
            $this->leftJoin[] = $table;
        }
        return $this;
    }

    /**
     * Inner Join table
     * @param string $table
     * @return static
     */
    public function innerJoin(string $table): static
    {
        if (!in_array($table, $this->innerJoin, true)) {
            $this->innerJoin[] = $table;
        }
        return $this;
    }

    /**
     * Define LIKE filter condition
     * @param string $column
     * @param string $value
     * @return static
     */
    public function like(string $column, string $value): static
    {
        $this->like[$column] = $value;
        return $this;
    }

    /**
     * Check if passed argument isn't array, then place it in array.
     * null argument should become [null], not [].
     * @param mixed $value
     * @return array
     */
    public function makeArray(mixed $value): array
    {
        $array = is_array($value) ? $value : [$value];
        return $array;
    }

    /**
     * Return CASE expression for mapping values to names, that can be used in ordering
     * @param string $column
     * @param array $names
     * @return string
     */
    public function makeCase(string $column, array $names): string
    {
        $expr = ["CASE {$column}"];
        foreach ($names as $type => $name) {
            $expr[] = "WHEN {$type} THEN {$this->escape($name)}";
        }
        $expr[] = "END";
        $caseExpr = implode(' ', $expr);
        return $caseExpr;
    }

    /**
     * Define ORDER BY clause
     * @param string $column
     * @param bool $ascending
     * @return static
     */
    public function order(string $column, bool $ascending = true): static
    {
        $this->order[] = [$column, $ascending];
        return $this;
    }

    /**
     * Define filtering by skipping values
     * @param string $column
     * @param mixed $value
     * @return static
     */
    public function skipArray(string $column, mixed $value): static
    {
        $this->skip[$column] = $this->makeArray($value);
        return $this;
    }

    /**
     * Return mysql query for counting
     * @return string
     */
    public function getCountQuery(): string
    {
        if ($this->countQuery) {
            return $this->countQuery;
        }

        $group = $this->getGroupClause();
        if ($group) {
            $query = $this->getCountQueryForGrouping();
        } else {
            $query = $this->getCountQueryNoGrouping();
        }
        return $query;
    }

    /**
     * Define count query explicitly
     * @param string $query
     * @return $this
     */
    public function setCountQuery(string $query): static
    {
        $this->countQuery = $query;
        return $this;
    }

    /**
     * Count query, when GROUP BY statement exists
     * We need to overload method for other than 'id' PK
     * @return string
     */
    public function getCountQueryForGrouping(): string
    {
        $from = $this->getFromClause();
        $join = $this->getJoinClause();
        $where = $this->getWhereClause();
        $group = $this->getGroupClause();
        $having = $this->getHavingClause();
        $alias = $this->getAlias();
        $table = $this->getTable();

        if ($having) {
            return "SELECT COUNT(1) AS `cnt` FROM (SELECT {$alias}.id{$from}{$join}{$where}{$group}{$having}) t";
        }

        $where .= ($where ? ' AND' : ' WHERE')
            . " {$alias}2.id = {$alias}.id";
        $query = "SELECT COUNT(1) AS `cnt` "
            . "FROM `{$table}` AS {$alias}2 "
            . "WHERE EXISTS ("
            . "SELECT {$alias}.id{$from}{$join}{$where}{$group})";
        return $query;
    }

    /**
     * Count query, when there is no GROUP BY statement in query
     * @return string
     */
    public function getCountQueryNoGrouping(): string
    {
        $from = $this->getFromClause();
        $join = $this->getJoinClause();
        $where = $this->getWhereClause();
        $query = "SELECT COUNT(1) AS `cnt`" . $from . $join . $where;
        return $query;
    }

    /**
     * Return mysql query for counting
     * @param string $column
     * @return string
     */
    public function getExistColumnQuery(string $column): string
    {
        $like = trim($column);
        $query = "SHOW COLUMNS FROM `{$this->getTable()}` LIKE '{$like}'";
        return $query;
    }

    /**
     * Return mysql query for counting
     * @return string
     */
    public function getDeleteQuery(): string
    {
        $delete = $this->getDeleteClause();
        $from = $this->getFromClause();
        $join = $this->getJoinClause();
        $where = $this->getWhereClause();
        $query = $delete . $from . $join . $where;
        return $query;
    }

    /**
     * Return query for fetching data
     * @return string
     */
    public function getResultQuery(): string
    {
        if ($this->resultQuery) {
            return $this->resultQuery;
        }

        $select = $this->getSelectClause();
        $from = $this->getFromClause();
        $join = $this->getJoinClause();
        $where = $this->getWhereClause();
        $group = $this->getGroupClause();
        $having = $this->getHavingClause();
        $order = $this->getOrderClause();
        $limit = $this->getLimitClause();
        $query = $select . $from . $join . $where . $group . $having . $order . $limit;
        return $query;
    }

    /**
     * Define result query explicitly
     * @param string $query
     * @return $this
     */
    public function setResultQuery(string $query): static
    {
        $this->resultQuery = $query;
        return $this;
    }

    /**
     * @return string
     */
    public function getTruncateQuery(): string
    {
        $query = "TRUNCATE `{$this->getTable()}`";
        return $query;
    }

    /**
     * Return DELETE clause
     * @return string
     */
    private function getDeleteClause(): string
    {
        $delete = 'DELETE ' . $this->getAlias() . '.*';
        return $delete;
    }

    /**
     * @return string
     */
    private function getFromClause(): string
    {
        $from = ' FROM `' . $this->getTable() . '` ' . $this->getAlias();
        return $from;
    }

    /**
     * @return string
     */
    private function getGroupClause(): string
    {
        $group = $this->group ? ' GROUP BY ' . implode(', ', $this->group) : '';
        return $group;
    }

    /**
     * @return string
     */
    private function getHavingClause(): string
    {
        $having = $this->having ? ' HAVING ' . implode(', ', $this->having) : '';
        return $having;
    }

    /**
     * @return string
     */
    private function getJoinClause(): string
    {
        $joinClause = '';
        $expressions = [];
        foreach ($this->innerJoin as $table) {
            $expressions[$table] = 'INNER ' . $this->getJoinByTable($table);
        }
        foreach ($this->leftJoin as $table) {
            if (!array_key_exists($table, $expressions)) {
                $expressions[$table] = 'LEFT ' . $this->getJoinByTable($table);
            }
        }
        if ($expressions) {
            $joinClause = ' ' . implode(' ', $expressions);
        }
        return $joinClause;
    }

    /**
     * @return string
     */
    private function getLimitClause(): string
    {
        $limitClause = '';
        if ($this->getChunkSize()) {
            $offset = $this->getChunkSize() * $this->getChunkNum();
            $limit = $this->getChunkSize();
            $limitClause = ' LIMIT ' . $offset . ', ' . $limit;
        } elseif (
            $this->getOffset()
            || $this->getLimit()
        ) {
            $offset = $this->getOffset() ?: 0;
            $limit = $this->getLimit() ?: 0;
            $limitClause = ' LIMIT ' . $offset . ', ' . $limit;
        }
        return $limitClause;
    }

    /**
     * Return ORDER BY clause, if ordering requested
     * @return string
     */
    private function getOrderClause(): string
    {
        $orderClause = '';
        $clauses = [];
        foreach ($this->order as [$column, $ascending]) {
            $clause = $column;
            if (!$ascending) {
                $clause .= ' DESC';
            }
            $clauses[] = $clause;
        }
        if ($clauses) {
            $orderClause = ' ORDER BY ' . implode(', ', $clauses);
        }
        return $orderClause;
    }

    /**
     * Return SELECT clause
     * @return string
     */
    private function getSelectClause(): string
    {
        $select = 'SELECT ';
        if ($this->isDistinct()) {
            $select .= 'DISTINCT ';
        }
        if ($this->getSelect()) {
            $select .= implode(', ', $this->getSelect());
        } else {
            $select .= $this->getAlias() . '.*';
        }
        return $select;
    }

    /**
     * Get array of conditions
     * @return string
     */
    private function getWhereClause(): string
    {
        $conditions = $this->getConditions();
        $conditions = array_filter($conditions);
        $cond = $conditions ? ' WHERE ' . implode(' AND ', $conditions) : '';
        return $cond;
    }

    // Getters and setters

    /**
     * @return int sequence number of next fetched portion, started from 0
     */
    public function getChunkNum(): int
    {
        return $this->chunkNum;
    }

    /**
     * @param int $chunkNum
     * @return static
     */
    public function setChunkNum(int $chunkNum): static
    {
        $this->chunkNum = $chunkNum;
        return $this;
    }

    public function dropChunkNum(): static
    {
        $this->setChunkNum(0);
        return $this;
    }

    /**
     * @param int $increase
     * @return static
     */
    public function increaseChunkNum(int $increase = 1): static
    {
        $this->chunkNum += $increase;
        return $this;
    }

    /**
     * @return int|null size of portion
     */
    public function getChunkSize(): ?int
    {
        return $this->chunkSize;
    }

    /**
     * Set size of data portion fetched per request.
     * We increase fetched portion number in every loadArray() call.
     * @param int|null $size
     * @return static
     */
    public function setChunkSize(?int $size): static
    {
        $this->chunkSize = $size;
        return $this;
    }

    public function dropChunkSize(): static
    {
        $this->setChunkSize(null);
        return $this;
    }

    /**
     * @return bool
     */
    public function isDistinct(): bool
    {
        return $this->isDistinct;
    }

    /**
     * Enable DISTINCT clause
     * @param bool $enabled
     * @return static
     */
    public function enableDistinct(bool $enabled): static
    {
        $this->isDistinct = $enabled;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     * @return static
     */
    public function setLimit(?int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    public function dropLimit(): static
    {
        $this->setLimit(null);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @param int|null $offset
     * @return static
     */
    public function setOffset(?int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    public function dropOffset(): static
    {
        $this->setOffset(null);
        return $this;
    }

    /**
     * @return array
     */
    public function getSelect(): array
    {
        return $this->select;
    }

    /**
     * Define concrete fields of result set
     * Use it, when we don't need to complete entities
     * @param string[] $resultSet
     * @return static
     */
    public function select(array $resultSet): static
    {
        $this->select = ArrayCast::makeStringArray($resultSet);
        return $this;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        if ($this->alias === '') {
            throw new InvalidArgumentException('Table alias undefined');
        }
        return $this->alias;
    }

    /**
     * @param string $alias
     * @return static
     */
    public function setAlias(string $alias): static
    {
        $this->alias = $alias;
        return $this;
    }

    public function dropAlias(): static
    {
        $this->setAlias('');
        return $this;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        if ($this->table === '') {
            throw new InvalidArgumentException('Table undefined');
        }
        return $this->table;
    }

    /**
     * @param string $table
     * @return static
     */
    public function setTable(string $table): static
    {
        $this->table = $table;
        return $this;
    }

    public function dropTable(): static
    {
        $this->setTable('');
        return $this;
    }

    /**
     * @return string[]
     */
    public function getJoins(): array
    {
        return $this->joins;
    }

    /**
     * @param string[] $joins
     * @return static
     */
    public function setJoins(array $joins): static
    {
        $this->joins = ArrayCast::makeStringArray($joins);
        return $this;
    }

    /**
     * Searches for $table key among pre-defined joins, or return $table as complete joining condition.
     * @param string $table
     * @return string
     */
    private function getJoinByTable(string $table): string
    {
        $joins = $this->getJoins();
        $join = !empty($joins[$table]) ? $joins[$table] : $table;
        return $join;
    }
}
