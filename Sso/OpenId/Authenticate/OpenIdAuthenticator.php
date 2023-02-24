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

namespace Sam\Sso\OpenId\Authenticate;

use Sam\Core\Service\CustomizableClass;
use Sam\Sso\OpenId\Authenticate\Internal\RedirectUrl\RedirectUrlDetectorCreateTrait;
use Sam\Sso\OpenId\Authenticate\Internal\User\Find\UserFinderCreateTrait;
use Sam\Sso\OpenId\Authenticate\Internal\User\Update\UserUpdaterCreateTrait;
use Sam\Sso\OpenId\Authenticate\Internal\Validate\AuthValidatorCreateTrait;
use Sam\Sso\OpenId\Authenticate\OpenIdAuthenticationResult as Result;
use Sam\Sso\OpenId\Client\OpenIdTokenData;
use Sam\Sso\OpenId\Common\Storage\OpenIdNativeSessionStorageCreateTrait;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;

/**
 * Authenticate user trough OpenId process. If pass validation and has user in SAM log the user in.
 */
class OpenIdAuthenticator extends CustomizableClass
{
    use AuthIdentityManagerCreateTrait;
    use AuthValidatorCreateTrait;
    use OpenIdNativeSessionStorageCreateTrait;
    use RedirectUrlDetectorCreateTrait;
    use UserFinderCreateTrait;
    use UserUpdaterCreateTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $systemAccountId system account id
     * @param string $queryState 'state' param coming from IdP redirect url. The state which initially is sent by our system to IdP.
     * @param string $code 'code' param coming from IdP redirect url, this is token data to decode.
     * @param string $errorDescriptionFromIdp 'error_description' param coming from IdP redirect url. It may contain public visible message that comes from IdP.
     * @param string $backPageUrlFromRequest back-page url
     * @return Result result object
     */
    public function authenticate(
        int $systemAccountId,
        string $queryState,
        string $code,
        string $errorDescriptionFromIdp,
        string $backPageUrlFromRequest
    ): Result {
        $authResult = Result::new();
        $authValidator = $this->createAuthValidator();
        $sessionStorage = $this->createOpenIdNativeSessionStorage();

        $validationResult = $authValidator->validate(
            $code,
            $queryState,
            $sessionStorage->getState(),
            $errorDescriptionFromIdp
        );

        if ($validationResult->hasError()) {
            log_debug(
                "OpenId authentication validation failed"
                . composeSuffix(['error' => $validationResult->concatenatedErrorMessage()])
            );
            $authResult->addErrors($validationResult->errorMessages());
        }

        $userId = null;
        if ($validationResult->isSuccess()) {
            $this->storeTokenData($validationResult->getTokenData());

            [$uuid, $email] = $validationResult->tokenDataExtractionResult->toUuidAndEmail();
            $authResult->setUserEmail($email);
            $userFindingResult = $this->createUserFinder()->find($uuid, $email);
            $userId = $userFindingResult->userId;
            if ($userId) {
                if ($userFindingResult->isFoundByEmail) {
                    $this->createUserUpdater()->updateUuid($userId, $uuid);
                }
                // all ok, we found the 'user id' in our system
                $this->createAuthIdentityManager()->applyUser($userId);
            } else {
                // 'user id' not found in our system
                $authResult->addNotice(OpenIdAuthenticationResult::NOTICE_AUTH_OK_USER_NOT_FOUND_IN_SYSTEM);
            }
        }

        $redirectUrl = $this->createRedirectUrlDetector()->detect(
            $systemAccountId,
            $validationResult->hasEmptyState(),
            $userId,
            $backPageUrlFromRequest,
            $sessionStorage->getBackUrl()
        );

        if ($userId) {
            $sessionStorage->deleteBackUrl();
        }

        $authResult->setRedirectUrl($redirectUrl);
        return $authResult;
    }

    protected function storeTokenData(OpenIdTokenData $tokenData): void
    {
        $this->createOpenIdNativeSessionStorage()
            ->setAccessToken($tokenData->accessToken)
            ->setRefreshToken($tokenData->refreshToken)
            ->setTokenExpirationTs($tokenData->calcExpiresInTs());
    }
}
