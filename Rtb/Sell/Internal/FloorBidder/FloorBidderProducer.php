<?php
/**
 * Creates new user for floor bid and register him in auction. New user username is "auction_<auction.id>_floor_<bidder#>"
 * We don't call this service for existing user, because such case is handled in caller.
 *
 * SAM-9644: Create new floor bidder user at "Run Live" page with all related records
 * SAM-9656: Allow new floor bidder user creation at Rtb Clerk console when enter alpha-numeric bidder#
 * SAM-5495: Rtb server and daemon refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           11/18/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Sell\Internal\FloorBidder;

use AuctionBidder;
use Sam\Bidder\AuctionBidder\Register\General\AuctionBidderRegistratorFactoryCreateTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerDtoFactory;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\EntityMaker\User\Lock\UserMakerLockerCreateTrait;
use Sam\EntityMaker\User\Save\UserMakerProducer;
use Sam\EntityMaker\User\Validate\UserMakerValidator;
use Sam\User\Account\Save\UserAccountProducerAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use User;

/**
 * Class FloorBidderProducer
 * @package Sam\Rtb\Sell
 */
class FloorBidderProducer extends CustomizableClass
{
    use AuctionBidderRegistratorFactoryCreateTrait;
    use BidderNumPaddingAwareTrait;
    use UserAccountProducerAwareTrait;
    use UserLoaderAwareTrait;
    use UserMakerLockerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Create new user for floor bidder whom lot is sold.
     * @param int $auctionId
     * @param string $bidderNumInput
     * @param int $editorUserId
     * @param int $auctionAccountId
     * @return AuctionBidder|null
     */
    public function create(
        int $auctionId,
        string $bidderNumInput,
        int $editorUserId,
        int $auctionAccountId
    ): ?AuctionBidder {
        $bidderNum = $this->getBidderNumberPadding()->sanitize($bidderNumInput);
        $username = $this->makeUsername($auctionId, $bidderNum);

        $floorBidderUser = $this->loadUserOrCreatePersisted(
            $username,
            $editorUserId,
            $auctionAccountId
        );
        if (!$floorBidderUser) {
            log_error('Floor bidder user creation failed' . composeSuffix(['username' => $username]));
            return null;
        }

        $registrator = $this->createAuctionBidderRegistratorFactory()->createWebAdminFloorBidderRegistrator(
            $floorBidderUser->Id,
            $auctionId,
            $bidderNum,
            $editorUserId
        );
        $auctionBidder = $registrator->register();
        if (!$auctionBidder) {
            log_error('Auction bidder registration failed' . composeSuffix(['error' => $registrator->getErrorMessage()]));
            return null;
        }

        return $auctionBidder;
    }

    /**
     * Create new active user with bidder privilege with suggested username, but without email.
     * @param string $username
     * @param int $editorUserId
     * @param int $auctionAccountId
     * @return User|null
     */
    protected function loadUserOrCreatePersisted(
        string $username,
        int $editorUserId,
        int $auctionAccountId
    ): ?User {
        /**
         * The next block is JIC logic, because we come there for new user always.
         */
        $user = $this->getUserLoader()->loadByUsername($username);
        if ($user) {
            return $this->updateUser($user, $editorUserId, $auctionAccountId);
        }

        return $this->createUserPersisted($username, $editorUserId, $auctionAccountId);
    }

    protected function createUserPersisted(
        string $username,
        int $editorUserId,
        int $auctionAccountId
    ): ?User {
        /**
         * @var UserMakerInputDto $userInputDto
         * @var UserMakerConfigDto $userConfigDto
         */
        [$userInputDto, $userConfigDto] = UserMakerDtoFactory::new()
            ->createDtos(Mode::WEB_ADMIN, $editorUserId, $auctionAccountId, $auctionAccountId);
        // User
        $userInputDto->userStatusId = Constants\User::US_ACTIVE;
        $userInputDto->username = $username;
        // Bidder
        $userInputDto->bidder = '1';
        // UserAccount
        $userInputDto->collateralAccountId = $auctionAccountId;

        $lockingResult = $this->createUserMakerLocker()->lock($userInputDto, $userConfigDto); // #user-lock-7
        if (!$lockingResult->isSuccess()) {
            return null;
        }
        try {
            $validator = UserMakerValidator::new()->construct($userInputDto, $userConfigDto);
            if (!$validator->validate()) {
                log_error("User maker validation failed" . composeSuffix(['error' => $validator->getErrorMessages()]));
                return null;
            }

            $producer = UserMakerProducer::new()->construct($userInputDto, $userConfigDto);
            $producer->produce();
            return $producer->getUser();
        } finally {
            $this->createUserMakerLocker()->unlock($userConfigDto); // #user-lock-7, unlock after success or failed save
        }
    }

    protected function updateUser(User $user, int $editorUserId, int $auctionAccountId): User
    {
        $this->getUserAccountProducer()->add($user->Id, $auctionAccountId, $editorUserId);
        return $user;
    }

    protected function makeUsername(int $auctionId, string $bidderNum): string
    {
        return sprintf('auction_%s_floor_%s', $auctionId, $bidderNum);
    }
}
