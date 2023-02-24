<?php
/**
 * SAM-9134: Refactor \User_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Read\Internal;

/**
 * Trait CsvRowDataMapperCreateTrait
 * @package Sam\Import\Csv\Read\Internal
 * @internal
 */
trait CsvRowDataMapperCreateTrait
{
    /**
     * @var CsvRowDataMapper|null
     */
    protected ?CsvRowDataMapper $csvRowDataMapper = null;

    /**
     * @return CsvRowDataMapper
     */
    protected function createCsvRowDataMapper(): CsvRowDataMapper
    {
        return $this->csvRowDataMapper ?: CsvRowDataMapper::new();
    }

    /**
     * @param CsvRowDataMapper $csvRowDataMapper
     * @return static
     * @internal
     */
    public function setCsvRowDataMapper(CsvRowDataMapper $csvRowDataMapper): static
    {
        $this->csvRowDataMapper = $csvRowDataMapper;
        return $this;
    }
}
