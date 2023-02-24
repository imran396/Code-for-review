<?php
/**
 * Created by IntelliJ IDEA.
 * User: namax
 * Date: 27/07/16
 * Time: 08:17 PM
 */

namespace Sam\Core\Constants;

/**
 * Class AccountVisibility
 * @package Sam\Core\Constants
 */
class AccountVisibility
{
    public const SEPARATE = 'separate';
    public const DIRECT_LINK = 'directlink';
    public const TRANSPARENT = 'transparent';

    /** @var string[] */
    public static array $types = [self::SEPARATE, self::DIRECT_LINK, self::TRANSPARENT];

    /** @var string[] */
    public static array $typeNames = [
        self::SEPARATE => 'separate',
        self::DIRECT_LINK => 'directlink',
        self::TRANSPARENT => 'transparent',
    ];
}
