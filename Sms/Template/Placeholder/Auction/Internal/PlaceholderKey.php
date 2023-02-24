<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\Auction\Internal;

/**
 * Class PlaceholderKey
 * @package Sam\Sms\Template\Placeholder\AuctionLot\Internal
 * @internal
 */
class PlaceholderKey
{
    public const ID = 'auction_id';
    public const TYPE = 'auction_type';
    public const END_DATE = 'auction_end_date';
    public const START_DATE = 'auction_start_date';
    public const NAME = 'auction_name';
    public const SALE_NO = 'auction_number';
    public const EVENT_ID = 'event_id';
}
