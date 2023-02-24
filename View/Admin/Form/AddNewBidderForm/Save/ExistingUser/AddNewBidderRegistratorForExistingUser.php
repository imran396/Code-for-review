<?php
/**
 * SAM-5716: Extract auction bidder validation and building logic from "Add New Bidder" form
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AddNewBidderForm\Save\ExistingUser;

use AuctionBidder;
use DateTime;
use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\EntityMaker\User\Lock\UserMakerLockerCreateTrait;
use Sam\EntityMaker\User\Save\UserMakerProducerCreateTrait;
use Sam\EntityMaker\User\Validate\UserMakerValidatorCreateTrait;
use Sam\Settings\SettingsManager;
use Sam\Storage\WriteRepository\Entity\AuctionBidder\AuctionBidderWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoader;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\View\Admin\Form\AddNewBidderForm\Dto\AddNewBidderDto;
use User;
use UserBilling;

/**
 * Class AddNewBidderRegistratorForExistingUser
 * @package Sam\View\Admin\Form\AddNewBidderForm\Save\ExistingUser
 */
class AddNewBidderRegistratorForExistingUser extends CustomizableClass
{
    use AuctionBidderHelperAwareTrait;
    use AuctionBidderWriteRepositoryAwareTrait;
    use BidderNumPaddingAwareTrait;
    use OptionalsTrait;
    use UserLoaderAwareTrait;
    use UserMakerLockerCreateTrait;
    use UserMakerProducerCreateTrait;
    use UserMakerValidatorCreateTrait;

    public const OP_ALL_USER_REQUIRE_CC_AUTH = OptionalKeyConstants::KEY_ALL_USER_REQUIRE_CC_AUTH; // bool
    public const OP_PLACE_BID_REQUIRE_CC = OptionalKeyConstants::KEY_PLACE_BID_REQUIRE_CC; // bool
    public const OP_USER = 'user';
    public const OP_USER_BILLING = 'userBilling';

    private ?User $changedUser = null;
    private array $successMessageParameters = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param AddNewBidderDto $addNewBidderDto
     * @param UserMakerInputDto $userInputDto
     * @param UserMakerConfigDto $userConfigDto
     */
    public function register(
        AddNewBidderDto $addNewBidderDto,
        UserMakerInputDto $userInputDto,
        UserMakerConfigDto $userConfigDto
    ): void {
        /** @var User|null $user */
        $user = $this->fetchOptional(self::OP_USER, [$addNewBidderDto->email]);
        if (!$user) {
            log_error('Available user not found by email' . composeSuffix(['email' => $addNewBidderDto->email]));
            return;
        }
        // Update user data only if his existing CC info isn't valid
        if (!$this->isValidUserCcInfo($user, $addNewBidderDto->auctionAccountId)) {
            $lockingResult = $this->createUserMakerLocker()->lock($userInputDto, $userConfigDto); // #user-lock-8
            if (!$lockingResult->isSuccess()) {
                return;
            }
            try {
                $validator = $this->createUserMakerValidator()->construct($userInputDto, $userConfigDto);
                $isValidInputUserDto = $validator->validate();
                if (!$isValidInputUserDto) {
                    log_error(
                        'User input dto is invalid'
                        . composeSuffix(['errors' => $validator->getErrorMessages()])
                    );
                    return;
                }
                $producer = $this->createUserMakerProducer()->construct($userInputDto, $userConfigDto);
                $producer->produce();
                $user = $producer->getUser();
                $this->changedUser = $user;
            } finally {
                $this->createUserMakerLocker()->unlock($userConfigDto); // #user-lock-8, unlock after success or failed save
            }
        }
        $auctionBidder = $this->makeBidder($addNewBidderDto, $user, $userConfigDto->editorUserId);
        if (!$auctionBidder) {
            log_error(
                "Available AuctionBidder not found, when adding bidder to auction"
                . composeSuffix(['uid' => $user->Id, 'aid' => $addNewBidderDto->auctionAccountId, 'bidderNum' => $addNewBidderDto->bidderNum])
            );
            return;
        }
        $this->getAuctionBidderWriteRepository()->saveWithModifier($auctionBidder, $userConfigDto->editorUserId);
        $this->setSuccessMessageParameters($user->Username, $auctionBidder->BidderNum);
    }

    /**
     * @return User|null
     */
    public function getChangedUser(): ?User
    {
        return $this->changedUser;
    }

    /**
     * @return string
     */
    public function getSuccessMessage(): string
    {
        $bidderNum = $this->getBidderNumberPadding()->clear($this->successMessageParameters['bidderNum']);
        return sprintf(
            'User "%s" has been assigned with bidder number "%s"',
            $this->successMessageParameters['username'],
            $bidderNum
        );
    }

    /**
     * @param string $username
     * @param string $bidderNum
     */
    private function setSuccessMessageParameters(string $username, string $bidderNum): void
    {
        $this->successMessageParameters = [
            'username' => $username,
            'bidderNum' => $bidderNum
        ];
    }

    /**
     * @param AddNewBidderDto $dto
     * @param User $user
     * @param int $editorUserId
     * @return AuctionBidder|null
     */
    private function makeBidder(AddNewBidderDto $dto, User $user, int $editorUserId): ?AuctionBidder
    {
        $bidderNum = $dto->bidderNum;
        $auctionBidderHelper = $this->getAuctionBidderHelper();
        $auctionBidder = $auctionBidderHelper->produceApprovedBidder($user->Id, $dto->auctionId, $bidderNum, $editorUserId);
        return $auctionBidder;
    }

    /**
     * Check existing user's CC Info, if PlaceBidRequireCc or AllUserRequireCcAuth is On
     * @param User $user
     * @param int $auctionAccountId
     * @return bool
     */
    private function isValidUserCcInfo(User $user, int $auctionAccountId): bool
    {
        /** @var UserBilling $userBilling */
        $userBilling = $this->fetchOptional(self::OP_USER_BILLING, [$user->Id]);
        $isCCValid = $userBilling->CcNumber !== ''
            && $userBilling->CcExpDate !== ''
            && !$this->isCcExpired($userBilling->CcExpDate);
        $isCCRequired = $this->isCcRequired($auctionAccountId);
        $isSuccess = !$isCCRequired || $isCCValid;
        log_debug(
            'Cc Info ' . ($isSuccess ? 'IS' : 'NOT') . ' validated. '
            . composeSuffix(
                [
                    'CCRequired' => (int)$isCCRequired,
                    'Has Valid CC' => (int)$isCCValid,
                ]
            )
        );

        return $isSuccess;
    }

    /**
     * @param string $expDate
     * @return bool
     */
    private function isCcExpired(string $expDate): bool
    {
        $expires = DateTime::createFromFormat('m-Y', $expDate);
        $now = new DateTime();
        return $expires < $now;
    }

    /**
     * @param int $auctionAccountId
     * @return bool
     */
    private function isCcRequired(int $auctionAccountId): bool
    {
        return $this->fetchOptional(self::OP_ALL_USER_REQUIRE_CC_AUTH, [$auctionAccountId])
            || $this->fetchOptional(self::OP_PLACE_BID_REQUIRE_CC, [$auctionAccountId]);
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_ALL_USER_REQUIRE_CC_AUTH] = $optionals[self::OP_ALL_USER_REQUIRE_CC_AUTH]
            ?? static function (int $auctionAccountId): bool {
                return (bool)SettingsManager::new()->get(Constants\Setting::ALL_USER_REQUIRE_CC_AUTH, $auctionAccountId);
            };

        $optionals[self::OP_PLACE_BID_REQUIRE_CC] = $optionals[self::OP_PLACE_BID_REQUIRE_CC]
            ?? static function (int $auctionAccountId): bool {
                return (bool)SettingsManager::new()->get(Constants\Setting::PLACE_BID_REQUIRE_CC, $auctionAccountId);
            };

        if (!array_key_exists(self::OP_USER, $optionals)) {
            $optionals[self::OP_USER] = static function (string $email): ?User {
                return UserLoader::new()->loadByEmail($email, true);
            };
        }

        $optionals[self::OP_USER_BILLING] = $optionals[self::OP_USER_BILLING]
            ?? static function (int $userId): UserBilling {
                return UserLoader::new()->loadUserBillingOrCreate($userId, true);
            };

        $this->setOptionals($optionals);
    }
}
