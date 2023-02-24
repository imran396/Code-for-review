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

namespace Sam\Storage\ReadRepository\Entity;

use Generator;
use Laminas\Filter\Word\UnderscoreToCamelCase;
use QMySqli5DatabaseResult;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Sql\QueryBuilderAwareTrait;

/**
 * Class ReadRepositoryBase
 * @package Sam\Storage\Repository
 */
abstract class ReadRepositoryBase extends CustomizableClass
{
    use DbConnectionTrait;
    use QueryBuilderAwareTrait;
    use MemoryCacheManagerAwareTrait;

    /**
     * @var string  You should define table name in child class
     */
    protected string $table = '';
    /**
     * @var string  You should define table alias in child class
     */
    protected string $alias = '';
    /**
     * @var string[]
     */
    protected array $joins = [];
    /**
     * @var int Fetched array result type
     */
    protected int $arrayResultType = QMySqli5DatabaseResult::FETCH_ASSOC;

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->arrayResultType = QMySqli5DatabaseResult::FETCH_ASSOC;
        $this->qb()
            ->clear()
            ->setAlias($this->getAlias())
            ->setTable($this->getTable())
            ->setJoins($this->getJoins());
        return $this;
    }

    /**
     * @return int count of found records
     */
    public function count(): int
    {
        $query = $this->qb()->getCountQuery();
        $this->query($query);
        $row = $this->fetchAssoc();
        $count = Cast::toInt($row['cnt']);
        return $count;
    }

    /**
     * @return bool - check, if at least one entry is found
     */
    public function exist(): bool
    {
        $isFound = $this->count() > 0;
        return $isFound;
    }

    /**
     * Perform exist column query
     * @param string $column
     * @return bool
     */
    public function existColumn(string $column): bool
    {
        $query = $this->qb()->getExistColumnQuery($column);
        $this->query($query);
        $row = $this->fetchAssoc();
        return (bool)$row;
    }

    /**
     * @param string $table
     * @param string $condition
     * @return $this
     */
    public function extendJoinCondition(string $table, string $condition): ReadRepositoryBase
    {
        $this->qb()->extendJoinCondition($table, $condition);
        return $this;
    }

    /**
     * Generator that yields result entities
     * saves transferring all the results to an array and then return those
     * @return Generator
     */
    public function yieldEntities(): Generator
    {
        $chunkSize = $this->qb()->getChunkSize();
        do {
            $query = $this->qb()->getResultQuery();
            $dbResult = $this->query($query);
            while ($row = $dbResult->GetNextRow()) {
                yield call_user_func($this->getDaoName() . '::InstantiateDbRow', $row);
            }
            if ($chunkSize) {
                $this->qb()->increaseChunkNum();
            }
        } while ($chunkSize && $dbResult->CountRows() === $chunkSize);
    }

    /**
     * Generator that yields result rows
     * @return Generator
     */
    public function yieldRows(): Generator
    {
        $chunkSize = $this->qb()->getChunkSize();
        do {
            $query = $this->qb()->getResultQuery();
            $dbResult = $this->query($query);
            while ($row = $this->fetchArray($this->arrayResultType)) {
                yield $row;
            }
            if ($chunkSize) {
                $this->qb()->increaseChunkNum();
            }
        } while ($chunkSize && $dbResult->CountRows() === $chunkSize);
    }

    /**
     * Load single entity from memory cache or db
     * @param int|null $ttl as in MemoryCacheManager::set
     * @param bool $isForceDb default false. Force load from db and set cache
     * @return object|null The value of the item from the cache
     */
    public function loadEntityFromCacheOrDb(?int $ttl = null, bool $isForceDb = false): ?object
    {
        return $this->loadFromCacheOrDb([$this, 'loadEntity'], $ttl, $isForceDb);
    }

    /**
     * Load single array projection of requested fields from memory cache or db
     * @param int|null $ttl as in MemoryCacheManager::set
     * @param bool $isForceDb default false. Force load from db and set cache
     * @return array The value of the item from the cache
     */
    public function loadRowFromCacheOrDb(?int $ttl = null, bool $isForceDb = false): array
    {
        return $this->loadFromCacheOrDb([$this, 'loadRow'], $ttl, $isForceDb);
    }

    /**
     * Load array of entities from memory cache or db
     * @param int|null $ttl as in MemoryCacheManager::set
     * @param bool $isForceDb default false. Force load from db and set cache
     * @return array
     */
    public function loadEntitiesFromCacheOrDb(?int $ttl = null, bool $isForceDb = false): array
    {
        return $this->loadFromCacheOrDb([$this, 'loadEntities'], $ttl, $isForceDb);
    }

    /**
     * Load array of fields projection from memory cache or db
     * @param int|null $ttl as in MemoryCacheManager::set
     * @param bool $isForceDb default false. Force load from db and set cache
     * @return array
     */
    public function loadRowsFromCacheOrDb(?int $ttl = null, bool $isForceDb = false): array
    {
        return $this->loadFromCacheOrDb([$this, 'loadRows'], $ttl, $isForceDb);
    }

    /**
     * Load data from memory cache or db
     * @param callable $loadFn
     * @param int|null $ttl as in MemoryCacheManager::set
     * @param bool $isForceDb default false. Force load from db and set cache
     * @return mixed
     */
    protected function loadFromCacheOrDb(callable $loadFn, ?int $ttl = null, bool $isForceDb = false): mixed
    {
        $key = hash('sha256', self::class . '::' . __METHOD__ . '-' . $this->qb()->getResultQuery());
        $result = $this->getMemoryCacheManager()->get($key);
        if (is_null($result) || $isForceDb) {
            $result = $loadFn();
            $this->getMemoryCacheManager()->set($key, $result, $ttl);
        }
        return $result;
    }

    /**
     * Truncate table
     */
    public function truncate(): void
    {
        $query = $this->qb()->getTruncateQuery();
        $this->nonQuery($query);
    }

    /**
     * Load array of entities
     * @return array
     */
    public function loadEntities(): array
    {
        $query = $this->qb()->getResultQuery();
        $dbResult = $this->query($query);

        if ($this->qb()->getChunkSize()) {
            $this->qb()->increaseChunkNum();
        }

        $entities = call_user_func($this->getDaoName() . '::InstantiateDbResult', $dbResult);
        return $entities;
    }

    /**
     * Load array of rows for defined fields
     * @return array
     */
    public function loadRows(): array
    {
        $query = $this->qb()->getResultQuery();
        $this->query($query);
        $rows = [];
        while ($row = $this->fetchArray($this->arrayResultType)) {
            $rows[] = $row;
        }

        if ($this->qb()->getChunkSize()) {
            $this->qb()->increaseChunkNum();
        }

        return $rows;
    }

    /**
     * Load single entity (the first instance)
     * @return object|null
     */
    public function loadEntity(): ?object
    {
        $query = $this->qb()
            ->limit(1)
            ->getResultQuery();
        $dbResult = $this->query($query);
        $row = $dbResult->GetNextRow();
        $entity = $row ? call_user_func($this->getDaoName() . '::InstantiateDbRow', $row) : null;
        return $entity;
    }

    /**
     * Load single row of selected fields
     * @return array empty array [] when record not found
     */
    public function loadRow(): array
    {
        $query = $this->qb()
            ->limit(1)
            ->getResultQuery();
        $this->query($query);
        $row = $this->fetchArray($this->getArrayResultType());
        return $row;
    }

    /**
     * Return entity class name
     * @return string
     */
    private function getDaoName(): string
    {
        $filter = new UnderscoreToCamelCase();
        $name = $filter->filter($this->table);
        return $name;
    }

    /**
     * Checks, if we want return entity object or selected field array
     * @return bool
     */
    public function shouldHydrateEntity(): bool
    {
        $should = !$this->getSelect();
        return $should;
    }

    /**
     * @param array $options
     * @return static
     * @deprecated TODO: get rid of this method
     * Define ORDER BY clause
     */
    public function applyOrderOptions(array $options): static
    {
        if (isset($options['order'])) {
            foreach ($options['order'] as $column => $ascending) {
                if (method_exists($this, 'orderBy' . $column)) {
                    $this->{'orderBy' . $column}($options['order'][$column]);
                }
            }
        }
        return $this;
    }
    // ----------------------- Getters and setters -----------------------

    /**
     * @return int
     */
    public function getArrayResultType(): int
    {
        return $this->arrayResultType;
    }

    /**
     * It allows to redefined result array to be indexed with numbers.
     * Associated by column names array is returned by default.
     * @param int $type
     * @return static
     */
    public function setArrayResultType(int $type): static
    {
        $this->arrayResultType = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     * @return static
     */
    public function setAlias(string $alias): static
    {
        $this->alias = $alias;
        $this->qb()->setAlias($alias);
        return $this;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @param string $table
     * @return static
     */
    public function setTable(string $table): static
    {
        $this->table = $table;
        $this->qb()->setTable($table);
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
        $this->qb()->setJoins($joins);
        return $this;
    }

    /**
     * @return int sequence number of next fetched portion, started from 0
     */
    public function getChunkNum(): int
    {
        return $this->qb()->getChunkNum();
    }

    /**
     * @return int|null size of portion
     */
    public function getChunkSize(): ?int
    {
        return $this->qb()->getChunkSize();
    }

    /**
     * Set size of data portion fetched per request.
     * We increase fetched portion number in every loadArray() call.
     * @param int|null $size
     * @return static
     */
    public function setChunkSize(?int $size): static
    {
        $this->qb()->setChunkSize($size);
        return $this;
    }

    /**
     * Enable DISTINCT clause
     * @param bool $enabled
     * @return static
     */
    public function enableDistinct(bool $enabled): static
    {
        $this->qb()->enableDistinct($enabled);
        return $this;
    }

    /**
     * @param mixed $var
     * @return string
     */
    public function escape(mixed $var): string
    {
        return $this->qb()->escape($var);
    }

    /**
     * @param string $condition
     * @return static
     */
    public function inlineCondition(string $condition): static
    {
        $this->qb()->inlineCondition($condition);
        return $this;
    }

    /**
     * @param string $operator
     * @return static
     */
    public function setLikeConjunctionOperator(string $operator): static
    {
        $this->qb()->setLikeConjunctionOperator($operator);
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
        $this->qb()->like($column, $value);
        return $this;
    }

    /**
     * Define HAVING clause
     * @param string $condition
     * @return static
     */
    public function having(string $condition): static
    {
        $this->qb()->having($condition);
        return $this;
    }

    /**
     * !
     * Define between filter
     * @param string $column
     * @param $from
     * @param $to
     * @param bool $not
     * @return static
     */
    protected function between(string $column, $from, $to, bool $not = false): static
    {
        $this->qb()->between($column, $from, $to, $not);
        return $this;
    }

    /**
     * !
     * Define filter array of values
     * @param string $column
     * @param mixed $value
     * @return static
     */
    protected function filterArray(string $column, mixed $value): static
    {
        $this->qb()->filterArray($column, $value);
        return $this;
    }

    /**
     * Define inequality filter
     * @param string $column
     * @param mixed $value
     * @param string $inequality
     * @return static
     */
    protected function filterInequality(string $column, mixed $value, string $inequality): static
    {
        $this->qb()->filterInequality($column, $value, $inequality);
        return $this;
    }

    /**
     * Define GROUP BY clause
     * @param string $column
     * @return static
     */
    protected function group(string $column): static
    {
        $this->qb()->group($column);
        return $this;
    }

    /**
     * Left Join table
     * @param string $table
     * @return static
     */
    protected function join(string $table): static
    {
        $this->qb()->join($table);
        return $this;
    }

    public function joinMultiple(array $tables): static
    {
        foreach ($tables as $table) {
            $this->join($table);
        }
        return $this;
    }

    /**
     * Inner Join table
     * @param string $table
     * @return static
     */
    protected function innerJoin(string $table): static
    {
        $this->qb()->innerJoin($table);
        return $this;
    }

    /**
     * Return CASE expression for mapping values to names, that can be used in ordering
     * @param string $column
     * @param array $names
     * @return string
     */
    protected function makeCase(string $column, array $names): string
    {
        $caseExpr = $this->qb()->makeCase($column, $names);
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
        $this->qb()->order($column, $ascending);
        return $this;
    }

    /**
     * Define filtering by skipping values
     * @param string $column
     * @param mixed $value
     * @return static
     */
    protected function skipArray(string $column, mixed $value): static
    {
        $this->qb()->skipArray($column, $value);
        return $this;
    }

    /**
     * @param string $query
     * @param mixed $value
     * @return static
     */
    protected function filterSubquery(string $query, mixed $value): static
    {
        $this->qb()->filterSubquery($query, $value);
        return $this;
    }

    /**
     * @param string $query
     * @param string $value
     * @return static
     */
    public function likeSubquery(string $query, string $value): static
    {
        $this->qb()->likeSubquery($query, $value);
        return $this;
    }

    /**
     * @param int|null $limit
     * @return static
     */
    public function limit(?int $limit): static
    {
        $this->qb()->setLimit($limit);
        return $this;
    }

    /**
     * @param int|null $offset
     * @return static
     */
    public function offset(?int $offset): static
    {
        $this->qb()->setOffset($offset);
        return $this;
    }

    /**
     * @param string|string[] $resultSet
     * @return static
     */
    public function addSelect(string|array $resultSet): static
    {
        $this->qb()->addSelect($resultSet);
        return $this;
    }

    /**
     * @return array
     */
    public function getSelect(): array
    {
        return $this->qb()->getSelect();
    }

    /**
     * Define concrete fields of result set
     * Use it, when we don't need to complete entities
     * @param string[] $resultSet
     * @return static
     */
    public function select(array $resultSet): static
    {
        $this->qb()->select($resultSet);
        return $this;
    }

    /**
     * @param string $query
     * @param mixed $value
     * @return static
     */
    public function skipSubquery(string $query, mixed $value): static
    {
        $this->qb()->skipSubquery($query, $value);
        return $this;
    }

    /**
     * Return COUNT query string
     * @return string
     */
    public function getCountQuery(): string
    {
        return $this->qb()->getCountQuery();
    }

    /**
     * Define count query explicitly
     * @param string $query
     * @return $this
     */
    public function setCountQuery(string $query): static
    {
        $this->qb()->setCountQuery($query);
        return $this;
    }

    /**
     * Return SELECT query string
     * @return string
     */
    public function getResultQuery(): string
    {
        return $this->qb()->getResultQuery();
    }

    /**
     * Define result query explicitly
     * @param string $query
     * @return $this
     */
    public function setResultQuery(string $query): static
    {
        $this->qb()->setResultQuery($query);
        return $this;
    }
}
