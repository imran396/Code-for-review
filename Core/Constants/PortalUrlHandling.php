<?php
/**
 * Created by IntelliJ IDEA.
 * User: namax
 * Date: 28/07/16
 * Time: 02:16 PM
 */

namespace Sam\Core\Constants;

/**
 * Class PortalUrlHandling
 * @package Sam\Core\Constants
 */
class PortalUrlHandling
{
    public const MAIN_DOMAIN = 'maindomain';
    public const SUBDOMAINS = 'subdomains';

    /** @var string[] */
    public static array $types = [self::MAIN_DOMAIN, self::SUBDOMAINS];
    /** @var string[] */
    public static array $typeNames = [
        self::MAIN_DOMAIN => 'maindomain',
        self::SUBDOMAINS => 'subdomains',
    ];
}
