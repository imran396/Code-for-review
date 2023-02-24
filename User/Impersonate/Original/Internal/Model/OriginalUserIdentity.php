<?php
/**
 * Original user - is the source user, who performs impersonation
 *
 * SAM-6576: File system key-value caching for visitor session data
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 30, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Impersonate\Original\Internal\Model;

use Sam\Core\Service\CustomizableClass;

/**
 * Class OriginalUserIdentity
 */
class OriginalUserIdentity extends CustomizableClass
{
    public readonly int $userId;
    public readonly string $username;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $userId
     * @param string $username
     * @return $this
     */
    public function construct(int $userId, string $username): static
    {
        $this->userId = $userId;
        $this->username = $username;
        return $this;
    }
}
