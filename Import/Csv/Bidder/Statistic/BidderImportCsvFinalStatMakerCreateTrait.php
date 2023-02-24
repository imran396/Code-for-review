<?php
/**
 * SAM-3796: Bidder upload into auction
 * SAM-9366: Refactor Sam\Bidder\AuctionBidder\CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Bidder\Statistic;

/**
 * Trait BidderImportCsvFinalStatMakerCreateTrait
 * @package Sam\Import\Csv\Bidder\Statistic
 */
trait BidderImportCsvFinalStatMakerCreateTrait
{
    protected ?BidderImportCsvFinalStatMaker $bidderImportCsvFinalStatMaker = null;

    /**
     * @return BidderImportCsvFinalStatMaker
     */
    protected function createBidderImportCsvFinalStatMaker(): BidderImportCsvFinalStatMaker
    {
        return $this->bidderImportCsvFinalStatMaker ?: BidderImportCsvFinalStatMaker::new();
    }

    /**
     * @param BidderImportCsvFinalStatMaker $bidderImportCsvFinalStatMaker
     * @return static
     * @internal
     */
    public function setBidderImportCsvFinalStatMaker(BidderImportCsvFinalStatMaker $bidderImportCsvFinalStatMaker): static
    {
        $this->bidderImportCsvFinalStatMaker = $bidderImportCsvFinalStatMaker;
        return $this;
    }
}
