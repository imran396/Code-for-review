<?php
/**
 * SAM-4664: User dates detector class
 * Constants for Bid transaction
 *
 * @author        Vahagn Hovsepyan
 * @since         Dec 26, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Core\Constants;

/**
 * Class BidTransaction
 */
class BidTransaction
{
    public const BS_WINNER = 1;
    public const BS_LOOSER = 2;
    public const BS_FAILED = 3;

    public const TYPE_REGULAR = 'regular';
    public const TYPE_BUY_NOW = 'buynow';
    public const TYPE_OFFER = 'offer';
    public const TYPE_FORCE_BID = 'forcebid';
    /** @var string[] */
    public static array $types = [self::TYPE_REGULAR, self::TYPE_BUY_NOW, self::TYPE_OFFER, self::TYPE_FORCE_BID];
}
