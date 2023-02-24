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

namespace Sam\Import\Csv\PostAuction\Internal\Process\Internal\Save;

use AuctionLotItem;
use LotItem;
use Sam\AuctionLot\Delete\AuctionLotDeleter;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\Import\Csv\PostAuction\Internal\Process\Internal\WinningUser\WinningUserProducer;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepository;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepository;
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

    public function saveLotItem(LotItem $lotItem, int $editorUserId): void
    {
        LotItemWriteRepository::new()->saveWithModifier($lotItem, $editorUserId);
    }

    public function saveAuctionLot(AuctionLotItem $auctionLot, int $editorUserId): void
    {
        AuctionLotItemWriteRepository::new()->saveWithModifier($auctionLot, $editorUserId);
    }

    public function deleteAuctionLot(AuctionLotItem $auctionLot, int $editorUserId): void
    {
        AuctionLotDeleter::new()
            ->construct()
            ->delete($auctionLot, $editorUserId);
    }

    /**
     * @param UserMakerInputDto $userInputDto
     * @param UserMakerConfigDto $userConfigDto
     * @param int $auctionId
     * @param float $bidderPremium
     * @return User
     */
    public function updateWinningUser(
        UserMakerInputDto $userInputDto,
        UserMakerConfigDto $userConfigDto,
        int $auctionId,
        float $bidderPremium
    ): User {
        return WinningUserProducer::new()->update(
            $userInputDto,
            $userConfigDto,
            $auctionId,
            $bidderPremium
        );
    }
}
