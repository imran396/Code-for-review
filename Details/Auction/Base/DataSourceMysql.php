<?php
/**
 * Base query builder for auction details context
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy, Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 18, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Base;

/**
 * Class DataSourceMysql
 */
class DataSourceMysql
    extends \Sam\Auction\AuctionList\DataSourceMysql
{
    /**
     * Return instance of self
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
