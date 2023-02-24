<?php
/**
 * SAM-9614: Refactor PartialUploadManager
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\AuctionLot;

use Sam\Import\Csv\Lot\Statistic\LotImportCsvProcessStatistic;

/**
 * This class contains statistics of processed auction lots
 *
 * Class AuctionLotImportCsvProcessStatistic
 * @package Sam\Import\Csv\Lot\AuctionLot\Statistic
 */
class AuctionLotImportCsvProcessStatistic extends LotImportCsvProcessStatistic
{
    /**
     * @var int[]
     */
    public array $addedAuctionItemIds = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param self $statistic
     * @return static
     */
    public function merge($statistic): static
    {
        parent::merge($statistic);
        $this->addedAuctionItemIds = array_merge($this->addedAuctionItemIds, $statistic->addedAuctionItemIds);
        return $this;
    }
}
