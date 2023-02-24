<?php
/**
 * Fetch and return auction details data
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Mar 2, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Feed;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;

/**
 * Class DataProvider
 * @package Sam\Details
 * @property Options $options
 * @extends  \Sam\Details\Auction\Base\DataProvider<DataSourceMysql>
 */
class DataProvider extends \Sam\Details\Auction\Base\DataProvider
{

    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * @override parent::getDataSource()
     * @return DataSourceMysql
     */
    public function getDataSource(): DataSourceMysql
    {
        if ($this->dataSource === null) {
            $dataSource = DataSourceMysql::new();
            $this->setDataSource($dataSource);
        }
        return $this->dataSource;
    }

    /**
     * @param DataSourceMysql $dataSource
     */
    public function initDataSource($dataSource): ?DataSourceMysql
    {
        $this->completePlaceholdersToResultSetFields();
        $resultSetFields = $this->collectResultSetFields();
        if (!$resultSetFields) {
            return null;
        }

        // Apply basic filtering options

        $dataSource->setMappedCustomFields($this->getActualAuctionCustomFields());
        $dataSource->setResultSetFields($resultSetFields);
        $dataSource->setSystemAccountId($this->options->getSystemAccountId());
        $dataSource->filterAuctionStatuses(Constants\Auction::$availableAuctionStatuses);
        $dataSource->filterPublished(true);

        // Process filtering, ordering, pagination defined by request parameters
        if ($this->options->accountId) {
            $dataSource->filterAccountId($this->options->accountId);
        }
        if ($this->options->accountName) {
            $dataSource->filterAccountNameOrCompany($this->options->accountName);
        }

        if ($this->options->itemsPerPage) {
            $dataSource->setOffset($this->options->itemsPerPage * ($this->options->page - 1));
            $dataSource->setLimit($this->options->itemsPerPage);
        }

        if ($this->options->auctionId) {
            $dataSource->filterIds((array)$this->options->auctionId);
        }

        if ($this->options->saleNo) {
            $dataSource->filterSaleNo(trim($this->options->saleNo));
        }

        if ($this->options->status) {
            $dataSource->filterRegularStatus(ArrayCast::makeIntArray($this->options->status));
        }

        if ($this->options->type) {
            $dataSource->filterAuctionTypes($this->options->type);
        }

        if ($this->options->order) {
            $dataSource->setOrder($this->options->order);
        }

        if ($this->options->userId) {
            $dataSource->setUserId($this->options->userId);
        }

        return $dataSource;
    }
}
