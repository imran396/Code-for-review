<?php
/**
 * SAM-10584: SAM SSO
 * SAM-10725: Add user through SSO
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

namespace Sam\Sso\OpenId\Signup;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerDtoFactory;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\EntityMaker\User\Lock\UserMakerLockerCreateTrait;
use Sam\EntityMaker\User\Save\UserMakerProducerCreateTrait;
use Sam\EntityMaker\User\Validate\UserMakerValidatorCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Sso\OpenId\Signup\OpenIdUserProductionResult as Result;
use Sam\Sso\OpenId\Url\OpenIdUrlProviderCreateTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * creates/produces user when registration option is enabled,
 * so that when user creates profile at OpenId/IdentityProvider/ his profile is created in SAM automatically.
 */
class OpenIdUserProducer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use OpenIdUrlProviderCreateTrait;
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;
    use UserLoaderAwareTrait;
    use UserMakerLockerCreateTrait;
    use UserMakerValidatorCreateTrait;
    use UserMakerProducerCreateTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function produce(string $email): Result
    {
        $result = Result::new()->construct();

        /**
         * @var UserMakerInputDto $userInputDto
         * @var UserMakerConfigDto $userConfigDto
         */
        [$userInputDto, $userConfigDto] = UserMakerDtoFactory::new()->createDtos(
            Mode::SSO_RESPONSIVE,
            $this->getUserLoader()->loadSystemUserId(),
            null,
            $this->getSystemAccountId()
        );

        $userInputDto = $this->prepareUserInputDto($userInputDto, $email);
        $userConfigDto = $this->prepareUserConfigDto($userConfigDto);
        $this->definePrivileges($userInputDto, $userConfigDto);

        $lockingResult = $this->createUserMakerLocker()->lock($userInputDto, $userConfigDto); // #user-lock-12
        if (!$lockingResult->isSuccess()) {
            log_error(
                "User locking failed, when creating new user by OpenId SSO Sign Up"
                . composeSuffix(['errors' => $lockingResult->getUnsuccessfulLockingResults()])
            );
            $result->addError(Result::ERR_USER_LOCKING);
            return $result;
        }

        try {
            $validator = $this->createUserMakerValidator()->construct($userInputDto, $userConfigDto);
            if (!$validator->validate()) {
                $result->addError(Result::ERR_USER_INPUT_DTO);
                return $result;
            }

            $userProducer = $this->createUserMakerProducer()->construct($userInputDto, $userConfigDto);
            $userProducer->produce();
            $redirectUrl = $this->createOpenIdUrlProvider()->buildLoginWithRedirectToProfileUrl();
            $result->setRedirectUrl($redirectUrl);
        } finally {
            $this->createUserMakerLocker()->unlock($userConfigDto); // #user-lock-12, unlock after success or failed save
        }
        return $result;
    }

    protected function definePrivileges(UserMakerInputDto $userInputDto, UserMakerConfigDto $userConfigDto): void
    {
        // Privileges: bidder
        $isDontMakeUserBidder = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::DONT_MAKE_USER_BIDDER);
        if (!$isDontMakeUserBidder) {
            $userConfigDto->saveBidderPrivileges = true;
            $userInputDto->bidder = '1';
            $userInputDto->bidderAgent = '';
            $userInputDto->bidderPreferred = (string)(bool)$this->getSettingsManager()
                ->getForMain(Constants\Setting::AUTO_PREFERRED);
        }
    }

    protected function prepareUserInputDto(UserMakerInputDto $userInputDto, string $email): UserMakerInputDto
    {
        $userInputDto->email = $email;
        $userInputDto->username = $email;
        $userInputDto->accountId = $this->cfg()->get('core->portal->mainAccountId');
        $userInputDto->regAuthDate = $this->getCurrentDateUtc()->format('Y-m-01 H:i:s');
        return $userInputDto;
    }

    protected function prepareUserConfigDto(UserMakerConfigDto $userConfigDto): UserMakerConfigDto
    {
        $userConfigDto->isSignupPage = true;
        $userConfigDto->isSignupAccountAdmin = true;
        return $userConfigDto;
    }

}
