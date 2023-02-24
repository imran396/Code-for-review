<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/21/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 =415 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class AuctionPermissionFormConstants
 */
class AuctionPermissionFormConstants
{
    public const CID_LST_AUCTION_VISIBILITY_ACCESS = 'aperm1';
    public const CID_LST_AUCTION_INFO_ACCESS = 'aperm2';
    public const CID_LST_AUCTION_CATALOG_ACCESS = 'aperm3';
    public const CID_LST_LIVE_VIEW_ACCESS = 'aperm4';
    public const CID_LST_LOT_DETAILS_ACCESS = 'aperm5';
    public const CID_LST_LOT_BIDDING_HISTORY_ACCESS = 'aperm6';
    public const CID_LST_LOT_BIDDING_INFO_ACCESS = 'aperm7';
    public const CID_LST_LOT_WINNING_BID_ACCESS = 'aperm8';
    public const CID_LST_LOT_STARTING_BID_ACCESS = 'aperm9';
    public const CID_CHK_BIDDING_PAUSED = 'aperm10';
    public const CID_BTN_SAVE = 'aperm11';
}
