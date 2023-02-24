<?php
/**
 * SAM-9134: Refactor \User_CsvUpload
 *
 * Facade for csv library
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Read;


/**
 * Trait CsvFileReaderAwareTrait
 * @package Sam\Import\Csv\Read
 */
trait CsvFileReaderAwareTrait
{
    /**
     * @var CsvFileReader|null
     */
    protected ?CsvFileReader $csvFileReader = null;

    /**
     * @return CsvFileReader
     */
    protected function getCsvFileReader(): CsvFileReader
    {
        if ($this->csvFileReader === null) {
            $this->csvFileReader = CsvFileReader::new();
        }
        return $this->csvFileReader;
    }

    /**
     * @param CsvFileReader $csvFileReader
     * @return static
     * @internal
     */
    public function setCsvFileReader(CsvFileReader $csvFileReader): static
    {
        $this->csvFileReader = $csvFileReader;
        return $this;
    }
}
