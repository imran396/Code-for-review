<?php
/**
 * Data provider class.
 * It binds relation between placeholder and db fields.
 * It allows to filter, order, paginate fetched result.
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Feb 16, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Web;

use Sam\Details\Auction\Base\DataSourceMysql;
use Sam\Core\Constants;

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

        if ($this->options->userId) {
            $dataSource->setUserId($this->options->userId);
        }

        if ($this->options->auctionId) {
            $dataSource->filterIds((array)$this->options->auctionId);
        }

        return $dataSource;
    }
}
