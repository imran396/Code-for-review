<?php
/**
 * SAM-9365: Refactor BidIncrementCsvUpload
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

namespace Sam\Import\Csv\BidIncrement\Statistic;

/**
 * Trait BidIncrementImportCsvFinalStatMakerCreateTrait
 * @package Sam\Import\Csv\BidIncrement\Statistic
 */
trait BidIncrementImportCsvFinalStatMakerCreateTrait
{
    /**
     * @var BidIncrementImportCsvFinalStatMaker|null
     */
    protected ?BidIncrementImportCsvFinalStatMaker $bidIncrementImportCsvFinalStatMaker = null;

    /**
     * @return BidIncrementImportCsvFinalStatMaker
     */
    protected function createBidIncrementImportCsvFinalStatMaker(): BidIncrementImportCsvFinalStatMaker
    {
        return $this->bidIncrementImportCsvFinalStatMaker ?: BidIncrementImportCsvFinalStatMaker::new();
    }

    /**
     * @param BidIncrementImportCsvFinalStatMaker $bidIncrementImportCsvFinalStatMaker
     * @return static
     * @internal
     */
    public function setBidIncrementImportCsvFinalStatMaker(BidIncrementImportCsvFinalStatMaker $bidIncrementImportCsvFinalStatMaker): static
    {
        $this->bidIncrementImportCsvFinalStatMaker = $bidIncrementImportCsvFinalStatMaker;
        return $this;
    }
}
