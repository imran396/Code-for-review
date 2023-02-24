<?php

namespace Sam\Core\Constants;

/**
 * Class Bid
 * @package Sam\Core\Constants
 */
class Bid
{
    // Absentee Bid Type (ab.bid_type)
    public const ABT_REGULAR = 1;
    public const ABT_PHONE = 2;
    /** @var int[] */
    public static array $absenteeBidTypes = [self::ABT_REGULAR, self::ABT_PHONE];
}
