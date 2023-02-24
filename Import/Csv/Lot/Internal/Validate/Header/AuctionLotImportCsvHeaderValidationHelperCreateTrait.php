<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\Internal\Validate\Header;

/**
 * Trait AuctionLotImportCsvHeaderValidationHelperCreateTrait
 * @package Sam\Import\Csv\Lot\Internal\Validate\Header
 */
trait AuctionLotImportCsvHeaderValidationHelperCreateTrait
{
    /**
     * @var AuctionLotImportCsvHeaderValidationHelper|null
     */
    protected ?AuctionLotImportCsvHeaderValidationHelper $auctionLotImportCsvHeaderValidationHelper = null;

    /**
     * @return AuctionLotImportCsvHeaderValidationHelper
     */
    protected function createAuctionLotImportCsvHeaderValidationHelper(): AuctionLotImportCsvHeaderValidationHelper
    {
        return $this->auctionLotImportCsvHeaderValidationHelper ?: AuctionLotImportCsvHeaderValidationHelper::new();
    }

    /**
     * @param AuctionLotImportCsvHeaderValidationHelper $auctionLotImportCsvHeaderValidationHelper
     * @return static
     * @internal
     */
    public function setAuctionLotImportCsvHeaderValidationHelper(AuctionLotImportCsvHeaderValidationHelper $auctionLotImportCsvHeaderValidationHelper): static
    {
        $this->auctionLotImportCsvHeaderValidationHelper = $auctionLotImportCsvHeaderValidationHelper;
        return $this;
    }
}
