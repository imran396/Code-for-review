<?php
/**
 * SAM-10844: Extend parameter filtering on auction lots, my items level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\Lot\Internal\MyItems;

use Sam\Api\GraphQL\Load\Internal\Lot\Internal\LotAggregateDataSourceInterface;
use Sam\Api\GraphQL\Load\Internal\Lot\Internal\LotAggregateTrait;

/**
 * Class BiddingMyItemsAggregateDatasourceMysql
 * @package Sam\Api\GraphQL\Load\Internal\Lot\Internal\MyItems
 */
class BiddingMyItemsAggregateDatasourceMysql extends \Sam\Core\Lot\LotList\MyItems\DataSource\BiddingMysql implements LotAggregateDataSourceInterface
{
    use LotAggregateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
