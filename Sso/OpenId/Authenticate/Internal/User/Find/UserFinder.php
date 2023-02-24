<?php
/**
 * This service searches for the existing user by UUID or by e-mail.
 *
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

namespace Sam\Sso\OpenId\Authenticate\Internal\User\Find;

use Sam\Core\Service\CustomizableClass;
use Sam\Sso\OpenId\Authenticate\Internal\User\Find\Internal\Load\DataProviderCreateTrait;
use Sam\Sso\OpenId\Authenticate\Internal\User\Find\UserFindingResult as Result;

class UserFinder extends CustomizableClass
{
    use DataProviderCreateTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Search the existing user by UUID or by e-mail.
     * @param string $uuid
     * @param string $email
     * @param bool $isReadOnlyDb
     * @return UserFindingResult
     */
    public function find(string $uuid, string $email, bool $isReadOnlyDb = false): Result
    {
        $dataProvider = $this->createDataProvider();

        // First search user by UUID
        if ($uuid !== '') {
            $userId = $dataProvider->loadUserIdByIdpUuid($uuid, $isReadOnlyDb);
            if ($userId) {
                return Result::new()->foundByUuid($userId);
            }
        }

        // if the idp_uuid is empty, search user by email
        if ($email !== '') {
            $userId = $dataProvider->loadUserIdByEmail($email, $isReadOnlyDb);
            if ($userId) {
                return Result::new()->foundByEmail($userId);
            }
        }

        return Result::new()->notFound();
    }
}
