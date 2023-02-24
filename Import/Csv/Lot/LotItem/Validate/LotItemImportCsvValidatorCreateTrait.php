<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\LotItem\Validate;

/**
 * Trait LotItemImportCsvValidatorCreateTrait
 * @package Sam\Import\Csv\Lot\LotItem
 */
trait LotItemImportCsvValidatorCreateTrait
{
    /**
     * @var LotItemImportCsvValidator|null
     */
    protected ?LotItemImportCsvValidator $lotItemImportCsvValidator = null;

    /**
     * @return LotItemImportCsvValidator
     */
    protected function createLotItemImportCsvValidator(): LotItemImportCsvValidator
    {
        return $this->lotItemImportCsvValidator ?: LotItemImportCsvValidator::new();
    }

    /**
     * @param LotItemImportCsvValidator $lotItemImportCsvValidator
     * @return static
     * @internal
     */
    public function setLotItemImportCsvValidator(LotItemImportCsvValidator $lotItemImportCsvValidator): static
    {
        $this->lotItemImportCsvValidator = $lotItemImportCsvValidator;
        return $this;
    }
}
