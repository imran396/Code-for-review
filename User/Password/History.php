<?php
/**
 * Password strength checker
 *
 * SAM-1238: Increased password security
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan, Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           4 May, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Password;

use Sam\Core\Data\JsonArray;
use Sam\Core\Service\CustomizableClass;
use UserAuthentication;

/**
 * Class History
 */
class History extends CustomizableClass
{
    // old password hashes limit
    private const OLD_PASSWORD_HISTORY_LIMIT = 10;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $password
     * @param UserAuthentication $userAuthentication
     * @return UserAuthentication
     */
    public function addPassword(string $password, UserAuthentication $userAuthentication): UserAuthentication
    {
        $passwordHash = HashHelper::new()->encrypt($password);
        $historyPasswordHashes = new JsonArray($userAuthentication->PwordHistory);
        $historyPasswordHashes->unshift($passwordHash);

        $historyPasswordHashesCount = $historyPasswordHashes->count();
        if ($historyPasswordHashesCount > self::OLD_PASSWORD_HISTORY_LIMIT) {
            $historyPasswordHashes->pop();
        }

        $userAuthentication->PwordHistory = $historyPasswordHashes->getJson();
        return $userAuthentication;
    }

    /**
     * Check if password exists in history
     * @param string $password
     * @param UserAuthentication $userAuthentication
     * @param int $checkedCount limit checking password count
     * @return bool
     */
    public function existInHistory(string $password, UserAuthentication $userAuthentication, int $checkedCount): bool
    {
        $isFound = false;
        $passwordHistory = [];

        if ($userAuthentication->PwordHistory) {
            $historyPasswordHashes = new JsonArray($userAuthentication->PwordHistory);
            $passwordHistory = array_slice($historyPasswordHashes->getArray(), 0, $checkedCount);
        }

        $hash = HashHelper::new();
        foreach ($passwordHistory as $passwordHash) {
            if ($hash->verify($password, $passwordHash)) {
                $isFound = true;
                break;
            }
        }
        return $isFound;
    }
}
