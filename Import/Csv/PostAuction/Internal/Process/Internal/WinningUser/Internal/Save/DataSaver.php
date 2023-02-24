<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Process\Internal\WinningUser\Internal\Save;

use Sam\Bidder\AuctionBidder\Register\General\AuctionBidderRegistratorFactory;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\EntityMaker\User\Save\UserMakerProducer;
use Sam\User\Save\UserCustomerNoApplier;
use User;

/**
 * Class DataSaver
 * @package
 */
class DataSaver extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function produceUser(UserMakerInputDto $userInputDto, UserMakerConfigDto $userConfigDto): User
    {
        $producer = UserMakerProducer::new()->construct($userInputDto, $userConfigDto);
        $producer->produce();
        return $producer->getUser();
    }

    public function applyCustomerNo(User $user, int $editorUserId): User
    {
        return UserCustomerNoApplier::new()->construct()->apply($user, $editorUserId);
    }

    public function registerAuctionBidder(int $userId, int $auctionId, float $bidderPremium, int $editorUserId): void
    {
        AuctionBidderRegistratorFactory::new()
            ->createCsvPostAuctionImport($userId, $auctionId, $bidderPremium, $editorUserId)
            ->register();
    }
}
