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

namespace Sam\Sso\OpenId\Authenticate\Internal\User\Update;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\UserAuthentication\UserAuthenticationWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

class UserUpdater extends CustomizableClass
{
    use UserAuthenticationWriteRepositoryAwareTrait;
    use UserLoaderAwareTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function updateUuid(int $userId, string $uuid, bool $isReadOnlyDb = false): void
    {
        $userAuthentication = $this->getUserLoader()->loadUserAuthenticationOrCreate($userId, $isReadOnlyDb);
        $userAuthentication->IdpUuid = $uuid;
        $this->getUserAuthenticationWriteRepository()->saveWithSystemModifier($userAuthentication);
    }

}
