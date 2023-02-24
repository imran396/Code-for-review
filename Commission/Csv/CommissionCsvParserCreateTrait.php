<?php
/**
 * SAM-9134: Refactor \User_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Commission\Csv;

/**
 * Trait CommissionCsvParserCreateTrait
 * @package Sam\Commission\Csv
 */
trait CommissionCsvParserCreateTrait
{
    /**
     * @var CommissionCsvParser|null
     */
    protected ?CommissionCsvParser $commissionCsvParser = null;

    /**
     * @return CommissionCsvParser
     */
    protected function createCommissionCsvParser(): CommissionCsvParser
    {
        return $this->commissionCsvParser ?: CommissionCsvParser::new();
    }

    /**
     * @param CommissionCsvParser $commissionCsvParser
     * @return static
     * @internal
     */
    public function setCommissionCsvParser(CommissionCsvParser $commissionCsvParser): static
    {
        $this->commissionCsvParser = $commissionCsvParser;
        return $this;
    }
}
