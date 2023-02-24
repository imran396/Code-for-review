<?php
/**
 * SAM-8893: Relocate pure functions from GeneralValidator to \Sam\Core namespace
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Ip\Validate;

use Sam\Core\Service\CustomizableClass;

/**
 * Class NetAddressChecker
 * @package Sam\Core\Ip\Validate
 */
class NetAddressChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Returns whether or not $hostname is a valid, fully-qualified hostname,
     * e.g., 'some.host.com'.
     *
     * @param string $hostname the hostname to test
     * @return bool the result
     * #[Pure]
     */
    public function isFullyQualifiedHostname(string $hostname): bool
    {
        return strlen($hostname) < 255
            && preg_match(
                '/^[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])?' .
                '(\.[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])?)*\.[a-z]{2,62}$/i',
                $hostname
            );
    }

    /**
     * Returns whether or not $ip is a valid IP address, e.g., '1.2.3.4'.
     *
     * @param string $ip the ip to test
     * @return bool the result
     * #[Pure]
     */
    public function isIp(string $ip): bool
    {
        $is = true;
        $isFound = preg_match('/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/', $ip, $matches);
        if (!$isFound) {
            $is = false;
        } else {
            array_shift($matches);
            foreach ($matches as $dec) {
                if ($dec > 255) {
                    $is = false;
                    break;
                }
            }
        }
        return $is;
    }

    /**
     * Returns whether or not valid sub-domain name
     *
     * @param string $subDomainName
     * @return bool
     * #[Pure]
     */
    public function isSubDomain(string $subDomainName): bool
    {
        return strlen($subDomainName) < 63
            && preg_match('/^[a-z\d]+([-|.][a-z\d]+)*$/i', $subDomainName);
    }
}
