<?php
/**
 * Base interface for DataSource classes.
 * It shows, that all DataSource should be able to return records, records count
 * and define record limit per request and offset.
 */

namespace Sam\Core\DataSource;

/**
 * Interface BaseInterface
 * @package Sam\Core\DataSource
 */
interface DataSourceGetResultInterface
{
    /**
     * Return number of results
     * @return int
     */
    public function getCount(): int;

    /**
     * Return all results
     * @return array
     */
    public function getResults(): array;
}
