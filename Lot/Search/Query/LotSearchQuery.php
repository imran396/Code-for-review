<?php
/**
 * SAM-6606: Refactoring classes in the \MySearch namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 06, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Search\Query;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotSearchQuery
 * @package Sam\Lot\Search\Query
 */
class LotSearchQuery extends CustomizableClass
{
    /** @var string */
    protected string $baseTable;
    /** @var string */
    protected string $baseTableAlias;
    /** @var array */
    protected array $select = [];
    /** @var string */
    protected string $countQueryPatten = 'SELECT COUNT(1) AS lot_total %s';
    /** @var array */
    protected array $join = [];
    /** @var array */
    protected array $joinCount = [];
    /** @var array */
    protected array $where = [];
    /** @var array */
    protected array $whereCount = [];
    /** @var array */
    protected array $group = [];
    /** @var array */
    protected array $groupCount = [];
    /** @var array */
    protected array $having = [];
    /** @var array */
    protected array $havingCount = [];
    /** @var array */
    protected array $orderBy = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $baseTable
     * @param string $baseTableAlias
     * @return static
     */
    public function construct(string $baseTable, string $baseTableAlias): static
    {
        $this->baseTable = $baseTable;
        $this->baseTableAlias = $baseTableAlias;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseTable(): string
    {
        return $this->baseTable;
    }

    /**
     * @return string
     */
    public function getBaseTableAlias(): string
    {
        return $this->baseTableAlias;
    }

    /**
     * @param string|string[] $select
     * @return static
     */
    public function addSelect(string|array $select): static
    {
        $this->select = array_merge($this->select, (array)$select);
        return $this;
    }

    /**
     * @param string $pattern
     * @return static
     */
    public function setCountQueryPattern(string $pattern): static
    {
        $this->countQueryPatten = $pattern;
        return $this;
    }

    /**
     * @param string|string[] $join
     * @return static
     */
    public function addJoin(string|array $join): static
    {
        $this->join = array_merge($this->join, (array)$join);
        return $this;
    }

    /**
     * @param string|string[] $join
     * @return static
     */
    public function addJoinCount(string|array $join): static
    {
        $this->joinCount = array_merge($this->joinCount, (array)$join);
        return $this;
    }

    /**
     * @param string|string[] $where
     * @return static
     */
    public function addWhere(string|array $where): static
    {
        $this->where = array_merge($this->where, (array)$where);
        return $this;
    }

    /**
     * @param string|string[] $where
     * @return static
     */
    public function addWhereCount(string|array $where): static
    {
        $this->whereCount = array_merge($this->whereCount, (array)$where);
        return $this;
    }

    /**
     * @param string|string[] $group
     * @return static
     */
    public function addGroup(string|array $group): static
    {
        $this->group = array_merge($this->group, (array)$group);
        return $this;
    }

    /**
     * @param string|string[] $group
     * @return static
     */
    public function addGroupCount(string|array $group): static
    {
        $this->groupCount = array_merge($this->groupCount, (array)$group);
        return $this;
    }

    /**
     * @param string|string[] $having
     * @return static
     */
    public function addHaving(string|array $having): static
    {
        $this->having = array_merge($this->having, (array)$having);
        return $this;
    }

    /**
     * @param string|string[] $having
     * @return static
     */
    public function addHavingCount(string|array $having): static
    {
        $this->havingCount = array_merge($this->havingCount, (array)$having);
        return $this;
    }

    /**
     * @param string|string[] $orderBy
     * @return static
     */
    public function addOrderBy(string|array $orderBy): static
    {
        $this->orderBy = array_merge($this->orderBy, (array)$orderBy);
        return $this;
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        $select = implode(', ', $this->select);
        $from = $this->getBaseTable() . ' AS ' . $this->getBaseTableAlias();
        $join = implode(' ', $this->join);
        $where = implode(' AND ', $this->where);
        $group = implode(', ', $this->group);
        $having = implode(' AND ', $this->having);
        $order = implode(', ', $this->orderBy);

        $sql = "SELECT {$select} FROM {$from} {$join}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        if ($group) {
            $sql .= " GROUP BY {$group}";
        }
        if ($having) {
            $sql .= " HAVING {$having}";
        }
        if ($order) {
            $sql .= " ORDER BY {$order}";
        }
        return $sql;
    }

    /**
     * @return string
     */
    public function getCountSql(): string
    {
        $from = $this->getBaseTable() . ' AS ' . $this->getBaseTableAlias();
        $join = implode(' ', $this->joinCount);
        $where = implode(' AND ', array_merge($this->where, $this->whereCount));
        $group = implode(', ', array_merge($this->group, $this->groupCount));
        $having = implode(' AND ', $this->havingCount);

        $sql = sprintf(
            $this->countQueryPatten,
            "FROM {$from} {$join}"
            . ($where ? " WHERE {$where}" : '')
            . ($group ? " GROUP BY {$group}" : '')
            . ($having ? " HAVING {$having}" : '')
        );
        return $sql;
    }


}
