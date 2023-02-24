<?php
/** @noinspection PhpUnused */

/** @noinspection PhpInconsistentReturnPointsInspection */

/**
 * General db connection logic
 *
 * SAM-3626: Apply DbConnectionTrait https://bidpath.atlassian.net/browse/SAM-3626
 *
 * @copyright   2018 Bidpath, Inc.
 * @author      Igors Kotlevskis
 * @package     com.swb.sam2
 * @version     SVN: $Id$
 * @since       Feb 14, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\DataSource;

use Generator;
use QApplication;
use QMySqli5Database;
use QMySqli5DatabaseResult;
use QMySqliDatabaseException;
use RuntimeException;

/**
 * Trait DbConnectionTrait
 * @package Sam\Core\DataSource
 */
trait DbConnectionTrait
{
    /**
     * Get data from read-only db, if read-only db is available
     */
    protected bool $isReadOnlyDb = false;
    /**
     * @var QMySqli5Database|null
     */
    protected $db;
    /**
     * @var QMySqli5DatabaseResult|QMySqli5DatabaseResult[]|null
     */
    private $dbResult;

    /**
     * Return DB connection
     * @return QMySqli5Database
     */
    protected function getDb()
    {
        /**
         * Igor K. 3 Jan, 2018
         * IDK why, but we lose db connection object
         * Eg. on save at User Edit page after few DELETE query calls (nonQuery()).
         * In case of problem, change to:
         * $this->db = QApplication::$Database[1];
         *
         * 2019-02-16: Restored problematic logic for research. I can't reproduce issue above.
         */
        // if (!$this->db instanceof QMySqli5Database) {
        //     $this->db = QApplication::$Database[1];
        // }
        // IK, We have problem in LotInfoPanel save
        return $this->db ?: QApplication::$Database[1];
    }

    /**
     * @param $db
     * @return static
     */
    public function setDb($db): static
    {
        $this->db = $db;
        return $this;
    }

    /**
     * Use read-only db
     * @param bool $enable
     * @return static
     */
    public function enableReadOnlyDb(bool $enable): static
    {
        $this->isReadOnlyDb = $enable;
        return $this;
    }

    /**
     * Performs query to db considering read-only db setting
     * @param string $query
     * @param bool|null $isReadOnlyDb TODO: make bool $isReadOnlyDb = false, when we get rid of $this->isReadOnlyDb
     * @return QMySqli5DatabaseResult
     */
    protected function query(string $query, ?bool $isReadOnlyDb = null)
    {
        try {
            log_traceQuery($query);
            $this->dbResult = $this->getDb()->Query($query, $isReadOnlyDb ?? $this->isReadOnlyDb);
        } catch (QMySqliDatabaseException $e) {
            log_errorBackTrace('Mysql query failed' . composeSuffix(['error' => $e->getCode() . ' - ' . $e->getMessage(), 'query' => $query]));
            throw new RuntimeException($e->getMessage(), $e->getCode());
        }
        return $this->dbResult;
    }

    /**
     * @param $query
     * @return QMySqli5DatabaseResult[]
     */
    protected function multiQuery($query)
    {
        try {
            log_traceQuery($query);
            $this->dbResult = $this->getDb()->MultiQuery($query, $this->isReadOnlyDb);
        } catch (QMySqliDatabaseException $e) {
            log_errorBackTrace($e->getCode() . ' - ' . $e->getMessage());
            throw new RuntimeException($e->getMessage(), $e->getCode());
        }
        return $this->dbResult;
    }

    /**
     * @param string $query
     */
    protected function nonQuery(string $query): void
    {
        try {
            log_traceQuery($query);
            $this->getDb()->NonQuery($query);
        } catch (QMySqliDatabaseException $e) {
            log_errorBackTrace($e->getCode() . ' - ' . $e->getMessage());
            throw new RuntimeException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param mixed $var
     * @return string
     */
    public function escape(mixed $var): string
    {
        $var = $this->getDb()->SqlVariable($var);
        return $var;
    }

    /**
     * Escape array fields to be used in sql query parameters
     * @param array $arr
     * @return array
     */
    public function escapeArray(array $arr): array
    {
        foreach ($arr as $i => $value) {
            $arr[$i] = $this->escape($value);
        }
        return $arr;
    }

    /**
     * Return fetched row
     * Columns are returned into the array having the field name as the array index
     * @return array
     */
    protected function fetchAssoc(): array
    {
        $row = $this->fetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
        return $row;
    }

    /**
     * Return fetched row
     * Columns are returned into the array having an enumerated index.
     * @return array
     */
    protected function fetchNum(): array
    {
        $row = $this->fetchArray(QMySqli5DatabaseResult::FETCH_NUM);
        return $row;
    }

    /**
     * Return fetched row
     * Columns are returned into the array having field name as the array index and as enumerated index.
     * @return array
     */
    protected function fetchBoth(): array
    {
        $row = $this->fetchArray(QMySqli5DatabaseResult::FETCH_BOTH);
        return $row;
    }

    /**
     * @param int $resultType
     * @return array
     */
    protected function fetchArray(int $resultType): array
    {
        $row = $this->dbResult->FetchArray($resultType) ?? [];
        return $row;
    }

    /**
     * Fetch all values in loop and return as associative array
     * @return array
     */
    protected function fetchAllAssoc(): array
    {
        $rows = $this->fetchAll(
            function () {
                return $this->fetchAssoc();
            }
        );
        return $rows;
    }

    /**
     * Fetch all values in loop and return as indexed array
     * @return array
     */
    protected function fetchAllNum(): array
    {
        $rows = $this->fetchAll(
            function () {
                return $this->fetchNum();
            }
        );
        return $rows;
    }

    /**
     * Fetch all values in loop and return as associative and indexed array
     * @return array
     */
    protected function fetchAllBoth(): array
    {
        $rows = $this->fetchAll(
            function () {
                return $this->fetchBoth();
            }
        );
        return $rows;
    }

    /**
     * Fetch all values in loop using passed method
     * @param callable $fetchMethod
     * @return array
     */
    private function fetchAll(callable $fetchMethod): array
    {
        $rows = [];
        while ($row = $fetchMethod()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Return indexed array fetching row generator
     * @return Generator
     */
    protected function yieldNumRowFetcher(): Generator
    {
        $fetcher = $this->yieldRowFetcher(
            function () {
                return $this->fetchNum();
            }
        );
        return $fetcher;
    }

    /**
     * Return associative and indexed array fetching row generator
     * @return Generator
     */
    protected function yieldBothRowFetcher(): Generator
    {
        $fetcher = $this->yieldRowFetcher(
            function () {
                return $this->fetchBoth();
            }
        );
        return $fetcher;
    }

    /**
     * Return associative array fetching row generator
     * @return Generator
     */
    protected function yieldAssocRowFetcher(): Generator
    {
        $fetcher = $this->yieldRowFetcher(
            function () {
                return $this->fetchAssoc();
            }
        );
        return $fetcher;
    }

    /**
     * @param callable $fetchMethod
     * @return Generator
     */
    private function yieldRowFetcher(callable $fetchMethod): Generator
    {
        while ($row = $fetchMethod()) {
            yield $row;
        }
    }

    /**
     * @return int
     */
    protected function countRows(): int
    {
        $count = $this->dbResult->CountRows();
        return $count;
    }

    protected function transactionBegin(): void
    {
        $this->getDb()->TransactionBegin();
    }

    protected function transactionCommit(): void
    {
        $this->getDb()->TransactionCommit();
    }

    protected function transactionRollback(): void
    {
        $this->getDb()->TransactionRollback();
    }

    /**
     * @return string
     */
    protected function getDbName(): string
    {
        return $this->getDb()->Database;
    }
}
