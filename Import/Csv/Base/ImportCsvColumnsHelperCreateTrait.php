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

namespace Sam\Import\Csv\Base;

/**
 * Trait ImportCsvColumnsHelperCreateTrait
 * @package Sam\Import\Csv\Base
 */
trait ImportCsvColumnsHelperCreateTrait
{
    /**
     * @var ImportCsvColumnsHelper|null
     */
    protected ?ImportCsvColumnsHelper $importCsvColumnsHelper = null;

    /**
     * @return ImportCsvColumnsHelper
     */
    protected function createImportCsvColumnsHelper(): ImportCsvColumnsHelper
    {
        return $this->importCsvColumnsHelper ?: ImportCsvColumnsHelper::new();
    }

    /**
     * @param ImportCsvColumnsHelper $importCsvColumnsHelper
     * @return static
     * @internal
     */
    public function setImportCsvColumnsHelper(ImportCsvColumnsHelper $importCsvColumnsHelper): static
    {
        $this->importCsvColumnsHelper = $importCsvColumnsHelper;
        return $this;
    }
}
