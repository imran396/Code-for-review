<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 * SAM-4832: Post auction import-Issue when no winning information in csv cell
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Process\Internal\WinningUser;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\Import\Csv\PostAuction\Internal\Process\Internal\WinningUser\Internal\Save\DataSaverCreateTrait;
use User;

/**
 * Class WinningUserProducer
 * @package Sam\Import\Csv\PostAuction\Internal\Process\Internal\WinningUser
 */
class WinningUserProducer extends CustomizableClass
{
    use DataSaverCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param UserMakerInputDto $userInputDto
     * @param UserMakerConfigDto $userConfigDto
     * @param int $auctionId
     * @param float $bidderPremium
     * @return User
     */
    public function update(
        UserMakerInputDto $userInputDto,
        UserMakerConfigDto $userConfigDto,
        int $auctionId,
        float $bidderPremium
    ): User {
        $isNew = !$userInputDto->id;
        $dataSaver = $this->createDataSaver();
        $user = $dataSaver->produceUser($userInputDto, $userConfigDto);
        if (
            $isNew
            && !$userInputDto->customerNo
        ) {
            $user = $dataSaver->applyCustomerNo($user, $userConfigDto->editorUserId);
        }
        $dataSaver->registerAuctionBidder($user->Id, $auctionId, $bidderPremium, $userConfigDto->editorUserId);
        return $user;
    }
}
