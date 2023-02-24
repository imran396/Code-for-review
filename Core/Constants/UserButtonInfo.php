<?php

namespace Sam\Core\Constants;

/**
 * Class UserButtonInfo
 * @package Sam\Core\Constants
 */
class UserButtonInfo
{
    public const CUSTOMER_NO = 1;
    public const NAME = 2;
    public const USERNAME = 3;

    /** @var string[] */
    public static array $availableInfo = [
        self::CUSTOMER_NO => "Customer no",
        self::NAME => "Name",
        self::USERNAME => "Username",
    ];
}
