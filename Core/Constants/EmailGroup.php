<?php
/**
 * SAM-11834: Adjust the Auction Email page for v4.0. Apply admin translations for Email Template Group names
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class EmailGroup
 * @package Sam\Core\Constants
 */
class EmailGroup
{
    public const G_REGISTRATION = 1;
    public const G_AUCTION_GENERAL = 2;
    public const G_LIVE_OR_HYBRID_AUCTION = 3;
    public const G_TIMED_ONLINE_AUCTION = 4;
    public const G_INVOICING = 5;
    public const G_SETTLEMENTS = 6;
    public const G_SYSTEM_GENERAL = 7;

    public const TRANSLATION_KEYS = [
        self::G_REGISTRATION => 'group.registration.name',
        self::G_AUCTION_GENERAL => 'group.auction_general.name',
        self::G_LIVE_OR_HYBRID_AUCTION => 'group.live_or_hybrid_auction.name',
        self::G_TIMED_ONLINE_AUCTION => 'group.timed_online_auction.name',
        self::G_INVOICING => 'group.invoicing.name',
        self::G_SETTLEMENTS => 'group.settlements.name',
        self::G_SYSTEM_GENERAL => 'group.system_general.name',
    ];
}
