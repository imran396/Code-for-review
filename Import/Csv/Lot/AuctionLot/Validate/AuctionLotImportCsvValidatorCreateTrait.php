<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\AuctionLot\Validate;

/**
 * Trait AuctionLotImportCsvValidatorCreateTrait
 * @package Sam\Import\Csv\Lot\AuctionLot
 */
trait AuctionLotImportCsvValidatorCreateTrait
{
    /**
     * @var AuctionLotImportCsvValidator|null
     */
    protected ?AuctionLotImportCsvValidator $auctionLotImportCsvValidator = null;

    /**
     * @return AuctionLotImportCsvValidator
     */
    protected function createAuctionLotImportCsvValidator(): AuctionLotImportCsvValidator
    {
        return $this->auctionLotImportCsvValidator ?: AuctionLotImportCsvValidator::new();
    }

    /**
     * @param AuctionLotImportCsvValidator $auctionLotImportCsvValidator
     * @return static
     * @internal
     */
    public function setAuctionLotImportCsvValidator(AuctionLotImportCsvValidator $auctionLotImportCsvValidator): static
    {
        $this->auctionLotImportCsvValidator = $auctionLotImportCsvValidator;
        return $this;
    }
}
