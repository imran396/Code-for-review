<?php


namespace Sam\Core\Constants;

/**
 * Class TimedOnlineOfferBid
 * @package Sam\Core\Constants
 */
class TimedOnlineOfferBid
{
    public const STATUS_ACCEPTED = 1;
    public const STATUS_REJECTED = 2;
    public const STATUS_NONE = 0;

    public const STATUS_NAMES = [
        self::STATUS_NONE => 'none',
        self::STATUS_ACCEPTED => 'accepted',
        self::STATUS_REJECTED => 'rejected',
    ];
}
