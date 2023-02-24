<?php
/**
 * SAM-5877: Advanced search rendering module
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\Cache;

use Sam\Core\Service\CustomizableClass;
use Sam\User\Flag\UserFlaggingAwareTrait;

/**
 * Class UserFlagCacher
 */
class UserFlagCacher extends CustomizableClass
{
    use UserFlaggingAwareTrait;

    private array $userFlags = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return user flag (cached)
     * @param int|null $userId null - in case we have no user (userId == null), then we return appropriate flag Constants\User::FLAG_NONE
     * @param int $accountId
     * @return int
     */
    public function getFlag(?int $userId, int $accountId): int
    {
        $key = (int)$userId . '_' . $accountId;
        if (!array_key_exists($key, $this->userFlags)) {
            $this->userFlags[$key] = $this->getUserFlagging()->detectFlag($userId, $accountId);
        }
        return $this->userFlags[$key];
    }
}
