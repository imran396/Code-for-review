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

namespace Sam\Core\Email\Validate;

use Sam\Core\Ip\Validate\NetAddressChecker;
use Sam\Core\Service\CustomizableClass;

/**
 * Class EmailAddressChecker
 * @package Sam\Core\Email\Validate
 */
class EmailAddressChecker extends CustomizableClass
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
     * Returns whether or not $localPart is a valid "local part" of an email
     * address.
     *
     * This check is RFC2822 compliant with the following exceptions:
     *
     *  o comments: user(a comment) of user(a comment)@address.com
     *  o quoted strings: "a valid@username" of "a valid@username"@address.com
     *  o escaped characters: a user of a user@address.com
     *
     * @param string $localPart the local part to test. Expects an unescaped string. No SQL escaping!
     * @return bool
     * @see http://tools.ietf.org/html/rfc2822
     * #[Pure]
     */
    public function isEmailLocalPart(string $localPart): bool
    {
        return strlen($localPart) < 64 && preg_match(
                '/^[-\\w!#$%&\'*+\\/=?^_`{|}~]+' .
                '(\\.[-\\w!#$%&\'*+\\/=?^_`{|}~]+)*$/',
                $localPart
            );
    }

    /**
     * Returns whether or not this is a valid email address.
     *
     * This check is RFC2822 compliant with the following exceptions:
     *
     *  o comments: user(a comment) of user(a comment)@address.com
     *  o quoted strings: "a valid@username" of "a valid@username"@address.com
     *  o escaped characters: a user of a user@address.com
     *
     * @param string $email expects an unescaped string. No SQL escaping!
     * @return bool
     * @see http://tools.ietf.org/html/rfc2822
     * #[Pure]
     */
    public function isEmail(string $email): bool
    {
        $netAddressChecker = NetAddressChecker::new();
        if (preg_match('/^(.+)@(.+)$/', $email, $matches)) {
            return $this->isEmailLocalPart($matches[1])
                && (
                    $netAddressChecker->isFullyQualifiedHostname($matches[2])
                    || $netAddressChecker->isIp($matches[2])
                );
        }

        return false;
    }
}
