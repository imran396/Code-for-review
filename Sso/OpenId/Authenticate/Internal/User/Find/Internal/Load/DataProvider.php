<?php
/**
 * SAM-10584: SAM SSO
 * SAM-10724: Login through SSO
 *
 * Project        sam
 * @author        Georgi Nikolov
 * @version       SVN: $Id: $
 * @since         Jun 15, 2022
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Sso\OpenId\Authenticate\Internal\User\Find\Internal\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;

/**
 * @internal
 * Class DataProvider
 * @package Sam\User\Auth\Credentials\Load
 */
class DataProvider extends CustomizableClass
{
    use UserReadRepositoryCreateTrait;

    /**
     * Get instance of DataProvider
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load user by Uuid (not blocked, from active account)
     * @internal
     */
    public function loadUserIdByIdpUuid(string $idpUuid, bool $isReadOnlyDb = false): ?int
    {
        $row = $this->createUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->select([
                'u.id',
                'uau.idp_uuid',
            ])
            ->joinUserAuthenticationFilterIdpUuid($idpUuid)
            ->filterUserStatusId(Constants\User::US_ACTIVE)
            ->joinAccountFilterActive(true)
            ->skipFlag(Constants\User::FLAG_BLOCK)
            ->loadRow();
        if ($row) {
            return (int)$row['id'];
        }
        return null;
    }

    /**
     * Load user by Uiid and fallback to Email (not blocked, from active account)
     * @internal
     */
    public function loadUserIdByEmail(string $email, bool $isReadOnlyDb = false): ?int
    {
        $row = $this->createUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->select(['u.id'])
            ->filterEmail($email)
            ->filterUserStatusId(Constants\User::US_ACTIVE)
            ->joinAccountFilterActive(true)
            ->skipFlag(Constants\User::FLAG_BLOCK)
            ->loadRow();
        if ($row) {
            return (int)$row['id'];
        }
        return null;
    }
}
