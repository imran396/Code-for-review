<?php
/**
 * SAM-4634:Refactor special terms report
 * https://bidpath.atlassian.net/browse/SAM-4634
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/8/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\SpecialTerm\Csv;

/**
 * Class DataLoader
 * @package Sam\Report\SpecialTerm\Csv
 */
class DataLoader extends \Sam\Report\SpecialTerm\Base\DataLoader
{
    /**
     * Class instantiation
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load data by portions with row count defined by $chunkSize property
     * @return array
     */
    public function loadNextChunk(): array
    {
        $rows = $this->prepareAuctionLotItemBidderTermsRepository()->loadRows();
        $incrementOffset = count($rows);
        $this->addOffset($incrementOffset);
        return $rows;
    }
}
