<?php
/**
 * SAM-4634:Refactor special terms report
 * https://bidpath.atlassian.net/browse/SAM-4634
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/9/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\SpecialTerm\Html;

/**
 * Class DataLoader
 * @package Sam\Report\SpecialTerm\Html
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
     * Load data by portions with row count
     * @return string[]
     */
    public function loadPage(): array
    {
        return $this->prepareAuctionLotItemBidderTermsRepository()->loadRows();
    }

    /**
     *Count all rows
     * @return int
     */
    public function count(): int
    {
        return $this->prepareAuctionLotItemBidderTermsRepository()->count();
    }
}
