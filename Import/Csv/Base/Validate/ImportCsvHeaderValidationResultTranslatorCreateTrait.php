<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Base\Validate;

/**
 * Trait ImportCsvHeaderValidationResultTranslatorCreateTrait
 * @package Sam\Import\Csv\Base\Validate
 */
trait ImportCsvHeaderValidationResultTranslatorCreateTrait
{
    protected ?ImportCsvHeaderValidationResultTranslator $importCsvHeaderValidationResultTranslator = null;

    /**
     * @return ImportCsvHeaderValidationResultTranslator
     */
    protected function createImportCsvHeaderValidationResultTranslator(): ImportCsvHeaderValidationResultTranslator
    {
        return $this->importCsvHeaderValidationResultTranslator ?: ImportCsvHeaderValidationResultTranslator::new();
    }

    /**
     * @param ImportCsvHeaderValidationResultTranslator $importCsvHeaderValidationResultTranslator
     * @return static
     * @internal
     */
    public function setImportCsvHeaderValidationResultTranslator(ImportCsvHeaderValidationResultTranslator $importCsvHeaderValidationResultTranslator): static
    {
        $this->importCsvHeaderValidationResultTranslator = $importCsvHeaderValidationResultTranslator;
        return $this;
    }
}
