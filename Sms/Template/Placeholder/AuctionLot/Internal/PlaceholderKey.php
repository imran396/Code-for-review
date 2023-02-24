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

namespace Sam\Sms\Template\Placeholder\AuctionLot\Internal;

/**
 * Class PlaceholderKey
 * @package Sam\Sms\Template\Placeholder\AuctionLot\Internal
 * @internal
 */
class PlaceholderKey
{
    public const QUANTITY = 'quantity';
    public const BUY_NOW_AMOUNT = 'buy_now_price';
    public const SAMPLE_LOT = 'featured';
    public const NOTE_TO_CLERK = 'note_to_clerk';
    public const GENERAL_NOTE = 'general_note';
    public const AUCTION_ID = 'auction_id';
    public const AUCTION_TYPE = 'auction_type';
    public const STATUS = 'status';
    public const NAME = 'lot_name';
    public const DETAILS_URL = 'lot_url';
    public const DETAILS_URL_WITHOUT_SEO = 'lot_url_without_seo';
    public const NO = 'lot_number';
    public const CURRENT_BID = 'current_bid';
    public const ASKING_BID = 'next_bid';
    public const BID_QUANTITY = 'bids';
    public const START_DATE = 'lot_start_date';
    public const END_DATE = 'lot_end_date';
    public const TIME_LEFT = 'time_left';
}
