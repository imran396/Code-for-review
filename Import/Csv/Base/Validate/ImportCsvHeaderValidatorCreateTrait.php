<?php
/**
 * SAM-9365: Refactor BidIncrementCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Base\Validate;

/**
 * Trait ImportCsvHeaderValidatorCreateTrait
 * @package Sam\Import\Csv\Base\Validate
 */
trait ImportCsvHeaderValidatorCreateTrait
{
    protected ?ImportCsvHeaderValidator $importCsvHeaderValidator = null;

    /**
     * @return ImportCsvHeaderValidator
     */
    protected function createImportCsvHeaderValidator(): ImportCsvHeaderValidator
    {
        return $this->importCsvHeaderValidator ?: ImportCsvHeaderValidator::new();
    }

    /**
     * @param ImportCsvHeaderValidator $importCsvHeaderValidator
     * @return static
     * @internal
     */
    public function setImportCsvHeaderValidator(ImportCsvHeaderValidator $importCsvHeaderValidator): static
    {
        $this->importCsvHeaderValidator = $importCsvHeaderValidator;
        return $this;
    }
}
