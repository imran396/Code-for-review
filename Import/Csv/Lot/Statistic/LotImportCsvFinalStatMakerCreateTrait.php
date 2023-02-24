<?php
/**
 * SAM-9614: Refactor PartialUploadManager
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

namespace Sam\Import\Csv\Lot\Statistic;

/**
 * Trait LotImportCsvFinalStatMakerCreateTrait
 * @package Sam\Import\Csv\Lot\Statistic
 */
trait LotImportCsvFinalStatMakerCreateTrait
{
    /**
     * @var LotImportCsvFinalStatMaker|null
     */
    protected ?LotImportCsvFinalStatMaker $lotImportCsvFinalStatMaker = null;

    /**
     * @return LotImportCsvFinalStatMaker
     */
    protected function createLotImportCsvFinalStatMaker(): LotImportCsvFinalStatMaker
    {
        return $this->lotImportCsvFinalStatMaker ?: LotImportCsvFinalStatMaker::new();
    }

    /**
     * @param LotImportCsvFinalStatMaker $lotImportCsvFinalStatMaker
     * @return static
     * @internal
     */
    public function setLotImportCsvFinalStatMaker(LotImportCsvFinalStatMaker $lotImportCsvFinalStatMaker): static
    {
        $this->lotImportCsvFinalStatMaker = $lotImportCsvFinalStatMaker;
        return $this;
    }
}
