<?php
/**
 * SAM-5795: "None" option missed in Auction list auto-completer at "Custom Lots" report page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           12-09, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Core\Constants;


/**
 * Class AuctionListAutocomplete
 * @package Sam\Core\Constants
 */
class AuctionListAutocomplete
{
    public const UNASSIGNED_AUCTION_ID = -1;
    public const UNASSIGNED_AUCTION_LABEL = 'None (unassigned to auction)';
}
