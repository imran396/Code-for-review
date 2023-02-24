<?php
/**
 * Fetch and return auction details data
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Jul 3, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\SeoUrl;

use Sam\Details\Auction\Base\DataSourceMysql;

/**
 * Class DataProvider
 * @package Sam\Details
 * @property Options $options
 */
class DataProvider extends \Sam\Details\Auction\Base\DataProvider
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
        $resultSetFields[] = 'id';

        // Apply basic filtering options

        $dataSource->setMappedCustomFields($this->getActualAuctionCustomFields());
        $dataSource->setResultSetFields($resultSetFields);
        $dataSource->setSystemAccountId($this->options->getSystemAccountId());

        if ($this->options->auctionIds) {
            $dataSource->filterIds($this->options->auctionIds);
        }

        return $dataSource;
    }
}
