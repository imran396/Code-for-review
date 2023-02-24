<?php
/**
 * SAM-10802: Supply uniqueness of auction lot fields: lot#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Lock;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerConfigDto;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputDto;
use Sam\EntityMaker\AuctionLot\Lock\LotNo\AuctionLotUniqueLotNoLockerCreateTrait;
use Sam\EntityMaker\AuctionLot\Lock\AuctionLotMakerLockingResult as Result;

/**
 * Class AuctionLotMakerLocker
 * @package Sam\EntityMaker\AuctionLot
 * @method AuctionLotMakerInputDto getInputDto()
 * @method AuctionLotMakerConfigDto getConfigDto()
 */
class AuctionLotMakerLocker extends CustomizableClass
{
    use AuctionLotUniqueLotNoLockerCreateTrait;

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    /**
     * @param AuctionLotMakerInputDto $inputDto
     * @param AuctionLotMakerConfigDto $configDto
     * @return Result
     */
    public function lock(
        AuctionLotMakerInputDto $inputDto,
        AuctionLotMakerConfigDto $configDto
    ): Result {
        $result = Result::new();
        $lotNoLockingResult = $this->createAuctionLotUniqueLotNoLocker()->lock($inputDto, $configDto);
        $result->addLockingResult($lotNoLockingResult);
        return $result;
    }

    public function unlock(AuctionLotMakerConfigDto $configDto): AuctionLotMakerConfigDto
    {
        $configDto = $this->createAuctionLotUniqueLotNoLocker()->unlock($configDto);
        return $configDto;
    }
}
