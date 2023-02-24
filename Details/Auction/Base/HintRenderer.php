<?php
/**
 * Placeholder informational section rendering special for Auction Feed
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Mar 2, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Base;

use Sam\Core\Constants;

/**
 * Class HintRenderer
 * @package Sam\Details
 */
abstract class HintRenderer extends \Sam\Details\Core\HintRenderer
{
    /**
     * @var string
     */
    protected string $beginEndKey = Constants\AuctionDetail::PL_NAME;
    /**
     * @var string
     */
    protected string $compositeView = Constants\AuctionDetail::PL_NAME
    . '|' . Constants\AuctionDetail::PL_DESCRIPTION . '[flt=StripTags;Length(20)]'
    . '|' . Constants\AuctionDetail::NOT_AVAILABLE;
    /**
     * See, uploads/inlinehelp/admin_auction_details.csv
     */
    protected string $inlineHelpSection = 'admin_auction_details';
}
