<?php
/**
 * Fetch and return lot details data
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         May 9, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Lot\SeoUrl\Build\Internal\DataSource;

use Sam\Details\Lot\Base\DataSourceMysql;
use Sam\Details\Lot\SeoUrl\Build\Internal\Option\Options;

/**
 * Class DataProvider
 * @package Sam\Details
 * @property Options $options
 */
class DataProvider extends \Sam\Details\Lot\Base\DataProvider
{

    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * @param DataSourceMysql $dataSource
     */
    public function initDataSource($dataSource): DataSourceMysql
    {
        $this->completePlaceholdersToResultSetFields();
        $resultSetFields = $this->collectResultSetFields();
        $resultSetFields[] = 'alid';

        // Apply basic filtering options

        $dataSource->setMappedLotCustomFields($this->getAvailableLotCustomFields());
        $dataSource->setResultSetFields($resultSetFields);
        $dataSource->setSystemAccountId($this->options->getSystemAccountId());

        // Process filtering, ordering, pagination defined by request parameters
        if ($this->options->auctionLotIds) {
            $dataSource->filterAuctionLotIds($this->options->auctionLotIds);
        }

        return $dataSource;
    }
}
