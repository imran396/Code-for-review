<?php

namespace Sam\Core\Constants;

/**
 * Class AuctionDomainMode
 * @package Sam\Core\Constants
 */
class AuctionDomainMode
{
    public const ANY_DOMAIN = 'A';
    public const ALWAYS_SUB_DOMAIN = 'S';
    public const ALWAYS_MAIN_DOMAIN = 'M';

    /** @var string[] */
    public static array $optionTexts = [
        self::ANY_DOMAIN => 'Any',
        self::ALWAYS_SUB_DOMAIN => 'Always the auction account (sub) domain',
        self::ALWAYS_MAIN_DOMAIN => 'Always the main domain',
    ];
}
