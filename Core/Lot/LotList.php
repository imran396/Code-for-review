<?php

namespace Sam\Core\Lot;

use InvalidArgumentException;
use Sam\Core\Lot\LotList\DataSourceMysql;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotList
 * @package Sam\Core\Lot
 */
abstract class LotList extends CustomizableClass
{
    private const MISSING_DATASOURCE_EXCEPTION = 1;

    protected ?DataSourceMysql $dataSource = null;

    /**
     * Set the data source
     * @param DataSourceMysql $dataSource
     * @return static
     */
    public function setDataSource(DataSourceMysql $dataSource): static
    {
        $this->dataSource = $dataSource;
        return $this;
    }

    /**
     * Return the data source
     * @return DataSourceMysql $dataSource
     */
    public function getDataSource(): DataSourceMysql
    {
        return $this->dataSource;
    }

    protected function prepQueryDataSource(): void
    {
        if (!$this->dataSource instanceof DataSourceMysql) {
            throw new InvalidArgumentException('Data source missing', self::MISSING_DATASOURCE_EXCEPTION);
        }
    }

    /**
     * Return an array with a lot list
     * @return array
     */
    public function getLotListArray(): array
    {
        $this->prepQueryDataSource();
        $results = $this->dataSource->getResults();
        return $results;
    }

    /**
     * Return total count of all results
     * @return int
     */
    public function getNumTotalResults(): int
    {
        $this->prepQueryDataSource();
        return $this->dataSource->getCount();
    }
}
