<?php
/**
 * Fetch and return lot details data
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 * SAM-6595: Templated-content building - simplify module structure for v3.5
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

namespace Sam\Details\Lot\Web\Base\Build\Internal\DataSource;

use Sam\Details\Lot\Base\DataSourceMysql;
use Sam\Details\Lot\Web\Base\Build\Internal\Option\Options;
use Sam\User\Access\LotAccessCheckerAwareTrait;

/**
 * Class DataProvider
 * @package Sam\Details
 * @property Options $options
 */
class DataProvider extends \Sam\Details\Lot\Base\DataProvider
{
    use LotAccessCheckerAwareTrait;

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
        $dataSource->setMappedLotCustomFields($this->getAvailableLotCustomFields());
        $dataSource->setResultSetFields($resultSetFields);
        $dataSource->setSystemAccountId($this->options->getSystemAccountId());
        $userAccess = $this->getLotAccessChecker()->detectRoles(
            (int)$this->options->lotItemId,
            (int)$this->options->auctionId,
            (int)$this->options->userId,
            true
        );
        $dataSource->setUserAccess([$userAccess, []]);   // should go before setMappedLotCustomFields(). todo: avoid that
        $dataSource->setUserId($this->options->userId);

        if ($this->options->auctionId) {
            $dataSource->filterAuctionIds((array)$this->options->auctionId);
        }

        if ($this->options->lotItemId) {
            $dataSource->filterLotItemIds((array)$this->options->lotItemId);
        }

        return $dataSource;
    }
}
