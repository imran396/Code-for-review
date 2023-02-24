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

namespace Sam\View\Admin\Form\AddNewBidderForm\Save\NewUser;

use Sam\Application\Access\AuctionBidderAccessCheckerCreateTrait;
use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Bidder\AuctionBidder\Save\AuctionBidderSaverCreateTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\EntityMaker\User\Lock\UserMakerLockerCreateTrait;
use Sam\EntityMaker\User\Save\UserMakerProducerCreateTrait;
use Sam\EntityMaker\User\Validate\UserMakerValidatorCreateTrait;
use Sam\Storage\WriteRepository\Entity\Bidder\BidderWriteRepositoryAwareTrait;
use Sam\User\Password;
use Sam\User\Save\UserCustomerNoApplierCreateTrait;
use Sam\User\Validate\UserExistenceCheckerAwareTrait;
use Sam\View\Admin\Form\AddNewBidderForm\Dto\AddNewBidderDto;
use User;

/**
 * Class AddNewBidderRegistratorForNewUser
 * @package Sam\View\Admin\Form\AddNewBidderForm\Save\NewUser
 */
class AddNewBidderRegistratorForNewUser extends CustomizableClass
{
    use AuctionBidderAccessCheckerCreateTrait;
    use AuctionBidderHelperAwareTrait;
    use AuctionBidderSaverCreateTrait;
    use BidderWriteRepositoryAwareTrait;
    use EditorUserAwareTrait;
    use EntityFactoryCreateTrait;
    use OptionalsTrait;
    use UserCustomerNoApplierCreateTrait;
    use UserExistenceCheckerAwareTrait;
    use UserMakerLockerCreateTrait;
    use UserMakerProducerCreateTrait;
    use UserMakerValidatorCreateTrait;

    public const OP_USER_PASSWORD = 'userPassword';

    protected ?User $newUser = null;
    protected array $successMessageParameters = [];

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
        $lockResult = $this->createUserMakerLocker()->lock($userInputDto, $userConfigDto); // #user-lock-9
        if (!$lockResult->isSuccess()) {
            log_error('Attempts limit exceeded when trying to get a free lock to create a user.');
            return;
        }
        try {
            $username = $this->generateUsername($addNewBidderDto);
            $userInputDto->username = $username;
            $password = $this->fetchOptional(self::OP_USER_PASSWORD);
            $userInputDto->pword = $password;
            $this->setSuccessMessageParameters($username, $password);

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

            $user = $this->createUserCustomerNoApplier()
                ->construct()
                ->apply($user, $userConfigDto->editorUserId);

            $this->makeBidder($addNewBidderDto, $user, $userConfigDto->editorUserId);
            $this->newUser = $user;
        } finally {
            $this->createUserMakerLocker()->unlock($userConfigDto); // #user-lock-9, unlock after success or failed save or validation
        }
    }

    /**
     * @return User|null
     */
    public function getChangedUser(): ?User
    {
        return $this->newUser;
    }

    /**
     * @return string
     */
    public function getSuccessMessage(): string
    {
        return sprintf(
            'New bidder added with username "%s" and password "%s"',
            $this->successMessageParameters['username'] ?? '',
            $this->successMessageParameters['password'] ?? ''
        );
    }

    /**
     * @param string $username
     * @param string $password
     */
    protected function setSuccessMessageParameters(string $username, string $password): void
    {
        $this->successMessageParameters = [
            'username' => $username,
            'password' => $password
        ];
    }

    /**
     * @param AddNewBidderDto $dto
     * @param User $user
     * @param int $editorUserId
     */
    protected function makeBidder(AddNewBidderDto $dto, User $user, int $editorUserId): void
    {
        $bidder = $this->createEntityFactory()->bidder();
        $bidder->UserId = $user->Id;
        if ($this->createAuctionBidderAccessChecker()->isAllowedMakeBidderPreferred($dto->auctionAccountId)) {
            $dto->preferredBidder
                ? $bidder->toPreferred()
                : $bidder->dropPreferred();
        }
        $this->getBidderWriteRepository()->saveWithModifier($bidder, $editorUserId);

        $bidderNum = $dto->bidderNum;
        $auctionBidderHelper = $this->getAuctionBidderHelper();
        $auctionBidder = $auctionBidderHelper->produceApprovedBidder(
            $user->Id,
            $dto->auctionId,
            $bidderNum,
            $editorUserId
        );

        if (!$auctionBidder) {
            log_error(
                'Auction bidder was not created'
                . composeSuffix(['u' => $user->Id, 'a' => $dto->auctionAccountId, 'bidder#' => $bidderNum])
            );
            return;
        }

        if ($dto->reseller) {
            $auctionBidder->ResellerApproved = true;
            $auctionBidder->ResellerId = $dto->resellerId;
        }
        $this->createAuctionBidderSaver()
            ->construct()
            ->save($auctionBidder, $editorUserId);
    }

    /**
     * Generate unique username from first letter of firstname and last name. Add numbers if needed.
     * @param AddNewBidderDto $dto
     * @return string
     */
    protected function generateUsername(AddNewBidderDto $dto): string
    {
        $shippingAddress = $dto->shippingAddress;
        $billingAddress = $dto->billingAddress;

        $firstName = '';
        if ($shippingAddress->firstName) {
            $firstName = $shippingAddress->firstName;
        } elseif ($billingAddress->firstName) {
            $firstName = $billingAddress->firstName;
        }

        $lastName = '';
        if ($shippingAddress->lastName) {
            $lastName = $shippingAddress->lastName;
        } elseif ($billingAddress->lastName) {
            $lastName = $billingAddress->lastName;
        }

        $username = '';
        if ($firstName) {
            $username = strtolower($firstName)[0];
        }
        if ($lastName) {
            $username .= strtolower($lastName);
        }
        if ($username === '') {
            $username = 'bidder';
        }

        return $this->uniqueUsername($username);
    }

    /**
     * @param string $username
     * @return string
     */
    protected function uniqueUsername(string $username): string
    {
        $index = 0;
        do {
            $generatedUsername = sprintf('%s%s', $username, $index ?: '');
            $index++;
        } while ($this->getUserExistenceChecker()->existByUsername($generatedUsername));

        return $generatedUsername;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_USER_PASSWORD] = $optionals[self::OP_USER_PASSWORD]
            ?? static function (): string {
                return Password\Generator::new()
                    ->initBySystemParametersOfMainAccount()
                    ->generate();
            };

        $this->setOptionals($optionals);
    }
}
