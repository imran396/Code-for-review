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

namespace Sam\Sso\OpenId\Jwt\Extract\Internal\Load\DataProvider;

use Exception;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\WriteRepository\Entity\UserAuthentication\UserAuthenticationWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

class DataProvider extends CustomizableClass
{
    use CurrentDateTrait;
    use UserAuthenticationWriteRepositoryAwareTrait;
    use UserLoaderAwareTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function parseKeySet(array $jsonWebKeys): array
    {
        $jwKeys = $error = '';
        try {
            $jwKeys = JWK::parseKeySet($jsonWebKeys);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        return [$jwKeys, $error];
    }

    public function decodeJwt(string $idToken, array $jwKeys): array
    {
        $decoded = $error = '';
        try {
            $decoded = (array)JWT::decode($idToken, $jwKeys);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        return [$decoded, $error];
    }

}
