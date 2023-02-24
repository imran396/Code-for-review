<?php
/**
 * SAM-9891: Get rid of RepositoryBase::delete() usage
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\DeleteRepository\Entity;

use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Sql\QueryBuilderAwareTrait;

/**
 * Class ReadRepositoryBase
 * @package Sam\Storage\Repository
 */
abstract class DeleteRepositoryBase extends CustomizableClass
{
    use DbConnectionTrait;
    use QueryBuilderAwareTrait;

    /**
     * @var string  You should define table name
     */
    protected string $table = '';
    /**
     * @var string  You should define table alias
     */
    protected string $alias = '';
    /**
     * @var string[]
     */
    protected array $joins = [];

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->qb()
            ->clear()
            ->setAlias($this->getAlias())
            ->setTable($this->getTable())
            ->setJoins($this->getJoins());
        return $this;
    }

    /**
     * Perform delete query
     */
    public function delete(): void
    {
        $query = $this->qb()->getDeleteQuery();
        $this->nonQuery($query);
    }

    /**
     * @param string $table
     * @param string $condition
     * @return $this
     */
    public function extendJoinCondition(string $table, string $condition): static
    {
        $this->qb()->extendJoinCondition($table, $condition);
        return $this;
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
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return string[]
     */
    public function getJoins(): array
    {
        return $this->joins;
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
}
