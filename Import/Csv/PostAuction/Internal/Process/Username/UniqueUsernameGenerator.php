<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Process\Username;

use Sam\Core\Email\Validate\EmailAddressChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Lock\UserModificationLockerCreateTrait;
use Sam\User\Validate\UserExistenceCheckerAwareTrait;

/**
 * Class responsible to generate a unique username for bidder when importing bids
 *
 * Class UniqueUsernameGenerator
 * @package Sam\Import\Csv\PostAuction\Internal\Process
 * @internal
 */
class UniqueUsernameGenerator extends CustomizableClass
{
    use UserExistenceCheckerAwareTrait;
    use UserModificationLockerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Generate unique username by local-part of email address or adding unique index
     * @param string $username
     * @param string $email
     * @return string
     */
    public function generate(string $username, string $email): string
    {
        $username = $username ?: $email;
        if (EmailAddressChecker::new()->isEmail($username)) {
            [$emailUsername, $emailDomain] = explode('@', $username);
            $generatedUsername = $this->makeUniqueUsername($emailUsername, '@' . $emailDomain);
        } else {
            $generatedUsername = $this->makeUniqueUsername($username);
        }
        return $generatedUsername;
    }

    /**
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    protected function makeUniqueUsername(string $prefix, string $suffix = ''): string
    {
        $iterator = 0;
        do {
            $generatedUsername = sprintf('%s%s%s', $prefix, $iterator ?: '', $suffix);
            $iterator++;
        } while ($this->getUserExistenceChecker()->existByUsername($generatedUsername));

        return $generatedUsername;
    }
}
